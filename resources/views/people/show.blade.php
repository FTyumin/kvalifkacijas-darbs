@extends('layouts.app')

@section('title', $person->name)

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header: photo + bio --}}
    <div class="flex flex-col md:flex-row gap-8">
        {{-- Director photo --}}
        <div class="flex-shrink-0">
            <img src="https://image.tmdb.org/t/p/w500/{{ $person->profile_path }}"
                 alt="{{ $person->name }}"
                 class="w-64 h-80 object-cover rounded-xl shadow">
        </div>

        {{-- Details --}}
        <div class="flex-1 space-y-4">
            <h1 class="text-3xl font-bold text-white">{{ $person->first_name }} {{ $person->last_name }}</h1>

            @if($person->biography)
                <p class="text-white leading-relaxed">
                    {{ $person->biography }}
                </p>
            @endif

        </div>
    </div>

    <div>
        <form action="{{ route('person.favorite', $person->id) }}" method="POST">
        @csrf

            <input type="text" class="hidden">

            <button type="submit" class="white">Favorite</button>
        </form>
    </div>

    {{-- Directed Movies --}}
    <section class="mt-12">
        
        @if($person->moviesAsDirector && $person->moviesAsDirector->count())
            <h2 class="text-2xl text-white font-semibold mb-4">Directed Movies</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($person->moviesAsDirector as $movie)
                        <a href="{{ route('movies.show', $movie->slug) }}"
                        class="group rounded-xl shadow hover:shadow-md transition overflow-hidden">
                            <div class="group relative">
                                    <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden relative">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                    src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}"  
                                    alt="Movie poster" />
                                
                                
                            </div>
                            </div>
                        </a>
                    @endforeach
                </div>
        @else
            
        @endif
    </section>

    <section class="mt-12">
        @if($person->moviesAsActor && $person->moviesAsActor->count())
            <h2 class="text-2xl text-white font-semibold mb-4">Movies</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($person->moviesAsActor as $movie)
                        <a href="{{ route('movies.show', $movie->slug) }}"
                        class="group rounded-xl shadow hover:shadow-md transition overflow-hidden">
                            <div class="group relative">
                                    <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden relative">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" 
                                    src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_url }}"  
                                    alt="Movie poster" />
                                
                                
                            </div>
                            </div>
                        </a>
                    @endforeach
                </div>
        @endif
    </section>
</div>


@endsection