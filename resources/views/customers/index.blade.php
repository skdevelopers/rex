<!-- resources/views/customers/index.blade.php -->
@extends('layouts.vertical', ['title' => 'Customers', 'sub_title' => 'Customers'])

@section('content')
    <div class="grid grid-cols-12">
        <div class="col-span-12">
            <!-- Table to display customer records -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-md shadow-md">
                    <thead>
                    <tr class="bg-gray-200">
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Address</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($customers as $customer)
                        <tr>
                            <td class="border px-4 py-2">{{ $customer->id }}</td>
                            <td class="border px-4 py-2">{{ $customer->name }}</td>
                            <td class="border px-4 py-2">{{ $customer->email }}</td>
                            <td class="border px-4 py-2">{{ $customer->address }}</td>
                            <td class="border px-4 py-2 whitespace-nowrap">
                                <!-- Edit Button -->
                                <a href="{{ route('customers.edit', $customer->id) }}" class="text-blue-500 hover:text-blue-700 mx-0.5">
                                    <i class="mgc_edit_line text-lg"></i>
                                </a>
                                <!-- Delete Button -->
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 mx-0.5" onclick="return confirm('Are you sure you want to delete this customer?')">
                                        <i class="mgc_delete_line text-xl"></i>
                                    </button>
                                </form>
                                <!-- Show Button -->
                                <a href="{{ route('customers.show', $customer->id) }}" class="text-green-500 hover:text-green-700 mx-0.5">
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
