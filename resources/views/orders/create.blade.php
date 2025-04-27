@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Create Order</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <!-- Customer Selection -->
        <div class="mb-3">
            <label class="form-label">Customer</label>
            <select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                @endforeach
            </select>
        </div>

        <!-- Shipping Address -->
        <div class="mb-3">
            <label class="form-label">Shipping Address</label>
            <input type="text" name="shipping_address" class="form-control" required>
        </div>

        <!-- Product Selection -->
        <div class="mb-3">
            <label class="form-label">Product</label>
            <select id="productSelect" class="form-control">
                <option value="">Select Product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock->quantity ?? 0 }}">
                        {{ $product->name }} - ${{ $product->price }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="text" id="productPrice" class="form-control" readonly>
        </div>

        <!-- Stock Quantity -->
        <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="text" id="stockQuantity" class="form-control" readonly>
        </div>

        <!-- Quantity -->
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" id="quantityInput" name="products[0][quantity]" class="form-control" min="1">
        </div>

        <!-- Hidden Product ID Input -->
        <input type="hidden" id="selectedProductId" name="products[0][product_id]">

        <!-- Total Price -->
        <div class="mb-3">
            <label class="form-label">Total Price</label>
            <input type="text" id="totalPrice" class="form-control" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>

<script>
document.getElementById('productSelect').addEventListener('change', function() {
    let selectedOption = this.options[this.selectedIndex];
    let price = selectedOption.getAttribute('data-price');
    let stock = selectedOption.getAttribute('data-stock');

    document.getElementById('productPrice').value = price;
    document.getElementById('stockQuantity').value = stock;
    document.getElementById('selectedProductId').value = this.value;
});

document.getElementById('quantityInput').addEventListener('input', function() {
    let quantity = parseInt(this.value);
    let stock = parseInt(document.getElementById('stockQuantity').value);
    let price = parseFloat(document.getElementById('productPrice').value);

    if (quantity > stock) {
        alert('Quantity exceeds available stock!');
        this.value = stock;
    }

    document.getElementById('totalPrice').value = (this.value * price).toFixed(2);
});
</script>
@endsection
