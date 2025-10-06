@extends('layouts.app')

@section('title', 'Preference quiz')

@section('content')
<div>
    <h2 class="text-xl font-bold mb-4">Tell us about your movie preferences</h2>

    <form method="POST" action="{{ route('quiz.store') }}">
        @csrf

        <div>
            <label>Favorite Genres</label>
            <select name="genres[]" multiple>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-4">Save</button>
    </form>
</div>

@endsection