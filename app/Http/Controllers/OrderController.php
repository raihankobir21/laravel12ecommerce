<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Customer;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'shipping_address' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'shipping_address' => $request->shipping_address,
                'total_amount' => 0,
                'status' => 'pending',
            ]);

            $totalAmount = 0;

            foreach ($request->products as $productData) {
                $product = Product::findOrFail($productData['product_id']);
                $stock = Stock::where('product_id', $product->id)->first();

                if (!$stock || $stock->quantity < $productData['quantity']) {
                    return redirect()->back()->with('error', 'Not enough stock available.');
                }

                $order->products()->attach($product->id, [
                    'price' => $product->price,
                    'quantity' => $productData['quantity']
                ]);

                $totalAmount += $product->price * $productData['quantity'];

                $stock->decrement('quantity', $productData['quantity']);
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order placed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error placing order: ' . $e->getMessage());
        }
    }
    public function show($id)
    {
        $order = Order::with('products')->findOrFail($id);
        return view('orders.show', compact('order'));
    }

    // Edit order
    public function edit($id)
    {
        $order = Order::with('products')->findOrFail($id);
        $customers = Customer::all();
        $products = Product::with('stock')->get();
        return view('orders.edit', compact('order', 'customers', 'products'));
    }

    // Update order
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'shipping_address' => 'required',
            'products' => 'required|array',
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($id);
        $order->customer_id = $request->customer_id;
        $order->shipping_address = $request->shipping_address;
        $order->total_amount = 0;
        $order->products()->detach();

        foreach ($request->products as $productData) {
            $product = Product::find($productData['id']);
            $stock = Stock::where('product_id', $product->id)->first();

            if ($productData['quantity'] > $stock->quantity) {
                return back()->with('error', 'Insufficient stock for ' . $product->name);
            }

            $order->products()->attach($product->id, [
                'quantity' => $productData['quantity'],
                'price' => $product->price,
            ]);

            $order->total_amount += $product->price * $productData['quantity'];
        }

        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    // Delete order
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        foreach ($order->products as $product) {
            $stock = Stock::where('product_id', $product->id)->first();
            $stock->quantity += $product->pivot->quantity;
            $stock->save();
        }

        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function approve($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'pending') {
            $order->status = 'approved';
            $order->save();

            return redirect()->route('orders.index')->with('success', 'Order approved successfully.');
        }

        return redirect()->route('orders.index')->with('error', 'Order cannot be approved.');
    }

}
