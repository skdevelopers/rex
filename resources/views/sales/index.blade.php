@extends('layouts.vertical', ['title' => 'Sales Rex ERP', 'sub_title' => 'Sales', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-6">
            <h2>Sales List</h2>
            <div class="mb-4">
                <a href="{{ route('sales.create') }}" class="btn inline-flex justify-center items-center bg-primary text-white w-full">
                    <i class="mgc_add_line text-lg me-2"></i> Create New Sale
                </a>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <!-- Display sales table or message -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-md shadow-md">
                    <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">Sale ID</th>
                        <th class="px-4 py-2">Customer Name</th>
                        <th class="px-4 py-2">Product</th>
                        <th class="px-4 py-2">Quantity</th>
                        <th class="px-4 py-2">Total Price</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="salesTableBody">
                    <!-- Sales rows will be populated here -->
                    @foreach ($sales as $sale)
                        <tr>
                            <td class="border px-4 py-2">{{ $sale->id }}</td>
                            <td class="border px-4 py-2">{{ $sale->customer->name }}</td>
                            <td class="border px-4 py-2">{{ $sale->product->name }}</td>
                            <td class="border px-4 py-2">{{ $sale->quantity }}</td>
                            <td class="border px-4 py-2">{{ $sale->total_price }}</td>
                            <td class="border px-4 py-2">{{ $sale->created_at->format('Y-m-d') }}</td>
                            <td class="border px-4 py-2 whitespace-nowrap">
                                <a href="{{ route('sales.edit', $sale->id) }}" class="text-blue-500 hover:text-blue-700 mx-0.5">
                                    <i class="mgc_edit_line text-lg"></i>
                                </a>
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mx-0.5" onclick="return confirm('Are you sure you want to delete this sale?')">
                                        <i class="mgc_delete_line text-xl"></i>
                                    </button>
                                </form>
                                <a href="{{ route('sales.show', $sale->id) }}" class="text-green-500 hover:text-green-700 mx-0.5">
                                    <i class="mgc_display_line text-lg"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div id="noSalesMessage" class="mt-4 text-center text-gray-500">
                    <!-- Message displayed when no sales are available -->
                    No sales found. <a href="{{ route('sales.create') }}" class="text-blue-500 hover:underline">Create one</a>.
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Script to fetch and populate sales data -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            axios.get('/api/sales')
                .then(response => {
                    const sales = response.data;
                    const salesTableBody = document.querySelector('#salesTableBody');
                    const noSalesMessage = document.querySelector('#noSalesMessage');

                    // Clear existing rows
                    salesTableBody.innerHTML = '';

                    if (sales.length > 0) {
                        // Append new rows with sales data
                        sales.forEach(sale => {
                            // Construct HTML row for the sale
                            const row = `
                                <tr>
                                    <td class="border px-4 py-2">${sale.id}</td>
                                    <td class="border px-4 py-2">${sale.customer.name}</td>
                                    <td class="border px-4 py-2">${sale.product.name}</td>
                                    <td class="border px-4 py-2">${sale.quantity}</td>
                                    <td class="border px-4 py-2">${sale.total_price}</td>
                                    <td class="border px-4 py-2">${new Date(sale.created_at).toLocaleDateString()}</td>
                                    <td class="border px-4 py-2 whitespace-nowrap">
                                        <a href="/sales/${sale.id}/edit" class="text-blue-500 hover:text-blue-700 mx-0.5">
                                            <i class="mgc_edit_line text-lg"></i>
                                        </a>
                                        <form action="/sales/${sale.id}" method="POST" class="inline">
                                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 mx-0.5" onclick="return confirm('Are you sure you want to delete this sale?')">
                                <i class="mgc_delete_line text-xl"></i>
                            </button>
                        </form>
                        <a href="/sales/${sale.id}" class="text-green-500 hover:text-green-700 mx-0.5">
                                            <i class="mgc_display_line text-lg"></i>
                                        </a>
                                    </td>
                                </tr>
                            `;

                            // Insert the row into the table body
                            salesTableBody.insertAdjacentHTML('beforeend', row);
                        });

                        // Show table if sales exist
                        salesTableBody.style.display = 'table-row-group';
                        noSalesMessage.style.display = 'none';
                    } else {
                        // Show message and create button if no sales
                        salesTableBody.style.display = 'none';
                        noSalesMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Axios Error:', error); // Log any Axios errors
                });
        });
    </script>
@endpush
