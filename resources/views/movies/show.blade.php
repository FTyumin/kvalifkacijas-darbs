@extends('layouts.app')

@section('title', $movie->name)

@section('content')

<div class="max-w-6xl mx-auto mt-8 px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Poster --}}
        <div class="md:col-span-1">
            <img src="{{ asset('images/cinema.webp') }}" 
                 alt=" poster" 
                 class="rounded-xl shadow-md w-full">
        </div>

        {{-- Details --}}
        <div class="md:col-span-2 space-y-6">
            <h1 class="text-3xl font-bold">{{ $movie->name }}</h1>
            
            <div class="flex items-center gap-4 text-gray-600">
                <span class="text-sm">Released: {{ $movie->year }}</span>
                <span class="text-sm">Rating: ⭐ {{ $movie->rating }}</span>
                <span class="text-sm">Country: {{ $movie->title }}</span>
                <span class="text-sm">Language: </span>

            </div>

            {{-- Genres --}}
            <div class="flex flex-wrap gap-2">
                @foreach($movie->genres as $genre)
                    <span class="px-3 py-1 bg-gray-100 text-sm rounded-full">
                        {{ $genre->name }} 
                    </span>
                @endforeach
            </div>

            {{-- Description --}}
            <p class="text-gray-700 leading-relaxed">
                description 
            </p>

            <!-- Actors,Director -->

            {{-- Actions --}}
            <div class="flex gap-3 mt-6">
            <!-- Add to watchlist -->
                <form action="{{ route('favorite.add', $movie->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <button class="p-2 bg-red-100 rounded hover:bg-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 text-red-700 hover:text-red-100">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>

                        </button>
                    </form>

            </div>
        </div>
        <!-- Review section -->
    </div>
</div>



@endsection