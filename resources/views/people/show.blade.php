@extends('layouts.app')

@section('title', $person->name)

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header: photo + bio --}}
    <div class="flex flex-col md:flex-row gap-8">
        {{-- Director photo --}}
        <div class="flex-shrink-0">
            <img src="{{ $person->photo_url ?? asset('images/person-placeholder.png') }}"
                 alt="{{ $person->name }}"
                 class="w-64 h-80 object-cover rounded-xl shadow">
        </div>

        {{-- Details --}}
        <div class="flex-1 space-y-4">
            <h1 class="text-3xl font-bold text-white">{{ $person->first_name }} {{ $person->last_name }}</h1>

            <div class="flex items-center gap-6 text-gray-600">
                @if($person->birth_year)
                    <span>Born: {{ $person->birth_year }}</span>
                @endif
                @if($person->death_date)
                    <span>Died: {{ $person->death_date->format('M d, Y') }}</span>
                @endif
                @if($person->birth_place)
                    <span>From: {{ $person->birth_place }}</span>
                @endif
                <span>Nationality: {{ $person->nationality }}</span>
            </div>

            @if($person->bio)
                <p class="text-gray-700 leading-relaxed">
                    {{ $person->bio }}
                </p>
            @endif

        </div>
    </div>

    {{-- Directed Movies --}}
    <section class="mt-12">
        <h2 class="text-2xl text-white font-semibold mb-4">Directed Movies</h2>

        @if($person->moviesAsDirector && $person->moviesAsDirector->count())
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
            <p class="text-gray-600">No directed movies listed for this director yet.</p>
        @endif
    </section>

    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Directed Movies</h2>

        @if($person->moviesAsActor && $person->moviesAsActor->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($person->movies as $movie)
                    <a href="{{ route('movies.show', $movie->id) }}"
                       class="group bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">
                        <img src="{{ asset('images/movie-placeholder.jpg') }}"
                             alt="{{ $movie->title }}"
                             class="w-full h-64 object-cover group-hover:opacity-90">
                        <div class="p-4">
                            <h3 class="font-semibold text-lg group-hover:text-blue-600">{{ $movie->title }}</h3>
                            <div class="text-sm text-gray-500">
                                {{ optional($movie->release_date)->format('Y') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>
</div>


@endsection