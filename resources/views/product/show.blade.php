@extends('layout.admin')

@section('admin-content')
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Top Action Bar -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.product.index') }}" class="p-2 bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-lg shadow-sm transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Product Details</h1>
                    <p class="text-xs text-gray-500">View complete overview and image gallery of this product.</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="#" class="px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-semibold text-sm rounded-lg transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Product
                </a>
            </div>
        </div>

        <!-- Product Card Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

            <!-- Left Side: Images Section (5 Columns) -->
            <div class="lg:col-span-5 space-y-4">
                @php
                    // index 0 এর প্রথম ছবিটি ব্যাকআপ হিসেবে প্রাইমারি ইমেজ হবে
                    $firstImage = $product->images->first()?->image;
                @endphp

                    <!-- Main Featured Image (Index 0) -->
                <div class="aspect-square bg-gray-100 rounded-xl overflow-hidden border border-gray-200 shadow-inner relative flex items-center justify-center">
                    @if($firstImage)
                        <img id="main-product-image" src="{{$firstImage}}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-all duration-300">
                    @else
                        <div class="text-center text-gray-400 p-6">
                            <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-xs">No image uploaded</p>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail List -->
                @if($product->images->count() > 1)
                    <div>
                        <p class="text-xs font-medium text-gray-400 mb-2">Gallery Images ({{ $product->images->count() }})</p>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $key => $img)
                                <button type="button" onclick="changeMainImage('{{ $img->image }}')" class="aspect-square rounded-lg border-2 border-gray-200 hover:border-indigo-600 focus:border-indigo-600 overflow-hidden bg-gray-50 transition shadow-sm">
                                    <img src="{{ $img->image }}" alt="Thumbnail {{ $key + 1 }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Side: Details Section (7 Columns) -->
            <div class="lg:col-span-7 flex flex-col justify-between space-y-6">
                <div class="space-y-4">

                    <!-- Status & SKU Badges -->
                    <div class="flex items-center gap-3">
                        @if($product->status == 'active')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                        </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">
                            <span class="w-2 h-2 rounded-full bg-gray-400"></span> Inactive
                        </span>
                        @endif

                        <span class="text-xs font-mono px-3 py-1 bg-slate-100 text-slate-600 rounded-full border border-slate-200">
                        SKU: {{ $product->sku }}
                    </span>
                    </div>

                    <!-- Product Name & ID -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 leading-tight">{{ $product->name }}</h2>
                        <p class="text-xs text-gray-400 mt-1">System ID: #{{ $product->id }} &bull; Slug: <span class="font-mono text-gray-500">{{ $product->slug }}</span></p>
                    </div>

                    <!-- Price & Stock Metrics Card -->
                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Unit Price</span>
                            <p class="text-2xl font-extrabold text-indigo-600 mt-0.5">${{ number_format($product->price, 2) }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Current Stock</span>
                            <p class="text-2xl font-bold mt-0.5 {{ $product->stock > 0 ? 'text-gray-900' : 'text-red-500' }}">
                                {{ $product->stock }} <span class="text-xs font-normal text-gray-500">units</span>
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700 mb-1">Description</h4>
                        <div class="text-sm text-gray-600 leading-relaxed bg-white p-4 rounded-xl border border-gray-100">
                            {!! nl2br(e($product->description ?? 'No description provided.')) !!}
                        </div>
                    </div>

                </div>

                <!-- Footer Metadata -->
                <div class="pt-4 border-t border-gray-100 text-xs text-gray-400 flex justify-between">
                    <span>Created: {{ $product->created_at ? $product->created_at->format('M d, Y h:i A') : 'N/A' }}</span>
                    <span>Last Updated: {{ $product->updated_at ? $product->updated_at->format('M d, Y h:i A') : 'N/A' }}</span>
                </div>

            </div>

        </div>
    </div>

    <!-- Image Switcher Script -->
    <script>
        function changeMainImage(imageSrc) {
            const mainImage = document.getElementById('main-product-image');
            if (mainImage) {
                mainImage.src = imageSrc;
            }
        }
    </script>
@endsection
