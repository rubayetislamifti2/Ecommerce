@extends('layout.app')

@section('content')
    <div class="bg-gray-50 py-12 min-h-[85vh] flex items-center justify-center">
        <div class="max-w-2xl w-full mx-auto px-4 sm:px-6">

            <!-- Main Card Container -->
            <div class="bg-white rounded-3xl border border-gray-100 shadow-xl overflow-hidden text-center p-8 sm:p-10">

                <!-- Cancel Icon -->
                <div class="mx-auto w-20 h-20 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mb-6 shadow-sm">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>

                <!-- Header Text -->
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Payment Cancelled</h1>
                <p class="text-sm text-gray-500 mt-2 max-w-md mx-auto">
                    Your payment process was cancelled or could not be completed. No charge was made for this transaction.
                </p>

                <!-- Order Details Box (If order exists) -->
                @if(isset($order))
                    <div class="mt-8 bg-gray-50 rounded-2xl p-6 border border-gray-100 text-left space-y-4">

                        <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-gray-200">
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Pending Order</span>
                                <p class="text-sm font-bold text-gray-800">#{{ $order->id }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Status</span>
                                <span class="inline-block text-xs font-semibold px-2.5 py-0.5 bg-rose-100 text-rose-700 rounded-full">
                                {{ ucfirst($order->status) }}
                            </span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Amount</span>
                                <p class="text-base font-extrabold text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500">
                            * Your items are still in your cart. You can review your cart details and try checking out again whenever you are ready.
                        </p>

                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <!-- Return to Cart/Checkout -->
                    <a href="#" class="w-full sm:w-auto px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition shadow-md shadow-indigo-600/20 text-sm inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Try Payment Again
                    </a>

                    <!-- Back to Shop -->
                    <a href="{{ route('welcome') }}" class="w-full sm:w-auto px-6 py-3 border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition text-sm inline-flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Return to Store
                    </a>
                </div>

            </div>

        </div>
    </div>
@endsection
