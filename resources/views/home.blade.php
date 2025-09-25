@extends('layouts.app')
@section('content')
<section class="relative min-h-[55vh] md:min-h-[45vh] lg:min-h-[40vh] isolate bg-gray-700">
  <img
    src="{{ asset('images/cinema.webp') }}"
    alt="movie theater"
    class="absolute inset-0 h-full w-full object-cover"
  />

  <div class="absolute inset-0 bg-black/50"></div>
  <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-black/60 to-transparent"></div>

  <!-- Content -->
  <div class="relative mx-auto max-w-5xl px-6 py-20 sm:py-28 md:py-36">
    <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-white">
      Honest Reviews. Zero Spoilers.
    </h1>
    <p class="mt-4 max-w-2xl text-white/80 md:text-lg">
      Movies & shows reviewed with care, context, and clarity.
    </p>
    <div class="mt-8 flex gap-3">
      <a href="#latest" class="rounded-2xl bg-white/10 px-5 py-3 text-white backdrop-blur hover:bg-white/20 transition">
        Latest Reviews
      </a>
      <a href="#search" class="rounded-2xl bg-white text-gray-900 px-5 py-3 hover:bg-gray-100 transition">
        Find a Title
      </a>
    </div>
  </div>

</section>

<!-- Trending Movies -->
<div class="my-20 mx-6 sm:mx-8 lg:mx-28 bg-gray-700">
  <div class="mb-12">
    <h1 class="mb-3 text-4xl font-bold leading-tight tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
      Biggest movies <span class="text-blue-600 dark:text-blue-400">Right Now</span>
    </h1>
    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl">
      Discover the most popular and trending movies that everyone is talking about
    </p>
  </div>

  <!-- Responsive Grid -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ">
    @foreach($movies as $movie)
    <div class="group bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600 overflow-hidden">
      
      <div class="relative overflow-hidden">
        <a href="{{ route('movies.show', $movie->id ) }}" class="block">
          <img class="aspect-[2/3] w-full object-cover transition-transform duration-300 group-hover:scale-105" 
               src="{{ asset('images/cinema.webp') }}" 
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
          <span class="text-green-600 dark:text-green-400 font-medium">89% Fresh</span>
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

  <!-- View All Button -->
  <div class="mt-12 text-center">
    <a href="#" class="inline-flex items-center px-6 py-3 text-base font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 dark:focus:ring-blue-800 transition-colors">
      View All Movies
      <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
      </svg>
    </a>
  </div>
</div>

<!-- Genres -->
<div class="my-16 mx-10 sm:px-8 lg:px-28">
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl">Genres</h1>

    <div class="grid grid-cols-4 gap-x-16 gap-y-10">
        @foreach($genres as $genre)
            <div class="w-[24rem] bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ $genre->name }}
                        </h5>
                    </a>
                    <a href="{{ route('genres.show', $genre->id ) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus-visible:ring-blue-800">
                        View Movies
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                </div>
            </div>   
        @endforeach
    </div>
</div>

@endsection