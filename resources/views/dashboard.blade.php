@extends('layouts.app')

@section('title', 'dashboard')

@section('content')

<section>
   
<div class="min-h-screen bg-black text-white">
    <main class="relative z-10">
        <div class="py-12 px-6 lg:px-28">
            <div class="mb-8">
                <h1 class="text-4xl font-bold mb-2">
                    Welcome back, <span class="text-blue-400">{{ $user->name }}</span>!
                </h1>

                <!-- Followers / Following -->
                <p class="text-lg text-gray-300 mb-2 flex items-center gap-4">
                    <span class="flex items-center gap-1">
                        <x-heroicon-o-user-group class="w-5 h-5 text-gray-400" />
                        {{ count($user->followers) }} Followers
                    </span>

                    <span class="flex items-center gap-1">
                        <x-heroicon-o-user-plus class="w-5 h-5 text-gray-400" />
                        {{ count($user->followees) }} Following
                    </span>
                </p>

                <p class="text-xl text-gray-400">
                    Here's what's happening with your movie collection
                </p>
            </div>


            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Watchlist Count -->
                <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Watchlist</p>
                            <p class="text-3xl font-bold text-blue-400">{{ count(auth()->user()->wantToWatch) }} </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Watched Movies -->
                <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Watched</p>
                            <p class="text-3xl font-bold text-green-400">{{ count(auth()->user()->seenMovies) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Reviews Written -->
                <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Reviews</p>
                            <p class="text-3xl font-bold text-purple-400">{{ count($reviews) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Average Rating Given -->
                <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Average Rating</p>
                            <p class="text-3xl font-bold text-yellow-400">{{ $average_review }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="px-6 lg:px-28 pb-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Watchlist -->
                <div class="lg:col-span-2">
                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                </svg>
                                My Watchlist
                            </h2>
                            
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($watchList as $movie)
                                <div class="group relative">
                                <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden relative">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                 src="https://image.tmdb.org/t/p/w500/{{ $movie->movie->poster_url }}"  
                                 alt="Movie poster" />
                            
                            <!-- Watched Badge -->
                            <div class="absolute top-2 right-2 bg-green-600 rounded-full p-1">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h3 class="mt-2 text-sm font-medium text-white line-clamp-2">
                            {{ $movie->movie->name }}
                        </h3>
                        <p class="text-xs text-gray-400">Added {{$movie->created_at->diffForHumans()}}</p>
                        
                        </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column: Recent Reviews -->
                <div>
                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-8 mb-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                                Recent Reviews
                            </h2>
                            <a href="/my-reviews" class="text-purple-400 hover:text-purple-300 text-sm font-medium transition-colors">
                                View All →
                            </a>
                        </div>

                        <div class="space-y-4">
                            @foreach($reviews as $review)
                            <a href="{{ route('reviews.show', $review) }}">
                                <div class="border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <img class="w-12 h-16 object-cover rounded" 
                                            src="https://image.tmdb.org/t/p/w500/{{ $review->movie->poster_url }}"
                                            alt="Movie poster" />
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-white mb-1">
                                                {{ $review->movie->name }}
                                            </h4>
                                            
                                            <!-- Star Rating -->
                                            <div class="flex items-center gap-1 mb-2">
                                                @for ($j = 0; $j < $review->rating; $j++)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                @endfor

                                                @for($j = $review->rating; $j < 5; $j++)
                                                    <svg class="w-4 h-4" fill="gray" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                @endfor
                                                <span class="text-sm text-gray-400 ml-1">{{ $review->rating }}</span>
                                            </div>
                                            
                                            <p class="text-sm text-gray-300 line-clamp-2">
                                            </p>
                                            
                                            <p class="text-xs text-gray-500 mt-2"> days ago</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <div class="w-8 h-8 bg-purple-600/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium">Edit profile</span>
                            </a>
                            
                            <a href="/suggestion" class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium">Send movie suggestion</span>
                            </a>

                            <a href="/quiz" class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors">
                                <div class="w-8 h-8 bg-blue-600/20 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium">Select favorite genres</span>
                            </a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="w-full"> 
                                @csrf                               
                                <button type="submit" class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors w-full">
                                    <div class="w-8 h-8 bg-green-600/20 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                        </svg>

                                    </div>   
                                    <span class="text-sm font-medium">{{ __('Log Out') }}</span>     
                                </button>     
                                  
                            </form>

                        </div>
                    </div>

                        <!-- Notifications -->
                    @if(auth()->user()->notifications->isNotEmpty())
                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-6 mt-4">
                        <h3 class="text-lg font-bold text-white mb-4">Notifications</h3>
                        <div class="space-y-3">
                            @foreach(auth()->user()->notifications as $notification)
                                <div class="p-4 bg-gray-800 rounded">
                                    <p class="text-white">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <span class="text-sm text-gray-400">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            @endforeach
                            
                        </div>
                    </div>
                    @endif

                    <!-- Admin actions -->
                    @if(auth()->user()->is_admin)
                        <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-6 mt-4">
                        <h3 class="text-lg font-bold text-white mb-4">Admin Actions</h3>
                            <div class="space-y-3">
                                <a href="/admin" class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 bg-purple-600/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium">Admin dashboard</span>
                                </a>

                                <a href="/movies/add" class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 bg-purple-600/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium">Add movie</span>
                                </a>

                                <a href="" class="flex items-center gap-3 p-3 bg-gray-700/50 rounded-lg hover:bg-gray-700 transition-colors">
                                    <div class="w-8 h-8 bg-purple-600/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium">Load movies from TMDB API</span>
                                </a>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Recently Watched Movies -->
        <div class="px-6 lg:px-28 pb-12">
            <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Recently Watched
                    </h2>

                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-8">
                    @forelse($seen as $movie)
                    <a href="{{ route('movies.show', $movie->movie->slug) }}">
                        <div class="group relative">
                            <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden relative">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                    src="https://image.tmdb.org/t/p/w500/{{ $movie->movie->poster_url }}" 
                                    alt="Movie poster" />
                                
                                <!-- Watched Badge -->
                                <div class="absolute top-2 right-2 bg-green-600 rounded-full p-1">
                                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <h3 class="mt-2 text-sm font-medium line-clamp-2">
                                {{ $movie->movie->name }}
                            </h3>
                            <p class="text-xs text-gray-400">Watched {{ $movie->created_at->diffForHumans() }}</p>
                            
                        </div>
                    </a>
                    @empty
                    
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
</section>

@endsection
