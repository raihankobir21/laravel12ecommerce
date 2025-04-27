@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Add Stock</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stocks.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Product</label>
            <select name="product_id" id="product-select" class="form-control" required>
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (Auto-filled)</label>
            <input type="text" id="product-price" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('stocks.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    document.getElementById('product-select').addEventListener('change', function() {
        let selectedOption = this.options[this.selectedIndex];
        let price = selectedOption.getAttribute('data-price');
        document.getElementById('product-price').value = price ? price : '';
    });
</script>

@endsection
