@extends('layouts.app')

@section('title', $genre->name)

@section('content')
<div class="relative min-h-[55vh] md:min-h-[45vh] lg:min-h-[40vh] mx-16 py-12">

    <h1 class="text-2xl text-yellow-600">{{ $genre->name }} Movies</h1>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-8">
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
                      transition-transform duration-500 group-hover:scale-105"
            />

            {{-- Gradient overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t
                        from-black/90 via-black/20 to-transparent"></div>

            {{-- Rating --}}
            @if($movie->rating)
                <div class="absolute top-3 left-3 flex items-center gap-1
                            rounded-md bg-black/70 backdrop-blur px-2 py-1
                            text-sm text-white">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    {{ $movie->rating }}
                </div>
            @endif

            {{-- Genres --}}
            <div class="absolute bottom-3 left-3 right-3 flex flex-wrap gap-1">
                @foreach($movie->genres->take(2) as $genre)
                    <span class="text-xs px-2 py-0.5 rounded-full
                                bg-blue-600/80 text-white backdrop-blur">
                        {{ $genre->name }}
                    </span>
                @endforeach
            </div>
        </a>

        {{-- Content --}}
        <div class="p-4">
            <h3 class="text-sm font-semibold text-white leading-tight line-clamp-2
                      group-hover:text-yellow-400 transition">
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

    {{ $movies->links() }}
</div>
@endsection