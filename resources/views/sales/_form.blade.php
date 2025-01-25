{{-- resources/views/sales/_form.blade.php --}}
<div class="grid grid-cols-12 gap-x-7 maxSm:gap-x-0 form-container">
    <div class="xl:col-span-4 lg:col-span-4 md:col-span-6 col-span-12">
        <div class="cashier-select-field mb-5">
            <h5 class="text-[15px] text-heading font-semibold mb-3">Select Supplier</h5>
            <select class="block w-full form-input" name="supplier" id="supplier-select" required>
                <option selected disabled value="default">Select Supplier</option>
                <!-- Suppliers will be loaded dynamically using axios -->
            </select>

        </div>
    </div>

    <div class="xl:col-span-3 lg:col-span-4 md:col-span-6 col-span-12">
        <div class="cashier-select-field mb-5">
            <h5 class="text-[15px] text-heading font-semibold mb-3">Bill Date</h5>
            <div class="cashier-select-field-style">
                <div class="form-group col-span-3">
                    <input type="date" class="form-input" name="bill_date" value="{{ old('bill_date', $sale->bill_date ?? '') }}" required>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-3 lg:col-span-4 md:col-span-6 col-span-12">
        <div class="cashier-select-field mb-5">
            <h5 class="text-[15px] text-heading font-semibold mb-3">Due Date</h5>
            <div class="cashier-select-field-style">
                <div class="form-group col-span-3">
                    <input type="date" class="form-input" name="due_date" value="{{ old('due_date', $sale->due_date ?? '') }}" required>
                </div>
            </div>
        </div>
    </div>

    <div class="xl:col-span-2 lg:col-span-4 md:col-span-6 col-span-12">
        <div class="cashier-select-field mb-5">
            <h5 class="text-[15px] text-heading font-semibold mb-3">Order #</h5>
            <div class="cashier-select-field-style">
                <div class="form-group col-span-3">
                    <input type="text" class="form-input" name="order" value="{{ old('order', $sale->order ?? '') }}" required>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="cashier-addsale-product-area pt-7">
    <div class="cashier-addsale-product-wrapper-inner">
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
                <!-- Products dynamically added with JavaScript and axios -->
            </div>
            <div class="mt-4 flex items-center justify-end px-4">
                <button type="button" onclick="addRow()" class="bg-blue-500 text-white py-2 px-4 rounded-md flex items-center"><i class="msr">add</i></button>
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
            <input type="number" id="discountPercentage" placeholder="0.00" class="h-8 w-16 bg-gray-200 border-none">
        </li>
        <li class="px-4 py-2.5 border-b border-solid border-gray-300 bg-gray-200">
            <span class="text-[15px] font-normal text-heading w-40 inline-block">Shipping <span class="float-right">:</span></span>
            <input type="text" id="shippingCost" placeholder="0.00" class="h-8 w-16 bg-gray-100 border-none">
        </li>
        <li class="px-4 py-2.5">
            <span class="text-[15px] font-bold text-heading w-40 inline-block">Grand Total <span class="float-right font-normal">:</span></span>
            <span id="grandTotal" class="text-[15px] font-bold text-heading inline-block">Rs0.00</span>
        </li>
    </ul>
</div>

<div class="grid grid-cols-12 gap-x-7 maxSm:gap-x-0">
    <div class="col-span-12">
        <div class="cashier-select-field mb-5">
            <h5 class="text-[15px] text-heading font-semibold mb-3">Purchase Note:</h5>
            <div class="cashier-input-field-style relative">
                <textarea name="purchase_note" placeholder="Type text...." class="w-full border border-gray-300 rounded-md p-2">{{ old('purchase_note', $sale->purchase_note ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        let rowCount = 0;

        // Load suppliers dynamically
        function loadSuppliers() {
            axios.get('/api/suppliers')
                .then(response => {
                    const supplierSelect = document.getElementById('supplier-select');
                    supplierSelect.innerHTML = '<option selected disabled value="default">Select Supplier</option>';
                    response.data.forEach(supplier => {
                        const option = document.createElement('option');
                        option.value = supplier.id;
                        option.text = supplier.name;
                        supplierSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching suppliers:', error);
                    alert('Failed to load suppliers. Please try again later.');
                });
        }

        // Add a new row
        function addRow() {
            rowCount++;
            const tableBody = document.getElementById('productTableBody');
            const newRow = document.createElement('div');
            newRow.classList.add('grid', 'grid-cols-12', 'gap-4', 'py-4');
            newRow.dataset.rowId = rowCount; // Add unique identifier for each row

            newRow.innerHTML = `
            <div class="col-span-1 flex items-center justify-center">
                <span>${rowCount}</span>
            </div>
            <div class="col-span-5 flex items-center justify-center">
                <input type="text" placeholder="Scan / search products by code / name" class="border border-gray-300 rounded w-full text-left product-search" oninput="searchProducts(this)">
            </div>
            <div class="col-span-1 flex items-center justify-center">
                <input type="text" class="unit border border-gray-300 rounded w-full text-center" readonly>
            </div>
            <div class="col-span-1 flex items-center justify-center">
                <input type="number" class="unit-price border border-gray-300 rounded w-full text-center" oninput="calculateTotal(this)" min="0" step="0.01">
            </div>
            <div class="col-span-1 flex items-center justify-center">
                <div class="relative">
                    <input type="number" class="q-input border border-gray-300 rounded w-20 text-center pr-8" value="1" min="1" oninput="calculateTotal(this)">
                    <div class="absolute inset-y-0 right-0 flex flex-col items-center justify-center h-full">
                        <button type="button" class="q-up p-1 bg-gray-300 rounded-t flex items-center justify-center w-8 h-1/2" onclick="incrementQty(this)">
                            <i class="msr">arrow_drop_up</i>
                        </button>
                        <button type="button" class="q-down p-1 bg-gray-300 rounded-b flex items-center justify-center w-8 h-1/2" onclick="decrementQty(this)">
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
        }

        // Search products dynamically
        function searchProducts(inputElement) {
            const query = inputElement.value;
            if (query.length > 2) {
                axios.get(`/api/products?query=${query}`)
                    .then(response => {
                        if (response.data.length > 0) {
                            const product = response.data[0]; // Select the first result
                            const row = inputElement.closest('.grid');
                            row.querySelector('.unit').value = product.unit;
                            row.querySelector('.unit-price').value = product.unit_price;
                            calculateTotal(row.querySelector('.unit-price'));
                        } else {
                            alert('No products found for the search query.');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching products:', error);
                        alert('Failed to fetch products. Please try again.');
                    });
            }
        }

        // Increment quantity
        function incrementQty(button) {
            const input = button.closest('.relative').querySelector('.q-input');
            input.value = parseInt(input.value) + 1;
            calculateTotal(input);
        }

        // Decrement quantity
        function decrementQty(button) {
            const input = button.closest('.relative').querySelector('.q-input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                calculateTotal(input);
            }
        }

        // Calculate total for a single row
        function calculateTotal(inputElement) {
            const row = inputElement.closest('.grid');
            const unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            const quantity = parseInt(row.querySelector('.q-input').value) || 0;
            const totalPrice = (unitPrice * quantity).toFixed(2);
            row.querySelector('.total-price').value = totalPrice;

            calculateGrandTotal();
        }

        // Calculate grand total
        function calculateGrandTotal() {
            let grandTotal = 0;
            document.querySelectorAll('.total-price').forEach(input => {
                grandTotal += parseFloat(input.value) || 0;
            });
            document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
        }

        // Remove a row
        function removeRow(button) {
            const row = button.closest('.grid');
            row.remove();
            calculateGrandTotal();
        }

        // Initialize the form
        document.addEventListener('DOMContentLoaded', function() {
            loadSuppliers(); // Load suppliers dynamically when the form loads
            addRow(); // Add the first row when the form loads
        });
    </script>
@endpush

