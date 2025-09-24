@extends('layouts.app')

@section('title', $actor->name)

@section('content')

<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Header: photo + bio --}}
    <div class="flex flex-col md:flex-row gap-8">
        {{-- Actor photo --}}
        <div class="flex-shrink-0">
            <img src="{{ $actor->photo_url ?? asset('images/person-placeholder.png') }}"
                 alt="{{ $actor->name }}"
                 class="w-64 h-80 object-cover rounded-xl shadow">
        </div>

        {{-- Details --}}
        <div class="flex-1 space-y-4">
            <h1 class="text-3xl font-bold">{{ $actor->name }}</h1>

            <div class="flex items-center gap-6 text-gray-600">
                @if($actor->birth_date)
                    <span>Born: {{ \Carbon\Carbon::parse($actor->birth_date)->format('M d, Y') }}</span>
                @endif
                @if($actor->death_date)
                    <span>Died: {{ \Carbon\Carbon::parse($actor->death_date)->format('M d, Y') }}</span>
                @endif
                @if($actor->birth_place)
                    <span>From: {{ $actor->birth_place }}</span>
                @endif
            </div>

            @if($actor->bio)
                <p class="text-gray-700 leading-relaxed">
                    {{ $actor->bio }}
                </p>
            @endif

            <div class="flex gap-3 pt-4">
                <a href="{{ route('actors.index') }}"
                   class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm">
                    ← Back to Actors
                </a>
                <a href="{{ route('actors.edit', $actor) }}"
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                    ✏️ Edit
                </a>
            </div>
        </div>
    </div>

    {{-- Filmography --}}
    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Filmography</h2>

        @if($actor->movies && $actor->movies->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($actor->movies as $movie)
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
                            @if(!empty($movie->pivot->character))
                                <div class="mt-1 text-sm text-gray-700">
                                    as <span class="italic">{{ $movie->pivot->character }}</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-gray-600">No movies listed for this actor yet.</p>
        @endif
    </section>
</div>

@endsection