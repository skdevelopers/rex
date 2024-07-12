{{--resources/views/products/_form.blade.php--}}
<div class="card p-6">
    <div class="form-group">
        <label for="name" class="mb-2 block">Name:</label>
        <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $product->name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="description" class="mb-2 block">Description:</label>
        <textarea name="description" id="description" class="form-input" required>{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="category_id" class="mb-2 block">Category:</label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
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
        <input type="number" name="quantity" id="quantity" class="form-input" value="{{ old('quantity', $product->quantity ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="unit" class="mb-2 block">Unit:</label>
        <input type="text" name="unit" id="unit" class="form-input" value="{{ old('unit', $product->unit ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="unit_price" class="mb-2 block">Unit Price:</label>
        <input type="text" name="unit_price" id="unit_price" class="form-input" value="{{ old('unit_price', $product->unit_price ?? '') }}" required>
    </div>
</div>
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const subSubcategorySelect = document.getElementById('sub_subcategory_id');

            function loadCategories(url, elementId, placeholder) {
                const targetElement = document.getElementById(elementId);
                axios.get(url)
                    .then(function(response) {
                        let options = `<option value="">${placeholder}</option>`;
                        response.data.forEach(category => {
                            options += `<option value="${category.id}">${category.name}</option>`;
                        });
                        targetElement.innerHTML = options;
                    })
                    .catch(function(error) {
                        console.error('Error loading categories:', error);
                    });
            }

            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                loadCategories(`/categories/${categoryId}/subcategories`, 'subcategory_id', 'Select Subcategory');
                subSubcategorySelect.innerHTML = '<option value="">Select Sub-Subcategory</option>';
            });

            subcategorySelect.addEventListener('change', function() {
                const subcategoryId = this.value;
                loadCategories(`/subcategories/${subcategoryId}/subsubcategories`, 'sub_subcategory_id', 'Select Sub-Subcategory');
            });

            if (categorySelect.value) {
                loadCategories(`/categories/${categorySelect.value}/subcategories`, 'subcategory_id', 'Select Subcategory');
            }
            if (subcategorySelect.value) {
                loadCategories(`/subcategories/${subcategorySelect.value}/subsubcategories`, 'sub_subcategory_id', 'Select Sub-Subcategory');
            }
        });
    </script>
@endsection

