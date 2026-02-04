<x-filament-panels::page>
    <div class="max-w-3xl mx-auto space-y-6">
        {{-- Profile Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Update your account's profile information and email address.</p>
            </div>
            <div class="p-6">
                <form wire:submit="updateProfile">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Full Name *</label>
                            <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Email Address *</label>
                            <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Phone Number *</label>
                            <input type="tel" wire:model="phone" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Update Password --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Update Password</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-6">
                <form wire:submit="updatePassword">
                    <div class="space-y-4">
                        @if(auth()->user()->password)
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Current Password *</label>
                            <input type="password" wire:model="current_password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            @error('current_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        @else
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <p class="text-sm text-blue-700 dark:text-blue-300">Anda login via Google. Silakan buat password untuk akses di masa depan.</p>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">New Password *</label>
                            <input type="password" wire:model="new_password" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            @error('new_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">Confirm New Password *</label>
                            <input type="password" wire:model="new_password_confirmation" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-medium">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Account Stats --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Statistics</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ auth()->user()->userCourses()->count() }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Courses</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ auth()->user()->lessonProgress()->where('is_completed', true)->count() }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Lessons Completed</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ auth()->user()->orders()->where('status', 'paid')->count() }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Orders</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ auth()->user()->created_at->format('Y') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Member Since</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-red-200 dark:border-red-800">
            <div class="p-6 border-b border-red-200 dark:border-red-800">
                <h3 class="text-lg font-semibold text-red-600 dark:text-red-400">Danger Zone</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Irreversible and destructive actions.</p>
            </div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">Delete Account</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Permanently delete your account and all of its data.</p>
                    </div>
                    <button type="button" 
                            onclick="confirm('Are you sure you want to delete your account? This action cannot be undone.') && Livewire.dispatch('deleteAccount')"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 font-medium">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
