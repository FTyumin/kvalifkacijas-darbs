@extends('layouts.app')

@section('content')
    <div class="h-64">
        <p class="text-white text-3xl">{{ $list->name }}</p>

        @foreach($list->movies as $movie)
            <div>
                <p class="text-white">{{ $movie->name }}</p>
            </div>
        @endforeach
    </div>
@endsection