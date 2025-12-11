@extends('layouts.app')

@section('content')
{{-- Background Elements --}}
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-600/20 to-purple-600/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-purple-600/20 to-pink-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 w-96 h-96 bg-gradient-to-br from-pink-600/10 to-blue-600/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
</div>

<div class="relative z-10 min-h-screen">
    {{-- Back Button --}}
    <div class="py-6 px-6 lg:px-28">
        <a href="{{ route('lists.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Lists
        </a>
    </div>

    {{-- List Header --}}
    <div class="px-6 lg:px-28 pb-12">
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-8 md:p-10 mb-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                <div class="flex-1">
                    {{-- List Title --}}
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                                {{ $list->name }}
                            </h1>
                            <div class="flex items-center gap-4 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <!-- <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center text-xs font-bold text-white">
                                        {{ substr($list->user->name, 0, 2) }}
                                    </div> -->
                                    <img src="{{ $list->user->image ? asset('storage/' . $list->user->image) : asset('images/person-placeholder.png') }}" alt="" class="h-8 w-8 object-cover rounded-xl">
                                    <span class="text-gray-300 text-sm">by <span class="font-medium">{{ $list->user->name }}</span></span>
                                </div>
                                <span class="text-gray-500 text-sm">•</span>
                                <span class="text-gray-400 text-sm">{{ $list->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- List Description --}}
                    @if($list->description)
                    <p class="text-gray-300 leading-relaxed mb-6">
                        {{ $list->description }}
                    </p>
                    @endif

                    {{-- List Stats --}}
                    <div class="flex items-center gap-6 flex-wrap">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-blue-600/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-white">{{ $list->movies->count() }}</p>
                                <p class="text-xs text-gray-400">Movies</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-green-600/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-white">{{ number_format($list->movies->avg('rating') ?? 0, 1) }}</p>
                                <p class="text-xs text-gray-400">Avg Rating</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col gap-3">
                    @auth
                        @if(Auth::id() === $list->user_id)
                       
                        @else
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                            Save List
                        </button>
                        @endif
                    @endauth

                </div>
            </div>
        </div>

        {{-- Movies Grid --}}
        @if($list->movies->count() > 0)
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                </svg>
                Movies in this List
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach($list->movies as $movie)
                <div class="group relative">
                    <a href="{{ route('movies.show', $movie) }}" class="block">
                        <div class="aspect-[2/3] bg-gray-700/50 rounded-lg overflow-hidden border border-gray-600/50 hover:border-gray-500 transition-all group-hover:shadow-lg group-hover:shadow-blue-500/20">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                 src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}"  
                                 alt="{{ $movie->title }}" 
                                 loading="lazy" />
                        </div>

                        {{-- Movie Info Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-lg flex flex-col justify-end p-3">
                            <h3 class="text-white font-semibold text-sm mb-1 line-clamp-2">
                                {{ $movie->name }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-white text-xs font-medium">{{ number_format($movie->rating ?? 0, 1) }}</span>
                                </div>
                                <span class="text-gray-400 text-xs">{{ $movie->year ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </a>

                    {{-- Remove Button (only for list owner) --}}
                    @auth
                       @if(Auth::id() === $list->user_id)
                        <form action="{{ route('lists.remove', $movie->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="list_id" value="{{ $list->id }}">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full transition-colors" onclick="return confirm('Remove this movie from the list?');">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                        @endif

                        @endauth
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                </div>
                @endforeach
            </div>
        </div>
        @else
        {{-- Empty State --}}
        <div class="flex flex-col items-center justify-center py-20">
            <div class="w-24 h-24 bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl flex items-center justify-center mb-6">
                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">No Movies Yet</h3>
            <p class="text-gray-400 mb-6 text-center max-w-md">
                This list is empty. Start adding your favorite movies to build your collection!
            </p>
            @auth
                @if(Auth::id() === $list->user_id)
                <a href="{{ route('movies.index') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Movies
                </a>
                @endif
            @endauth
        </div>
        @endif
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection