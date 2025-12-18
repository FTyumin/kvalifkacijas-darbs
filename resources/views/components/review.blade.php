 <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
    <a href="{{ route('movies.show', $review->movie->slug) }}" class="block">
        <div class="flex gap-4 p-6">
            {{-- Movie Poster --}}
            <div class="flex-shrink-0">
                <img src="https://image.tmdb.org/t/p/w200/{{ $review->movie->poster_url }}" 
                alt="movie poster" 
                class="w-24 h-36 object-cover rounded-lg shadow-md">
            </div>

            {{-- Card Content --}}
                <div class="flex-1 min-w-0">
                    {{-- Card Header --}}
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-white hover:text-blue-400 transition-colors mb-2">
                                {{ $review->title }}
                            </h2>
                            <div class="flex items-center gap-3 flex-wrap">
                                <a href="{{ route('profile.show', $review->user) }}" 
                                class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                        <img src="{{ $review->user->image ? asset('storage/' . $review->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">
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
                        </div>
                    </div>

                </div>
       
            </div>
            
            {{-- Card Footer --}}
            <a href="{{ route('reviews.show', $review) }}">

                <div class="bg-gray-900/50 px-6 py-3 border-t border-gray-700 flex items-center justify-between">
                    <span class="text-sm text-gray-400">Click to read full review</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </a>                        
        </a>
</article>
             