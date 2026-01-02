<article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden flex items-start mt-8">
    {{-- Movie image --}}
    <div class="w-28 sm:w-32 flex-shrink-0 h-auto">
        <a href="{{ route('movies.show', $review->movie) }}" class="block">
            <img src="https://image.tmdb.org/t/p/w500/{{ $review->movie->poster_url }}"
                alt="{{ $review->name }}"
                class="w-full h-auto"
            >
        </a>
    </div>


    {{-- Content --}}
    <div class="flex-1 flex flex-col">
        <div class="p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('profile.show', $review->user) }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                        <div class="w-10 h-10 rounded-full overflow-hidden">
                            <img
                                src="{{ $review->user->image ? asset('storage/' . $review->user->image) : asset('images/person-placeholder.png') }}"
                                alt=""
                                class="w-full h-full object-cover"
                            >
                        </div>
                        <div>
                            <h1 class="font-medium text-xl text-white">{{ $review->title }}</h1>
                            <p class="font-medium text-gray-200">{{ $review->user->name }}</p>
                            <time class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</time>
                        </div>
                    </a>
                </div>

                <div class="flex items-center gap-1 bg-yellow-500/10 border border-yellow-500/30 rounded-lg px-3 py-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-500">
                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-lg font-bold text-yellow-500">{{ $review->rating }}</span>
                    <span class="text-xs text-gray-400">/5</span>
                </div>
            </div>

            @if($review->spoilers)
                <div class="p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg" x-data="{ open: false }">
                    <div class="flex items-center gap-2">
                    @svg('heroicon-o-exclamation-triangle', 'w-5 h-5 text-yellow-500 flex-shrink-0')

                    <span class="text-sm text-yellow-500 font-medium">
                        This review contains spoilers
                    </span>

                    <button type="button" class="ml-auto px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-medium rounded hover:bg-yellow-400"
                        @click="open = !open"
                    >
                        <span x-text="open ? 'Hide' : 'Show'"></span>
                    </button>
                </div>

                <div x-show="open" x-transition class="mt-3 pt-3 border-t border-yellow-500/20">
                    <p class="text-gray-300 leading-relaxed">
                        {{ $review->description }}
                    </p>
                </div>
            </div>
            @else
                <p class="text-gray-300 leading-relaxed">
                    {{ $review->description }}
                </p>
            @endif

            @if(auth()->check() && auth()->user()->is_admin)
            <x-confirm-modal title="Delete review?"
                message="Delete this review and all its comments?"
                    :action="route('reviews.destroy', $review)"
                    method="DELETE">
                    <x-slot name="trigger" class="w-max">
                        <button class="text-xs text-red-400 hover:text-red-300">
                            Delete review
                        </button>
                    </x-slot>
            </x-confirm-modal>
        @endif
        @auth
            <form action="{{ route('reviews.like', $review) }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-1">
                    @if($review->likedBy->contains(auth()->id()))
                        ❤️
                    @else
                        🤍
                    @endif
                    <span class="text-white">{{ $review->likedBy->count() }}</span>
                </button>
            </form>
        @else
            <div class="flex items-center gap-1">
                🤍
                <span class="text-white">{{ $review->likedBy->count() }}</span>
            </div>
        @endauth
        </div>
        <div class="bg-gray-900/50 px-6 py-3 border-t border-gray-700 flex items-center justify-between">
            <a href="{{ route('reviews.show', $review) }}" class="text-sm text-yellow-400 hover:text-yellow-300 font-medium transition">
                View full review and comments
            </a>
            <span class="text-xs text-gray-400">
                {{ $review->comments->count() }} {{ Str::plural('comment', $review->comments->count()) }}
            </span>
        </div>

    </div>
</article>
