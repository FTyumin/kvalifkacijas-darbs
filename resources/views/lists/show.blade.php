@extends('layouts.app')

@section('content')

<div class="relative z-10 min-h-screen">
    {{-- Back Button --}}
    <div class="py-6 px-6 lg:px-28">
        <a href="{{ route('lists.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition-colors group">
            <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Lists
        </a>

    {{-- List Header --}}
    <div class="flex items-start gap-4 mb-4">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-start gap-3 flex-wrap">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                    {{ $list->name }}
                </h1>
                
                @auth
                    @if(Auth::id() === $list->user_id)
                        {{-- Inline edit button --}}
                        <a href="{{ route('lists.edit', $list) }}"
                        class="mt-1 inline-flex items-center gap-2 rounded-lg border border-white/10
                                bg-white/5 px-3 py-1.5 text-sm text-gray-200
                                hover:bg-white/10 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5h2m-1 0v14m7-7H5"/>
                            </svg>
                            Edit
                        </a>

                        {{-- Delete button --}}
                        <x-confirm-modal
                            title="Delete list?"
                            message="This will permanently delete the list and remove all movies from it. This action cannot be undone."
                            :action="route('lists.destroy', $list)"
                            method="DELETE"
                        >
                            <x-slot name="trigger">
                                <button
                                    class="mt-1 inline-flex items-center gap-2 rounded-lg
                                        bg-red-600/10 border border-red-500/30
                                        px-3 py-1.5 text-sm text-red-300
                                        hover:bg-red-600/20 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0l1-2h6l1 2"/>
                                    </svg>
                                    Delete
                                </button>
                            </x-slot>
                        </x-confirm-modal>
                    @endif
                @endauth
            </div>

            @if($list->description)
                <p class="mt-2 text-gray-300 leading-relaxed">
                    {{ $list->description }}
                </p>
            @endif

            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <img src="{{ $list->user->image ? asset('storage/' . $list->user->image) : asset('images/person-placeholder.png') }}"
                        alt=""
                        class="h-8 w-8 object-cover rounded-xl">
                    <span class="text-gray-300 text-sm">
                        by <span class="font-medium">{{ $list->user->name }}</span>
                    </span>
                </div>
                <span class="text-gray-500 text-sm">•</span>
                <span class="text-gray-400 text-sm">{{ $list->created_at->format('M d, Y') }}</span>
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
                    <a href="{{ route('movies.show', $movie->slug) }}" class="block">
                        <div class="aspect-[2/3] bg-gray-700/50 rounded-lg overflow-hidden border border-gray-600/50 hover:border-gray-500 transition-all group-hover:shadow-lg group-hover:shadow-blue-500/20">
                            <img class="w-full h-full object-cover  transition-transform duration-300" 
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
                                    <span class="text-white text-xs font-medium">{{ number_format($movie->tmdb_rating ?? 0, 1) }}</span>
                                </div>
                                <span class="text-gray-400 text-xs">{{ $movie->year ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </a>

                    {{-- Remove Button (only for list owner) --}}
                    @auth
                       @if(Auth::id() === $list->user_id)
                        <form  class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="list_id" value="{{ $list->id }}">
                            
                            
                        </form>
                        <div 
                           class="absolute mt-6 top-6 right-2 opacity-0 group-hover:opacity-100 transition-opacity"
                        >
                            <x-confirm-modal
                                title="Remove movie?"
                                message="This movie will be removed from your list. This action cannot be undone."
                                :action="route('lists.remove', [$list->id, $movie->id])"
                                method="DELETE"
                            >
                                <x-slot name="trigger">
                                    <button
                                        class="bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full transition"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </x-slot>
                            </x-confirm-modal>
                        </div>
                        
                        @endif

                        @endauth
                       
                </div>
                @endforeach

                 @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
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
@endsection