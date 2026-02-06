<x-layouts.public :title="'Checkout - ' . $course->title">
    {{-- Toast Notification Container --}}
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    {{-- Breadcrumb --}}
    <section class="bg-gradient-to-r from-orange-500 to-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <nav class="flex items-center flex-wrap gap-x-2 gap-y-1 text-sm text-orange-100 mb-2">
                <a href="{{ url('/') }}" class="hover:text-white transition whitespace-nowrap">Home</a>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ url('/courses/' . $course->slug) }}" class="hover:text-white transition truncate max-w-[200px] md:max-w-none" title="{{ $course->title }}">{{ Str::limit($course->title, 40) }}</a>
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white font-medium whitespace-nowrap">Checkout</span>
            </nav>
            <h1 class="text-2xl md:text-3xl font-bold text-white">Checkout</h1>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-8 lg:py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid lg:grid-cols-5 gap-6 lg:gap-8">
                {{-- Payment Form --}}
                <div class="lg:col-span-3 min-w-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        {{-- Form Header --}}
                        <div class="bg-gray-50 px-4 sm:px-6 py-4 border-b border-gray-100">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Metode Pembayaran
                            </h2>
                        </div>

                        <form action="{{ route('checkout.process', $course->uuid) }}" method="POST" id="checkout-form" class="p-4 sm:p-6">
                            @csrf

                            {{-- Coupon Section --}}
                            <div class="mb-6 sm:mb-8">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <svg class="w-4 h-4 inline mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    Punya Kode Kupon?
                                </label>
                                <div class="flex flex-col sm:flex-row gap-2 relative">
                                    <div class="relative flex-1 min-w-0">
                                        <input 
                                            type="text" 
                                            name="coupon_code" 
                                            id="coupon_code"
                                            class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500 px-4 py-3 pr-12 text-sm transition-all duration-200"
                                            placeholder="Masukkan kode kupon"
                                            oninput="resetCouponState()"
                                        >
                                        <!-- Success Icon -->
                                        <div id="coupon-success-icon" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <!-- Error Icon -->
                                        <div id="coupon-error-icon" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <button 
                                        type="button" 
                                        onclick="applyCoupon()"
                                        id="apply-coupon-btn"
                                        class="px-6 py-3 bg-orange-100 hover:bg-orange-200 text-orange-600 rounded-xl font-medium transition text-sm flex items-center justify-center gap-2 whitespace-nowrap"
                                    >
                                        <span id="apply-coupon-text">Terapkan</span>
                                        <svg id="apply-coupon-spinner" class="hidden animate-spin h-4 w-4 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Payment Methods --}}
                            @if(count($paymentMethods) > 0)
                                @php
                                    $groupedMethods = collect($paymentMethods)->groupBy(function($method) {
                                        $code = $method['paymentMethod'];
                                        if (str_starts_with($code, 'VA') || str_starts_with($code, 'BC') || str_starts_with($code, 'M1') || str_starts_with($code, 'BT') || str_starts_with($code, 'B1') || str_starts_with($code, 'A1') || str_starts_with($code, 'NC') || str_starts_with($code, 'BR') || str_starts_with($code, 'S1') || str_starts_with($code, 'I1')) {
                                            return 'Virtual Account';
                                        } elseif (in_array($code, ['OV', 'SA', 'LF', 'LA', 'DA', 'SL'])) {
                                            return 'E-Wallet';
                                        } elseif (in_array($code, ['SP', 'AG', 'AC', 'AT'])) {
                                            return 'Retail / Convenience Store';
                                        } elseif (in_array($code, ['CC', 'DN'])) {
                                            return 'Credit Card';
                                        } elseif ($code === 'QR') {
                                            return 'QRIS';
                                        } else {
                                            return 'Lainnya';
                                        }
                                    });
                                    
                                    $groupIcons = [
                                        'Virtual Account' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                        'E-Wallet' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
                                        'Retail / Convenience Store' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>',
                                        'Credit Card' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>',
                                        'QRIS' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>',
                                    ];
                                @endphp

                                <div class="space-y-3" x-data="{ selectedMethod: '', openGroup: 'Virtual Account' }">
                                    @foreach($groupedMethods as $group => $methods)
                                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                                            {{-- Collapse Header --}}
                                            <button 
                                                type="button"
                                                @click="openGroup = openGroup === '{{ $group }}' ? '' : '{{ $group }}'"
                                                class="w-full bg-gray-50 px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition"
                                            >
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        {!! $groupIcons[$group] ?? $groupIcons['Virtual Account'] !!}
                                                    </svg>
                                                    <span class="font-medium text-sm text-gray-700">{{ $group }}</span>
                                                    <span class="text-xs text-gray-400">({{ count($methods) }})</span>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': openGroup === '{{ $group }}' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            
                                            {{-- Collapse Content --}}
                                            <div 
                                                x-show="openGroup === '{{ $group }}'"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 -translate-y-2"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 -translate-y-2"
                                                class="divide-y divide-gray-100"
                                            >
                                                @foreach($methods as $method)
                                                    <label 
                                                        class="flex items-center gap-4 p-4 cursor-pointer hover:bg-orange-50 transition-all duration-200 group"
                                                        :class="{ 'bg-orange-50': selectedMethod === '{{ $method['paymentMethod'] }}' }"
                                                    >
                                                        <input 
                                                            type="radio" 
                                                            name="payment_method" 
                                                            value="{{ $method['paymentMethod'] }}"
                                                            class="hidden"
                                                            x-model="selectedMethod"
                                                            required
                                                        >
                                                        
                                                        {{-- Custom Radio Button --}}
                                                        <div class="flex-shrink-0 w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-all duration-200"
                                                             :class="{ 'border-orange-500 bg-orange-500 scale-110': selectedMethod === '{{ $method['paymentMethod'] }}' }">
                                                            <div class="w-2 h-2 rounded-full bg-white transition-transform duration-200 scale-0"
                                                                 :class="{ 'scale-100': selectedMethod === '{{ $method['paymentMethod'] }}' }"></div>
                                                        </div>
                                                        
                                                        @if(isset($method['paymentImage']) && $method['paymentImage'])
                                                            <img src="{{ $method['paymentImage'] }}" alt="{{ $method['paymentName'] }}" class="h-7 w-auto object-contain">
                                                        @else
                                                            <div class="h-7 w-12 bg-gray-100 rounded flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div class="flex-1">
                                                            <p class="font-medium text-sm text-gray-900 group-hover:text-orange-600 transition"
                                                               :class="{ 'text-orange-600': selectedMethod === '{{ $method['paymentMethod'] }}' }">{{ $method['paymentName'] }}</p>
                                                        </div>
                                                        @if(isset($method['totalFee']) && $method['totalFee'] > 0)
                                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                                                +Rp {{ number_format($method['totalFee'], 0, ',', '.') }}
                                                            </span>
                                                        @endif
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
                                    <svg class="w-12 h-12 text-yellow-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <p class="text-yellow-700 font-medium mb-1">Tidak dapat memuat metode pembayaran</p>
                                    <p class="text-sm text-yellow-600">Silakan refresh halaman atau coba lagi nanti.</p>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:col-span-2 min-w-0">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:sticky lg:top-24">
                        {{-- Summary Header --}}
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 sm:px-6 py-4">
                            <h2 class="text-base sm:text-lg font-semibold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Ringkasan Pesanan
                            </h2>
                        </div>

                        <div class="p-4 sm:p-6">
                            {{-- Course Card --}}
                            <div class="flex gap-3 sm:gap-4 mb-6">
                                @if($course->thumbnail)
                                    <img src="{{ $course->thumbnail_url }}" alt="{{ $course->title }}" class="w-20 sm:w-24 h-14 sm:h-16 object-cover rounded-xl shadow-sm flex-shrink-0">
                                @else
                                    <div class="w-20 sm:w-24 h-14 sm:h-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 sm:w-8 h-6 sm:h-8 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2">{{ $course->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 truncate">{{ $course->instructor->name ?? 'Digitalabs' }}</p>
                                    @if($course->category)
                                        <span class="inline-block mt-2 px-2 py-0.5 bg-orange-100 text-orange-600 text-xs rounded-full">
                                            {{ $course->category->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Course Benefits --}}
                            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                                <p class="text-xs font-medium text-gray-700 mb-2">Yang Anda dapatkan:</p>
                                <ul class="space-y-2 text-xs text-gray-600">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Akses selamanya
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Update materi gratis
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Sertifikat kelulusan
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Grup diskusi eksklusif
                                    </li>
                                </ul>
                            </div>

                            <hr class="border-gray-100 mb-4">

                            {{-- Price Breakdown --}}
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span id="subtotal" class="font-medium">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-green-600" id="discount-row" style="display: none;">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        Diskon
                                    </span>
                                    <span id="discount-amount" class="font-medium">-Rp 0</span>
                                </div>
                            </div>

                            <hr class="border-gray-100 my-4">

                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-900">Total Pembayaran</span>
                                <span id="total" class="text-2xl font-bold text-orange-500">Rp {{ number_format($course->price, 0, ',', '.') }}</span>
                            </div>

                            @if($affiliate)
                                <div class="mt-4 p-3 bg-green-50 border border-green-100 rounded-xl text-sm">
                                    <p class="text-green-700 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Referral dari <strong>{{ $affiliate->user->name }}</strong>
                                    </p>
                                </div>
                            @endif

                            {{-- Submit Button --}}
                            <button 
                                type="submit" 
                                form="checkout-form"
                                class="w-full mt-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-xl transition shadow-lg shadow-orange-500/25 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 cursor-pointer"
                                @if(count($paymentMethods) === 0) disabled @endif
                                onclick="return validatePaymentMethod(event)"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Bayar Sekarang
                            </button>

                            <p class="text-xs text-gray-500 text-center mt-4">
                                Dengan melakukan pembayaran, Anda menyetujui 
                                <a href="{{ url('/terms') }}" class="text-orange-500 hover:underline">Syarat & Ketentuan</a> 
                                kami.
                            </p>

                            {{-- Trust Badges --}}
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex items-center justify-center gap-6 text-gray-400">
                                    <div class="text-center">
                                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span class="text-xs">Aman</span>
                                    </div>
                                    <div class="text-center">
                                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-xs">Cepat</span>
                                    </div>
                                    <div class="text-center">
                                        <svg class="w-6 h-6 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span class="text-xs">Terpercaya</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        const originalPrice = {{ $course->price }};
        let currentDiscount = 0;

        function formatRupiah(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }

        // Toast notification function
        function showToast(message, type = 'error') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const icons = {
                success: '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                error: '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                warning: '<svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
            };
            
            const colors = {
                success: 'bg-white border-green-200',
                error: 'bg-white border-red-200',
                warning: 'bg-white border-orange-200'
            };
            
            toast.className = `${colors[type]} border shadow-lg rounded-xl p-4 flex items-center gap-3 min-w-[320px] transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                ${icons[type]}
                <p class="text-sm text-gray-700 flex-1">${message}</p>
                <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        function validatePaymentMethod(event) {
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            
            if (!selectedMethod) {
                event.preventDefault();
                showToast('Silakan pilih metode pembayaran terlebih dahulu!', 'warning');
                
                // Scroll to payment methods section
                const paymentSection = document.querySelector('input[name="payment_method"]');
                if (paymentSection) {
                    paymentSection.closest('.space-y-3').scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                return false;
            }
            
            return true;
        }

        function resetCouponState() {
            const couponInput = document.getElementById('coupon_code');
            const successIcon = document.getElementById('coupon-success-icon');
            const errorIcon = document.getElementById('coupon-error-icon');
            
            // Reset to default border when user types
            couponInput.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50');
            couponInput.classList.add('border-gray-300');
            
            // Hide icons
            successIcon.classList.add('hidden');
            errorIcon.classList.add('hidden');
        }

        function applyCoupon() {
            const code = document.getElementById('coupon_code').value.trim();
            const couponInput = document.getElementById('coupon_code');
            const successIcon = document.getElementById('coupon-success-icon');
            const errorIcon = document.getElementById('coupon-error-icon');
            const discountRow = document.getElementById('discount-row');
            const discountAmount = document.getElementById('discount-amount');
            const totalEl = document.getElementById('total');
            const btn = document.getElementById('apply-coupon-btn');
            const btnText = document.getElementById('apply-coupon-text');
            const spinner = document.getElementById('apply-coupon-spinner');

            if (!code) {
                showToast('Masukkan kode kupon terlebih dahulu', 'warning');
                return;
            }

            // Show loading animation
            btn.disabled = true;
            btnText.textContent = 'Memeriksa...';
            spinner.classList.remove('hidden');
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            fetch('{{ route("checkout.apply-coupon") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    code: code,
                    amount: originalPrice
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentDiscount = data.discount;
                    discountRow.style.display = 'flex';
                    discountAmount.textContent = '-' + formatRupiah(data.discount);
                    totalEl.textContent = formatRupiah(data.total);
                    
                    // Green border + success icon
                    couponInput.classList.remove('border-red-500', 'border-gray-300', 'bg-red-50');
                    couponInput.classList.add('border-green-500', 'bg-green-50');
                    successIcon.classList.remove('hidden');
                    errorIcon.classList.add('hidden');
                    
                    showToast('✓ ' + data.message, 'success');
                } else {
                    currentDiscount = 0;
                    discountRow.style.display = 'none';
                    totalEl.textContent = formatRupiah(originalPrice);
                    
                    // Red border + error icon
                    couponInput.classList.remove('border-green-500', 'border-gray-300', 'bg-green-50');
                    couponInput.classList.add('border-red-500', 'bg-red-50');
                    errorIcon.classList.remove('hidden');
                    successIcon.classList.add('hidden');
                    
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                // Red border + error icon
                couponInput.classList.remove('border-green-500', 'border-gray-300', 'bg-green-50');
                couponInput.classList.add('border-red-500', 'bg-red-50');
                errorIcon.classList.remove('hidden');
                successIcon.classList.add('hidden');
                
                showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
            })
            .finally(() => {
                // Reset button state
                btn.disabled = false;
                btnText.textContent = 'Terapkan';
                spinner.classList.add('hidden');
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
            });
        }
    </script>
    @endpush
</x-layouts.public>
