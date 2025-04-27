@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Stock List</h2>

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('stocks.create') }}" class="btn btn-success">Add New Stock</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>SL No</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stocks as $index => $stock)
                <tr>
                    <td>{{ $stocks->firstItem() + $index }}</td>
                    <td>{{ $stock->product->name }}</td>
                    <td>{{ number_format($stock->product->price, 2) }}</td>
                    <td>{{ $stock->quantity }}</td>
                    <td>
                        <a href="{{ route('stocks.show', $stock->id) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('stocks.edit', $stock->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
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
        {{ $stocks->links() }}
    </div>
</div>
@endsection
