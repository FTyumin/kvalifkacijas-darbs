@extends('layouts.app')

@section('title', 'Suggestion')

@section('content')
<div>
    <h1>Send movie suggestion</h1>
    <form method="POST" action="{{ route('suggestions.store') }}" >
        @csrf
        <input type="text" name="title" id="title">

        <button type="submit">Submit suggestion</button>
    </form>
</div>


@endsection