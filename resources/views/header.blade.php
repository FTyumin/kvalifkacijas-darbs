<header class="flex flex-wrap lg:justify-start lg:flex-nowrap w-full py-5 px-8 z-50 relative border-b border-gray-200">
 <nav class="relative w-full flex justify-between items-center px-4 sm:px-8 lg:px-28 gap-6">    
  
  <div>
    <span>LOGO</span>
    <button>Menu</button>
  </div>

  <div class="flex-1 max-w-2xl">
    <form class="">   
      @csrf
        <label for="default-search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="search" id="default-search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search Movies, Tv Shows, Actors" required />
            <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
        </div>
    </form>
  </div>

  <div class="flex gap-6">
   <div>Movies</div>
   <div>Tv shows</div>
   <div> FYP</div>
   <div>Sign in</div>
  </div>

 </nav>
</header>