@extends('layout.app')

@section('content')
    <div class="bg-gray-50 py-12 min-h-[85vh] flex items-center justify-center">
        <div class="max-w-2xl w-full mx-auto px-4 sm:px-6">

            <!-- Main Card Container -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden text-center p-8 sm:p-10">

                <!-- Success Icon Animation / Graphic -->
                <div class="mx-auto w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <!-- Header Text -->
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Payment Successful!</h1>
                <p class="text-sm text-gray-500 mt-2">
                    Thank you for your purchase. Your order has been placed and is now being processed.
                </p>

                <!-- Order Details Box -->
                @if(isset($order))
                    <div class="mt-8 bg-gray-50 rounded-2xl p-6 border border-gray-100 text-left space-y-4">

                        <!-- Order Meta Info -->
                        <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-gray-200">
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Order ID</span>
                                <p class="text-sm font-bold text-gray-800">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Payment Method</span>
                                <p class="text-sm font-bold text-gray-800 uppercase">{{ $order->payment_method ?? 'COD / Card' }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Paid</span>
                                <p class="text-base font-extrabold text-indigo-600">${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <!-- Purchased Items Summary -->
                        @if($order->items && $order->items->count() > 0)
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider block mb-2">Order Items</span>
                                <div class="space-y-2">
                                    @foreach($order->items as $item)
                                        <div class="flex justify-between items-center text-sm">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-gray-800">{{ $item->product->name ?? 'Product' }}</span>
                                                <span class="text-xs text-gray-400">x{{ $item->quantity }}</span>
                                            </div>
                                            <span class="font-semibold text-gray-700">${{ number_format($item->sub_total, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Shipping Address -->
                        @if($order->shipping_address)
                            <div class="pt-3 border-t border-gray-200">
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider block mb-1">Shipping Address</span>
                                <p class="text-sm text-gray-600 leading-snug">{{ $order->shipping_address }}</p>
                            </div>
                        @endif

                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('welcome') }}" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition shadow-md shadow-indigo-600/20 text-sm inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Continue Shopping
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
