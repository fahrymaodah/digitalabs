<div>
    {{-- Review Summary --}}
    <div class="bg-gray-50 rounded-xl p-6 mb-6" id="reviews-summary">
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div class="text-center">
                <p class="text-5xl font-bold text-gray-900">{{ number_format($reviewStats['average'], 1) }}</p>
                <div class="flex items-center justify-center my-2">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($reviewStats['average']) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-500">{{ $reviewStats['total'] }} ulasan</p>
            </div>
            <div class="flex-1 space-y-2">
                @for($star = 5; $star >= 1; $star--)
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 w-8">{{ $star }} ⭐</span>
                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        @php
                            $percentage = $reviewStats['total'] > 0 
                                ? ($reviewStats['distribution'][$star] / $reviewStats['total']) * 100 
                                : 0;
                        @endphp
                        <div class="h-full bg-yellow-400" style="width: {{ $percentage }}%"></div>
                    </div>
                    <span class="text-sm text-gray-500 w-8">{{ $reviewStats['distribution'][$star] }}</span>
                </div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Review List --}}
    @if($this->reviews->count() > 0)
    <div class="space-y-6" id="reviews-list">
        @foreach($this->reviews as $review)
        <div class="border-b border-gray-100 pb-6" wire:key="review-{{ $review->id }}">
            <div class="flex items-start gap-4">
                <img src="{{ $review->user->avatar_url }}" 
                     alt="{{ $review->user->name }}"
                     class="w-12 h-12 rounded-full object-cover">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $review->user->name }}</h4>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <span class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700">{{ $review->review }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    {{-- Pagination - Livewire 4 Style --}}
    @if($this->reviews->hasPages())
    <div class="mt-8">
        {{ $this->reviews->links(data: ['scrollTo' => '#reviews-summary']) }}
    </div>
    @endif
    @else
    <p class="text-center text-gray-500 py-8">Belum ada ulasan untuk kelas ini.</p>
    @endif
</div>
