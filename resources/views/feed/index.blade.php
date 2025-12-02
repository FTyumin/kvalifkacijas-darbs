@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-8">
        <h1 class="text-2xl text-white font-bold mb-6">Your Feed</h1>
        
        <div class="space-y-6">
            @forelse($reviews as $review)
            <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <a href="{{ route('reviews.show', $review) }}" class="block">
                    <div class="flex gap-4 p-6">
                        {{-- Movie Poster --}}
                        <div class="flex-shrink-0">
                            <img src="https://image.tmdb.org/t/p/w200/{{ $review->movie->poster_url }}" 
                                alt="{{ $review->movie->name }} poster" 
                                class="w-24 h-36 object-cover rounded-lg shadow-md">
                        </div>

                        {{-- Review Content --}}
                        <div class="flex-1 min-w-0">
                            {{-- Review Header --}}
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <div class="flex-1">
                                    <h2 class="text-xl font-bold text-white hover:text-blue-400 transition-colors mb-2">
                                        {{ $review->movie->name }}
                                    </h2>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <a href="{{ route('profile.show', $review->user) }}" 
                                        onclick="event.stopPropagation()"
                                        class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                                <span class="text-white text-sm font-semibold">{{ substr($review->user->name, 0, 1) }}</span>
                                            </div>
                                            <span class="text-gray-300 text-sm font-medium">{{ $review->user->name }}</span>
                                        </a>
                                        <span class="text-gray-500">•</span>
                                        <time class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</time>
                                        <span class="text-gray-500">•</span>
                                        <span class="text-sm text-gray-400">{{ $review->comments->count() }} {{ Str::plural('comment', $review->comments->count()) }}</span>
                                    </div>
                                </div>
                                
                                {{-- Rating Badge --}}
                                <div class="flex items-center gap-1 mb-2">
                                    @for ($j = 0; $j < $review->rating; $j++)
                                        <svg class="w-4 h-4" fill="yellow" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    @for ($j = $review->rating; $j < 5; $j++)
                                        <svg class="w-4 h-4" fill="black" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <!-- <span class="text-sm text-gray-400 ml-1">{{ $review->rating }}</span> -->
                                </div>
                            </div>

                            {{-- Review Content --}}
                            @if($review->spoilers)
                                {{-- Spoiler Warning --}}
                                <div class="p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500 flex-shrink-0">
                                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-sm text-yellow-500 font-medium">This review contains spoilers</span>
                                        <button 
                                            onclick="event.preventDefault(); event.stopPropagation(); toggleSpoiler({{ $review->id }})" 
                                            class="ml-auto px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-medium rounded hover:bg-yellow-400 transition-colors"
                                        >
                                            Show
                                        </button>
                                    </div>
                                    
                                    {{-- Hidden Content --}}
                                    <div id="spoiler-content-{{ $review->id }}" class="hidden mt-3 pt-3 border-t border-yellow-500/20">
                                        <p class="text-gray-300 leading-relaxed line-clamp-3">{{ $review->description }}</p>
                                        <button 
                                            onclick="event.preventDefault(); event.stopPropagation(); toggleSpoiler({{ $review->id }})" 
                                            class="mt-2 text-sm text-yellow-500 hover:text-yellow-400 font-medium"
                                        >
                                            Hide
                                        </button>
                                    </div>
                                </div>
                            @else
                                {{-- Regular Review Preview --}}
                                <p class="text-gray-300 leading-relaxed line-clamp-3">
                                    {{ $review->description }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Card Footer --}}
                    <div class="bg-gray-900/50 px-6 py-3 border-t border-gray-700 flex items-center justify-between">
                        <span class="text-sm text-gray-400">Click to read full review</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </a>
            </article>
            @empty
                
            @endforelse
        </div>
        
        
    </div>
@endsection