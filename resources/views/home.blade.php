@extends('layouts.app')
@section('content')
<section class="relative min-h-[60vh] md:min-h-[55vh] lg:min-h-[50vh] overflow-hidden isolate">


    <!-- Dark cinematic overlay -->
    <div class="absolute inset-0 bg-gradient-to-b"></div>

    <!-- Content -->
    <div class="relative mx-auto max-w-6xl px-6 py-32 md:py-40 flex flex-col items-start">

        <span class="text-blue-400 font-semibold tracking-wide text-sm md:text-base uppercase mb-4">
            Welcome to Your Movie World
        </span>

        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-tight max-w-3xl drop-shadow-xl">
            Discover. Track. Share<span class="text-blue-500">.</span>
        </h1>

        <p class="mt-5 max-w-2xl text-white/80 md:text-lg leading-relaxed">
            Browse movies, follow your friends, create lists, and see what everyone is watching.
        </p>

        <!-- Buttons -->
        <div class="mt-8 flex flex-wrap gap-4">
            <a href="/reviews"
               class="px-6 py-3 rounded-xl text-white font-medium bg-white/10 backdrop-blur border border-white/20 hover:bg-white/20 hover:border-white/30 transition">
                Browse Latest Reviews
            </a>

            <a href="#search"
               class="px-6 py-3 rounded-xl font-medium bg-blue-600 text-white hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
                Find a Movie
            </a>
        </div>
    </div>

</section>


<!-- Trending Movies -->
<div class="my-20 mx-6 sm:mx-8 lg:mx-28 p-8">
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
          <a href="{{ route('movies.show', $movie->slug ) }}" class="group/title">
            <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white group-hover/title:text-blue-600 dark:group-hover/title:text-blue-400 transition-colors line-clamp-2">
              {{ $movie->name }}
            </h3>
          </a>

          <div class="mb-3 flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
            <span>{{ $movie->year }}</span>
            <span>•</span>
            <span>{{ $movie->duration }} mins</span>
            <span>•</span>
            <!-- <span class="text-green-600 dark:text-green-400 font-medium">89% Fresh</span> -->
          </div>

          <p class="mb-4 text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
            {{ $movie->description }}
          </p>

          <div class="flex gap-2">
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
    <a href="/movies" class="inline-flex items-center px-6 py-3 text-base font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 dark:focus:ring-blue-800 transition-colors">
      View All Movies
      <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
      </svg>
    </a>
  </div>

    <!-- Responsive Grid -->
     <h2 class="text-white">Recommendations</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ">
    @foreach($userRecommendations as $movie)
      
      <div class="group bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 dark:bg-gray-800 dark:border-gray-700 dark:hover:border-gray-600 overflow-hidden">
        
        <div class="relative overflow-hidden">
          <a href="{{ route('movies.show', $movie['movie']['slug'] ) }}" class="block">
            <img class="aspect-[2/3] w-full object-cover transition-transform duration-300 group-hover:scale-105" 
                src="https://image.tmdb.org/t/p/w500/{{ $movie['movie']->poster_url }}" 
                alt="Movie poster" />
          </a>
          
          <div class="absolute top-3 left-3 bg-black/70 backdrop-blur-sm text-white px-2 py-1 rounded-md text-sm font-medium flex items-center gap-1">
            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
            </svg>
            {{ $movie['movie']->rating }}
          </div>

          <div class="absolute bottom-3 left-3 flex gap-1">
            @foreach($movie['movie']->genres as $genre)
              <span class="bg-blue-600/90 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full">{{ $genre->name }}</span>
            @endforeach
          </div>
        </div>

        <!-- Content Section -->
        <div class="p-5">
          <a href="{{ route('movies.show', $movie['movie']->slug ) }}" class="group/title">
            <h3 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white group-hover/title:text-blue-600 dark:group-hover/title:text-blue-400 transition-colors line-clamp-2">
              {{ $movie['movie']->name }}
            </h3>
          </a>

          <div class="mb-3 flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
            <span>{{ $movie['movie']->year }}</span>
            <span>•</span>
            <span>{{ $movie['movie']->duration }} mins</span>
            <span>•</span>
            <!-- <span class="text-green-600 dark:text-green-400 font-medium">89% Fresh</span> -->
          </div>

          <p class="mb-4 text-sm text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
            {{ $movie["movie"]->description }}
          </p>

          <div class="flex gap-2">
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



<!-- Genres -->
<div class="my-16 mx-10 sm:px-8 lg:px-28">
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:text-5xl lg:text-6xl">Genres</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-16 gap-y-10">
        @foreach($genres as $genre)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
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

<!-- Popular Lists -->
 <div class="my-16 mx-10 sm:px-8 lg:px-28">
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-white md:text-5xl lg:text-6xl">Lists</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-16 gap-y-10">
        @foreach($lists as $list)
          <a href="{{ route('lists.show', $list) }}" class="group">
            <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 hover:bg-gray-800/70 hover:border-gray-600 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10 h-full flex flex-col">
                {{-- List Header --}}
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    
                    <div class="flex items-center gap-2 text-xs text-gray-400 bg-gray-700/50 px-3 py-1 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                        {{ $list->movies->count() ?? 0 }} movies
                    </div>
                </div>

                {{-- List Title --}}
                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors line-clamp-2">
                    {{ $list->name }}
                </h3>

                {{-- List Description --}}
                <p class="text-gray-400 text-sm mb-4 flex-grow line-clamp-3 leading-relaxed">
                    {{ $list->description ?? 'No description provided' }}
                </p>

                {{-- List Footer --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full overflow-hidden">
                            <!-- {{ substr($list->user->name, 0, 2) }} -->
                              <img src="{{ $list->user->image ? asset('storage/' . $list->user->image) : asset('images/person-placeholder.png') }}" alt="" class="h-8 w-8 object-cover">
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-300">{{ $list->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $list->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<!-- Reviews -->
 <div class="my-16 mx-10 sm:px-8 lg:px-28">
   
</div>

@endsection