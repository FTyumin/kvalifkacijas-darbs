@extends('layouts.app')

@section('content')

<div>
    @if (count($results) > 0)
    <ul>
        @foreach ($results as $result)
            <li>{{ $result->name }}</li>
        @endforeach
    </ul>
@else
    <p>No results found.</p>
@endif
</div>

@endsection