@extends('layout.admin')

@section('admin-content')
    <div class="max-w-4xl mx-auto">

        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Add New Product</h1>
                <p class="text-sm text-gray-500">Create a new product with multiple images and variants.</p>
            </div>
            <a href="{{route('admin.product.index')}}" class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium rounded-lg text-sm transition">
                &larr; Back to Products
            </a>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 lg:p-8">

            <form action="{{route('admin.product.store')}}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Product Name (Full Width) -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{old('name')}}" required placeholder="e.g. Wireless Headphones"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50">
                </div>

                <!-- Price, Stock & Status -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1">Price ($) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" id="price" name="price" value="{{old('price')}}" required placeholder="0.00"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50">
                    </div>

                    <!-- Stock -->
                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                        <input type="number" id="stock" name="stock" value="{{old('stock')}}" required placeholder="0" min="0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50">
                    </div>

                    <!-- Status -->
{{--                    <div>--}}
{{--                        <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>--}}
{{--                        <select id="status" name="status" required--}}
{{--                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50">--}}
{{--                            <option value="active" selected>Active</option>--}}
{{--                            <option value="inactive">Inactive</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                    <textarea id="description" name="description" rows="4" placeholder="Enter product details..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-gray-50/50">{{old('description')}}</textarea>
                </div>

                <!-- Multiple Product Images Section -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Product Images (Multiple)</label>

                    <!-- File Drag & Drop Box -->
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-gray-300 rounded-xl hover:border-indigo-500 transition cursor-pointer bg-gray-50/50 relative">
                        <input type="file" id="images" name="images[]" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileSelect(event)">

                        <div class="space-y-1 text-center pointer-events-none">
                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.01" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <span class="font-medium text-indigo-600 hover:text-indigo-500">Upload multiple files</span>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-400">PNG, JPG, WEBP up to 5MB each</p>
                        </div>
                    </div>

                    <!-- Dynamic Preview Container -->
                    <div id="image-preview" class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-4 mt-4"></div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="reset" onclick="clearPreview()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg text-sm hover:bg-gray-200 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-md shadow-indigo-600/20 transition">
                        Save Product
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- JavaScript for Dynamic File Selection & Individual Image Removal -->
    <script>
        const selectedFiles = new DataTransfer();

        function handleFileSelect(event) {
            const input = event.target;
            const previewContainer = document.getElementById('image-preview');
            const files = Array.from(input.files);

            files.forEach(file => {
                selectedFiles.items.add(file);

                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgWrapper = document.createElement('div');
                    imgWrapper.className = 'relative group rounded-xl overflow-hidden border border-gray-200 aspect-square bg-gray-100 shadow-sm';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-full object-cover';

                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className = 'absolute top-1.5 right-1.5 bg-red-500/90 hover:bg-red-600 text-white rounded-full p-1 transition opacity-90 group-hover:opacity-100 shadow-md';
                    deleteBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                `;

                    deleteBtn.onclick = function() {
                        removeFile(file, imgWrapper);
                    };

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(deleteBtn);
                    previewContainer.appendChild(imgWrapper);
                };
                reader.readAsDataURL(file);
            });

            input.files = selectedFiles.files;
        }

        function removeFile(fileToRemove, wrapperElement) {
            const input = document.getElementById('images');
            const dt = new DataTransfer();

            for (let i = 0; i < selectedFiles.files.length; i++) {
                const file = selectedFiles.files[i];
                if (file !== fileToRemove) {
                    dt.items.add(file);
                }
            }

            selectedFiles.items.clear();
            for (let i = 0; i < dt.files.length; i++) {
                selectedFiles.items.add(dt.files[i]);
            }
            input.files = selectedFiles.files;

            wrapperElement.remove();
        }

        function clearPreview() {
            selectedFiles.items.clear();
            document.getElementById('image-preview').innerHTML = '';
            document.getElementById('images').files = selectedFiles.files;
        }
    </script>
@endsection
