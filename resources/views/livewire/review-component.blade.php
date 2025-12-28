<article class="rounded-2xl overflow-hidden border border-gray-700/80 bg-gradient-to-br from-gray-900 via-gray-900 to-gray-800 shadow-[0_20px_60px_-30px_rgba(0,0,0,0.8)]">

    <div class="p-6">
        <div class="flex gap-4 mb-4">
            <div class="w-20 flex-shrink-0">
                <a href="{{ route('movies.show', $review->movie) }}" class="group block aspect-[2/3] rounded-xl overflow-hidden bg-gray-800 ring-1 ring-white/5">
                    <img
                        src="https://image.tmdb.org/t/p/w200/{{ $review->movie->poster_url }}"
                        alt="{{ $review->movie->name }} poster"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                    >
                </a>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-4">
                    <h1 class="text-white text-lg font-semibold tracking-tight">{{ $review->movie->name }}</h1>
                    <span class="text-xs uppercase tracking-widest text-gray-500">Review</span>
                </div>
                <div class="flex items-start justify-between gap-4 mt-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.show', $review->user) }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center overflow-hidden ring-2 ring-white/5">
                                <img src="{{ $review->user->image ? asset('storage/' . $review->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">
                            </div>
                        </a>
                        <div>
                            <h1 class="font-medium text-xl text-white leading-snug">{{ $review->title }}</h1>
                            <p class="font-medium text-gray-200">{{ $review->user->name }}</p>
                            <time class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</time>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 bg-yellow-500/10 border border-yellow-500/30 rounded-full px-3 py-1 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-500">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg font-bold text-yellow-500">{{ $review->rating }}</span>
                        <span class="text-xs text-gray-400">/5</span>
                    </div>


                </div>
            </div>
        </div>
        @if($review->spoilers)
        <div class="p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-xl" x-data="{ open: false }">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500 flex-shrink-0"> 
                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" /> </svg>
                <span class="text-sm text-yellow-500 font-medium">
                    This review contains spoilers
                </span>
    
                <button
                    class="ml-auto px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-medium rounded-lg hover:bg-yellow-400"
                    @click="open = !open"
                >
                    <span x-text="open ? 'Hide' : 'Show'"></span>
                </button>
            </div>

            <div x-show="open" x-transition
                class="mt-3 pt-3 border-t border-yellow-500/20">
                <p class="text-gray-300 leading-relaxed">
                    {{ \Illuminate\Support\Str::limit($review->description, 100) }}
                </p>
            </div>
        </div>
        @else
            <p class="text-gray-300 leading-relaxed">
                {{ $review->description }}
            </p>
        @endif


    <button wire:click="toggleLike" class="flex items-center gap-2 mt-4 rounded-full border border-gray-700 px-3 py-1 text-sm text-gray-300 hover:border-gray-600 hover:text-white transition-colors">
        @if($review->likedBy->contains(auth()->id()))
            ❤️
        @else
            🤍
        @endif
        <span class="text-white">{{ $review->likedBy->count() }}</span>
    </button>

    </div>

    <div class="bg-gray-900/60 px-6 py-3 border-t border-gray-700/80 flex items-center justify-between">
        <a href="{{ route('reviews.show', $review) }}" class="text-sm text-yellow-400 hover:text-yellow-300 font-medium transition">
            View full review and comments
        </a>

        <span class="text-xs text-gray-400">{{ $review->comments->count() }} {{ Str::plural('comment', $review->comments->count()) }}</span>
    </div>
</article>
