<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReturnProductController;

Route::get('/', function () {
    return redirect()->route('products.index'); // Redirect to the products page
});

Route::resource('categories', CategoryController::class);
Route::resource('subcategories', SubcategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('products', ProductController::class);
Route::resource('stocks', StockController::class);
Route::resource('customers', CustomerController::class);
Route::resource('orders', OrderController::class);
Route::resource('returns', ReturnProductController::class);

Route::get('/get-order-products/{order}', function($orderId) {
    $order = App\Models\Order::with('products')->find($orderId);
    return response()->json($order->products);
});



Route::get('returns/get-orders/{customerId}', [ReturnProductController::class, 'getCustomerOrders'])->name('returns.getOrders');


// Additional Order Route for checkout (if needed)
Route::post('orders/{order}/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
Route::patch('/orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve');



