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
                <option value="{{ $category->id }}" @if(isset($product) && $product->category_id == $category->id) selected @endif>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="subcategory_id" class="mb-2 block">Subcategory:</label>
        <select name="subcategory_id" id="subcategory_id" class="form-select">
            <option value="">Select Subcategory</option>
            @if(isset($product->subcategory_id))
                <option value="{{ $product->subcategory_id }}" selected>{{ $product->subcategory->name }}</option>
            @endif
        </select>
    </div>

    <div class="form-group">
        <label for="sub_subcategory_id" class="mb-2 block">Sub-Subcategory:</label>
        <select name="sub_subcategory_id" id="sub_subcategory_id" class="form-select">
            <option value="">Select Sub-Subcategory</option>
            @if(isset($product->sub_subcategory_id))
                <option value="{{ $product->sub_subcategory_id }}" selected>{{ $product->subSubcategory->name }}</option>
            @endif
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
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const subSubcategorySelect = document.getElementById('sub_subcategory_id');

            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                console.log('Category changed:', categoryId); // Debug log
                loadSubcategories(categoryId, 'subcategory_id', 'Select Subcategory');
                subSubcategorySelect.innerHTML = '<option value="">Select Sub-Subcategory</option>';
            });

            subcategorySelect.addEventListener('change', function() {
                const subcategoryId = this.value;
                console.log('Subcategory changed:', subcategoryId); // Debug log
                loadSubcategories(subcategoryId, 'sub_subcategory_id', 'Select Sub-Subcategory');
            });

            function loadSubcategories(parentId, elementId, placeholder) {
                console.log('Loading subcategories for parent ID:', parentId); // Debug log
                const targetElement = document.getElementById(elementId);
                if (!parentId) {
                    targetElement.innerHTML = `<option value="">${placeholder}</option>`;
                    console.log('No parent ID provided, clearing subcategories.'); // Debug log
                    return;
                }

                axios.get(`/categories/${parentId}/subcategories`)
                    .then(function(response) {
                        console.log('Subcategories loaded:', response.data); // Debug log
                        let options = `<option value="">${placeholder}</option>`;
                        if (response.data.length > 0) {
                            response.data.forEach(subcategory => {
                                options += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                            });
                        } else {
                            options += '<option value="">N/A</option>';
                            options += '<option value="create"><a href="/subcategories/create">Create new subcategory</a></option>';
                        }
                        targetElement.innerHTML = options;
                    })
                    .catch(function(error) {
                        console.error('Error loading subcategories:', error); // Debug log
                    });
            }

            // Initial population on edit if category is selected
            if (categorySelect.value) {
                loadSubcategories(categorySelect.value, 'subcategory_id', 'Select Subcategory');
            }
            if (subcategorySelect.value) {
                loadSubcategories(subcategorySelect.value, 'sub_subcategory_id', 'Select Sub-Subcategory');
            }
        });
    </script>
@endpush
