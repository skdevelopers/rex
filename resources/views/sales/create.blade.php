@extends('layouts.vertical', ['title' => 'add Sale', 'sub_title' => 'Sale', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    @push('styles')
        <style>
            .q-up,
            .q-down {
                width: 24px;
                text-align: center;
                cursor: pointer;
                user-select: none;
            }

            .q-up:hover,
            .q-down:hover {
                background-color: #d1d5db; /* Tailwind CSS gray-400 */
            }

            .q-input::-webkit-outer-spin-button,
            .q-input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            .q-input[type=number] {
                -moz-appearance: textfield;
            }
        </style>
    @endpush
    <div class="cashier-content-area mt-7">
        <div class="cashier-addsale-area bg-white p-7 custom-shadow rounded-lg mb-5">
            <h4 class="text-[20px] font-bold text-heading mb-11 text-blue-500">Customer Bill</h4>
            <form action="{{ route('sales.create') }}" method="post">
                @csrf
                <div class="grid grid-cols-12 gap-x-7 maxSm:gap-x-0 form-container">
                    <div class="xl:col-span-4 lg:col-span-4 md:col-span-6 col-span-12">
                        <div class="cashier-select-field mb-5">
                            <h5 class="text-[15px] text-heading font-semibold mb-3">Select Vendor</h5>
                            <div class="cashier-select-field-style form-group">
                                <select class="block w-full form-input" name="vendor">
                                    <option selected disabled value="default">Select Vendor</option>
                                    <option value="vendor-1">Vendor 1</option>
                                    <option value="vendor-2">Vendor 2</option>
                                    <option value="vendor-3">Vendor 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="xl:col-span-3 lg:col-span-4 md:col-span-6 col-span-12">
                        <div class="cashier-select-field mb-5">
                            <h5 class="text-[15px] text-heading font-semibold mb-3">Bill Date</h5>
                            <div class="cashier-select-field-style">
                                <div class="form-group col-span-3">
                                    <input type="date" class="form-input" id="datepicker-basic" name="dates[]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xl:col-span-3 lg:col-span-4 md:col-span-6 col-span-12">
                        <div class="cashier-select-field mb-5">
                            <h5 class="text-[15px] text-heading font-semibold mb-3">Due Date</h5>
                            <div class="cashier-select-field-style">
                                <div class="form-group col-span-3">
                                    <input type="date" class="form-input" id="datepicker-basic" name="dates[]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="xl:col-span-2 lg:col-span-4 md:col-span-6 col-span-12">
                        <div class="cashier-select-field mb-5">
                            <h5 class="text-[15px] text-heading font-semibold mb-3">Order #</h5>
                            <div class="cashier-select-field-style">
                                <div class="form-group col-span-3">
                                    <input type="text" class="form-input" id="order" name="order" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sale Form Start -->
                <div class="cashier-addsale-product-area pt-7">
                    <div class="cashier-addsale-product-wrapper-inner">
                        <div class="cashier-addsale-product-wrapper-inner-wrap">
                            <div id="productTable">
                                <div id="productTable">
                                    <div class="grid grid-cols-12 gap-4 bg-gray-200 py-4 text-center">
                                        <div class="col-span-1 text-[15px] font-semibold text-heading">No #</div>
                                        <div class="col-span-5 text-[15px] font-semibold text-heading">Products Details</div>
                                        <div class="col-span-1 text-[15px] font-semibold text-heading">Unit</div>
                                        <div class="col-span-1 text-[15px] font-semibold text-heading">Price</div>
                                        <div class="col-span-1 text-[15px] font-semibold text-heading">Quantity</div>
                                        <div class="col-span-2 text-[15px] font-semibold text-heading">Sub Total</div>
                                        <div class="col-span-1 text-[15px] font-semibold text-heading">Action</div>
                                    </div>
                                    <div id="productTableBody" class="divide-y divide-gray-300">
                                        <!-- Existing rows go here -->
                                    </div>
                                    <div class="mt-4 flex items-center justify-end px-4">
                                        <button type="button" onclick="addRow()" class="bg-blue-500 text-white py-2 px-4 rounded-md flex items-center"><i class="msr">add</i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cashier-addsale-product-cost text-right pt-9 pb-9 border-b border-solid border-gray-300 mb-7">
                        <ul>
                            <li class="px-4 py-2.5 border-b border-solid border-gray-300 bg-gray-200">
                                <span class="text-[15px] font-normal text-heading w-40 inline-block">Total Amount<span class="float-right">:</span></span>
                                <span id="totalAmount" class="text-[15px] font-normal text-heading inline-block">Rs0.00</span>
                            </li>
                            <li class="px-4 py-2.5 border-b border-solid border-gray-300">
                                <span class="text-[15px] font-normal text-heading w-40 inline-block">Discount (%) <span class="float-right">:</span></span>
                                <input type="number" id="discountPercentage" placeholder="0" class="h-8 w-16 bg-gray-00">
                            </li>
                            <li class="px-4 py-2.5 border-b border-solid border-gray-300 bg-gray-200">
                                <span class="text-[15px] font-normal text-heading w-40 inline-block">Shipping <span class="float-right">:</span></span>
                                <input type="text" id="shippingCost" placeholder="0.00" class="h-8 w-16 bg-gray-00">
                            </li>
                            <li class="px-4 py-2.5">
                                <span class="text-[15px] font-bold text-heading w-40 inline-block">Grand Total <span class="float-right font-normal">:</span></span>
                                <span id="grandTotal" class="text-[15px] font-bold text-heading inline-block">Rs0.00</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Sale Form End -->
                <div class="grid grid-cols-12 gap-x-7 maxSm:gap-x-0">
                    <div class="col-span-12">
                        <div class="cashier-select-field mb-5">
                            <h5 class="text-[15px] text-heading font-semibold mb-3">Purchase Note:</h5>
                            <div class="cashier-input-field-style relative">
                                <textarea name="purchase_note" placeholder="Type text...." class="w-full border border-gray-300 rounded-md p-2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cashier-addsale-product-btn default-light-theme pt-2">
                    <button type="submit" class="btn-primary bg-blue-500 text-white py-3 px-4 rounded-md">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 0;

            // Function to add a new row
            function addRow() {
                rowCount++;
                const tableBody = document.getElementById('productTableBody');
                const newRow = document.createElement('div');
                newRow.classList.add('grid', 'grid-cols-12', 'gap-4', 'py-4');

                newRow.innerHTML = `
                    <div class="col-span-1 flex items-center justify-center">
                        <span>${rowCount}</span>
                    </div>
                    <div class="col-span-5 flex items-center justify-center">
                        <input type="text" placeholder="Scan / search products by code / name" class="border border-gray-300 rounded w-full text-left">
                    </div>
                    <div class="col-span-1 flex items-center justify-center">
                        <input type="text" class="unit border border-gray-300 rounded w-full text-center">
                    </div>
                    <div class="col-span-1 flex items-center justify-center">
                        <input type="text" class="unit-price border border-gray-300 rounded w-full text-center" oninput="calculateTotal(this)">
                    </div>
                    <div class="col-span-1 flex items-center justify-center">
                        <div class="relative">
                            <input type="number" class="q-input border border-gray-300 rounded w-20 text-center pr-8" value="1" oninput="calculateTotal(this)">
                            <div class="absolute inset-y-0 right-0 flex flex-col items-center justify-center h-full">
                                <button class="q-up p-1 bg-gray-300 rounded-t flex items-center justify-center w-8 h-1/2" onclick="incrementQty(this)">
                                    <i class="msr">arrow_drop_up</i>
                                </button>
                                <button class="q-down p-1 bg-gray-300 rounded-b flex items-center justify-center w-8 h-1/2" onclick="decrementQty(this)">
                                    <i class="msr">arrow_drop_down</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2 flex items-center justify-center">
                        <input type="text" class="total-price border border-gray-300 rounded w-full text-center" readonly>
                    </div>
                    <div class="col-span-1 flex items-center justify-center">
                        <a href="javascript:void(0)" class="product-delete-btn flex items-center" onclick="removeRow(this)">
                            <i class="msr">cancel</i>
                        </a>
                    </div>
                `;

                tableBody.appendChild(newRow);

                // Attach event listeners to new row inputs for real-time calculation
                newRow.querySelectorAll('.unit-price, .q-input').forEach(input => {
                    input.addEventListener('input', () => calculateTotal(input));
                });

                // Recalculate totals for new rows
                calculateGrandTotal();
            }

            // Function to remove a row
            function removeRow(element) {
                const row = element.closest('.grid');
                row.remove();
                calculateGrandTotal();
            }

            // Function to increment quantity
            function incrementQty(element) {
                const input = element.closest('.relative').querySelector('.q-input');
                input.value = parseInt(input.value) + 1;
                calculateTotal(input);
            }

            // Function to decrement quantity
            function decrementQty(element) {
                const input = element.closest('.relative').querySelector('.q-input');
                input.value = Math.max(1, parseInt(input.value) - 1);
                calculateTotal(input);
            }

            // Function to calculate the total price for a row
            function calculateTotal(element) {
                const row = element.closest('.grid');
                const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                const quantity = parseInt(row.querySelector('.q-input').value) || 0;

                const subtotal = unitPrice * quantity;

                row.querySelector('.total-price').value = subtotal.toFixed(2);
                calculateGrandTotal();
            }

            // Function to calculate the grand total for all rows
            function calculateGrandTotal() {
                let totalAmount = 0;
                document.querySelectorAll('#productTableBody .grid').forEach(row => {
                    const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                    const quantity = parseInt(row.querySelector('.q-input').value) || 0;

                    totalAmount += unitPrice * quantity;
                });

                console.log('Total Amount: ', totalAmount);  // Debug statement

                // Get the discount percentage and calculate the discount amount
                const discountPercentageElem = document.getElementById('discountPercentage');
                const shippingCostElem = document.getElementById('shippingCost');
                const totalAmountElem = document.getElementById('totalAmount');
                const grandTotalElem = document.getElementById('grandTotal');

                if (discountPercentageElem && shippingCostElem && totalAmountElem && grandTotalElem) {
                    const discountPercentage = parseFloat(discountPercentageElem.value) || 0;
                    const totalDiscount = totalAmount * (discountPercentage / 100);

                    // Get the shipping cost and add it to the grand total
                    const shippingCost = parseFloat(shippingCostElem.value) || 0;
                    const grandTotal = totalAmount - totalDiscount + shippingCost;

                    totalAmountElem.innerText = `Rs${totalAmount.toFixed(2)}`;
                    grandTotalElem.innerText = `Rs${grandTotal.toFixed(2)}`;

                    console.log('Discount Percentage: ', discountPercentage);  // Debug statement
                    console.log('Total Discount: ', totalDiscount);  // Debug statement
                    console.log('Shipping Cost: ', shippingCost);  // Debug statement
                    console.log('Grand Total: ', grandTotal);  // Debug statement
                }
            }

            // Make functions available globally
            window.addRow = addRow;
            window.removeRow = removeRow;
            window.incrementQty = incrementQty;
            window.decrementQty = decrementQty;
            window.calculateTotal = calculateTotal;

            // Add initial row
            addRow();

            // Attach event listeners to the shipping cost and discount percentage input fields
            document.getElementById('shippingCost').addEventListener('input', calculateGrandTotal);
            document.getElementById('discountPercentage').addEventListener('input', calculateGrandTotal);
        });
    </script>
@endsection
