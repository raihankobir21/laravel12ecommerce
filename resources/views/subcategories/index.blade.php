@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Subcategories</h2>
    <a href="{{ route('subcategories.create') }}" class="btn btn-primary mb-3">Add Subcategory</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>SL No</th>
                <th>Name</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subcategories as $subcategory)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $subcategory->name }}</td>
                    <td>{{ $subcategory->category->name }}</td>
                    <td>
                        <a href="{{ route('subcategories.show', $subcategory) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('subcategories.edit', $subcategory) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('subcategories.destroy', $subcategory) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $subcategories->links() }}
</div>
@endsection
