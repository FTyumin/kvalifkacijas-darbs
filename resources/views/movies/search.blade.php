@extends('layouts.app')

@section('content')

<div class="py-4 px-28 flex flex-col gap-3">
  @if (count($movies) > 0)
    <h1 class="text-3xl text-white">Results for <span class="text-yellow-500">"{{ $search }}"</span></h1>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ">
        @foreach($movies as $movie)
          <div class="group relative rounded-xl overflow-hidden bg-neutral-900
              border border-white/5 hover:border-white/15
              transition-all duration-300">

            {{-- Poster --}}
            <a href="{{ route('movies.show', $movie->slug) }}" class="block relative">
                <img
                    src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}"
                    alt="{{ $movie->name }}"
                    class="aspect-[2/3] w-full object-cover
                          transition-transform duration-500 group-hover:scale-105"/>

                {{-- Gradient overlay --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>

                {{-- Rating --}}
                <div class="absolute top-3 left-3 flex items-center gap-1 rounded-md bg-black/70 backdrop-blur px-2 py-1
                  text-sm text-white">
                    @svg('heroicon-s-star', 'w-4 h-4 text-yellow-400')
                    {{ $movie->tmdb_rating }}
                </div>

                {{-- Genres --}}
                <div class="absolute bottom-3 left-3 right-3 flex flex-wrap gap-1">
                    @foreach($movie->genres->take(2) as $genre)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-blue-600/80 text-white backdrop-blur">
                            {{ $genre->name }}
                        </span>
                    @endforeach
                </div>
            </a>

            {{-- Content --}}
            <div class="p-4">
                <h3 class="text-sm font-semibold text-white leading-tight line-clamp-2 group-hover:text-yellow-400 transition">
                    {{ $movie->name }}
                </h3>

                <div class="mt-1 flex items-center gap-2 text-xs text-gray-400">
                    <span>{{ $movie->year }}</span>
                    <span>•</span>
                    <span>{{ $movie->duration }} min</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
@else
    <div class="mt-12 rounded-2xl border border-gray-800 bg-gradient-to-b from-gray-900/60 to-gray-900/30 p-10 text-center shadow-lg">
        <h1 class="text-2xl font-semibold text-white">No results for <span class="text-green-400">"{{ $search }}"</span></h1>
        <p class="mt-2 text-gray-400">Try a different movie title</p>
        <div class="mt-6 flex justify-center gap-3">
            <a class="inline-flex items-center rounded-lg bg-gray-800 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 transition-colors" href="/movies">
                Browse all movies
            </a>
        </div>
    </div>
  @endif
  @if($people && $people->count() > 0)
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-white mb-6">
            People <span class="text-gray-400 text-lg font-normal">({{ $people->count() }} results)</span>
        </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($people as $person)
                <a href="{{ route('people.show', $person->slug) }}" 
                   class="group flex items-center gap-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all duration-300">
                    
                    <!-- Person Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white group-hover:text-yellow-600 dark:group-hover:text-yellow-400 transition-colors truncate">
                            {{ $person->first_name }} {{ $person->last_name }}
                        </h3>
                        
                    </div>

                    <!-- Arrow Icon -->
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="mt-12 rounded-2xl border border-gray-800 bg-gradient-to-b from-gray-900/60 to-gray-900/30 p-10 text-center shadow-lg">
        <h1 class="text-2xl font-semibold text-white">No people found for <span class="text-green-400">"{{ $search }}"</span></h1>
        <p class="mt-2 text-gray-400">Try a different movie title</p>
    </div>
@endif
</div>
@endsection
