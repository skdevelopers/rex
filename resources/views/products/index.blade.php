<!-- resources/views/products/index.blade.php -->
@extends('layouts.vertical', ['title' => 'Products Rex ERP', 'sub_title' => 'Products', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Product List</h2>
                <div class="mb-4">
                    <a href="{{ route('products.create') }}" class="btn inline-flex justify-center items-center bg-primary text-white w-full fc-dropdown">
                        <i class="mgc_add_line text-lg me-2"></i> Create New
                    </a>
                </div>
            </div>
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Subcategory</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Unit Price</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ optional($product->category)->name ?? 'N/A' }}</td>
                            <td>{{ optional($product->subcategory)->name ?? 'N/A' }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->unit }}</td>
                            <td>{{ $product->unit_price }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="text-blue-500 hover:text-blue-700 mx-0.5">
                                    <i class="mgc_edit_line text-lg"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mx-0.5">
                                        <i class="mgc_delete_line text-xl"></i>
                                    </button>
                                </form>
                                <a href="{{ route('products.show', $product->id) }}" class="text-green-500 hover:text-green-700 mx-0.5">
                                    <i class="mgc_display_line text-lg"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

