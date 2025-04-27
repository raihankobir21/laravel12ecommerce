@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order Details</h2>
    <p><strong>Customer:</strong> {{ $order->customer->name }} ({{ $order->customer->email }})</p>
    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
    <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong>Total Amount:</strong> ${{ $order->total_amount }}</p>

    <h4>Products</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>${{ $product->pivot->price }}</td>
                <td>{{ $product->pivot->quantity }}</td>
                <td>${{ $product->pivot->price * $product->pivot->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
