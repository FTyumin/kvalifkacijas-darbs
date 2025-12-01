@extends('layouts.app')

@section('content')

<div class="py-4 px-28 flex flex-col gap-3">
  @if (count($movies) > 0)
    <h1 class="text-3xl text-white">Results for <span class="text-green-500">"{{ $search }}"</span></h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ">
        @foreach($movies as $movie)
        <div class="group bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600 overflow-hidden">
          
          <div class="relative overflow-hidden">
            <a href="{{ route('movies.show', $movie->slug ) }}" class="block">
              <img class="aspect-[2/3] w-full object-cover transition-transform duration-300 group-hover:scale-105" 
                  src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}" 
                  alt="Movie poster" />
            </a>
            
            <div class="absolute top-3 left-3 bg-black/70 backdrop-blur-sm text-white px-2 py-1 rounded-md text-sm font-medium flex items-center gap-1">
              <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
              {{ $movie->rating }}
            </div>

            <div class="absolute bottom-3 left-3 flex gap-1">
              @foreach($movie->genres as $genre)
                <span class="bg-blue-600/90 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full">{{ $genre->name }}</span>
              @endforeach
            </div>
          </div>

          <!-- Content Section -->
          <div class="p-5">
            <a href="{{ route('movies.show', $movie->id ) }}" class="group/title">
              <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white group-hover/title:text-blue-600 dark:group-hover/title:text-blue-400 transition-colors line-clamp-2">
                {{ $movie->name }}
              </h3>
            </a>

            <div class="mb-3 flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
              <span>{{ $movie->year }}</span>
              <span>•</span>
              <span>{{ $movie->duration }} min</span>
              <span>•</span>
              <span class="text-green-600 dark:text-green-400 font-medium">{{ $movie->tmdb_rating }}</span>
            </div>

            <p class="mb-4 text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
              {{ $movie->description }}
            </p>

            <div class="flex gap-2">
              <a href="{{ route('movies.show', $movie->id ) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors">
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
    
  @else
    <h1 class="text-3xl text-white">No Results were found for <span class="text-green-500">"{{ $search }}"</span></h1>
  @endif

  <div>
      <h1 class="text-3xl text-white">Results for <span class="text-green-500">"{{ $search }}"</span></h1>

      @foreach($people as $person)
        <div class="flex gap-3">
          <a href="{{ route('people.show', $person->slug) }}" class="text-sm text-blue-400 hover:text-blue-300 hover:underline">
            <h2 class="text-white">{{ $person->first_name }}</h2>
            <h2 class="text-white"> {{ $person->last_name }}</h2>
          </a>
        </div>
      @endforeach
    </div>
</div>

@endsection