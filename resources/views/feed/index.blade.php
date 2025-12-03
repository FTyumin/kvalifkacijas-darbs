@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-8 min-h-screen">
        <h1 class="text-2xl text-white font-bold mb-6">Your Feed</h1>
        
        <div class="space-y-6">
            @foreach($activities as $post)
                <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <a href="" class="block">
                        <div class="flex gap-4 p-6">
                            {{-- Movie Poster --}}
                            <div class="flex-shrink-0">
                              
                            </div>

                            {{-- Card Content --}}
                            @if($post->activityable_type == 'App\Models\Review')
                                <div class="flex-1 min-w-0">
                                    {{-- Card Header --}}
                                    <div class="flex items-start justify-between gap-4 mb-4">
                                        <div class="flex-1">
                                            <h2 class="text-xl font-bold text-white hover:text-blue-400 transition-colors mb-2">
                                                {{ $post->review->title }}
                                            </h2>
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <a href="" 
                                                onclick="event.stopPropagation()"
                                                class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                        <!-- <span class="text-white text-sm font-semibold">{{ substr($post->user->name, 0, 1) }}</span> -->
                                                        <img src="{{ $post->user->image ? asset('storage/' . $post->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">
                                                    </div>
                                                    <span class="text-gray-300 text-sm font-medium">{{ $post->user->name }}</span>
                                                </a>
                                                <span class="text-gray-500">•</span>
                                                <time class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</time>
                                                <span class="text-gray-500">•</span>
                                                <span class="text-sm text-gray-400">COMMENT(LIKE) COUNT</span>
                                            </div>
                                        </div>
                                        
                                        {{-- Rating Badge --}}
                                        <div class="flex items-center gap-1 mb-2">
                                            RATING GOES HERE
                                        </div>
                                    </div>

                                    {{-- Review Content --}}
                                    @if($post->activityable_type == 'App\Models\Review')
                        
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Card Footer --}}
                        @if($post->activityable_type == 'App\Models\Review')
                            <div class="bg-gray-900/50 px-6 py-3 border-t border-gray-700 flex items-center justify-between">
                                <span class="text-sm text-gray-400">Click to read full review</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                    
                        @endif

                    </a>
                </article>
            @endforeach
        </div>
        
        
    </div>
@endsection