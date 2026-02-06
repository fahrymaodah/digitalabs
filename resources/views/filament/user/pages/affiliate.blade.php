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
                                <select wire:model="bank_name" class="w-full pl-4 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent appearance-none" required>
                                    <option value="">Select Bank</option>
                                    <option value="BCA">BCA</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                    <option value="Mandiri">Mandiri</option>
                                    <option value="CIMB Niaga">CIMB Niaga</option>
                                    <option value="Permata">Permata</option>
                                    <option value="Danamon">Danamon</option>
                                    <option value="BTN">BTN</option>
                                    <option value="BSI">BSI</option>
                                    <option value="Jago">Bank Jago</option>
                                    <option value="Jenius">Jenius (BTPN)</option>
                                    <option value="SeaBank">SeaBank</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Account Number *</label>
                                <input type="text" wire:model="bank_account_number" placeholder="Enter your bank account number" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" maxlength="30" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Account Holder Name *</label>
                                <input type="text" wire:model="bank_account_name" placeholder="Name as shown in bank" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" maxlength="100" required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Why do you want to become an affiliate?</label>
                                <textarea wire:model="notes" placeholder="Tell us about your interest..." class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" rows="3" maxlength="500"></textarea>
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
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Your Referral Link</h3>
                    <span id="copy-toast" class="hidden text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                        Link copied ✅
                    </span>
                </div>
                <div class="flex gap-2">
                    <input type="text" 
                           value="{{ $referral_link }}" 
                           readonly 
                           class="flex-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white">
                    <button 
                        onclick="copyReferralLink()"
                        class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium flex items-center gap-2">
                        <x-heroicon-o-clipboard class="w-5 h-5" />
                        <span class="hidden sm:inline">Copy</span>
                    </button>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Share this link to earn {{ $stats['commission_rate'] }}% commission on every sale.
                </p>
            </div>

            {{-- Bank Information & Payout Request --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Bank Information --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <x-heroicon-o-building-library class="w-5 h-5 text-gray-400" />
                            Bank Information
                        </h3>
                        @if(!$isEditingBank)
                            <button wire:click="toggleEditBank" class="text-sm text-amber-500 hover:text-amber-600 font-medium flex items-center gap-1">
                                <x-heroicon-o-pencil class="w-4 h-4" />
                                Edit
                            </button>
                        @endif
                    </div>
                    <div class="p-6">
                        @if($isEditingBank)
                            <form wire:submit="updateBankInfo" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name</label>
                                    <select wire:model="bank_name" class="w-full pl-4 pr-10 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent appearance-none" required>
                                        <option value="">Select Bank</option>
                                        <option value="BCA">BCA</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BRI">BRI</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="CIMB Niaga">CIMB Niaga</option>
                                        <option value="Permata">Permata</option>
                                        <option value="Danamon">Danamon</option>
                                        <option value="BTN">BTN</option>
                                        <option value="BSI">BSI</option>
                                        <option value="Jago">Bank Jago</option>
                                        <option value="Jenius">Jenius (BTPN)</option>
                                        <option value="SeaBank">SeaBank</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    @error('bank_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Number</label>
                                    <input type="text" wire:model="bank_account_number" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" maxlength="30" required>
                                    @error('bank_account_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Holder Name</label>
                                    <input type="text" wire:model="bank_account_name" class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" maxlength="100" required>
                                    @error('bank_account_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button type="submit" class="flex-1 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">Save</button>
                                    <button type="button" wire:click="toggleEditBank" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</button>
                                </div>
                            </form>
                        @else
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Bank</p>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $affiliate->bank_name ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Account Number</p>
                                    <p class="text-gray-900 dark:text-white font-medium font-mono">{{ $affiliate->bank_account_number ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Account Holder</p>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $affiliate->bank_account_name ?? '-' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Request Payout --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <x-heroicon-o-banknotes class="w-5 h-5 text-gray-400" />
                            Request Payout
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($hasPendingPayout ?? false)
                            <div class="text-center py-4">
                                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <x-heroicon-o-clock class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                                </div>
                                <p class="font-medium text-gray-900 dark:text-white mb-1">Payout In Progress</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">You have a pending payout request of</p>
                                <p class="text-xl font-bold text-amber-500">Rp {{ number_format($pendingPayout->amount ?? 0, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Status: <span class="capitalize font-medium">{{ $pendingPayout->status ?? 'pending' }}</span></p>
                            </div>
                        @elseif($stats['pending_earnings'] < 100000)
                            <div class="text-center py-4">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <x-heroicon-o-exclamation-circle class="w-6 h-6 text-gray-400" />
                                </div>
                                <p class="font-medium text-gray-900 dark:text-white mb-1">Insufficient Balance</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Minimum payout is <span class="font-semibold">Rp 100,000</span></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Current: <span class="font-medium text-amber-500">Rp {{ number_format($stats['pending_earnings'], 0, ',', '.') }}</span></p>
                            </div>
                        @else
                            <form wire:submit="requestPayout" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount to Withdraw</label>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">Rp</span>
                                        <input type="number" wire:model="payout_amount" placeholder="100000" min="100000" step="1" class="w-full pl-12 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:border-transparent" required>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Min: Rp 100,000 - Max: Rp {{ number_format($stats['pending_earnings'], 0, ',', '.') }}</p>
                                    @error('payout_amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Transfer to</p>
                                    <p class="text-sm text-gray-900 dark:text-white font-medium">{{ $affiliate->bank_name }} - {{ $affiliate->bank_account_number }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $affiliate->bank_account_name }}</p>
                                </div>
                                <button type="submit" class="relative w-full px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 disabled:bg-gray-400 disabled:cursor-not-allowed font-semibold flex items-center justify-center gap-2 transition-all" wire:loading.attr="disabled" wire:target="requestPayout">
                                    {{-- Normal State --}}
                                    <span wire:loading.remove wire:target="requestPayout" class="flex items-center gap-2">
                                        <x-heroicon-o-arrow-up-tray class="w-5 h-5" />
                                        Request Payout
                                    </span>
                                    {{-- Loading State --}}
                                    <span wire:loading wire:target="requestPayout" class="flex items-center gap-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">Payouts are processed within 1-3 business days</p>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                {{-- Commission History --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow" wire:key="commissions-section">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Commission History</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All commissions you've earned ({{ $commissionsTotal ?? 0 }})</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4 mb-6" style="min-height: 284px;">
                            @forelse($commissions as $commission)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            Order #{{ $commission->order?->order_number ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $commission->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <span class="font-semibold text-green-500">
                                        +Rp {{ number_format($commission->commission_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No commissions yet</p>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        @if(($commissionsPaginated?->total() ?? 0) > $commissionsPerPage)
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Showing {{ ($commissionsPaginated->currentPage() - 1) * $commissionsPerPage + 1 }} - {{ min($commissionsPaginated->currentPage() * $commissionsPerPage, $commissionsTotal) }} of {{ $commissionsTotal }}
                                </div>
                                <div class="flex gap-1">
                                    @if ($commissionsPaginated->onFirstPage())
                                        <button class="px-2 py-1 text-gray-300 dark:text-gray-600 cursor-not-allowed" disabled>
                                            <x-heroicon-o-chevron-left class="w-5 h-5" />
                                        </button>
                                    @else
                                        <button wire:click="$set('commissionsPage', {{ $commissionsPaginated->currentPage() - 1 }})" class="px-2 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <x-heroicon-o-chevron-left class="w-5 h-5" />
                                        </button>
                                    @endif

                                    @foreach ($commissionsPaginated->getUrlRange(1, $commissionsPaginated->lastPage()) as $page => $url)
                                        @if ($page == $commissionsPaginated->currentPage())
                                            <span class="px-3 py-1 bg-amber-500 text-white rounded">{{ $page }}</span>
                                        @else
                                            <button wire:click="$set('commissionsPage', {{ $page }})" class="px-3 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">{{ $page }}</button>
                                        @endif
                                    @endforeach

                                    @if ($commissionsPaginated->hasMorePages())
                                        <button wire:click="$set('commissionsPage', {{ $commissionsPaginated->currentPage() + 1 }})" class="px-2 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <x-heroicon-o-chevron-right class="w-5 h-5" />
                                        </button>
                                    @else
                                        <button class="px-2 py-1 text-gray-300 dark:text-gray-600 cursor-not-allowed" disabled>
                                            <x-heroicon-o-chevron-right class="w-5 h-5" />
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Payout History --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow" wire:key="payouts-section">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Payout History</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All payouts you've received ({{ $payoutsTotal ?? 0 }})</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4 mb-6" style="min-height: 284px;">
                            @forelse($payouts as $payout)
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            Payout {{ $payout->payout_number ?? 'N/A' }}
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $payout->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            Rp {{ number_format($payout->amount, 0, ',', '.') }}
                                        </span>
                                        <span @class([
                                            'text-xs font-medium px-2.5 py-1.5 rounded-full',
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' => $payout->status === 'completed',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100' => $payout->status === 'pending',
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100' => $payout->status === 'processing',
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' => $payout->status === 'failed',
                                        ])>
                                            {{ ucfirst($payout->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No payouts yet</p>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        @if(($payoutsPaginated?->total() ?? 0) > $payoutsPerPage)
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Showing {{ ($payoutsPaginated->currentPage() - 1) * $payoutsPerPage + 1 }} - {{ min($payoutsPaginated->currentPage() * $payoutsPerPage, $payoutsTotal) }} of {{ $payoutsTotal }}
                                </div>
                                <div class="flex gap-1">
                                    @if ($payoutsPaginated->onFirstPage())
                                        <button class="px-2 py-1 text-gray-300 dark:text-gray-600 cursor-not-allowed" disabled>
                                            <x-heroicon-o-chevron-left class="w-5 h-5" />
                                        </button>
                                    @else
                                        <button wire:click="$set('payoutsPage', {{ $payoutsPaginated->currentPage() - 1 }})" class="px-2 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <x-heroicon-o-chevron-left class="w-5 h-5" />
                                        </button>
                                    @endif

                                    @foreach ($payoutsPaginated->getUrlRange(1, $payoutsPaginated->lastPage()) as $page => $url)
                                        @if ($page == $payoutsPaginated->currentPage())
                                            <span class="px-3 py-1 bg-amber-500 text-white rounded">{{ $page }}</span>
                                        @else
                                            <button wire:click="$set('payoutsPage', {{ $page }})" class="px-3 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">{{ $page }}</button>
                                        @endif
                                    @endforeach

                                    @if ($payoutsPaginated->hasMorePages())
                                        <button wire:click="$set('payoutsPage', {{ $payoutsPaginated->currentPage() + 1 }})" class="px-2 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                                            <x-heroicon-o-chevron-right class="w-5 h-5" />
                                        </button>
                                    @else
                                        <button class="px-2 py-1 text-gray-300 dark:text-gray-600 cursor-not-allowed" disabled>
                                            <x-heroicon-o-chevron-right class="w-5 h-5" />
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>

@push('scripts')
<script>
    function copyReferralLink() {
        const toast = document.getElementById('copy-toast');
        const text = @json($referral_link);

        navigator.clipboard.writeText(text).then(() => {
            toast.classList.remove('hidden');
            toast.classList.add('animate-pulse');

            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('animate-pulse');
            }, 2000);
        });
    }
</script>
@endpush
