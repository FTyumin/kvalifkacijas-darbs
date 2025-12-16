@extends('layouts.app')

@section('content')

    <div>
        @if(session('error'))
            <div class="bg-red-600 text-white px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('movies.store') }}" method="POST">
            @csrf
            <div class="relative">
                    
                    <input type="numeric" id="movie_id" name="movie_id" required autocomplete="name"
                        class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                        placeholder="movie id"
                    >
                </div>
            <button type="submit">
                <p class="text-white text-2xl">Add movie </p>
            </button>

        </form>
    </div>

@endsection