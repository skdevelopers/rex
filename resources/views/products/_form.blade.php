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
            @if (isset($product) && $product->subcategory)
                <option value="{{ $product->subcategory->id }}" selected>{{ $product->subcategory->name }}</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label for="sub_subcategory_id" class="mb-2 block">Sub-Subcategory:</label>
        <select name="sub_subcategory_id" id="sub_subcategory_id" class="form-select">
            <option value="">Select Sub-Subcategory</option>
            @if (isset($product) && $product->subSubcategory)
                <option value="{{ $product->subSubcategory->id }}" selected>{{ $product->subSubcategory->name }}</option>
            @endif
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded and parsed');

            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const subSubcategorySelect = document.getElementById('sub_subcategory_id');

            function loadCategories(url, elementId, placeholder, selectedValue = null) {
                console.log(`Loading categories from URL: ${url} into element: ${elementId}`);
                const targetElement = document.getElementById(elementId);
                targetElement.innerHTML = `<option value="">${placeholder}</option>`; // Reset options

                axios.get(url)
                    .then(function(response) {
                        let options = `<option value="">${placeholder}</option>`;
                        response.data.forEach(category => {
                            options += `<option value="${category.id}">${category.name}</option>`;
                        });
                        targetElement.innerHTML = options;

                        // Select the previous value if available
                        if (selectedValue) {
                            targetElement.value = selectedValue;
                        }
                    })
                    .catch(function(error) {
                        console.error('Error loading categories:', error);
                    });
            }

            if (categorySelect) {
                categorySelect.addEventListener('change', function() {
                    console.log(`Category changed: ${this.value}`);
                    const categoryId = this.value;
                    const url = `/categories/${categoryId}/subcategories`;
                    console.log(`Requesting subcategories from URL: ${url}`);
                    loadCategories(url, 'subcategory_id', 'Select Subcategory');
                    subSubcategorySelect.innerHTML = '<option value="">Select Sub-Subcategory</option>'; // Reset sub-subcategory options
                });
            } else {
                console.error('Category select element not found.');
            }

            if (subcategorySelect) {
                subcategorySelect.addEventListener('change', function() {
                    console.log(`Subcategory changed: ${this.value}`);
                    const subcategoryId = this.value;
                    const url = `/subcategories/${subcategoryId}/subsubcategories`;
                    console.log(`Requesting sub-subcategories from URL: ${url}`);
                    loadCategories(url, 'sub_subcategory_id', 'Select Sub-Subcategory');
                });
            } else {
                console.error('Subcategory select element not found.');
            }

            // Initial load if editing
            if (categorySelect && categorySelect.value) {
                console.log(`Initial load for category: ${categorySelect.value}`);
                const url = `/categories/${categorySelect.value}/subcategories`;
                console.log(`Requesting subcategories from URL: ${url}`);
                loadCategories(url, 'subcategory_id', 'Select Subcategory', "{{ $product->subcategory_id ?? '' }}");
            }
            if (subcategorySelect && subcategorySelect.value) {
                console.log(`Initial load for subcategory: ${subcategorySelect.value}`);
                const url = `/subcategories/${subcategorySelect.value}/subsubcategories`;
                console.log(`Requesting sub-subcategories from URL: ${url}`);
                loadCategories(url, 'sub_subcategory_id', 'Select Sub-Subcategory', "{{ $product->sub_subcategory_id ?? '' }}");
            }
        });
    </script>
@endpush
