@extends('layouts.app')

@section('title', 'Movies')

@section('content')
<div class="relative min-h-[55vh] md:min-h-[45vh] lg:min-h-[40vh] mx-16 py-12">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl text-yellow-600">Movies</h1>
        <button id="filterToggle" class="lg:hidden px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filters
        </button>
    </div>

    <!-- Filters Section -->
    <div id="filtersSection" class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hidden lg:block">
        <form method="GET" action="{{ route('movies.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Genre Filter -->
                <div class="grid grid-cols-2 gap-2">
                  @foreach($genres as $genre)
                      <label class="flex items-center gap-2 text-white text-sm">
                          <input 
                              type="checkbox"
                              name="genres[]"
                              value="{{ $genre->id }}"
                              {{ in_array($genre->id, request('genres', [])) ? 'checked' : '' }}
                          >
                          {{ $genre->name }}
                      </label>
                  @endforeach
                </div>


                <!-- Rating Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Rating</label>
                    <select name="min_rating" class="w-full px-3 py-2 border border-gray-300 text-white dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                        <option value="">Any Rating</option>
                        @foreach([9, 8, 7, 6, 5] as $rating)
                            <option value="{{ $rating }}" {{ request('min_rating') == $rating ? 'selected' : '' }}>
                                {{ $rating }}+ ⭐
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Year Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                    <select name="year" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 text-white rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                    <select name="sort" class="w-full px-3 py-2 border border-gray-300 text-white dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700">
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                        <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Year</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Title</option>
                    </select>
                </div>

                <!-- Actor Filter -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">
                      Director
                  </label>
                  <select 
                      name="directors[]" 
                      multiple
                      class="w-full px-3 py-2 border border-gray-300 text-white rounded-lg focus:ring-2 focus:ring-blue-500"
                  >
                      @foreach($directors as $director)
                          <option 
                              value="{{ $director->id }}" 
                              class="text-white bg-gray-800"
                              {{ collect(request('directors'))->contains($director->id) ? 'selected' : '' }}
                          >
                              {{ $director->first_name }} {{ $director->last_name }}
                          </option>
                      @endforeach
                  </select>
              </div>

            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Apply Filters
                </button>
                <a href="{{ route('movies.index') }}" class="px-6 py-2 bg-gray-200 rounded-lg hover:bg-gray-300  transition-colors font-medium">
                    Clear All
                </a>
            </div>
        </form>
    </div>

    @if(request('genres') || request('min_rating') || request('year') || request('directors'))
        <div class="mb-4 flex flex-wrap gap-2">
            <span class="text-sm text-white ">Active filters:</span>
           @foreach($genres->whereIn('id', request('genres', [])) as $genre)
              <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-900 text-blue-200 rounded-full text-sm">
                  {{ $genre->name }}
                  <a href="{{ route('movies.index', request()->except('genres')) }}">×</a>
              </span>
          @endforeach
            @if(request('min_rating'))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                    Rating {{ request('min_rating') }}+
                    <a href="{{ route('movies.index', array_filter(request()->except('min_rating'))) }}" class="hover:text-blue-600">×</a>
                </span>
            @endif
            @if(request('year'))
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                    Year: {{ request('year') }}
                    <a href="{{ route('movies.index', array_filter(request()->except('year'))) }}" class="hover:text-blue-600">×</a>
                </span>
            @endif
            @if(request('directors'))
              @foreach($directors->whereIn('id', request('directors')) as $director)
                  <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                      Director: {{ $director->first_name }} {{ $director->last_name }}
                      <a href="{{ route('movies.index', array_merge(request()->except('directors'), ['directors' => collect(request('directors'))->reject(fn($id) => $id == $director->id)->toArray()])) }}" 
                        class="hover:text-blue-600">×</a>
                  </span>
              @endforeach
            @endif
        </div>
    @endif
  


  <!-- Movie Grid -->
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10 mt-3">
  
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
          {{ $movie->tmdb_rating }}
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

    {{ $movies->links() }}
</div>
<!-- Mobile Filter Toggle Script -->
<script>
  document.getElementById('filterToggle')?.addEventListener('click', function() {
      const filters = document.getElementById('filtersSection');
      filters.classList.toggle('hidden');
  });
</script>
@endsection