@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Returned Products</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ route('returns.create') }}" class="btn btn-primary mb-3">Create Return</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity Returned</th>
                <th>Reason</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $return)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>#{{ $return->order->id }}</td>
                <td>{{ $return->order->customer->name }}</td>
                <td>{{ $return->product->name }}</td>
                <td>{{ $return->quantity }}</td>
                <td>{{ $return->reason }}</td>
                <td>{{ $return->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $returns->links() }}
</div>
@endsection
