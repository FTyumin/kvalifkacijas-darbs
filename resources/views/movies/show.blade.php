@extends('layouts.app')

@section('title', $movie->name)

@section('content')

<div class="max-w-6xl mx-auto mt-8 px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Poster --}}
        <div class="md:col-span-1">
            <img src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}" 
                 alt="{{ $movie->name }} poster" 
                 class="rounded-xl shadow-md w-full sticky top-4">
        </div>

        {{-- Details --}}
        <div class="md:col-span-2 space-y-6">
            <h1 class="text-3xl text-white font-bold">{{ $movie->name }}</h1>
            
            <div class="flex flex-wrap items-center gap-4 text-gray-400">
                <span class="text-sm">{{ $movie->year }}</span>
                <span>•</span>
                <div class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-500">
                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm text-white font-medium">{{ $movie->tmdb_rating }}</span>
                </div>
                <span>•</span>
                <span class="text-sm uppercase">{{ $movie->language }}</span>
                <span>•</span>
                <span class="text-sm">{{ $movie->duration }} mins</span>
            </div>

            {{-- Genres --}}
            <div class="flex flex-wrap gap-2">
                @foreach($movie->genres as $genre)
                 <a href="{{ route('genres.show', $genre->id ) }}" 
                    class="px-3 py-1 bg-gray-700/50 hover:bg-gray-700 text-gray-300 hover:text-white text-sm rounded-full transition-colors">
                    {{ $genre->name }}
                 </a>
                @endforeach
            </div>

            {{-- Description --}}
            <p class="text-gray-300 leading-relaxed">
                {{ $movie->description }}
            </p>

            {{-- Director & Cast --}}
            <div class="space-y-4 py-4 border-t border-gray-700">
                @if(isset($movie->director))
                    <div class="flex gap-3">
                        <span class="text-sm font-semibold text-gray-400 min-w-[80px]">Director</span>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('people.show', $movie->director) }}" 
                               class="text-sm text-blue-400 hover:text-blue-300 hover:underline transition-colors">
                                {{ $movie->director->first_name }} {{ $movie->director->last_name }}
                            </a>
                        </div>
                    </div>
                @endif

                @if(isset($movie->actors) && count($movie->actors) > 0)
                    <div class="flex gap-3">
                        <span class="text-sm font-semibold text-gray-400 min-w-[80px]">Stars</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($movie->actors->take(5) as $actor)
                                <a href="{{ route('people.show', $actor->slug) }}" 
                                   class="text-sm text-blue-400 hover:text-blue-300 hover:underline transition-colors">
                                    {{ $actor->first_name }} {{ $actor->last_name }}<span class="text-gray-500">{{ !$loop->last ? ',' : '' }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Trailer --}}
            @if ($movie->trailer_url)
                <div class="aspect-video rounded-xl overflow-hidden bg-gray-900">
                    <iframe 
                        src="https://www.youtube.com/embed/{{ $movie->trailer_url }}" 
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        class="w-full h-full"
                    ></iframe>
                </div>
            @endif

            {{-- Actions --}}
            @if(Auth::check())
                <div class="flex gap-3 mt-6">
                    <form action="{{ route('seen.toggle', $movie->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="group flex flex-col items-center gap-2 w-24 py-3 {{ $isSeen ? 'bg-green-600/20' : 'bg-gray-700/50' }} rounded-lg hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                 stroke="currentColor" class="w-7 h-7 {{ $isSeen ? 'text-green-500' : 'text-gray-400' }} group-hover:text-green-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="text-xs {{ $isSeen ? 'text-green-500' : 'text-gray-400' }} group-hover:text-white transition-colors">Seen</span>
                        </button>
                    </form>

                    <form action="{{ route('favorite.toggle', $movie->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="group flex flex-col items-center gap-2 w-24 py-3 {{ $isFavorite ? 'bg-red-600/20' : 'bg-gray-700/50' }} rounded-lg hover:bg-gray-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-7 h-7 {{ $isFavorite ? 'text-red-500' : 'text-gray-400' }} group-hover:text-red-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                            <span class="text-xs {{ $isFavorite ? 'text-red-500' : 'text-gray-400' }} group-hover:text-white transition-colors">Like</span>
                        </button>
                    </form>

                    <form action="{{ route('watchlist.toggle', $movie->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="group flex flex-col items-center gap-2 w-24 py-3 {{ $isWatchList ? 'bg-blue-600/20' : 'bg-gray-700/50' }} rounded-lg hover:bg-gray-700 transition-colors relative">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                 stroke="currentColor" class="w-7 h-7 {{ $isWatchList ? 'text-blue-500' : 'text-gray-400' }} group-hover:text-blue-500 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" 
                                      d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            @if(!$isWatchList)
                            <div class="absolute top-2 right-2 w-4 h-4 bg-gray-600 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" 
                                     stroke="currentColor" class="w-3 h-3 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </div>
                            @endif
                            <span class="text-xs {{ $isWatchList ? 'text-blue-500' : 'text-gray-400' }} group-hover:text-white transition-colors">Watchlist</span>
                        </button>
                    </form>

                    @if(Auth::check() && !(Auth::user()->lists->isEmpty()))
                        <div class="relative w-24">
                            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')"
                                    class="group flex flex-col items-center gap-2 w-full py-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" 
                                    stroke="currentColor" class="w-7 h-7 text-gray-400 group-hover:text-purple-500 transition-colors">
                                    <path stroke-linecap="round" stroke-linejoin="round" 
                                        d="M12 10.5v6m3-3H9m4.06-7.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                                </svg>
                                <span class="text-xs text-gray-400 group-hover:text-white transition-colors">Add List</span>
                            </button>
                            
                            <div class="hidden absolute top-full mt-2 w-48 bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-10">
                                @foreach(Auth::user()->lists as $option)
                                <form action="{{ route('lists.add', $movie->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="listId" value="{{ $option->id }}">
                                    <button type="submit" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-700 first:rounded-t-lg last:rounded-b-lg transition-colors">
                                        {{ $option->name }}
                                    </button>
                                </form>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Similar Movies Section --}}
    @if(count($similarMovies) > 0)
    <div class="mt-12 pt-8 border-t border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($similarMovies as $recommendation)
                <a href="{{ route('movies.show', $recommendation['movie']->slug) }}" 
                class="group">
                    <div class="relative overflow-hidden rounded-lg shadow-lg aspect-[2/3]">
                        <img src="https://image.tmdb.org/t/p/w500/{{ $recommendation['movie']->poster_url }}" 
                            alt="{{ $recommendation['movie']->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/0 to-black/0 opacity-0 group-hover:opacity-100 transition-opacity">
                            <div class="absolute bottom-0 left-0 right-0 p-3">
                                <div class="flex items-center gap-1 mb-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3 h-3 text-yellow-500">
                                        <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-xs text-white font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-white group-hover:text-blue-400 transition-colors line-clamp-2">
                        {{ $recommendation['movie']->name }}
                    </h3>
                    <p class="text-xs text-gray-400"></p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Write Review Section --}}
    @if(Auth::check())
    <div class="mt-12 pt-8 border-t border-gray-700">
        <x-write-review/>
    </div>
    @endif

    {{-- Reviews Section --}}
    <div class="mt-12 pt-8 border-t border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-500">
                <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0 1 12 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 0 1-3.476.383.39.39 0 0 0-.297.17l-2.755 4.133a.75.75 0 0 1-1.248 0l-2.755-4.133a.39.39 0 0 0-.297-.17 48.9 48.9 0 0 1-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97Z" clip-rule="evenodd" />
            </svg>
            Reviews
            <span class="text-base font-normal text-gray-400">({{ $movie->reviews->count() }})</span>
        </h2>

        @if($movie->reviews->isEmpty())
            <div class="bg-gray-800 rounded-xl p-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-600 mx-auto mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
                <h3 class="text-xl font-semibold text-white mb-2">No Reviews Yet</h3>
                <p class="text-gray-400">Be the first to share your thoughts about this movie!</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($movie->reviews as $review)
                <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show', $review->user) }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold">{{ substr($review->user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-white">{{ $review->user->name }}</p>
                                        <time class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</time>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="flex items-center gap-1 bg-yellow-500/10 border border-yellow-500/30 rounded-lg px-3 py-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-500">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-lg font-bold text-yellow-500">{{ $review->rating }}</span>
                                <span class="text-xs text-gray-400">/10</span>
                            </div>
                        </div>

                        @if($review->spoilers)
                            <div class="p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500 flex-shrink-0">
                                        <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm text-yellow-500 font-medium">This review contains spoilers</span>
                                    <button 
                                        onclick="toggleSpoiler({{ $review->id }})" 
                                        class="ml-auto px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-medium rounded hover:bg-yellow-400 transition-colors"
                                    >
                                        Show
                                    </button>
                                </div>
                                
                                <div id="spoiler-content-{{ $review->id }}" class="hidden mt-3 pt-3 border-t border-yellow-500/20">
                                    <p class="text-gray-300 leading-relaxed">{{ $review->description }}</p>
                                    <button 
                                        onclick="toggleSpoiler({{ $review->id }})" 
                                        class="mt-2 text-sm text-yellow-500 hover:text-yellow-400 font-medium"
                                    >
                                        Hide
                                    </button>
                                </div>
                            </div>
                        @else
                            <p class="text-gray-300 leading-relaxed">{{ $review->description }}</p>
                        @endif
                    </div>
                    
                    <div class="bg-gray-900/50 px-6 py-3 border-t border-gray-700 flex items-center justify-between">
                        <a href="{{ route('reviews.show', $review) }}" class="text-sm text-blue-400 hover:text-blue-300 font-medium">
                            View full review and comments
                        </a>
                        <span class="text-xs text-gray-400">{{ $review->comments->count() }} {{ Str::plural('comment', $review->comments->count()) }}</span>
                    </div>
                </article>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>

<!-- <script>
function toggleSpoiler(reviewId) {
    const content = document.getElementById(`spoiler-content-${reviewId}`);
    content.classList.toggle('hidden');
}
</script> -->
@once('scripts')
<script>

function toggleSpoiler(reviewId) {
    const warning = document.querySelector(`.spoiler-warning-${reviewId}`);
    const content = document.querySelector(`.spoiler-content-${reviewId}`);
    
    if (content.classList.contains('hidden')) {
        // Show spoiler content
        warning.classList.add('hidden');
        content.classList.remove('hidden');
    } else {
        // Hide spoiler content
        warning.classList.remove('hidden');
        content.classList.add('hidden');
    }
}

</script>
@endonce
@endsection