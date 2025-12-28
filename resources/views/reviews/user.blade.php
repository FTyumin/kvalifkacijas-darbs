@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">{{$user->name}} reviews</h1>
        <p class="text-gray-400"></p>
    </div>

    {{-- Reviews List --}}
    <div class="space-y-4">

        @forelse($reviews as $review)
            <livewire:reviewComponent :review="$review" />

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