@extends('layouts.app')

@section('content')
{{-- Background Elements --}}
<div class="relative z-10 min-h-screen">
    {{-- Header Section --}}
    <div class="py-12 px-6 lg:px-28">
        <div class="mb-12">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 flex items-center gap-3">
                        <!-- <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg> -->
                        @svg('heroicon-o-list-bullet', 'h-8')
                        Movie Lists
                    </h1>
                    <p class="text-xl text-gray-400">
                        Explore curated collections created by the community
                    </p>
                </div>
                @if(Auth::check())
                    <a href="{{ route('lists.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-black transform hover:scale-105 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create New List
                    </a>
                @endif
            </div>
        </div>

        {{-- Lists Grid --}}
        @if(empty($lists))
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20">
                <div class="w-24 h-24 bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">No Lists Yet</h3>
                <p class="text-gray-400 mb-6 text-center max-w-md">
                    Be the first to create a movie list and share your favorite films with the community!
                </p>
                @if(Auth::check())
                    <a href="{{ route('lists.create') }}" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-black transform hover:scale-105 inline-flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Your First List
                    </a>
                @endif
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($lists as $list)
                    <a href="{{ route('lists.show', $list) }}" class="group">
                        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-6 hover:bg-gray-800/70 hover:border-gray-600 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10 h-full flex flex-col">
                            {{-- List Header --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                
                                <div class="flex items-center gap-2 text-xs text-gray-400 bg-gray-700/50 px-3 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                    {{ $list->movies->count() ?? 0 }} movies
                                </div>
                            </div>

                            {{-- List Title --}}
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-blue-400 transition-colors line-clamp-2">
                                {{ $list->name }}
                            </h3>

                            {{-- List Description --}}
                            <p class="text-gray-400 text-sm mb-4 flex-grow line-clamp-3 leading-relaxed">
                                {{ $list->description ?? 'No description provided' }}
                            </p>

                            {{-- List Footer --}}
                            <div class="flex items-center justify-between pt-4 border-t border-gray-700">
                                <div class="flex items-center gap-2">
                                    <div class="w-10 h-10 overflow-hidden">
                                        <img src="{{ $list->user->image ? asset('storage/' . $list->user->image) : asset('images/person-placeholder.png') }}" alt="" class="h-8 w-8 object-cover rounded-xl">
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-300">{{ $list->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $list->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-400 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </div>
                        </div>
                    </a>
                @endforeach
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

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection