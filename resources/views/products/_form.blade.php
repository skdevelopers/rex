<div class="card p-6">
    <div class="form-group">
        <label for="name" class="mb-2 block">Name:</label>
        <input type="text" name="name" id="name" class="form-input" value="{{ $product->name ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="description" class="mb-2 block">Description:</label>
        <textarea name="description" id="description" class="form-input" required>{{ $product->description ?? '' }}</textarea>
    </div>

    <div class="form-group">
        <label for="category_id" class="mb-2 block">Category:</label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                        @if(isset($product) && $product->category_id == $category->id) selected @endif>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="subcategory_id" class="mb-2 block">Subcategory:</label>
        <select name="subcategory_id" id="subcategory_id" class="form-select">
            <option value="">Select Subcategory</option>
        </select>
    </div>

    <div class="form-group">
        <label for="sub_subcategory_id" class="mb-2 block">Sub-Subcategory:</label>
        <select name="sub_subcategory_id" id="sub_subcategory_id" class="form-select">
            <option value="">Select Sub-Subcategory</option>
        </select>
    </div>

    <div class="form-group">
        <label for="quantity" class="mb-2 block">Quantity:</label>
        <input type="number" name="quantity" id="quantity" class="form-input" value="{{ $product->quantity ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="unit" class="mb-2 block">Unit:</label>
        <input type="text" name="unit" id="unit" class="form-input" value="{{ $product->unit ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="unit_price" class="mb-2 block">Unit Price:</label>
        <input type="text" name="unit_price" id="unit_price" class="form-input" value="{{ $product->unit_price ?? '' }}" required>
    </div>
</div>

@push('scripts')
    <script>
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            loadSubcategories(categoryId, 'subcategory_id', 'Select Subcategory');
            document.getElementById('sub_subcategory_id').innerHTML = '<option value="">Select Sub-Subcategory</option>';
        });

        document.getElementById('subcategory_id').addEventListener('change', function() {
            const subcategoryId = this.value;
            loadSubcategories(subcategoryId, 'sub_subcategory_id', 'Select Sub-Subcategory');
        });

        function loadSubcategories(parentId, elementId, placeholder) {
            const targetElement = document.getElementById(elementId);
            if (!parentId) {
                targetElement.innerHTML = `<option value="">${placeholder}</option>`;
                return;
            }

            axios.get(`/categories/${parentId}/subcategories`)
                    .then(function(response) {
                        let options = `<option value="">${placeholder}</option>`;
                        response.data.forEach(subcategory => {
                            options += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                        });
                        targetElement.innerHTML = options;
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                    });
        }
    </script>
@endpush

