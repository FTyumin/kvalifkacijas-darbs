
<div class="group relative rounded-xl overflow-hidden bg-neutral-900
            border border-white/5 hover:border-white/15
            transition-all duration-300">

    {{-- Poster --}}
    <a href="{{ route('movies.show', $movie->slug) }}" class="block relative">
        <img src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}"
            alt="{{ $movie->name }}"
            class="aspect-[2/3] w-full object-cover transition-transform duration-500 group-hover:scale-105"/>

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