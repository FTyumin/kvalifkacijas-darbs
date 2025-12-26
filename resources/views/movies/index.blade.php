@extends('layouts.app')

@section('title', 'Movies')

@section('content')
<div class="relative mx-6 lg:mx-16 py-10 mb-10">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-white">
        Movies
    </h1>

    

         


  </div>



        </div>
  <div>
    
  </div>
    

  
  <!-- Movie Grid -->
 <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-8">
  <aside class="h-full">
      <div x-data="{ open: false }" @keydown.escape.window="open = false" class="relative"
      >

      <div class="flex items-center justify-between mb-6">

      <!-- Mobile filter button -->
      <button
          @click="open = true"
          class="lg:hidden flex items-center gap-2 px-4 py-2
                bg-blue-600 hover:bg-blue-700 text-white
                rounded-xl transition"
      >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4h18M4 8h16M6 12h12M8 16h8" />
          </svg>
          Filters
      </button>

      <!-- Backdrop -->
          <div
              x-show="open"
              x-transition.opacity
              @click="open = false"
              class="fixed inset-0 bg-black/60 z-40 lg:hidden"
          ></div>

          <div
            x-show="open || window.innerWidth >= 1024"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed lg:static inset-y-0 left-0 z-50 lg:z-auto
                  w-[85%] max-w-sm lg:w-full
                  bg-gray-900/95 backdrop-blur
                  border-r border-gray-700
                  p-6 overflow-y-auto
                  lg:rounded-2xl lg:border lg:sticky lg:top-6">
            

            <div class="flex items-center justify-between mb-6 lg:hidden">
                <h2 class="text-xl font-semibold text-white">Filters</h2>
                <button @click="open = false" class="text-gray-400 hover:text-white text-2xl">
                    ×
                </button>
            </div>
          <form method="GET" action="{{ route('movies.index') }}" class="space-y-6">

      <!-- Genres -->
      <div>
          <p class="text-sm font-medium text-gray-300 mb-3">Genres</p>
          <div class="grid grid-cols-2 gap-2">
              @foreach($genres as $genre)
                  <label class="flex items-center gap-2 text-sm text-gray-200">
                      <input
                          type="checkbox"
                          name="genres[]"
                          value="{{ $genre->id }}"
                          class="rounded border-gray-600 bg-gray-800 text-blue-500 focus:ring-blue-500"
                          {{ in_array($genre->id, request('genres', [])) ? 'checked' : '' }}
                      >
                      {{ $genre->name }}
                  </label>
              @endforeach
          </div>
      </div>

      <!-- Rating -->
      <div>
          <label class="block text-sm text-gray-300 mb-2">Min Rating</label>
          <select name="min_rating"
              class="w-full rounded-lg bg-gray-800 border-gray-700 text-white focus:ring-blue-500">
              <option value="">Any</option>
              @foreach([9,8,7,6,5] as $r)
                  <option value="{{ $r }}" {{ request('min_rating') == $r ? 'selected' : '' }}>
                      {{ $r }}+ ⭐
                  </option>
              @endforeach
          </select>
      </div>

      <!-- Year -->
      <div>
          <label class="block text-sm text-gray-300 mb-2">Year</label>
          <select name="decade"
              class="w-full rounded-lg bg-gray-800 border-gray-700 text-white focus:ring-blue-500">
              <option value="">All</option>
              @foreach($decades as $d)
                  <option value="{{ $d }}" {{ request('decade') == (string)$d ? 'selected' : '' }}>
                    {{ $d }}s
                </option>
              @endforeach
                 
          </select>
      </div>

      <!-- Director -->
      <div x-data="{ q: ''}">
          <label class="block text-sm text-gray-300 mb-2">Director</label>
          <input type="text" x-model="q" placeholder="Search directors"
          class="w-full mb-3 rounded-lg bg-gray-800 border border-gray-700 text-white px-3 py-2 focus:ring-blue-500">
          <select name="directors[]" multiple
              class="w-full h-40 rounded-lg bg-gray-800 border-gray-700 text-white focus:ring-blue-500">
              @foreach($directors as $director)
                  <option value="{{ $director->id }}"
                    x-show="q === '' || ('{{ $director->first_name }} {{ $director->last_name }}').toLowerCase().includes(q.toLowerCase())"
                    {{ collect(request('directors'))->contains($director->id) ? 'selected' : '' }}>
                    {{ $director->first_name }} {{ $director->last_name }}
                  </option>
              @endforeach
          </select>
      </div>

      <!-- Buttons -->
      <div class="flex gap-3 pt-4">
          <button type="submit"
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-xl">
              Apply
          </button>

          <a href="{{ route('movies.index') }}"
              class="flex-1 bg-gray-700 hover:bg-gray-600 text-white py-2 rounded-xl text-center">
              Reset
          </a>
      </div>

      </form>

    </aside>
    <div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 mt-3">
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

    <div class="mt-6">
      {{ $movies->links() }}
    </div>
    
    </div>
   
</div>

@endsection