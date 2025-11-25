<header class="sticky top-0 w-full py-4 px-6 z-50 bg-black/95 backdrop-blur-md border-b border-gray-800">
  <nav class="relative w-full flex justify-between items-center px-0 sm:px-4 lg:px-28 gap-6 h-12">
    
    <!-- Left Section: Logo + Mobile Menu -->
    <div class="flex items-center gap-6 h-full">
      <!-- Logo -->
      <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
        <!-- <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
         
        </div> -->
        <span class="text-xl font-bold text-white tracking-tight">Movie Platform</span>
      </a>

      <!-- Desktop Navigation -->
      <div class="hidden lg:flex items-center gap-8">
        <a href="/" class="text-gray-300 hover:text-white transition-colors font-medium">Movies</a>
      </div>

      <div class="hidden lg:flex items-center gap-8">
        <a href="{{ route('reviews') }}" class="text-gray-300 hover:text-white transition-colors font-medium">Reviews</a>
      </div>

      <div class="hidden lg:flex items-center gap-8">
        <a href="/lists" class="text-gray-300 hover:text-white transition-colors font-medium">Lists</a>
      </div>

      </div>
    </div>

    <!-- Center: Search Bar -->
    <div class="flex-1 max-w-2xl mx-6">

      <form class="relative" method="GET" action="{{ route('movies.search') }}">
        @csrf
        <label for="search" class="sr-only">Search movies, directors and actors</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
          </div>
          
          <!-- Search Input -->
          <input 
            type="search" 
            id="search" 
            name="search"
            class="block w-full pl-12 pr-24 py-3 text-sm text-white placeholder-gray-400 bg-gray-800/50 border border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all" 
            placeholder="Search movies, TV shows, actors..." 
            autocomplete="off"
          />
          
          <!-- Search Button -->
          <button 
            type="submit" 
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800"
          >
            Search
          </button>
        </div>
      </form>
    </div>

    <!-- Right Section: Actions -->
    <div class="flex items-center gap-4 h-full">
      <!-- Watchlist -->
     

      @auth
      <!-- <a href="/dashboard" class="hidden sm:flex items-center gap-2 text-gray-300 hover:text-white transition-colors font-medium">
        <span class="hidden  md:inline">Dashboard</span>
      </a> -->

      @endauth
      
      <!-- User Menu / Sign In -->
      <div class="flex items-center gap-3 h-full">
        
        <!-- Sign In Button -->
        @guest
        <a href="{{ url('/login') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
          </svg>
          Sign In
        </a>
        @endguest

        <!-- @auth
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-4 py-2 transition-colors focus:outline-none
             focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 flex ">
              <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/>
              </svg>
              {{ __('Log Out') }}
            </button>
          </form>
        @endauth -->

        <div class="w-10 h-10 rounded-full overflow-hidden">
          <a href="/dashboard">
            @auth
              @if(auth()->user()->image)
                <img src="{{ asset('storage/' . auth()->user()->image) }}"
                    alt="{{ auth()->user()->name}}"
                    class="w-full h-full object-cover">
                    
              @else 
                <img src="{{ asset('images/person-placeholder.png') }}" 
                    alt="placeholder img"
                    class="w-full h-full object-cover">
                    
              @endif
            @endauth

          </a>
        </div>
      </div>

      <!-- Mobile Menu Button -->
      <button class="lg:hidden p-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition-colors" type="button" aria-label="Toggle menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>
  </nav>

  <!-- Mobile Navigation Menu (Hidden by default) -->
  <div class="lg:hidden mt-4 pt-4 border-t border-gray-800 hidden">
    <div class="flex flex-col gap-4 px-4">
      <a href="/" class="text-gray-300 hover:text-white transition-colors font-medium py-2">Movies</a>
      <a href="/trending" class="text-gray-300 hover:text-white transition-colors font-medium py-2">Trending</a>
      <a href="/watchlist" class="flex items-center gap-2 text-gray-300 hover:text-white transition-colors font-medium py-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
        </svg>
        Watchlist
      </a>
    </div>
  </div>
</header>