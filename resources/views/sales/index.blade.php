{{-- resources/views/sales/index.blade.php --}}
@extends('layouts.vertical', ['title' => 'Sales', 'sub_title' => 'Sales'])

@section('content')
    <div class="grid grid-cols-12">
        <div class="mb-4">
            <a href="{{ route('sales.create') }}" class="btn inline-flex justify-center items-center bg-primary text-white w-full">
                <i class="mgc_add_line text-lg me-2"></i> Create New
            </a>
        </div>
    </div>
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <!-- Display sales table or message -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-md shadow-md">
                    <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Vendor</th>
                        <th class="px-4 py-2">Order #</th>
                        <th class="px-4 py-2">Bill Date</th>
                        <th class="px-4 py-2">Due Date</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="saleTableBody">
                    <!-- Sale rows will be populated here -->
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            axios.get('/api/sales')
                .then(response => {
                    const sales = response.data;
                    const saleTableBody = document.querySelector('#saleTableBody');
                    const noSalesMessage = document.querySelector('#noSalesMessage');

                    saleTableBody.innerHTML = '';

                    if (sales.length > 0) {
                        sales.forEach(sale => {
                            const row = `
                                <tr>
                                    <td class="border px-4 py-2">${sale.id}</td>
                                    <td class="border px-4 py-2">${sale.vendor}</td>
                                    <td class="border px-4 py-2">${sale.order}</td>
                                    <td class="border px-4 py-2">${sale.bill_date}</td>
                                    <td class="border px-4 py-2">${sale.due_date}</td>
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

                            saleTableBody.insertAdjacentHTML('beforeend', row);
                        });

                        saleTableBody.style.display = 'table-row-group';
                        noSalesMessage.style.display = 'none';
                    } else {
                        saleTableBody.style.display = 'none';
                        noSalesMessage.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Axios Error:', error);
                });
        });
    </script>
@endpush
