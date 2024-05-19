<div class="card p-6">
    <div class="form-group">
        <label for="name" class="mb-2 block">Name:</label>
        <input type="text" name="name" id="name" class="form-input" value="{{ $product->name ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="description" class="mb-2 block">Description:</label>
        <textarea name="description" id="description" class="form-input"
                  required>{{ $product->description ?? '' }}</textarea>
    </div>

    <div class="form-group">
        <label for="category_id" class="mb-2 block">Category:</label>
        <select name="category_id" id="category_id" class="form-select" required>
            <option value="">Select Category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                        @if(isset($product) && $product->category_id == $category->id) selected @endif>{{ $category->name }}</option>
                @if ($category->children->count() > 0)
                    @foreach ($category->children as $subcategory)
                        <option value="{{ $subcategory->id }}"
                                @if(isset($product) && $product->subcategory_id == $subcategory->id) selected @endif>
                            &nbsp;&nbsp;&nbsp;{{ $subcategory->name }}</option>
                        @if ($subcategory->children->count() > 0)
                            {{-- Check if sub-subcategories exist --}}
                            @foreach ($subcategory->children as $subSubcategory)
                                <option value="{{ $subSubcategory->id }}"
                                        @if(isset($product) && $product->subcategory_id == $subSubcategory->id) selected @endif>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subSubcategory->name }}</option>
                            @endforeach
                        @endif
                    @endforeach
                @endif
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="subcategory_id" class="mb-2 block">Subcategory:</label>
        <select name="subcategory_id" id="subcategory_id" class="form-select" required>
            <option value="">Select Subcategory</option>
            @foreach ($subcategory->children as $subSubcategory)
                <option value="{{ $subSubcategory->id }}"
                        @if(isset($product) && $product->subcategory_id == $subSubcategory->id) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subSubcategory->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="attributes" class="mb-2 block">Attributes:</label>
        <select name="attributes[]" id="attributes" class="form-select" multiple>
            @foreach($attributes as $attribute)
                <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="quantity" class="mb-2 block">Quantity:</label>
        <input type="number" name="quantity" id="quantity" class="form-input" value="{{ $product->quantity ?? '' }}"
               required>
    </div>

    <div class="form-group">
        <label for="unit" class="mb-2 block">Unit:</label>
        <input type="text" name="unit" id="unit" class="form-input" value="{{ $product->unit ?? '' }}" required>
    </div>

    <div class="form-group">
        <label for="unit_price" class="mb-2 block">Unit Price:</label>
        <input type="text" name="unit_price" id="unit_price" class="form-input" value="{{ $product->unit_price ?? '' }}"
               required>
    </div>
</div>

@push('script')
    <script>
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            if (!categoryId) {
                document.getElementById('subcategory_id').innerHTML = '<option value="">Select Subcategory</option>';
                return;
            }

            axios.get(`/categories/${categoryId}/subcategories`)
                .then(function(response) {
                    let subcategoryOptions = '<option value="">Select Subcategory</option>';
                    for (const id in response.data) {
                        subcategoryOptions += `<option value="${id}">${response.data[id]}</option>`;
                    }
                    document.getElementById('subcategory_id').innerHTML = subcategoryOptions;
                })
                .catch(function(error) {
                    console.error('Error:', error);
                });
        });
    </script>
@endpush