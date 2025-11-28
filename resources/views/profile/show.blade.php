@extends('layouts.app')

@section('title', 'dashboard')

@section('content')

<section>
   
<div class="min-h-screen bg-black text-white">

    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-600 rounded-full mix-blend-multiply filter blur-xl opacity-10 floating-element"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-600 rounded-full mix-blend-multiply filter blur-xl opacity-10 floating-element"></div>
        <div class="absolute top-1/3 left-1/3 w-60 h-60 bg-pink-600 rounded-full mix-blend-multiply filter blur-xl opacity-5 floating-element"></div>
    </div>

    <main class="relative z-10">
        <div class="py-12 px-6 lg:px-28">
            <div class="mb-8">
                <h1 class="text-4xl font-bold mb-4">
                     <span class="text-blue-400">{{ $user->name }} Profile</span>
                </h1>
                <div x-data="{ 
                    following: {{ auth()->check() && auth()->user()->followees()->where('followee_id', $user->id)->exists() ? 'true' : 'false' }}, 
                    loading: false,
                    async toggle() {
                        this.loading = true;
                        const method = this.following ? 'DELETE' : 'POST';
                        const endpoint = `/api/users/{{ $user->id }}/${this.following ? 'unfollow' : 'follow'}`;
                        
                        try {
                            const res = await fetch(endpoint, {
                                method: method,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await res.json();
                            if (res.ok) this.following = data.following;
                            } catch (e) {
                                console.error(e);
                            }
                            this.loading = false;
                        }
                    }">
                    <button 
                        @click="toggle()"
                        :disabled="loading"
                        class="px-4 py-2 rounded"
                        :class="following ? 'bg-blue-500 text-white' : 'bg-blue-500 text-white'"
                        x-text="loading ? 'Loading...' : (following ? 'Unfollow' : 'Follow')">
                    </button>
                </div>
                <p class="text-xl text-gray-400">
                    Check out his/her favorite movies!
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <!-- Watchlist Count -->
                <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Watchlist</p>
                            <p class="text-3xl font-bold text-blue-400">{{ count(auth()->user()->wantToWatch) }}</p>
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
                            <p class="text-sm font-medium text-gray-400">Avg Rating</p>
                            <p class="text-3xl font-bold text-yellow-400">{{ $average_review }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Followers</p>
                            <p class="text-3xl font-bold text-blue-400">{{ count(auth()->user()->followers) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-400">Following</p>
                            <p class="text-3xl font-bold text-blue-400">{{ count(auth()->user()->followees) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
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
                                 Watchlist
                            </h2>
                            <a href="/watchlist" class="text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors">
                                View All →
                            </a>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @if(!empty($movies))
                            @foreach($movies as $movie)
                            <div class="group relative">
                                <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden">
                                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                         src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}" 
                                         alt="Movie poster" />
                                </div>
                                
                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm mb-2 transition-colors">
                                            <a href="{{ route('movies.show', $movie->id) }}">View</a>
                                        </button>
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm block w-full transition-colors">
                                            <form action="{{ route('favorite.remove',$movie->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ trans('Are you sure ? ') }}');"
                                                    style="">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" value="Delete">
                                                </form>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Movie Title -->
                                <h3 class="mt-2 text-sm font-medium text-white line-clamp-2">
                                    {{ $movie->name }}
                                </h3>
                                <p class="text-xs text-gray-400">{{ $movie->director->name }}</p>
                            </div>
                            @endforeach
                            @endif
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
                            <a href="" class="text-purple-400 hover:text-purple-300 text-sm font-medium transition-colors">
                                View All →
                            </a>
                        </div>

                        <div class="space-y-4">
                            @if($reviews)
                                @foreach($reviews as $review)
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
                                                @for ($j = 0; $j < 5; $j++)
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                                @endfor
                                                <span class="text-sm text-gray-400 ml-1">{{ $review->rating }}</span>
                                            </div>
                                            
                                            <p class="text-sm text-gray-300 line-clamp-2">
                                            </p>
                                            
                                            <time class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

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
                    <a href="/watched" class="text-green-400 hover:text-green-300 text-sm font-medium transition-colors">
                        View All →
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @for ($i = 0; $i < 6; $i++)
                    <div class="group relative">
                        <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden relative">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                 src="{{ asset('images/cinema.webp') }}" 
                                 alt="Movie poster" />
                            
                            <!-- Watched Badge -->
                            <div class="absolute top-2 right-2 bg-green-600 rounded-full p-1">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <h3 class="mt-2 text-sm font-medium text-white line-clamp-2">
                            {{ $i % 4 == 0 ? 'The Matrix Resurrections' : ($i % 4 == 1 ? 'Spider-Man: No Way Home' : ($i % 4 == 2 ? 'Top Gun: Maverick' : 'Everything Everywhere All at Once')) }}
                        </h3>
                        <p class="text-xs text-gray-400">Watched {{ $i + 1 }} {{ $i == 0 ? 'day' : 'days' }} ago</p>
                        
                        <!-- Your Rating -->
                        <div class="flex items-center gap-1 mt-1">
                            @for ($j = 0; $j < 5; $j++)
                            <svg class="w-3 h-3 {{ $j < (4 - $i%3) ? 'text-yellow-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </main>
</div>
</section>

@endsection
