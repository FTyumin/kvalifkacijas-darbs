@extends('layouts.app')

@section('title', 'Suggestion')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-black via-neutral-900 to-black flex items-center justify-center px-4">

    <div class="w-full max-w-3xl bg-neutral-900/80 border border-white/5 rounded-2xl p-8 grid md:grid-cols-2 gap-8">

        {{-- Poster placeholder --}}
        <div class="flex items-center justify-center">
            <div class="w-48 aspect-[2/3] rounded-xl bg-gradient-to-br from-neutral-800 to-neutral-700 
                        flex items-center justify-center text-gray-400 text-sm border border-white/10">
                Movie Poster
            </div>
        </div>

        {{-- Form --}}
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">
                Send Movie Suggestion
            </h1>
            <p class="text-gray-400 mb-6">
                Suggest a movie you'd like to see added to the platform.
            </p>

            <form method="POST" action="{{ route('suggestions.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="title" class="block text-sm text-gray-300 mb-1">
                        Movie title
                    </label>
                    <input
                        type="text"
                        name="title"
                        id="title"
                        placeholder="e.g. The Departed"
                        required
                        class="w-full rounded-lg bg-neutral-800 border border-white/10 
                               px-4 py-2 text-white placeholder-gray-500
                               focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 hover:bg-blue-500 
                           text-white font-medium py-2 transition"
                >
                    Submit suggestion
                </button>
            </form>
        </div>

    </div>

</div>



@endsection