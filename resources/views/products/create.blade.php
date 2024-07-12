@extends('layouts.vertical', ['title' => 'Products Rex ERP', 'sub_title' => 'Products', 'mode' => $mode ?? '', 'demo' => $demo ?? ''])

@section('content')
    <div class="container">
        <div class="grid lg:grid-cols-4 gap-6">
            <div class="col-span-1 flex flex-col gap-6">
                <div class="card p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="card-title">Add Images</h4>
                        <div class="inline-flex items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-700 w-9 h-9">
                            <i class="mgc_add_line"></i>
                        </div>
                    </div>

                    <form action="#" class="dropzone text-gray-700 dark:text-gray-300 h-52">
                        <div class="fallback">
                            <input name="file" type="file" multiple="multiple">
                        </div>
                        <div class="dz-message needsclick w-full h-full flex items-center justify-center">
                            <i class="mgc_pic_2_line text-8xl"></i>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-3 space-y-6">
                <div class="card p-6">
                    <h2 class="mb-6">Create Product</h2>
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                        @include('products._form')

                        <div class="flex justify-end gap-3">
                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-500 focus:outline-none">
                                Cancel
                            </button>
                            <button type="submit" class="inline-flex items-center rounded-md border border-transparent bg-green-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none">
                                Create Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category_id');
            const subcategorySelect = document.getElementById('subcategory_id');
            const subSubcategorySelect = document.getElementById('sub_subcategory_id');

            function loadSubcategories(parentId, elementId, placeholder) {
                const targetElement = document.getElementById(elementId);
                axios.get(`/categories/${parentId}/subcategories`)
                    .then(function(response) {
                        let options = `<option value="">${placeholder}</option>`;
                        response.data.forEach(subcategory => {
                            options += `<option value="${subcategory.id}">${subcategory.name}</option>`;
                        });
                        targetElement.innerHTML = options;
                    })
                    .catch(function(error) {
                        console.error('Error loading subcategories:', error);
                    });
            }

            categorySelect.addEventListener('change', function() {
                const categoryId = this.value;
                loadSubcategories(categoryId, 'subcategory_id', 'Select Subcategory');
                subSubcategorySelect.innerHTML = '<option value="">Select Sub-Subcategory</option>';
            });

            subcategorySelect.addEventListener('change', function() {
                const subcategoryId = this.value;
                loadSubcategories(subcategoryId, 'sub_subcategory_id', 'Select Sub-Subcategory');
            });
        });
    </script>
@endsection
