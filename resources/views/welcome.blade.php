@extends('layout.app')

@section('content')
    <div class="bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header & Filter Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-200 pb-6 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Our Products</h1>
                    <p class="mt-2 text-sm text-gray-500">Explore our latest collection of premium products.</p>
                </div>

                <!-- Sorting & Search Filters -->
                <div class="mt-4 md:mt-0 flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <!-- Search Box -->
                    <div class="relative w-full sm:w-64">
                        <input type="text" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Sort Dropdown -->
                    <select class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Sort by: Featured</option>
                        <option value="low-high">Price: Low to High</option>
                        <option value="high-low">Price: High to Low</option>
                        <option value="newest">Newest Arrivals</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

                @forelse($products as $product)
                    @php
                        $firstImage = $product->images->first()?->image;
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col">
                        <div class="relative group">
                            <a href="{{ route('user.products.show', $product) }}">
                                @if($firstImage)
                                    <img src="{{ $firstImage }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </a>

                            @if(!$product->status)
                                <span class="absolute top-3 left-3 bg-gray-500 text-white text-xs font-bold px-2 py-1 rounded">Inactive</span>
                            @elseif($product->stock <= 0)
                                <span class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Out of Stock</span>
                            @endif

                            <button class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm p-1.5 rounded-full text-gray-600 hover:text-red-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            </button>
                        </div>
                        <div class="p-4 flex-grow flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-bold text-gray-900 mt-1 hover:text-indigo-600 transition">
                                    <a href="{{ route('admin.product.show', $product) }}">{{ $product->name }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $product->description ?? 'No description available.' }}</p>
                            </div>
                            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                </div>
                                @if(Auth::check())
                                    <form action="{{route('add.to.cart')}}" method="POST">
                                        @csrf
                                        <input type="hidden" name="items[0][product_id]" value="{{ $product->id }}">
                                        <input type="hidden" name="items[0][quantity]" value="1">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{route('login-page')}}" class="bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 text-gray-400">
                        <p class="text-sm">No products available at the moment.</p>
                    </div>
                @endforelse

            </div>

            <!-- Pagination Bar (Cursor-based: Prev/Next) -->
            @if($products->hasPages())
                <div class="mt-12 flex justify-center">
                    <nav class="inline-flex rounded-md shadow-sm gap-2">
                        @if($products->previousPageUrl())
                            <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                                &laquo; Previous
                            </a>
                        @else
                            <span class="px-4 py-2 rounded-md border border-gray-200 bg-gray-50 text-sm font-medium text-gray-300 cursor-not-allowed">
                                &laquo; Previous
                            </span>
                        @endif

                        @if($products->nextPageUrl())
                            <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                                Next &raquo;
                            </a>
                        @else
                            <span class="px-4 py-2 rounded-md border border-gray-200 bg-gray-50 text-sm font-medium text-gray-300 cursor-not-allowed">
                                Next &raquo;
                            </span>
                        @endif
                    </nav>
                </div>
            @endif

        </div>
    </div>
@endsection
