@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    
    {{-- Review Card --}}
    <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
        {{-- Review Header --}}
        <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <a href="{{ route('movies.show', $review->movie) }}" class="text-2xl font-bold text-white hover:text-blue-400 transition-colors">
                        {{ $review->movie->name }}
                    </a>
                    <div class="flex items-center gap-3 mt-2">
                        <a href="{{ route('profile.show', $review->user) }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">{{ substr($review->user->name, 0, 1) }}</span>
                            </div>
                            <span class="text-gray-300 font-medium">{{ $review->user->name }}</span>
                        </a>
                        <span class="text-gray-500">•</span>
                        <time class="text-sm text-gray-400">{{ $review->created_at->format('M d, Y') }}</time>
                    </div>
                </div>
                
                {{-- Rating Badge --}}
                <div class="flex flex-col items-center bg-yellow-500/10 border border-yellow-500/30 rounded-lg px-4 py-2">
                    <div class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-yellow-500">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-2xl font-bold text-yellow-500">{{ $review->rating }}</span>
                    </div>
                    <span class="text-xs text-gray-400">out of 5</span>
                </div>
            </div>
        </div>

        {{-- Review Content --}}
        <div class="px-6 py-6">
            @if($review->spoilers)
                {{-- Spoiler Warning --}}
                <div id="spoiler-warning-{{ $review->id }}" class="p-4 bg-yellow-500/10 border-2 border-yellow-500/30 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-yellow-500 flex-shrink-0 mt-0.5">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        </svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-yellow-500 mb-1">Spoiler Warning</h3>
                            <p class="text-sm text-gray-300 mb-3">This review contains spoilers that may reveal important plot points.</p>
                            <button 
                                onclick="toggleSpoiler({{ $review->id }})" 
                                class="px-4 py-2 bg-yellow-500 text-gray-900 text-sm font-medium rounded-lg hover:bg-yellow-400 transition-colors"
                            >
                                Show Review
                            </button>
                        </div>
                    </div>
                </div>
                
                {{-- Hidden Spoiler Content --}}
                <div id="spoiler-content-{{ $review->id }}" class="hidden">
                    <div class="prose prose-invert max-w-none">
                        <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $review->description }}</p>
                    </div>
                    <button 
                        onclick="toggleSpoiler({{ $review->id }})" 
                        class="mt-4 px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors"
                    >
                        Hide Review
                    </button>
                </div>
            @else
                <div class="prose prose-invert max-w-none">
                    <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $review->description }}</p>
                </div>
            @endif
            <form action="{{ route('reviews.like', $review) }}" method="POST">
                @csrf
    
                <button type="submit" class="flex items-center gap-1">
                    @if($review->likedBy->contains(auth()->id()))
                        ❤️
                    @else
                        🤍
                    @endif
                    <span class="text-white">{{ $review->likedBy->count() }}</span>
                </button>
            </form>
        </div>

    </article>

    {{-- Comments Section --}}
    <div class="bg-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-500">
                    <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0 1 12 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 0 1-3.476.383.39.39 0 0 0-.297.17l-2.755 4.133a.75.75 0 0 1-1.248 0l-2.755-4.133a.39.39 0 0 0-.297-.17 48.9 48.9 0 0 1-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97Z" clip-rule="evenodd" />
                </svg>
                Comments
                <span class="text-sm font-normal text-gray-400">({{ $review->comments->count() }})</span>
            </h2>
        </div>

        {{-- Comments List --}}
        @if($review->comments->count() > 0)
        <div class="divide-y divide-gray-700">
            @foreach($review->comments as $comment)
            <div class="px-6 py-4 hover:bg-gray-700/30 transition-colors">
                <div class="flex gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold">{{ substr($comment->user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <a href="{{ route('profile.show', $comment->user) }}" class="font-medium text-white hover:text-blue-400 transition-colors">
                                {{ $comment->user->name }}
                            </a>
                            <span class="text-gray-500">•</span>
                            <time class="text-sm text-gray-400">{{ $comment->created_at->diffForHumans() }}</time>
                        </div>
                        <p class="text-gray-300 leading-relaxed">{{ $comment->description }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-6 py-12 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-600 mx-auto mb-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
            </svg>
            <p class="text-gray-400">No comments yet. Be the first to comment!</p>
        </div>
        @endif

        {{-- Add Comment Form --}}
        @auth
        <div class="bg-gray-900/30 px-6 py-6 border-t border-gray-700">
            <form action="{{ route('comments.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="review_id" value="{{ $review->id }}">

                @if (session('warning'))
                <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                    <p class="text-red-400 text-sm">{{ session('warning') }}</p>
                </div>
                @endif

                @if (session('success'))
                <div class="p-4 bg-green-500/10 border border-green-500/30 rounded-lg">
                    <p class="text-green-400 text-sm">{{ session('success') }}</p>
                </div>
                @endif

                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-300 mb-2">
                        Add your comment
                    </label>
                    <textarea
                        id="comment"
                        name="comment"
                        rows="4"
                        class="block w-full px-4 py-3 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400
                               focus:ring-2 focus:ring-blue-500 focus:border-transparent transition
                               disabled:opacity-50 disabled:pointer-events-none"
                        placeholder="Share your thoughts about this review..."
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800
                           transition-colors"
                >
                    Post Comment
                </button>
            </form>
        </div>
        @else
        <div class="bg-gray-900/30 px-6 py-6 border-t border-gray-700 text-center">
            <p class="text-gray-400 mb-3">You must be logged in to comment</p>
            <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                Log In
            </a>
        </div>
        @endauth
    </div>
</div>

<script>
function toggleSpoiler(reviewId) {
    const warning = document.getElementById(`spoiler-warning-${reviewId}`);
    const content = document.getElementById(`spoiler-content-${reviewId}`);
    
    warning.classList.toggle('hidden');
    content.classList.toggle('hidden');
}
</script>

@endsection