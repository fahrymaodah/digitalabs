<x-filament-panels::page>
    @if($status === 'none')
        {{-- Not an affiliate yet - Show registration form --}}
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <div class="p-6 bg-gradient-to-r from-amber-500 to-orange-500 text-white">
                    <h2 class="text-2xl font-bold mb-2">Join Our Affiliate Program</h2>
                    <p class="opacity-90">Earn commission by promoting our courses to your audience.</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-heroicon-o-currency-dollar class="w-6 h-6 text-amber-600 dark:text-amber-400" />
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">10% Commission</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">On every successful sale</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-heroicon-o-clock class="w-6 h-6 text-green-600 dark:text-green-400" />
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">30-Day Cookie</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Extended tracking period</p>
                        </div>
                        <div class="text-center">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-heroicon-o-banknotes class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Fast Payout</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Min. Rp 100,000</p>
                        </div>
                    </div>

                    <form wire:submit="register">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Bank Name *</label>
                                <select wire:model="bank_name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                    <option value="">Select Bank</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="CIMB Niaga">CIMB Niaga</option>
                                    <option value="Permata">Permata</option>
                                    <option value="Danamon">Danamon</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Account Number *</label>
                                <input type="text" wire:model="bank_account_number" placeholder="Enter your bank account number" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" maxlength="30" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Account Holder Name *</label>
                                <input type="text" wire:model="bank_account_name" placeholder="Name as shown in bank" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" maxlength="100" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Why do you want to become an affiliate?</label>
                                <textarea wire:model="notes" placeholder="Tell us about your interest..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" rows="3" maxlength="500"></textarea>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="w-full px-4 py-3 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-semibold">
                                Apply Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @elseif($status === 'pending')
        {{-- Pending approval --}}
        <div class="max-w-lg mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center">
                <div class="w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-clock class="w-8 h-8 text-yellow-600 dark:text-yellow-400" />
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Application Pending</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-4">
                    Your affiliate application is being reviewed. We'll notify you once it's approved.
                </p>
                <p class="text-sm text-gray-400 dark:text-gray-500">
                    Submitted on {{ $affiliate->created_at->format('d M Y') }}
                </p>
            </div>
        </div>

    @elseif($status === 'rejected')
        {{-- Rejected --}}
        <div class="max-w-lg mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center">
                <div class="w-16 h-16 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-x-circle class="w-8 h-8 text-red-600 dark:text-red-400" />
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Application Rejected</h2>
                <p class="text-gray-500 dark:text-gray-400">
                    Unfortunately, your affiliate application was not approved at this time.
                </p>
            </div>
        </div>

    @elseif($status === 'suspended')
        {{-- Suspended --}}
        <div class="max-w-lg mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <x-heroicon-o-pause-circle class="w-8 h-8 text-gray-600 dark:text-gray-400" />
                </div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Account Suspended</h2>
                <p class="text-gray-500 dark:text-gray-400">
                    Your affiliate account has been suspended. Please contact support for more information.
                </p>
            </div>
        </div>

    @elseif($status === 'approved')
        {{-- Approved - Show dashboard --}}
        <div class="space-y-6">
            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Commission Rate</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['commission_rate'] }}%</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total Earnings</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['total_earnings'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pending Earnings</p>
                    <p class="text-2xl font-bold text-amber-500">Rp {{ number_format($stats['pending_earnings'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Paid Earnings</p>
                    <p class="text-2xl font-bold text-green-500">Rp {{ number_format($stats['paid_earnings'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Referral Link --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Your Referral Link</h3>
                <div class="flex gap-2">
                    <input type="text" 
                           value="{{ $referral_link }}" 
                           readonly 
                           class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white">
                    <button 
                        onclick="navigator.clipboard.writeText('{{ $referral_link }}'); alert('Copied!');"
                        class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">
                        <x-heroicon-o-clipboard class="w-5 h-5" />
                    </button>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Share this link to earn {{ $stats['commission_rate'] }}% commission on every sale.
                </p>
            </div>

            {{-- Request Payout --}}
            @if($stats['pending_earnings'] >= 100000)
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-green-800 dark:text-green-200">Ready for Payout!</h3>
                            <p class="text-sm text-green-600 dark:text-green-400">
                                You have Rp {{ number_format($stats['pending_earnings'], 0, ',', '.') }} available for withdrawal.
                            </p>
                        </div>
                        <button 
                            wire:click="requestPayout"
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 font-medium">
                            Request Payout
                        </button>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Commission History --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Recent Commissions</h3>
                    </div>
                    <div class="p-6">
                        @forelse($commissions as $commission)
                            <div class="flex items-center justify-between mb-4 last:mb-0">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Order #{{ $commission->order?->order_number ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $commission->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="font-semibold text-green-500">
                                    +Rp {{ number_format($commission->amount, 0, ',', '.') }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">No commissions yet</p>
                        @endforelse
                    </div>
                </div>

                {{-- Payout History --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Payout History</h3>
                    </div>
                    <div class="p-6">
                        @forelse($payouts as $payout)
                            <div class="flex items-center justify-between mb-4 last:mb-0">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $payout->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payout->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($payout->status === 'processing') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($payout->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    {{ ucfirst($payout->status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400 text-center py-4">No payouts yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
