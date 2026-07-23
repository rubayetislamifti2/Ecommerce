@extends('layout.app')

@section('content')
    <div class="bg-gray-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumb / Back Link -->
            <div class="mb-6">
                <a href="#" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to Products
                </a>
            </div>

            <!-- Main Product Section Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 lg:p-10">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

                    <!-- Left Side: Product Gallery (5 Columns) -->
                    <div class="lg:col-span-5 space-y-4" x-data="{ activeImage: '{{ $product->images->first()?->image ? $product->images->first()->image : '' }}' }">
                        @php
                            $firstImage = $product->images->first()?->image;
                        @endphp

                            <!-- Featured Image Container -->
                        <div class="aspect-square bg-gray-50 rounded-xl overflow-hidden border border-gray-100 relative group flex items-center justify-center">
                            @if($firstImage)
                                <img :src="activeImage ? activeImage : '{{ $firstImage }}'" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" id="main-product-image">
                            @else
                                <div class="w-full h-full bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs">No image available</span>
                                </div>
                            @endif

                            <!-- Status Badges -->
                            @if(!$product->status)
                                <span class="absolute top-4 left-4 bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-md shadow-sm">Inactive</span>
                            @elseif($product->stock <= 0)
                                <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-md shadow-sm">Out of Stock</span>
                            @endif
                        </div>

                        <!-- Gallery Thumbnails (Index 0, 1, 2...) -->
                        @if($product->images->count() > 1)
                            <div class="grid grid-cols-5 gap-3">
                                @foreach($product->images as $img)
                                    <button type="button" @click="activeImage = '{{ $img->image }}'" class="aspect-square rounded-lg border-2 border-gray-100 hover:border-indigo-600 focus:border-indigo-600 overflow-hidden bg-gray-50 transition shadow-sm">
                                        <img src="{{ $img->image }}" alt="Thumbnail" class="w-full h-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Right Side: Product Details & Add to Cart (7 Columns) -->
                    <div class="lg:col-span-7 flex flex-col justify-between space-y-6">
                        <div class="space-y-4">

                            <!-- Title & Stock Status -->
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    @if($product->stock > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        In Stock ({{ $product->stock }} left)
                                    </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Out of Stock
                                    </span>
                                    @endif
                                </div>
                                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-snug">{{ $product->name }}</h1>
                            </div>

                            <!-- Price Section -->
                            <div class="flex items-baseline gap-3 pb-4 border-b border-gray-100">
                                <span class="text-3xl font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-400">Inclusive of all taxes</span>
                            </div>

                            <!-- Description -->
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Overview</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">
                                    {{ $product->description ?? 'No detailed description available for this product.' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100 space-y-4" x-data="{ quantity: 1, maxStock: {{ $product->stock }} }">

                            <form action="{{ route('add.to.cart') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">

                                <!-- Quantity Selector -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Quantity</label>
                                    <div class="flex items-center border border-gray-300 rounded-lg w-32 bg-gray-50">
                                        <button type="button" @click="if(quantity > 1) quantity--" class="px-3 py-2 text-gray-600 hover:text-indigo-600 font-bold transition text-sm">
                                            &minus;
                                        </button>
                                        <input type="number" name="items[0][quantity]" x-model="quantity" min="1" :max="maxStock" class="w-full text-center bg-transparent border-none text-sm font-bold text-gray-900 focus:outline-none p-0" readonly>
                                        <button type="button" @click="if(quantity < maxStock) quantity++" class="px-3 py-2 text-gray-600 hover:text-indigo-600 font-bold transition text-sm">
                                            &plus;
                                        </button>
                                    </div>
                                </div>

                                <!-- Action Buttons Grid -->
                                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                    @auth
                                        <!-- Add to Cart Button -->
                                        <button type="submit"
                                                @if($product->stock <= 0 || !$product->status) disabled @endif
                                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                            <span>Add to Cart</span>
                                        </button>
                                    @else
                                        <!-- Not logged in -> Login e pathao -->
                                        <a href="{{ route('login-page') }}"
                                           class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-md shadow-indigo-600/20 transition flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span>Login to Add Cart</span>
                                        </a>
                                    @endauth

                                    <!-- Wishlist Button -->
                                    <button type="button" class="p-3 border border-gray-200 text-gray-600 hover:text-red-500 hover:border-red-200 rounded-xl transition flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    </button>
                                </div>
                            </form>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
