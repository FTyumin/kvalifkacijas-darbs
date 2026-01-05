@extends('layouts.app')

@section('title', 'Movies')

@section('content')
<div class="relative mx-6 lg:mx-16 py-10 mb-10">
    <h1 class="text-3xl font-bold text-white">
        Movies
    </h1>

</div>

  <!-- Movie Grid -->
 <div class="grid grid-cols-1 lg:grid-cols-[320px_1fr] gap-8">
  <aside class="h-full">
      <div x-data="{ open: false }" @keydown.escape.window="open = false" class="relative">

      <div class="flex items-center justify-between mb-6">

      <!-- Mobile filter button -->
      <button @click="open = true"
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
          <div x-show="open" x-transition.opacity @click="open = false"
              class="fixed inset-0 bg-black/60 z-40 lg:hidden"
          ></div>

          <div x-show="open || window.innerWidth >= 1024"
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
                      <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
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

      <!-- Sort -->
      <div>
          <label class="block text-sm text-gray-300 mb-2">Sort By</label>
          <select name="sort"
              class="w-full rounded-lg bg-gray-800 border-gray-700 text-white focus:ring-blue-500">
              <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Newest</option>
              <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A–Z)</option>
              <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>TMDB Rating</option>
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
            <x-movie-card :movie="$movie" />
        @endforeach
    </div>

    <div class="mt-6">
      {{ $movies->links() }}
    </div>
    
    </div>
   
</div>

@endsection
