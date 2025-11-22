@extends('layouts.app')

@section('content')

<div class="py-4 px-28 flex flex-col gap-3">
    <h1 class="text-3xl">Most recent reviews</span></h1>
    @foreach($reviews as $review)
      
        <div class="flex flex-col bg-white block w-full px-4 py-2 border border-gray-300 rounded-lg">
            <div class="p-4 md:p-5">
                <h1 class="text-lg font-bold text-gray-800">
                        {{ $review->movie->name }}
                </h1>
                <a href="{{ route('profile.show', $review->user) }}">
                    <h3 class="text-sm font-bold text-gray-800">
                        {{ $review->user->name }}
                    </h3>      
                </a>

                @if($review->spoilers)
                <!-- Spoiler Warning and Button -->
                <div class="spoiler-warning-{{ $review->id }} mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800 font-medium">⚠️ This review contains spoilers</p>
                    <button 
                        onclick="toggleSpoiler({{ $review->id }})" 
                        class="mt-2 px-4 py-2 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors"
                    >
                        Show Review
                    </button>
                </div>
                
                <!-- Hidden Spoiler Content -->
                <div class="spoiler-content-{{ $review->id }} hidden mt-2">
                    <p class="text-gray-500">
                        {{ $review->description }}
                    </p>
                    <p class="mt-2">
                        Rating: {{ $review->rating }}
                    </p>
                    <button 
                        onclick="toggleSpoiler({{ $review->id }})" 
                        class="mt-2 px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors"
                    >
                        Hide Review
                    </button>
                </div>
            </div>
            <div class="bg-gray-100 border-t border-gray-200 rounded-b-xl py-3 px-4 md:py-4 md:px-5">
                <p class="mt-1 text-sm text-gray-500">
                    {{ $review->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
        @else
        <div class="flex flex-col bg-white block w-full rounded-lg">
            <div class="p-4 md:p-5">
                <p class="mt-2 text-gray-500">
                    {{ $review->description }}
                </p>
                <p>
                    Rating: {{ $review->rating }}
                </p>
            </div>
            <div class="bg-gray-100 border-t border-gray-200 rounded-b-xl py-3 px-4 md:py-4 md:px-5">
                <p class="mt-1 text-sm text-gray-500">
                    {{ $review->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    @endif
    @endforeach
</div>

@endsection