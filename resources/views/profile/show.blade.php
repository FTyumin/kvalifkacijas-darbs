@extends('layouts.app')

@section('title', 'dashboard')

@section('content')
    <div class="min-h-screen bg-neutral-900 text-white">
        <main class="relative z-10">

            {{-- Header --}}
            <div class="py-12 px-6 lg:px-28">
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm text-gray-300 hover:text-white transition-colors mb-4">
                    ← Back
                </a>
                <div class="mb-10 space-y-4">
                    <h1 class="text-4xl font-bold">
                        <span class="text-blue-400">{{ $user->name }} Profile</span>
                    </h1>

                    {{-- Follow / Unfollow --}}
                    <div x-data="{
                            following: {{ auth()->check() && auth()->user()->followees()->where('followee_id', $user->id)->exists() ? 'true' : 'false' }},
                            loading: false,
                            async toggle() {
                                this.loading = true;
                                const method = this.following ? 'DELETE' : 'POST';
                                const endpoint = `/api/users/{{ $user->id }}/${this.following ? 'unfollow' : 'follow'}`;

                                try {
                                    const res = await fetch(endpoint, {
                                        method,
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        }
                                    });
                                    const data = await res.json();
                                    if (res.ok) this.following = data.following;
                                } catch (e) {
                                    console.error(e);
                                } finally {
                                    this.loading = false;
                                }
                            }
                        }"
                    >
                        @auth
                            @if(auth()->id() !== $user->id)
                                <button @click="toggle()" :disabled="loading"
                                    class="px-4 py-2 rounded bg-blue-500 text-white disabled:opacity-60"
                                    x-text="loading ? 'Loading...' : (following ? 'Unfollow' : 'Follow')"
                                ></button>
                            @endif
                        @endauth
                    </div>

                    {{-- Followers / Following --}}
                    <p class="text-lg text-gray-300 flex items-center gap-4">
                        <x-user-list-modal title="Followers" :users="$user->followers->map->follower->filter()"
                            empty-message="No followers yet."
                        >
                            <x-slot name="trigger">
                                <span class="flex items-center gap-1 cursor-pointer hover:text-white transition">
                                    <x-heroicon-o-user-group class="w-5 h-5 text-gray-400" />
                                    {{ $user->followers->count() }} Followers
                                </span>
                            </x-slot>
                        </x-user-list-modal>

                        <x-user-list-modal title="Following" :users="$user->followees->map->followee->filter()"
                            empty-message="Not following anyone yet."
                        >
                            <x-slot name="trigger">
                                <span class="flex items-center gap-1 cursor-pointer hover:text-white transition">
                                    <x-heroicon-o-user-plus class="w-5 h-5 text-gray-400" />
                                    {{ $user->followees->count() }} Following
                                </span>
                            </x-slot>
                        </x-user-list-modal>
                    </p>

                    <p class="text-xl text-gray-400">
                        Check out his/her favorite movies!
                    </p>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Watchlist</p>
                                <p class="text-3xl font-bold text-blue-400">{{ $user->wantToWatch->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-600/20 rounded-xl flex items-center justify-center">
                                @svg('heroicon-o-bookmark', 'w-6 h-6 text-blue-400')
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Watched</p>
                                <p class="text-3xl font-bold text-green-400">{{ $user->seenMovies->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-600/20 rounded-xl flex items-center justify-center">
                                @svg('heroicon-o-check-circle', 'w-6 h-6 text-green-400')
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Reviews</p>
                                <p class="text-3xl font-bold text-purple-400">{{ $reviews->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-600/20 rounded-xl flex items-center justify-center">
                                @svg('heroicon-o-pencil-square', 'w-6 h-6 text-purple-400')
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-800/50 glass border border-gray-700 rounded-xl p-6 hover:border-gray-600 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-400">Avg Rating</p>
                                <p class="text-3xl font-bold text-yellow-400">{{ $average_review }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-600/20 rounded-xl flex items-center justify-center">
                                @svg('heroicon-s-star', 'w-6 h-6 text-yellow-400')
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="px-6 lg:px-28 pb-12">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    {{-- Left Column: Recently Watched --}}
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                                    @svg('heroicon-o-bookmark', 'w-6 h-6 text-blue-400')
                                    Recently Watched
                                </h2>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                                @forelse($seen as $entry)
                                    <a href="{{ route('movies.show', $entry->movie->slug) }}" class="block">
                                        <div class="group relative">
                                            <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden relative">
                                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                    src="https://image.tmdb.org/t/p/w500/{{ $entry->movie->poster_url }}"
                                                    alt="Movie poster"
                                                />
                                                <div class="absolute top-2 right-2 bg-green-600 rounded-full p-1">
                                                    @svg('heroicon-s-check', 'w-3 h-3 text-white')
                                                </div>
                                            </div>

                                            <h3 class="mt-2 text-sm font-medium line-clamp-2">
                                                {{ $entry->movie->name }}
                                            </h3>
                                            <p class="text-xs text-gray-400">Watched {{ $entry->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-sm text-gray-400 col-span-full">No watched movies yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Recent Reviews --}}
                    <div class="space-y-8">
                        <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-8">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                                    @svg('heroicon-o-pencil-square', 'w-6 h-6 text-purple-400')
                                    Recent Reviews
                                </h2>
                                <a href="/reviews" class="text-purple-400 hover:text-purple-300 text-sm font-medium transition-colors">
                                    View All →
                                </a>
                            </div>

                            <div class="space-y-4">
                                @forelse($reviews as $review)
                                    <div class="border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <img class="w-12 h-16 object-cover rounded"
                                                src="https://image.tmdb.org/t/p/w500/{{ $review->movie->poster_url }}"
                                                alt="Movie poster"
                                            />
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-white mb-1">
                                                    {{ $review->movie->name }}
                                                </h4>

                                                <div class="flex items-center gap-1 mb-2">
                                                    @for ($j = 0; $j < 5; $j++)
                                                        @svg('heroicon-s-star', 'w-4 h-4 ' . ($j < $review->rating ? 'text-yellow-400' : 'text-gray-600'))
                                                    @endfor
                                                    <span class="text-sm text-gray-400 ml-1">{{ $review->rating }}</span>
                                                </div>

                                                <p class="text-sm text-gray-300 line-clamp-2">
                                                    {{ $review->title }}
                                                </p>

                                                <time class="text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</time>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-400">No reviews yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Favorite movies --}}
            <div class="px-6 lg:px-28 pb-12">
                <div class="bg-gray-800/50 glass border border-gray-700 rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            @svg('heroicon-o-check-circle', 'w-6 h-6 text-green-400')
                            Favorites
                        </h2>
                    </div>

                    @if($favorites->isEmpty())
                        <p class="text-sm text-gray-400">No favorites yet.</p>
                    @else
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach($favorites as $fav)
                                <a href="{{ route('movies.show', $fav->movie->slug) }}" class="group">
                                    <div class="aspect-[2/3] bg-gray-700 rounded-lg overflow-hidden">
                                        <img
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            src="https://image.tmdb.org/t/p/w500/{{ $fav->movie->poster_url }}"
                                            alt="Movie poster"
                                        />
                                    </div>
                                    <h3 class="mt-2 text-sm font-medium text-white line-clamp-2">
                                        {{ $fav->movie->name }}
                                    </h3>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
@endsection
