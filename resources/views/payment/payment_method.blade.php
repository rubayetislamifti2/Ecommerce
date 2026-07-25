@extends('layout.app')

@section('content')
    <div class="bg-gray-50/50 py-12 min-h-[85vh]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-10 text-center">
            <span class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100 mb-3">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Secure Payment
            </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 tracking-tight">Payment Methods</h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1 max-w-sm mx-auto">Add and manage your payment cards and mobile wallets safely for faster checkouts.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">

                <!-- Left: Add New Payment Method (7 Cols) -->
                <div class="md:col-span-7 bg-white rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50 p-6 sm:p-8" x-data="{ tab: 'card' }">

                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-100">
                        <h2 class="text-base font-bold text-gray-900 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm font-black">+</span>
                            Add Payment Method
                        </h2>
                        <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Encrypted</span>
                    </div>

                    <!-- Tab Switcher -->
                    <div class="grid grid-cols-2 gap-2 p-1 bg-gray-100 rounded-xl mb-6">
                        <button type="button" @click="tab = 'card'"
                                :class="tab === 'card' ? 'bg-white shadow-sm text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-bold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H5a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Card
                        </button>
                        <button type="button" @click="tab = 'bkash'"
                                :class="tab === 'bkash' ? 'bg-white shadow-sm text-pink-600' : 'text-gray-500 hover:text-gray-700'"
                                class="flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-bold transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            bKash
                        </button>
                    </div>

                    <!-- Card Form -->
                    <form id="add-card-form" x-show="tab === 'card'" class="space-y-5" style="display: block;">
                        @csrf

                        <!-- Cardholder Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Cardholder Name</label>
                            <input id="cardholder_name" type="text" placeholder="John Doe" required
                                   class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:bg-white transition">
                        </div>

                        <!-- Stripe Card Element -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Card Information</label>
                            <div id="card-element" class="px-4 py-3.5 bg-gray-50/50 border border-gray-200 rounded-xl focus-within:ring-2 focus-within:ring-indigo-600 focus-within:bg-white transition"></div>
                            <p id="card-errors" class="text-xs font-medium text-rose-600 mt-2" role="alert"></p>
                        </div>

                        <!-- Set as Default -->
                        <label class="flex items-center gap-3 p-3.5 bg-gray-50/60 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                            <input id="default_card" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="text-xs font-semibold text-gray-700">Set as default payment method</span>
                        </label>

                        <!-- Submit Button -->
                        <button id="submit-btn" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-indigo-600/20 flex items-center justify-center gap-2 text-sm">
                            <svg id="spinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <svg id="btn-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="btn-text">Save Card</span>
                        </button>
                    </form>

                    <!-- bKash Form (Static UI - not wired to backend yet) -->
                    <form id="add-bkash-form" x-show="tab === 'bkash'" class="space-y-5" style="display: none;" onsubmit="return false;">
                        @csrf

                        <!-- bKash Info Banner -->
                        <div class="flex items-start gap-3 p-3.5 bg-pink-50 border border-pink-100 rounded-xl">
                            <svg class="w-5 h-5 text-pink-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-pink-700 leading-relaxed">You'll be redirected to the bKash app or portal to confirm this wallet. We only store your bKash number for faster checkout — never your PIN.</p>
                        </div>

                        <!-- bKash Account Holder Name -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Account Holder Name</label>
                            <input id="bkash_name" type="text" placeholder="John Doe" required
                                   class="w-full px-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:bg-white transition">
                        </div>

                        <!-- bKash Number -->
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">bKash Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-semibold">+880</span>
                                <input id="bkash_number" type="tel" placeholder="1XXXXXXXXX" required maxlength="10" pattern="[0-9]{10}"
                                       class="w-full pl-14 pr-4 py-3 bg-gray-50/50 border border-gray-200 rounded-xl text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:bg-white transition">
                            </div>
                            <p id="bkash-errors" class="text-xs font-medium text-rose-600 mt-2" role="alert"></p>
                        </div>

                        <!-- Set as Default -->
                        <label class="flex items-center gap-3 p-3.5 bg-gray-50/60 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                            <input id="default_bkash" type="checkbox" class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <span class="text-xs font-semibold text-gray-700">Set as default payment method</span>
                        </label>

                        <!-- Submit Button -->
                        <button id="bkash-submit-btn" type="submit" class="w-full bg-pink-600 hover:bg-pink-700 disabled:bg-gray-300 text-white font-bold py-3.5 px-4 rounded-xl transition shadow-lg shadow-pink-600/20 flex items-center justify-center gap-2 text-sm">
                            <svg id="bkash-spinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            <svg id="bkash-btn-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span id="bkash-btn-text">Save bKash Number</span>
                        </button>
                    </form>

                    <!-- Success Message -->
                    <div id="success-message" class="hidden mt-5 p-4 bg-emerald-50 text-emerald-700 text-sm font-semibold rounded-xl border border-emerald-200 flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span id="success-text">Card saved successfully! Reloading...</span>
                    </div>
                </div>

                <!-- Right: Card Preview & Saved Methods List (5 Cols) -->
                <div class="md:col-span-5 space-y-6">

                    <!-- Modern Minimalist Card Mockup -->
                    <div class="relative w-full aspect-[1.586/1] rounded-2xl bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-800 p-6 text-white shadow-xl flex flex-col justify-between overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-2xl"></div>

                        <div class="flex justify-between items-center relative z-10">
                            <div class="w-9 h-6 bg-amber-400/90 rounded border border-amber-300/50"></div>
                            <span id="preview-brand" class="text-xs font-black tracking-widest text-indigo-300 uppercase">New Card</span>
                        </div>

                        <div class="relative z-10">
                            <p id="preview-number" class="text-base sm:text-lg font-mono tracking-widest text-slate-100">•••• •••• •••• ••••</p>
                        </div>

                        <div class="flex justify-between items-end relative z-10">
                            <div>
                                <span class="text-[9px] uppercase text-slate-400 tracking-wider block">Holder</span>
                                <span id="preview-name" class="text-xs font-bold uppercase tracking-wide text-white">Your Name</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] uppercase text-slate-400 tracking-wider block">Expires</span>
                                <span class="text-xs font-bold text-white">MM/YY</span>
                            </div>
                        </div>
                    </div>

                    <!-- Saved Payment Methods List -->
                    <div class="bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Saved Payment Methods</h3>

                        <div id="saved-cards-list" class="space-y-2.5">
                            @forelse($card as $item)
                                <div class="flex items-center justify-between p-3.5 bg-gray-50/70 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                                    <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 text-[10px] font-black uppercase rounded bg-white border border-gray-200 text-indigo-600 shadow-2xs">
                                        {{ $item->brand }}
                                    </span>
                                        <div>
                                            <p class="text-xs font-bold text-gray-800">•••• {{ $item->last_four }}</p>
                                            <p class="text-[10px] text-gray-400">Expires {{ str_pad($item->exp_month, 2, '0', STR_PAD_LEFT) }}/{{ substr($item->exp_year, -2) }}</p>
                                        </div>
                                    </div>

                                    @if($item->is_default)
                                        <span class="text-[9px] font-bold px-2 py-0.5 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-full">DEFAULT</span>
                                    @else
                                        <form action="{{ route('payment-method.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Remove this card?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-rose-500 transition p-1" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <p class="text-xs font-medium text-gray-400">No cards saved yet.</p>
                                </div>
                            @endforelse

                            <!-- bKash Saved Methods (Static preview only, not wired to backend yet) -->
                            <div class="flex items-center justify-between p-3.5 bg-pink-50/50 border border-pink-100 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 text-[10px] font-black uppercase rounded bg-pink-600 text-white shadow-2xs">
                                        bKash
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-gray-800">+880 1XXXXXXXXX</p>
                                        <p class="text-[10px] text-gray-400">Sample Account</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-bold px-2 py-0.5 bg-pink-50 text-pink-600 border border-pink-100 rounded-full">DEFAULT</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{ config('stripe.stripe.key') }}');
        const elements = stripe.elements();

        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '14px',
                    color: '#111827',
                    fontFamily: 'sans-serif',
                    '::placeholder': { color: '#9CA3AF' },
                },
                invalid: { color: '#E11D48' },
            },
        });
        cardElement.mount('#card-element');

        const form = document.getElementById('add-card-form');
        const errorDiv = document.getElementById('card-errors');
        const submitBtn = document.getElementById('submit-btn');
        const spinner = document.getElementById('spinner');
        const btnIcon = document.getElementById('btn-icon');
        const btnText = document.getElementById('btn-text');
        const successMsg = document.getElementById('success-message');
        const successText = document.getElementById('success-text');

        const nameInput = document.getElementById('cardholder_name');
        const previewName = document.getElementById('preview-name');
        nameInput.addEventListener('input', () => {
            previewName.textContent = nameInput.value.trim() ? nameInput.value : 'Your Name';
        });

        cardElement.on('change', (event) => {
            errorDiv.textContent = event.error ? event.error.message : '';

            const previewBrand = document.getElementById('preview-brand');
            if (event.brand && event.brand !== 'unknown') {
                previewBrand.textContent = event.brand.toUpperCase();
            } else {
                previewBrand.textContent = 'New Card';
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const cardholderName = nameInput.value;
            if (!cardholderName.trim()) {
                errorDiv.textContent = 'Please enter the cardholder name.';
                return;
            }

            submitBtn.disabled = true;
            spinner.classList.remove('hidden');
            btnIcon.classList.add('hidden');
            btnText.textContent = 'Processing...';

            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: { name: cardholderName },
            });

            if (error) {
                errorDiv.textContent = error.message;
                resetCardButton();
                return;
            }

            try {
                const response = await fetch('{{ route("payment-method.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id,
                        is_default: document.getElementById('default_card').checked,
                    }),
                });

                const data = await response.json();

                if (!response.ok) {
                    errorDiv.textContent = data.message || 'Something went wrong. Please try again.';
                    resetCardButton();
                    return;
                }

                form.classList.add('hidden');
                successText.textContent = 'Card saved successfully! Reloading...';
                successMsg.classList.remove('hidden');

                setTimeout(() => window.location.reload(), 1200);

            } catch (err) {
                errorDiv.textContent = 'Network error. Please try again.';
                resetCardButton();
            }
        });

        function resetCardButton() {
            submitBtn.disabled = false;
            spinner.classList.add('hidden');
            btnIcon.classList.remove('hidden');
            btnText.textContent = 'Save Card';
        }
    </script>
@endsection
