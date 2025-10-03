@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
  <h1 class="text-2xl font-bold mb-6">Top Movies</h1>

  <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @foreach($movies as $m)
      <div class="bg-white rounded overflow-hidden shadow">
        <img src="{{ $m['poster'] }}" alt="{{ $m['title'] }}" class="w-full h-56 object-cover">
        <div class="p-2">
          <h3 class="text-sm font-semibold">{{ $m['title'] }}</h3>
          <p class="text-xs text-gray-500">{{ $m['year'] }}</p>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
