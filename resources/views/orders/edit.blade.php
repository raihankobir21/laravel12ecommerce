@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Order</h2>
    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Customer</label>
            <select name="customer_id" class="form-control" required>
                @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" {{ $customer->id == $order->customer_id ? 'selected' : '' }}>
                    {{ $customer->name }} ({{ $customer->email }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Shipping Address</label>
            <textarea name="shipping_address" class="form-control" required>{{ $order->shipping_address }}</textarea>
        </div>

        <h4>Products</h4>
        <div id="productList">
            @foreach ($order->products as $product)
            <div class="product-row">
                <select name="products[{{ $loop->index }}][id]" class="form-control product-select">
                    @foreach ($products as $p)
                    <option value="{{ $p->id }}" data-price="{{ $p->price }}" data-stock="{{ $p->stock ? $p->stock->quantity : 0 }}"
                        {{ $p->id == $product->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                    @endforeach
                </select>

                <input type="number" name="products[{{ $loop->index }}][quantity]" class="form-control quantity-input"
                    value="{{ $product->pivot->quantity }}" min="1" max="{{ $product->stock->quantity }}" required>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".product-select").forEach(function (select) {
        select.addEventListener("change", function () {
            let selectedOption = this.options[this.selectedIndex];
            let price = selectedOption.getAttribute("data-price");
            let stock = selectedOption.getAttribute("data-stock");

            let row = this.closest(".product-row");
            row.querySelector(".price-input").value = price;
            row.querySelector(".stock-input").value = stock;
        });
    });

    document.querySelectorAll(".quantity-input").forEach(function (input) {
        input.addEventListener("input", function () {
            let stock = parseInt(this.closest(".product-row").querySelector(".stock-input").value);
            if (this.value > stock) {
                alert("Quantity exceeds stock availability!");
                this.value = stock;
            }
        });
    });
});

</script>
@endsection
