@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Most Recent Reviews</h1>
        <p class="text-gray-400">Discover what the community thinks about the latest movies</p>
    </div>

    {{-- Reviews List --}}
    <div class="space-y-4">
        @forelse($reviews as $review)
        <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
            <a href="{{ route('reviews.show', $review) }}" class="block">
                <div class="p-6">
                    {{-- Review Header --}}
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-white hover:text-blue-400 transition-colors mb-2">
                                {{ $review->movie->name }}
                            </h2>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('profile.show', $review->user) }}" 
                                   class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white text-sm font-semibold">{{ substr($review->user->name, 0, 1) }}</span>
                                    </div>
                                    <span class="text-gray-300 text-sm font-medium">{{ $review->user->name }}</span>
                                </a>
                                <span class="text-gray-500">•</span>
                                <time class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</time>
                                <span class="text-gray-500">•</span>
                                <span class="text-sm text-gray-400">{{ $review->comments->count() }} {{ Str::plural('comment', $review->comments->count()) }}</span>
                            </div>
                        </div>
                        
                        {{-- Rating Badge --}}
                        <div class="flex flex-col items-center bg-yellow-500/10 border border-yellow-500/30 rounded-lg px-3 py-2 flex-shrink-0">
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-500">
                                    <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-xl font-bold text-yellow-500">{{ $review->rating }}</span>
                            </div>
                            <span class="text-xs text-gray-400">/5</span>
                        </div>
                    </div>

                    {{-- Review Content --}}
                    @if($review->spoilers)
                        {{-- Spoiler Warning --}}
                        <div class="p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg" x-data="{ open: false }">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500 flex-shrink-0">
                                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-yellow-500 font-medium">This review contains spoilers</span>
                                <button 
                                    @click="open = !open"
                                    class="ml-auto px-3 py-1 bg-yellow-500 text-gray-900 text-xs font-medium rounded hover:bg-yellow-400 transition-colors">
                                    Show
                                </button>
                            </div>
                            
                            {{-- Hidden Content --}}
                            <div x-show="open" x-transition
                                class="mt-3 pt-3 border-t border-yellow-500/20">
                                <p class="text-gray-300 leading-relaxed">
                                    {{ $review->description }}
                                </p>
                            </div>
                        </div>
                    @else
                        {{-- Regular Review Preview --}}
                        <p class="text-gray-300 leading-relaxed line-clamp-3">
                            {{ $review->description }}
                        </p>
                    @endif
                </div>

                {{-- Card Footer --}}
                <div class="bg-gray-900/50 px-6 py-3 border-t border-gray-700 flex items-center justify-between">
                    <span class="text-sm text-gray-400">Click to read full review</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </div>
            </a>
        </article>
        @empty
        {{-- Empty State --}}
        <div class="bg-gray-800 rounded-xl shadow-lg p-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-600 mx-auto mb-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
            </svg>
            <h3 class="text-xl font-semibold text-white mb-2">No Reviews Yet</h3>
            <p class="text-gray-400">Be the first to share your thoughts on a movie!</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    
</div>
@endsection