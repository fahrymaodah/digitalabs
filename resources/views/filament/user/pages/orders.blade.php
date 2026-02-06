<x-filament-panels::page>
    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                {{-- Order Header --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($order->status === 'paid') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($order->status === 'expired') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4">
                                @if($item->course && $item->course->thumbnail)
                                    <img src="{{ Storage::url($item->course->thumbnail) }}" 
                                         alt="{{ $item->course->title }}" 
                                         class="w-12 h-12 object-cover rounded-lg">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                        <x-heroicon-o-academic-cap class="w-8 h-8 text-gray-400" />
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $item->course?->title ?? $item->course_title ?? 'Course' }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Order Summary --}}
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Subtotal</span>
                                <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($order->discount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">
                                        Discount
                                        @if($order->coupon)
                                            ({{ $order->coupon->code }})
                                        @endif
                                    </span>
                                    <span class="text-green-600 dark:text-green-400">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            @if($order->payment_fee > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Payment Fee</span>
                                    <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->payment_fee, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between font-semibold text-lg pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-900 dark:text-white">Total</span>
                                <span class="text-gray-900 dark:text-white">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($order->payment_method)
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Payment Method: <span class="font-medium text-gray-900 dark:text-white">{{ $order->payment_method }}</span>
                                </p>
                                @if($order->paid_at)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Paid at: <span class="font-medium text-gray-900 dark:text-white">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                                    </p>
                                @endif
                                @if($order->affiliate)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Affiliate: <span class="font-medium text-gray-900 dark:text-white">{{ $order->affiliate->user->name ?? 'N/A' }}</span> ({{ $order->affiliate->referral_code ?? 'N/A' }})
                                    </p>
                                @endif
                            </div>
                        @endif

                        @if($order->status === 'pending' && $order->duitku_payment_url)
                            <div class="mt-4">
                                <a href="{{ route('checkout.pay', $order->order_number) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">
                                    <x-heroicon-o-credit-card class="w-4 h-4 mr-2" />
                                    Complete Payment
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-12 text-center">
                <x-heroicon-o-shopping-bag class="w-12 h-12 mx-auto text-gray-400 mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No orders yet</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Your order history will appear here after your first purchase.</p>
                <a href="/courses" class="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">
                    Browse Courses
                </a>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
