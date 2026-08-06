@extends('layout.app')

@section('content')
    <div class="bg-gray-50 py-10 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h1 class="text-3xl font-extrabold text-gray-900 mb-8">Shopping Cart & Checkout</h1>

            @if($order && $order->orderItems->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                    <!-- Left Side: Cart Items List (7 Columns) -->
                    <div class="lg:col-span-7 space-y-4">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                            <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                                <h2 class="text-lg font-bold text-gray-900">Your Cart Items ({{ $order->orderItems->count() }})</h2>
                                <span class="text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full border border-amber-200">
                                Order #{{ $order->id }} ({{ ucfirst($order->status) }})
                            </span>
                            </div>

                            <div class="divide-y divide-gray-100">
                                @foreach($order->orderItems as $item)
                                    @php

                                        $firstImage = $item->product->images->first()?->image;
                                    @endphp
                                    <div class="py-4 flex items-center gap-4">
                                        <!-- Product Image -->
                                        <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 flex-shrink-0">
                                            @if($firstImage)
                                                <img src="{{ $firstImage }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Details -->
                                        <div class="flex-1">
                                            <h3 class="font-bold text-gray-900 text-base leading-tight">{{ $item->product->name }}</h3>
                                            <p class="text-xs text-gray-400 mt-1">Unit Price: ${{ number_format($item->price, 2) }}</p>
                                            <p class="text-sm font-semibold text-gray-700 mt-2">Qty: {{ $item->quantity }}</p>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="text-right">
                                            <span class="text-lg font-extrabold text-gray-900">${{ number_format($item->sub_total, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Ready to Checkout / Shipping Details (5 Columns) -->
                    <div class="lg:col-span-5">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-6 space-y-6">

                            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3">Order Summary & Checkout</h2>

                            <!-- Price Breakdown -->
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Subtotal</span>
                                    <span class="font-semibold text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Shipping Fee</span>
                                    <span class="font-semibold text-green-600">Free</span>
                                </div>
                                <div class="border-t border-gray-100 pt-3 flex justify-between items-baseline">
                                    <span class="text-base font-bold text-gray-900">Total Amount</span>
                                    <span class="text-2xl font-black text-indigo-600">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>

                            <!-- Checkout Form (Shipping & Payment) -->
                            <form action="{{route('order.checkout')}}" method="POST" class="space-y-4 pt-2">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                <input type="hidden" name="total_amount" value="{{$order->total_amount}}">

{{--                                <div>--}}
{{--                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Shipping Address</label>--}}
{{--                                    <textarea name="shipping_address" rows="2" required placeholder="Enter full address..." class="w-full p-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none"></textarea>--}}
{{--                                </div>--}}

{{--                                <div>--}}
{{--                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Phone Number</label>--}}
{{--                                    <input type="text" name="phone" required placeholder="+880 1700..." class="w-full p-3 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:outline-none">--}}
{{--                                </div>--}}

                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-3">Payment Method</label>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                                        <!-- Cash on Delivery (COD) Option -->
                                        <label class="relative flex flex-col items-center justify-between p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30 has-[:checked]:ring-2 has-[:checked]:ring-indigo-600 transition shadow-sm">
                                            <input type="radio" name="provider" value="cod" class="sr-only" checked>
                                            <div class="flex items-center justify-between w-full mb-2">
                                                <!-- COD Image Logo -->
                                                <div class="h-8 flex items-center">
                                                    <img src="{{ asset('cash-on-delivery.png') }}" alt="Cash on Delivery" class="h-7 w-auto object-contain">
                                                </div>
                                                <span class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center bg-white check-icon">
                                                    <span class="w-2 h-2 bg-indigo-600 rounded-full hidden"></span>
                                                </span>
                                            </div>
                                            <span class="text-xs font-bold text-gray-800 self-start">Cash on Delivery</span>
                                        </label>

                                        <!-- Stripe Option -->
                                        <label class="relative flex flex-col items-center justify-between p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30 has-[:checked]:ring-2 has-[:checked]:ring-indigo-600 transition shadow-sm">
                                            <input type="radio" name="provider" value="stripe" class="sr-only">
                                            <div class="flex items-center justify-between w-full mb-2">
                                                <!-- Stripe Image Logo -->
                                                <div class="h-8 flex items-center">
                                                    <img src="{{ asset('stripe.png') }}" alt="Stripe" class="h-6 w-auto object-contain">
                                                </div>
                                                <span class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center bg-white check-icon">
                                                    <span class="w-2 h-2 bg-indigo-600 rounded-full hidden"></span>
                                                </span>
                                            </div>
                                            <span class="text-xs font-bold text-gray-800 self-start">Stripe Payment</span>
                                        </label>

                                        <!-- bKash Option -->
                                        <label class="relative flex flex-col items-center justify-between p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30 has-[:checked]:ring-2 has-[:checked]:ring-indigo-600 transition shadow-sm">
                                            <input type="radio" name="provider" value="bkash" class="sr-only">
                                            <div class="flex items-center justify-between w-full mb-2">
                                                <!-- bKash Image Logo -->
                                                <div class="h-8 flex items-center">
                                                    <img src="{{ asset('bkash-seeklogo.png') }}" alt="bKash" class="h-7 w-auto object-contain">
                                                </div>
                                                <span class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center bg-white check-icon">
                                                    <span class="w-2 h-2 bg-indigo-600 rounded-full hidden"></span>
                                                </span>
                                            </div>
                                            <span class="text-xs font-bold text-gray-800 self-start">bKash Online</span>
                                        </label>

                                        <label class="relative flex flex-col items-center justify-between p-4 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-indigo-500 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50/30 has-[:checked]:ring-2 has-[:checked]:ring-indigo-600 transition shadow-sm">
                                            <input type="radio" name="provider" value="sslcommerz" class="sr-only">
                                            <div class="flex items-center justify-between w-full mb-2">
                                                <!-- bKash Image Logo -->
                                                <div class="h-8 flex items-center">
                                                    <img src="{{ asset('globe.png') }}" alt="sslcommerz" class="h-7 w-auto object-contain">
                                                </div>
                                                <span class="w-4 h-4 border border-gray-300 rounded-full flex items-center justify-center bg-white check-icon">
                                                    <span class="w-2 h-2 bg-indigo-600 rounded-full hidden"></span>
                                                </span>
                                            </div>
                                            <span class="text-xs font-bold text-gray-800 self-start">SSLCommerz</span>
                                        </label>

                                    </div>
                                </div>

                                <style>
                                    label:has(input[type="radio"]:checked) .check-icon span {
                                        display: block !important;
                                    }
                                    label:has(input[type="radio"]:checked) .check-icon {
                                        border-color: #4F46E5 !important;
                                    }
                                </style>

                                <!-- Submit Checkout -->
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-indigo-600/25 transition flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Complete Order</span>
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            @else
                <!-- Empty Cart View -->
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center max-w-lg mx-auto shadow-sm">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Your cart is currently empty</h3>
                    <p class="text-sm text-gray-500 mt-1 mb-6">Looks like you haven't added any products to your cart yet.</p>
                    <a href="#" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm rounded-xl transition shadow-md shadow-indigo-600/20">
                        Explore Products
                    </a>
                </div>
            @endif

        </div>
    </div>
@endsection
