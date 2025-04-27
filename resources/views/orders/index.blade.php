@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Orders</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('orders.create') }}" class="btn btn-success">Add New Order</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sl No</th>
                <th>Customer</th>
                <th>Shipping Address</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->shipping_address }}</td>
                <td>${{ number_format($order->total_amount, 2) }}</td>
                <td>
                    <span class="badge
                        {{ $order->status == 'pending' ? 'bg-warning' : 'bg-success' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>

                    @if($order->status === 'pending')
                        <form action="{{ route('orders.approve', $order->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                        </form>
                    @endif

                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete this order?');">
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
        {{ $orders->links() }}
    </div>
</div>
@endsection
