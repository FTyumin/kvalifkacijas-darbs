<header class="sticky top-0 w-full py-4 px-4 sm:px-6 z-50 bg-black/95 backdrop-blur-md border-b border-gray-800">
  <nav class="relative w-full flex justify-between items-center gap-4">
    
    <!-- Left Section: Logo + Desktop Navigation -->
    <div class="flex items-center gap-6">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
        <span class="text-lg sm:text-xl font-bold text-white tracking-tight">Movie Platform</span>
      </a>

      <!-- Desktop Navigation -->
      <div class="hidden lg:flex items-center gap-6">
        <a href="{{ route('movies.index') }}" class="text-gray-300 hover:text-white transition-colors font-medium text-md">Movies</a>
        <a href="/reviews" class="text-gray-300 hover:text-white transition-colors font-medium text-md">Reviews</a>
        <a href="/lists" class="text-gray-300 hover:text-white transition-colors font-medium text-md">Lists</a>
        <a href="/feed" class="text-gray-300 hover:text-white transition-colors font-medium text-md">Feed</a>
      </div>
    </div>

    <!-- Center: Search Bar (Desktop Only) -->
    <div class="hidden md:flex flex-1 max-w-2xl mx-6">
      <form class="relative w-full" method="GET" action="{{ route('movies.search') }}">
        @csrf
        <label for="search" class="sr-only">Search movies, directors and actors</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          
          <input 
            type="search" 
            id="search" 
            name="search"
            class="block w-full pl-12 pr-24 py-3 text-sm text-white placeholder-gray-400 bg-gray-800/50 border border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
            placeholder="Search movies, TV shows, actors..." 
            autocomplete="off"
          />
          
          <button 
            type="submit" 
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Search
          </button>
        </div>
      </form>
    </div>

    <!-- Right Section: Actions -->
    <div class="flex items-center gap-3">
      
      <!-- Mobile Search Button -->
      <button 
        onclick="document.getElementById('mobile-search').classList.toggle('hidden')" 
        class="md:hidden p-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
        type="button" 
        aria-label="Toggle search"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </button>

      <!-- Sign In Button (Desktop) / User Profile -->
      @guest
        <a href="{{ url('/login') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          Sign In
        </a>
        
        <!-- Mobile Sign In Icon -->
        <a href="{{ url('/login') }}" class="sm:hidden p-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors" aria-label="Sign in">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
        </a>
      @endguest

      @auth
        <a href="{{ route('profile.show', auth()->user()->id) }}" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden ring-2 ring-gray-700 hover:ring-blue-500 transition-all">
          @if(auth()->user()->image)
            <img src="{{ asset('storage/' . auth()->user()->image) }}"
                alt="{{ auth()->user()->name}}"
                class="w-full h-full object-cover">
          @else 
            <img src="{{ asset('images/person-placeholder.png') }}" 
                alt="placeholder img"
                class="w-full h-full object-cover">
          @endif
        </a>
      @endauth

      <!-- Mobile Menu Button -->
      <button 
        onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" 
        class="lg:hidden p-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors" 
        type="button" 
        aria-label="Toggle menu"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </nav>

  <!-- Mobile Search Bar -->
  <div id="mobile-search" class="hidden md:hidden mt-4 pt-4 border-t border-gray-800">
    <form class="relative" method="GET" action="{{ route('movies.search') }}">
      @csrf
      <label for="mobile-search-input" class="sr-only">Search movies</label>
      <div class="relative">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
          <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
        </div>
        
        <input 
          type="search" 
          id="mobile-search-input" 
          name="search"
          class="block w-full pl-12 pr-20 py-3 text-sm text-white placeholder-gray-400 bg-gray-800/50 border border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
          placeholder="Search movies..." 
          autocomplete="off"
        />
        
        <button 
          type="submit" 
          class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-3 py-2 transition-colors"
        >
          Search
        </button>
      </div>
    </form>
  </div>

  <!-- Mobile Navigation Menu -->
  <div id="mobile-menu" class="lg:hidden hidden mt-4 pt-4 border-t border-gray-800">
    <div class="flex flex-col gap-3">
      <a href="{{ route('movies.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
        </svg>
        <span class="font-medium">Movies</span>
      </a>
      
      <a href="/reviews" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
        </svg>
        <span class="font-medium">Reviews</span>
      </a>
      
      <a href="/lists" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        <span class="font-medium">Lists</span>
      </a>
      
      <a href="/feed" class="flex items-center gap-3 px-4 py-3 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <span class="font-medium">Feed</span>
      </a>

      @guest
      <a href="{{ url('/login') }}" class="flex items-center gap-3 px-4 py-3 mt-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
        </svg>
        <span class="font-medium">Sign In</span>
      </a>
      @endguest
    </div>
  </div>
</header>