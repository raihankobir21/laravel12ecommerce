<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\StoreStockRequest;
use App\Http\Requests\UpdateStockRequest;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product')->paginate(5);
        return view('stocks.index', compact('stocks'));
    }

    public function create()
    {
        $products = Product::all();
        return view('stocks.create', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the product price from the products table
        $product = Product::findOrFail($request->product_id);

        // Create stock entry (without price field)
        Stock::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock added successfully.');
    }


    public function show(Stock $stock)
    {
        return view('stocks.show', compact('stock'));
    }

    public function edit(Stock $stock)
    {
        $products = Product::all();
        return view('stocks.edit', compact('stock', 'products'));
    }

    public function update(Request $request, Stock $stock)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Update stock entry (without price field)
        $stock->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('stocks.index')->with('success', 'Stock updated successfully.');
    }

    public function destroy(Stock $stock)
    {
        $stock->delete();
        return redirect()->route('stocks.index');
    }
}
