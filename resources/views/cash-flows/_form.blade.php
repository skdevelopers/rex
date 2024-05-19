<div>
    <label for="cash_receipts" class="text-gray-800 text-sm font-medium inline-block mb-2">Cash Receipts:</label>
    <input type="text" name="cash_receipts" id="cash_receipts" value="{{ old('cash_receipts', $cashFlow->cash_receipts ?? '') }}" class="form-input" required>
</div>

<div>
    <label for="cash_disbursements" class="text-gray-800 text-sm font-medium inline-block mb-2">Cash Disbursements:</label>
    <input type="text" name="cash_disbursements" id="cash_disbursements" value="{{ old('cash_disbursements', $cashFlow->cash_disbursements ?? '') }}" class="form-input" required>
</div>

<div>
    <label for="customer_id" class="text-gray-800 text-sm font-medium inline-block mb-2">Customer:</label>
    <select name="customer_id" id="customer_id" class="form-select">
        <option value="">Select Customer</option>
    </select>
</div>

<div>
    <label for="supplier_id" class="text-gray-800 text-sm font-medium inline-block mb-2">Supplier:</label>
    <select name="supplier_id" id="supplier_id" class="form-select">
        <option value="">Select Supplier</option>
    </select>
</div>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM Content Loaded');

            axios.get('/api/customers')
                .then(response => {
                    const customers = response.data;
                    const customerDropdown = document.getElementById('customer_id');
                    customerDropdown.innerHTML = '<option value="">Select Customer</option>';
                    customers.forEach(customer => {
                        const option = `<option value="${customer.id}">${customer.name}</option>`;
                        customerDropdown.innerHTML += option;
                    });
                })
                .catch(error => {
                    console.error('Error fetching customers:', error);
                });

            axios.get('/api/suppliers')
                .then(response => {
                    const suppliers = response.data;
                    const supplierDropdown = document.getElementById('supplier_id');
                    supplierDropdown.innerHTML = '<option value="">Select Supplier</option>';
                    suppliers.forEach(supplier => {
                        const option = `<option value="${supplier.id}">${supplier.name}</option>`;
                        supplierDropdown.innerHTML += option;
                    });
                })
                .catch(error => {
                    console.error('Error fetching suppliers:', error);
                });
        });

        document.getElementById('cash_receipts').addEventListener('input', function () {
            const customerField = document.getElementById('customer_id');
            if (this.value > 0) {
                customerField.setAttribute('required', 'required');
            } else {
                customerField.removeAttribute('required');
            }
        });

        document.getElementById('cash_disbursements').addEventListener('input', function () {
            const supplierField = document.getElementById('supplier_id');
            if (this.value > 0) {
                supplierField.setAttribute('required', 'required');
            } else {
                supplierField.removeAttribute('required');
            }
        });
    </script>
@endpush
