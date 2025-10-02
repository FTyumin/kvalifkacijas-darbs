@extends('layouts.app')

@section('title', $movie->name)

@section('content')
<div class="max-w-6xl mx-auto mt-8 px-4 py-8 space-y-12">

  <!-- Movie header section -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

    {{-- Poster --}}
    <div class="md:col-span-1">
      <img src="{{ asset('images/cinema.webp') }}" 
           alt="poster" 
           class="rounded-xl shadow-md w-full">
    </div>

    {{-- Details --}}
    <div class="md:col-span-2 space-y-6">
      <h1 class="text-3xl font-bold">{{ $movie->name }}</h1>
      
      <div class="flex flex-wrap gap-4 text-gray-600">
        <span class="text-sm">Released: {{ $movie->year }}</span>
        <span class="text-sm">Rating: ⭐ {{ $movie->rating }}</span>
        <span class="text-sm">Country: {{ $movie->title }}</span>
        <span class="text-sm">Language: English</span>
      </div>

      {{-- Genres --}}
      <div class="flex flex-wrap gap-2">
        @foreach($movie->genres as $genre)
          <span class="px-3 py-1 bg-gray-100 text-sm rounded-full">
            {{ $genre->name }} 
          </span>
        @endforeach
      </div>

      {{-- Description --}}
      <p class="text-gray-700 leading-relaxed">
        {{ $movie->description ?? 'No description available.' }}
      </p>

      {{-- Actions --}}
      <div class="flex gap-3 mt-6">
        <form action="{{ route('favorite.add', $movie->id) }}" method="POST">
          @csrf
          <button class="p-2 bg-red-100 rounded hover:bg-red-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke-width="1.5"
                 stroke="currentColor" 
                 class="w-6 h-6 text-red-700 hover:text-red-100">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 
                   1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 
                   3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 
                   9-12z" />
            </svg>
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Review Form -->
  <div>
    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
      @csrf

      <h3 class="text-2xl font-semibold">
        Write a Review for <span class="text-green-600">{{ $movie->name }}</span>
      </h3>
      <input type="hidden" name="movie_id" value="{{ $movie->id }}">

      {{-- Rating --}}
      <fieldset class="flex items-center space-x-1">
        <legend class="sr-only">Rating</legend>
        @for($i=1;$i<=5;$i++)
          <label class="relative cursor-pointer">
            <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" />
            <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors" 
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 20 20" fill="currentColor">
              <path d="M9.049 2.927c.3-.921 1.603-.921 
                       1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 
                       0 1.371 1.24.588 1.81l-3.36 2.44a1 1 
                       0 00-.364 1.118l1.287 3.951c.3.921-.755 
                       1.688-1.54 1.118l-3.36-2.44a1 1 
                       0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118
                       l1.286-3.951a1 1 0 00-.364-1.118L2.98 
                       9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 
                       1 0 00.95-.69l1.286-3.95z" />
            </svg>
          </label>
        @endfor
      </fieldset>

      {{-- Review text --}}
      <div>
        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Your Review</label>
        <textarea id="comment" name="comment" rows="4"
          class="block w-full px-4 py-2 border border-gray-300 rounded-lg 
                 focus:ring-blue-500 focus:border-blue-500 transition"
          placeholder="Share your thoughts...">{{ old('comment') }}</textarea>
      </div>

      {{-- Submit --}}
      <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
        Submit Review
      </button>
    </form>
  </div>

  <!-- Reviews List -->
  <div>
    <h3 class="text-xl font-semibold mb-4">Reviews</h3>
    @if($movie->reviews->isEmpty())
      <p>No reviews yet. Be the first one!</p>
    @else
      <div class="space-y-4">
        @foreach($movie->reviews as $review)
          <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
            <div class="p-4">
              <h4 class="text-lg font-bold">{{ $review->user->name }}</h4>
              <p class="mt-2 text-gray-600">{{ $review->description }}</p>
              <p class="mt-1 text-sm">⭐ {{ $review->rating }}/5</p>
            </div>
            <div class="bg-gray-50 border-t px-4 py-2 text-sm text-gray-500">
              {{ $review->created_at->format('d M Y') }}
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

  <div class="mt-8">
    <h2 class="text-2xl font-semibold">Recommendations</h2>
      <!-- Responsive Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ">
        @foreach($recommendations as $movie)
        <div class="group bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600 overflow-hidden">
          
          <div class="relative overflow-hidden">
            <a href="{{ route('movies.show', $movie['id'] ) }}" class="block">
              <img class="aspect-[2/3] w-full object-cover transition-transform duration-300 group-hover:scale-105" 
                  src="{{ asset('images/cinema.webp') }}" 
                  alt="Movie poster" />
            </a>
            
            <div class="absolute top-3 left-3 bg-black/70 backdrop-blur-sm text-white px-2 py-1 rounded-md text-sm font-medium flex items-center gap-1">
              <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>

            </div>

            <div class="absolute bottom-3 left-3 flex gap-1">
              
            </div>
          </div>

          <!-- Content Section -->
          <div class="p-5">
            <a href="{{ route('movies.show', $movie['id'] ) }}" class="group/title">
              <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white group-hover/title:text-blue-600 dark:group-hover/title:text-blue-400 transition-colors line-clamp-2">
                {{ $movie['name'] }}
              </h3>
            </a>

            <div class="mb-3 flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
              <span>{{ $movie['year'] }}</span>
              <span>•</span>
              <!-- <span>{{ 'duration' }} min</span> -->
              <span>•</span>
              <span class="text-green-600 dark:text-green-400 font-medium">89% Fresh</span>
            </div>

            <p class="mb-4 text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
              {{ $movie['description'] }}
            </p>

            <div class="flex gap-2">
              <a href="{{ route('movies.show', $movie['id'] ) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
                View Details
              </a>
              <button class="p-2 text-gray-500 hover:text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-gray-400 dark:hover:text-red-400 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
        @endforeach
      </div>
  </div>

</div>
@endsection
