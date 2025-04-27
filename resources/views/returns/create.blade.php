@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Return a Product</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('returns.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Select Order</label>
            <select id="orderSelect" name="order_id" class="form-control" required>
                <option value="">Select Order</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">Order #{{ $order->id }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Product</label>
            <select id="productSelect" name="product_id" class="form-control" required>
                <option value="">Select Product</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantityInput" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Reason</label>
            <textarea name="reason" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit Return</button>
    </form>
</div>

<script>
    document.getElementById('orderSelect').addEventListener('change', function() {
        let orderId = this.value;
        fetch(`/api/get-order-products/${orderId}`)
            .then(response => response.json())
            .then(data => {
                let productSelect = document.getElementById('productSelect');
                productSelect.innerHTML = '<option value="">Select Product</option>';
                data.forEach(product => {
                    productSelect.innerHTML += `<option value="${product.id}">${product.name}</option>`;
                });
            });
    });
</script>
@endsection
