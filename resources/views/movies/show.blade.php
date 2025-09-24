@extends('layouts.app')

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
            <h1 class="text-3xl font-bold">Movie Title</h1>
            
            <div class="flex items-center gap-4 text-gray-600">
                <span class="text-sm">Released: </span>
                <span class="text-sm">Rating: ⭐ 3,4</span>
                <span class="text-sm">Country: </span>
                <span class="text-sm">Language: </span>

            </div>

            {{-- Genres --}}
            <div class="flex flex-wrap gap-2">
                @for($i=0; $i<3; $i++)
                    <span class="px-3 py-1 bg-gray-100 text-sm rounded-full">
                         genre_name 
                    </span>
                @endfor
            </div>

            {{-- Description --}}
            <p class="text-gray-700 leading-relaxed">
                description 
            </p>

            {{-- Actions --}}
            <div class="flex gap-3 mt-6">
            <!-- Add to watchlist -->


            </div>
        </div>
        <!-- Review section -->
    </div>
</div>



@endsection