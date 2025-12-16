@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')
    <div class="">
        <h1 class="text-white">Admin dashboard, bruv</h1>

        @foreach($suggestions as $sug)
            <p class="text-white">{{ $sug->title }}</p>
            <form method="POST" action="{{ route('suggestions.approve', $sug) }}">
                @csrf
                <button type="submit" class="btn btn-success text-white">
                    Approve
                </button>
            </form>
        @endforeach
    </div>
@endsection
