@extends('layout.admin')

@section('admin-content')
    <div class="max-w-7xl mx-auto">

        <!-- Page Header & Add Button -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products Management</h1>
                <p class="text-sm text-gray-500">Manage your inventory, prices, and stock.</p>
            </div>
            <a href="{{ route('admin.product.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add New Product
            </a>
        </div>

        <!-- Products Table Card -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Product</th>
                        <th class="py-3.5 px-6">SKU</th>
                        <th class="py-3.5 px-6">Price</th>
                        <th class="py-3.5 px-6">Stock</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition" onclick="window.location='{{ route('admin.product.show', $product->id) }}'">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                        @php
                                            $firstImage = $product->images->first()?->image;
                                        @endphp

                                        @if($firstImage)
                                            <img src="{{$firstImage}}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-400">ID: #{{ $product->id }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- SKU -->
                            <td class="py-4 px-6 font-mono text-xs text-gray-600">
                                {{ $product->sku }}
                            </td>

                            <!-- Price -->
                            <td class="py-4 px-6 font-semibold text-gray-900">
                                ${{ number_format($product->price, 2) }}
                            </td>

                            <!-- Stock -->
                            <td class="py-4 px-6">
                                @if($product->stock > 10)
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-green-50 text-green-700 rounded-full border border-green-200">
                                        {{ $product->stock }} in stock
                                    </span>
                                @elseif($product->stock > 0)
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-yellow-50 text-yellow-700 rounded-full border border-yellow-200">
                                        Low: {{ $product->stock }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-semibold bg-red-50 text-red-700 rounded-full border border-red-200">
                                        Out of stock
                                    </span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6">
                                @if($product->status == 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Edit Button -->
                                    <a href="#" class="p-2 bg-gray-50 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <!-- Delete Button -->
                                    <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-gray-50 text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-lg transition" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.186 1.15a2 2 0 01-1.95 1.65H7.136a2 2 0 01-1.95-1.65L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <p class="text-sm">No products found in the database.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-gray-200 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        @if($products->count())
                            Showing {{ $products->count() }} results
                        @endif
                    </p>

                    <div class="flex items-center gap-2">
                        {{-- Previous Page Link --}}
                        @if($products->previousPageUrl())
                            <a href="{{ $products->previousPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                &laquo; Previous
                            </a>
                        @else
                            <span class="px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                    &laquo; Previous
                </span>
                        @endif

                        {{-- Next Page Link --}}
                        @if($products->nextPageUrl())
                            <a href="{{ $products->nextPageUrl() }}" class="px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Next &raquo;
                            </a>
                        @else
                            <span class="px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 rounded-lg cursor-not-allowed">
                    Next &raquo;
                </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
