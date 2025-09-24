@extends('layouts.app')

@section('title', $director->name)

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header: photo + bio --}}
    <div class="flex flex-col md:flex-row gap-8">
        {{-- Director photo --}}
        <div class="flex-shrink-0">
            <img src="{{ $director->photo_url ?? asset('images/person-placeholder.png') }}"
                 alt="{{ $director->name }}"
                 class="w-64 h-80 object-cover rounded-xl shadow">
        </div>

        {{-- Details --}}
        <div class="flex-1 space-y-4">
            <h1 class="text-3xl font-bold">{{ $director->name }}</h1>

            <div class="flex items-center gap-6 text-gray-600">
                @if($director->birth_year)
                    <span>Born: {{ $director->birth_year }}</span>
                @endif
                @if($director->death_date)
                    <span>Died: {{ $director->death_date->format('M d, Y') }}</span>
                @endif
                @if($director->birth_place)
                    <span>From: {{ $director->birth_place }}</span>
                @endif
                <span>Nationality: {{ $director->nationality }}</span>
            </div>

            @if($director->bio)
                <p class="text-gray-700 leading-relaxed">
                    {{ $director->bio }}
                </p>
            @endif

            <div class="flex gap-3 pt-4">
                <a href="{{ route('directors.index') }}"
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
                    ← Back to Directors
                </a>
                
            </div>
        </div>
    </div>

    {{-- Directed Movies --}}
    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Directed Movies</h2>

        @if($director->movies && $director->movies->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($director->movies as $movie)
                    <a href="{{ route('movies.show', $movie->id) }}"
                       class="group bg-white rounded-xl shadow hover:shadow-md transition overflow-hidden">
                        <img src="{{ $movie->poster_url ?? asset('images/placeholder.jpg') }}"
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
        @else
            <p class="text-gray-600">No directed movies listed for this director yet.</p>
        @endif
    </section>
</div>


@endsection