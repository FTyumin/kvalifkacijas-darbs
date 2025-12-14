<div class="md:col-span-full">
    <!-- Success/Warning Messages -->
    @if (session()->has('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif
    
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form wire:submit.prevent="save" class="mt-16 mx-auto space-y-6 mb-12">

        <div>
            <label for="title" class="block text-sm font-medium text-white mb-1">
                Title of your review
            </label>
            <input type="text" id="title" name="title" required class="px-4 py-2 border border-gray-300 rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 transition
                        disabled:opacity-50 disabled:pointer-events-none"
                    wire:model="title"
                    autocomplete="off">
        </div>

        <h3 class="text-3xl text-white font-semibold">Write a Review for <span class="text-green-600">{{ $movie->name }}</span></h3>
        <input type="hidden" name="movie_id" value="{{ $movie->id }}" autocomplete="off" >
        {{-- Star Rating --}}
        <fieldset class="flex items-center space-x-1" wire:model="rating" autocomplete="off">
            <legend class="sr-only">Rating</legend>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="1" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="1"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="2" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="2"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="3" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="3"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="4" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="4"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="5" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="5"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
        </fieldset>
        @error('rating')
            <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror

        @if (session('warning'))
            <div class="alert alert-warning">
                <p class="text-red-600 text-sm">{{ session('warning') }}</p>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Review Text --}}
        <div>
            <label for="comment" class="block text-sm font-medium text-white mb-1">
                Your Review
            </label>
            <textarea id="comment" name="comment" rows="4" wire:model="comment" autocomplete="off"
                class="block w-full px-4 py-2 border border-gray-300 rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 transition
                        disabled:opacity-50 disabled:pointer-events-none"
                    placeholder="Share your thoughts about this movie"
                >{{ old('comment') }}
            </textarea>
            @error('comment')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-row gap-3 items-center text-white">
            <input type="checkbox" name="spoiler" wire:model="spoilers"> <p>Contains spoilers</p>
        </div>

        {{-- Submit --}}
        <button type="submit" class="inline-block px-6 py-3 bg-blue-600 text-white 
            font-medium rounded-lg hover:bg-blue-700 transition">
            Submit Review
        </button>
    </form>

    {{-- Reviews Section --}}
    <div class="mt-12 pt-8 border-t border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-500">
                <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0 1 12 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 0 1-3.476.383.39.39 0 0 0-.297.17l-2.755 4.133a.75.75 0 0 1-1.248 0l-2.755-4.133a.39.39 0 0 0-.297-.17 48.9 48.9 0 0 1-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97Z" clip-rule="evenodd" />
            </svg>
            Reviews
            <span class="text-base font-normal text-gray-400">({{ $reviews->count() }})</span>
        </h2>

            <div class="space-y-4">
                @forelse($reviews as $review)
                <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show', $review->user) }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center overflow-hidden">
                                      <img src="{{ $review->user->image ? asset('storage/' . $post->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">

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
                @empty
                    <div class="bg-gray-800 rounded-xl p-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-600 mx-auto mb-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-white mb-2">No Reviews Yet</h3>
                        <p class="text-gray-400">Be the first to share your thoughts about this movie!</p>
                    </div>
                @endforelse
            </div>

    </div>
</div>
