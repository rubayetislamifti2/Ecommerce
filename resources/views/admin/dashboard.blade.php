@extends('layout.admin')

@section('admin-content')
    <div>
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
            <p class="text-sm text-gray-500">Monitor products, sales, and orders from here.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase">Total Sales</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">$18,420</h3>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase">Total Orders</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">452</h3>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase">Total Products</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{$totalProducts}}</h3>
            </div>
            <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                <p class="text-xs font-semibold text-gray-400 uppercase">Total Users</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">1,200</h3>
            </div>
        </div>

        <!-- Product and Order Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Products -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <h2 class="font-bold text-gray-900 mb-4">Products Inventory</h2>
                <table class="w-full text-left text-sm">
                    <thead class="border-b text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="pb-2">Name</th>
                        <th class="pb-2">Price</th>
                        <th class="pb-2">Stock</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y text-gray-700">
                    @foreach($products as $product)
                        <tr>
                            <td class="py-3 font-medium text-gray-900">{{$product->name}}</td>
                            <td class="py-3">${{$product->price}}</td>
                            @if($product->stock == 0)
                                <td class="py-3 text-red-600 font-semibold">Out of stock</td>
                            @else
                                <td class="py-3 text-green-600 font-semibold">{{$product->stock}} in stock</td>
                            @endif

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Orders -->
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <h2 class="font-bold text-gray-900 mb-4">Recent Orders</h2>
                <table class="w-full text-left text-sm">
                    <thead class="border-b text-xs text-gray-400 uppercase">
                    <tr>
                        <th class="pb-2">Order ID</th>
                        <th class="pb-2">Customer</th>
                        <th class="pb-2">Status</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y text-gray-700">
                    <tr>
                        <td class="py-3 font-medium text-indigo-600">#ORD-101</td>
                        <td class="py-3">Rahim</td>
                        <td class="py-3"><span class="px-2 py-0.5 text-xs bg-green-100 text-green-700 rounded-full font-semibold">Paid</span></td>
                    </tr>
                    <tr>
                        <td class="py-3 font-medium text-indigo-600">#ORD-102</td>
                        <td class="py-3">Karim</td>
                        <td class="py-3"><span class="px-2 py-0.5 text-xs bg-yellow-100 text-yellow-700 rounded-full font-semibold">Pending</span></td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
