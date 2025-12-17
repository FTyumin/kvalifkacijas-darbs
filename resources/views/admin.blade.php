@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-black via-neutral-900 to-black p-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Admin Dashboard</h1>
        <p class="text-gray-400 mt-1">Overview of platform activity</p>
    </div>

    {{-- Top stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        {{-- Most followed user --}}
        <div class="rounded-xl bg-neutral-900/80 border border-white/5 p-5">
            <p class="text-sm text-gray-400">Most Followed User</p>
            <p class="text-xl font-semibold text-white mt-1">
                {{ $userWithMostFollowers?->name ?? '—' }}
            </p>
            <p class="text-gray-400 text-sm">
                {{ $userWithMostFollowers?->followers_count ?? 0 }} followers
            </p>
        </div>

        {{-- Top review --}}
        <div class="rounded-xl bg-neutral-900/80 border border-white/5 p-5">
            <p class="text-sm text-gray-400">Top Review</p>
            <p class="text-white font-medium mt-1 line-clamp-2">
                {{ $topReview?->title ?? '—' }}
            </p>
            <p class="text-gray-400 text-sm">
                {{ $topReview?->liked_by_count ?? 0 }} likes
            </p>
        </div>

        {{-- Suggestions --}}
        <div class="rounded-xl bg-neutral-900/80 border border-white/5 p-5">
            <p class="text-sm text-gray-400">Pending Suggestions</p>
            <p class="text-3xl font-bold text-white mt-1">
                {{ $suggestions->count() }}
            </p>
        </div>

        {{-- Placeholder for future stat --}}
        <div class="rounded-xl bg-neutral-900/80 border border-white/5 p-5">
            <p class="text-sm text-gray-400">System Status</p>
            <p class="text-green-400 font-semibold mt-1">Operational</p>
        </div>
    </div>

    {{-- Lists --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">

        {{-- Most Favorited Movies --}}
        <div class="rounded-xl bg-neutral-900/80 border border-white/5 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Most Favorited Movies</h2>

            <ul class="space-y-3">
                @foreach($mostFavorites as $movie)
                    <li class="flex justify-between text-gray-300">
                        <span>{{ $movie->title }}</span>
                        <span class="text-gray-400">{{ $movie->favoriters_count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Most Watched Movies --}}
        <div class="rounded-xl bg-neutral-900/80 border border-white/5 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Most Watched Movies</h2>

            <ul class="space-y-3">
                @foreach($mostWatched as $movie)
                    <li class="flex justify-between text-gray-300">
                        <span>{{ $movie->name }}</span>
                        <span class="text-gray-400">{{ $movie->watchers_count }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>

    {{-- Suggestions --}}
    <div class="rounded-xl bg-neutral-900/80 border border-white/5 p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Pending Suggestions</h2>

        @forelse($suggestions as $sug)
            <div class="flex items-center justify-between py-3 border-b border-white/5 last:border-none">
                <div>
                    <p class="text-white font-medium">{{ $sug->title }}</p>
                    <p class="text-sm text-gray-400">Submitted by user #{{ $sug->user_id }}</p>
                </div>

                <form method="POST" action="{{ route('suggestions.approve', $sug) }}">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white text-sm transition"
                    >
                        Approve
                    </button>
                </form>
            </div>
        @empty
            <p class="text-gray-400">No pending suggestions 🎉</p>
        @endforelse
    </div>

</div>
@endsection

