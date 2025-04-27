@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h2 class="mb-4">Product List</h2>
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-success">Add New Product</a>
        </div>
    </div>


    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead class="">
            <tr>
                <th>SL No</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Subcategory</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
                <tr>
                    <td>{{ $products->firstItem() + $index }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ $product->subcategory->name }}</td>
                    <td>{{ $product->supplier->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>

                        <td>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" width="50" height="50">
                            @else
                                No Image
                            @endif
                        </td>


                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
