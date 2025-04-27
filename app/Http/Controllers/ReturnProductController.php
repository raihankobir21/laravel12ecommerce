<?php

namespace App\Http\Controllers;

use App\Models\ReturnProduct;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class ReturnProductController extends Controller{
   // Display the index of returned products
   public function index()
   {
       $returns = ReturnProduct::with(['order', 'product'])->latest()->paginate(10);
       return view('returns.index', compact('returns'));
   }

   // Show the form to create a return for a specific customer
   public function create()
   {
       $customers = Customer::all();
       return view('returns.create', compact('customers'));
   }

   // Fetch orders and their products when a customer is selected
   public function getCustomerOrders($customerId)
   {
       $orders = Order::with(['products' => function ($query) {
                          $query->select('products.id', 'products.name', 'order_product.quantity', 'order_product.price');
                      }])
                      ->where('customer_id', $customerId)
                      ->get();

       return response()->json($orders);
   }

   // Store the returned product
   public function store(Request $request)
   {
       // Validate incoming data
       $validated = $request->validate([
           'customer_id' => 'required',
           'order_id' => 'required',
           'product_id' => 'required',
           'quantity' => 'required|integer|min:1',
           'reason' => 'required|string',
       ]);

       // Retrieve the product from the order
       $orderProduct = Order::find($request->order_id)
                            ->products()
                            ->where('product_id', $request->product_id)
                            ->first();

       if (!$orderProduct) {
           return redirect()->back()->with('error', 'Product not found in the selected order.');
       }

       // Ensure the quantity returned is not more than the quantity in the order
       if ($request->quantity > $orderProduct->pivot->quantity) {
           return redirect()->back()->with('error', 'Return quantity cannot exceed the ordered quantity.');
       }

       // Create a new return record
       $return = ReturnProduct::create([
           'customer_id' => $request->customer_id,
           'order_id' => $request->order_id,
           'product_id' => $request->product_id,
           'quantity' => $request->quantity,
           'reason' => $request->reason,
       ]);

       // Add the returned quantity to the stock
       $product = Product::find($request->product_id);
       $product->stock += $request->quantity;  // Add the returned quantity back to stock
       $product->save();

       return redirect()->route('returns.index')->with('success', 'Product returned and stock updated.');
   }
}
