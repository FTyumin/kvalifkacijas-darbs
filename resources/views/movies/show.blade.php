@extends('layouts.app')

@section('title', $movie->name)

@section('content')

<div class="max-w-6xl mx-auto mt-8 px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Poster --}}
        <div class="md:col-span-1">
            <img src="{{ asset('images/cinema.webp') }}" 
                 alt=" poster" 
                 class="rounded-xl shadow-md w-full">
        </div>

        {{-- Details --}}
        <div class="md:col-span-2 space-y-6">
            <h1 class="text-3xl font-bold">{{ $movie->name }}</h1>
            
            <div class="flex items-center gap-4 text-gray-600">
                <span class="text-sm">Released: {{ $movie->year }}</span>
                <span class="text-sm">Rating: ⭐ {{ $movie->rating }}</span>
                <span class="text-sm">Country: {{ $movie->title }}</span>
                <span class="text-sm">Language: </span>

            </div>

            {{-- Genres --}}
            <div class="flex flex-wrap gap-2">
                @foreach($movie->genres as $genre)
                 <a href="{{ route('genres.show', $genre->id ) }}" class="hover:text-gray-400">
                    <span class="px-3 py-1 bg-gray-100 text-sm rounded-full">
                        {{ $genre->name }} 
                    </span>
                 </a>
                @endforeach
            </div>

            {{-- Description --}}
            <p class="text-gray-700 leading-relaxed">
                description 
            </p>

            <!-- Actors,Director -->

            {{-- Actions --}}
            <div class="flex gap-3 mt-6">
            <!-- Add to watchlist -->
                <form action="{{ route('favorite.add', $movie->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <button class="p-2 bg-red-100 rounded hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-red-700 hover:text-red-100">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                 </form>

                 <form action="{{ route('bookmark.add', $movie->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <button class="hover:bg-green-400">
                          <img src="{{ asset('images/eye.svg') }}" alt="" class="size-9">
                          Already seen
                        </button>
                 </form>

                 
                 

            </div>
        </div>
        <!-- Review section -->
         <div>
            <form action="{{ route('reviews.store') }}" method="POST" class="mt-16 mx-auto space-y-6 mb-12">
              @csrf

              <h3 class="text-3xl font-semibold">Write a Review for <span class="text-green-600">{{ $movie->name }}</span></h3>
              <input type="hidden" name="movie_id" value="{{ $movie->id }}">
              {{-- Star Rating --}}
              <fieldset class="flex items-center space-x-1">
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
                <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
                  Your Review
                </label>
                <textarea
                  id="comment"
                  name="comment"
                  rows="4"
                  class="block w-full px-4 py-2 border border-gray-300 rounded-lg 
                        focus:ring-blue-500 focus:border-blue-500 transition
                        disabled:opacity-50 disabled:pointer-events-none"
                  placeholder="Share your thoughts about this movie"
                >{{ old('comment') }}</textarea>
                @error('comment')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror
              </div>
                <div class="flex flex-row gap-3 items-center">
                  <input type="checkbox" name="spoiler"> <p>Contains spoilers</p>
                </div>

              {{-- Submit --}}
              <button
                type="submit"
                class="inline-block px-6 py-3 bg-blue-600 text-white font-medium 
                      rounded-lg hover:bg-blue-700 transition">
                Submit Review
              </button>
              </form>

         </div>
    </div>
    @if($movie->reviews->isEmpty())
        <h3 class="my-6 text-xl font-semibold">No Reviews for this movie, be the first one</h3>
    @else

    <h3 class="my-6">Reviews</h3>
    @foreach($movie->reviews as $review)
      @if($review->spoilers)
        <div class="flex flex-col bg-white block w-full px-4 py-2 border border-gray-300 rounded-lg">
            <div class="p-4 md:p-5">
                <a href="{{ route('profile.show', $review->user) }}">
                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $review->user->name }}
                    </h3>      
                </a>

                
                <!-- Spoiler Warning and Button -->
                <div class="spoiler-warning-{{ $review->id }} mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800 font-medium">⚠️ This review contains spoilers</p>
                    <button 
                        onclick="toggleSpoiler({{ $review->id }})" 
                        class="mt-2 px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors"
                    >
                        Show Review
                    </button>
                </div>
                
                <!-- Hidden Spoiler Content -->
                <div class="spoiler-content-{{ $review->id }} hidden mt-2">
                    <p class="text-gray-500">
                        {{ $review->description }}
                    </p>
                    <p class="mt-2">
                        Rating: {{ $review->rating }}
                    </p>
                    <button 
                        onclick="toggleSpoiler({{ $review->id }})" 
                        class="mt-2 px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors"
                    >
                        Hide Review
                    </button>
                </div>
            </div>
            <div class="bg-gray-100 border-t border-gray-200 rounded-b-xl py-3 px-4 md:py-4 md:px-5">
                <p class="mt-1 text-sm text-gray-500">
                    {{ $review->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
        @else
        <div class="flex flex-col bg-white block w-full px-4 py-2 border border-gray-300 rounded-lg">
            <div class="p-4 md:p-5">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $review->user->name }}
                </h3>
                <p class="mt-2 text-gray-500">
                    {{ $review->description }}
                </p>
                <p>
                    Rating: {{ $review->rating }}
                </p>
            </div>
            <div class="bg-gray-100 border-t border-gray-200 rounded-b-xl py-3 px-4 md:py-4 md:px-5">
                <p class="mt-1 text-sm text-gray-500">
                    {{ $review->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    @endif
    @endforeach
    @endif

</div>
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