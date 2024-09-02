{{-- resources/views/sales/show.blade.php --}}
@extends('layouts.vertical', ['title' => 'Sale Details', 'sub_title' => 'Sale'])

@section('content')
    <div class="cashier-content-area mt-7">
        <div class="cashier-addsale-area bg-white p-6 custom-shadow rounded-lg mb-5">
            <h4 class="text-[20px] font-bold text-heading mb-11 text-blue-500">Sale Details</h4>
            <div class="grid grid-cols-12 gap-x-7 maxSm:gap-x-0">
                <div class="xl:col-span-4 lg:col-span-4 md:col-span-6 col-span-12">
                    <div class="cashier-select-field mb-5">
                        <h5 class="text-[15px] text-heading font-semibold mb-3">Vendor</h5>
                        <div class="cashier-select-field-style form-group">
                            <p>{{ $sale->vendor }}</p>
                        </div>
                    </div>
                </div>
                <div class="xl:col-span-3 lg:col-span-4 md:col-span-6 col-span-12">
                    <div class="cashier-select-field mb-5">
                        <h5 class="text-[15px] text-heading font-semibold mb-3">Bill Date</h5>
                        <div class="cashier-select-field-style">
                            <p>{{ $sale->bill_date }}</p>
                        </div>
                    </div>
                </div>
                <div class="xl:col-span-3 lg:col-span-4 md:col-span-6 col-span-12">
                    <div class="cashier-select-field mb-5">
                        <h5 class="text-[15px] text-heading font-semibold mb-3">Due Date</h5>
                        <div class="cashier-select-field-style">
                            <p>{{ $sale->due_date }}</p>
                        </div>
                    </div>
                </div>
                <div class="xl:col-span-2 lg:col-span-4 md:col-span-6 col-span-12">
                    <div class="cashier-select-field mb-5">
                        <h5 class="text-[15px] text-heading font-semibold mb-3">Order #</h5>
                        <div class="cashier-select-field-style">
                            <p>{{ $sale->order }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="cashier-addsale-product-area pt-7">
                <div class="cashier-addsale-product-wrapper-inner">
                    <div class="cashier-addsale-product-wrapper-inner-wrap">
                        <div id="productTable">
                            <div class="grid grid-cols-12 gap-4 bg-gray-200 py-4 text-center">
                                <div class="col-span-1 text-[15px] font-semibold text-heading">No #</div>
                                <div class="col-span-5 text-[15px] font-semibold text-heading">Products Details</div>
                                <div class="col-span-1 text-[15px] font-semibold text-heading">Unit</div>
                                <div class="col-span-1 text-[15px] font-semibold text-heading">Price</div>
                                <div class="col-span-1 text-[15px] font-semibold text-heading">Quantity</div>
                                <div class="col-span-2 text-[15px] font-semibold text-heading">Sub Total</div>
                            </div>
                            <div id="productTableBody" class="divide-y divide-gray-300">
                                @foreach ($sale->products as $product)
                                    <div class="grid grid-cols-12 gap-4 py-4">
                                        <div class="col-span-1 flex items-center justify-center">
                                            <span>{{ $loop->iteration }}</span>
                                        </div>
                                        <div class="col-span-5 flex items-center justify-center">
                                            <span>{{ $product->name }}</span>
                                        </div>
                                        <div class="col-span-1 flex items-center justify-center">
                                            <span>{{ $product->pivot->unit }}</span>
                                        </div>
                                        <div class="col-span-1 flex items-center justify-center">
                                            <span>{{ $product->pivot->unit_price }}</span>
                                        </div>
                                        <div class="col-span-1 flex items-center justify-center">
                                            <span>{{ $product->pivot->quantity }}</span>
                                        </div>
                                        <div class="col-span-2 flex items-center justify-center">
                                            <span>{{ $product->pivot->subtotal }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="cashier-addsale-product-cost text-right pt-9 pb-9 border-b border-solid border-gray-300 mb-7">
                <ul>
                    <li class="px-4 py-2.5 border-b border-solid border-gray-300 bg-gray-200">
                        <span class="text-[15px] font-normal text-heading w-40 inline-block">Total Amount<span class="float-right">:</span></span>
                        <span id="totalAmount" class="text-[15px] font-normal text-heading inline-block">{{ $sale->total_amount }}</span>
                    </li>
                    <li class="px-4 py-2.5 border-b border-solid border-gray-300">
                        <span class="text-[15px] font-normal text-heading w-40 inline-block">Discount (%) <span class="float-right">:</span></span>
                        <span>{{ $sale->discount_percentage }}</span>
                    </li>
                    <li class="px-4 py-2.5 border-b border-solid border-gray-300 bg-gray-200">
                        <span class="text-[15px] font-normal text-heading w-40 inline-block">Shipping <span class="float-right">:</span></span>
                        <span>{{ $sale->shipping_cost }}</span>
                    </li>
                    <li class="px-4 py-2.5">
                        <span class="text-[15px] font-bold text-heading w-40 inline-block">Grand Total <span class="float-right font-normal">:</span></span>
                        <span id="grandTotal" class="text-[15px] font-bold text-heading inline-block">{{ $sale->grand_total }}</span>
                    </li>
                </ul>
            </div>

            <!-- Purchase Note -->
            <div class="grid grid-cols-12 gap-x-7 maxSm:gap-x-0">
                <div class="col-span-12">
                    <div class="cashier-select-field mb-5">
                        <h5 class="text-[15px] text-heading font-semibold mb-3">Purchase Note:</h5>
                        <div class="cashier-input-field-style relative">
                            <p>{{ $sale->purchase_note }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end">
                <a href="{{ route('sales.edit', $sale->id) }}" class="btn bg-blue-500 text-white py-2 px-4 rounded-md">Edit</a>
                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn bg-red-500 text-white py-2 px-4 rounded-md" onclick="return confirm('Are you sure you want to delete this sale?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection
