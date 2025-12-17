@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto py-8 min-h-screen">
        <h1 class="text-2xl text-white font-bold mb-6">Your Feed</h1>
        
        <div class="space-y-6">
            @foreach($activities as $post)
            @if($post->activityable_type == 'App\Models\Review')
                <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                    <a href="" class="block">
                        <div class="flex gap-4 p-6">
                            {{-- Movie Poster --}}
                            <div class="flex-shrink-0">
                               <img src="https://image.tmdb.org/t/p/w200/{{ $post->activityable->movie->poster_url }}" 
                                alt="movie poster" 
                                class="w-24 h-36 object-cover rounded-lg shadow-md">
                            </div>

                            {{-- Card Content --}}
                                <div class="flex-1 min-w-0">
                                    {{-- Card Header --}}
                                    <div class="flex items-start justify-between gap-4 mb-4">
                                        <div class="flex-1">
                                            <h2 class="text-xl font-bold text-white hover:text-blue-400 transition-colors mb-2">
                                                {{ $post->activityable->title }}
                                            </h2>
                                            <div class="flex items-center gap-3 flex-wrap">
                                                <a href="" 
                                                onclick="event.stopPropagation()"
                                                class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                        <!-- <span class="text-white text-sm font-semibold">{{ substr($post->user->name, 0, 1) }}</span> -->
                                                        <img src="{{ $post->user->image ? asset('storage/' . $post->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">
                                                    </div>
                                                    <span class="text-gray-300 text-sm font-medium">{{ $post->activityable->user->name }}</span>
                                                </a>
                                                <span class="text-gray-500">•</span>
                                                <time class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</time>
                                                <span class="text-gray-500">•</span>
                                                <span class="text-sm text-gray-400">{{ $post->activityable->comments->count() }} {{ Str::plural('comment', $post->activityable->comments->count()) }}</span>
                                            </div>
                                        </div>
                                        
                                        {{-- Rating Badge --}}
                                <div class="flex items-center gap-1 mb-2">
                                    @for ($j = 0; $j < $post->activityable->rating; $j++)
                                        <svg class="w-4 h-4" fill="yellow" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    @for ($j = $post->activityable->rating; $j < 5; $j++)
                                        <svg class="w-4 h-4" fill="black" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                    </div>

                                    {{-- Review Content --}}
                                    @if($post->activityable_type == 'App\Models\Review')
                        
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Card Footer --}}
                            <div class="bg-gray-900/50 px-6 py-3 border-t border-gray-700 flex items-center justify-between">
                                <span class="text-sm text-gray-400">Click to read full review</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                                                        
                        </a>
                </article>
                @elseif($post->activityable_type == 'App\Models\MovieList')
                    <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="block">
                            <div class="flex gap-4 p-6">
                                {{-- Movie Poster --}}
                                <div class="flex-shrink-0">
                                
                                </div>

                                {{-- Card Content --}}  
                                    <div class="flex-1 min-w-0">
                                        {{-- Card Header --}}
                                        <div class="flex items-start justify-between gap-4 mb-4">
                                            <div class="flex-1">
                                                <h2 class="text-lg font-semibold text-gray-900 mb-2">
                                                    <span class="text-yellow-400 cursor-pointer">
                                                        
                                                    </span>
                                                    <span class="text-white font-normal">created a list</span>
                                                    <span class="text-yellow-400"><a href="{{ route('lists.show', $post->activityable->id) }}">{{ $post->activityable->name }}</a></span>
                                                </h2>
                                                <div class="flex items-center gap-3 flex-wrap">
                                                    <a href="" 
                                                    onclick="event.stopPropagation()"
                                                    class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                            <!-- <span class="text-white text-sm font-semibold">{{ substr($post->user->name, 0, 1) }}</span> -->
                                                            <img src="{{ $post->user->image ? asset('storage/' . $post->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">
                                                        </div>
                                                        <span class="text-gray-300 text-sm font-medium">{{ $post->activityable->user->name }}</span>
                                                    </a>
                                                    <span class="text-gray-500">•</span>
                                                    <time class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</time>
                                                </div>
                                            </div>
                                            
                                        </div>

                                    </div>
                                </div>
                        </div>
                    </article>

                @elseif($post->activityable_type == 'Maize\Markable\Models\Favorite')
                    <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="block">
                            <div class="flex gap-4 p-6">
                                {{-- Movie Poster --}}
                                <div class="flex-shrink-0">
                                    <a href="{{ route('movies.show',$post->activityable->movie->slug ) }}">
                                        <img src="https://image.tmdb.org/t/p/w200/{{ $post->activityable->movie->poster_url }}" 
                                        alt="movie poster" 
                                        class="w-24 h-36 object-cover rounded-lg shadow-md">
                                    </a>
                                </div>

                                {{-- Card Content --}}  
                                    <div class="flex-1 min-w-0">
                                        {{-- Card Header --}}
                                        <div class="flex items-start justify-between gap-4 mb-4">
                                            <div class="flex-1">
                                                <h2 class="text-lg font-semibold text-gray-900 mb-2">
                                                    <span class="text-yellow-400 cursor-pointer">
                                                        {{$post->user->name}}
                                                    </span>
                                                    <span class="text-white font-normal">favorited</span>
                                                     <span>{{ $post->activityable->movie->name }}</span>
                                                </h2>
                                                <div class="flex items-center gap-3 flex-wrap">
                                                    <a href="" 
                                                    onclick="event.stopPropagation()"
                                                    class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                            <!-- <span class="text-white text-sm font-semibold">{{ substr($post->user->name, 0, 1) }}</span> -->
                                                            <img src="{{ $post->user->image ? asset('storage/' . $post->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">
                                                        </div>
                                                        <span class="text-gray-300 text-sm font-medium">{{ $post->activityable->user->name }}</span>
                                                    </a>
                                                    <span class="text-gray-500">•</span>
                                                    <time class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</time>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </article>

                @elseif($post->activityable_type == 'App\Models\userRelationship')
                    <article class="bg-gray-800 rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="block">
                            <div class="flex gap-4 p-6">

                                {{-- Card Content --}}  
                                    <div class="flex-1 min-w-0">
                                        {{-- Card Header --}}
                                        <div class="flex items-start justify-between gap-4 mb-4">
                                            <div class="flex-1">
                                                <h2 class="text-lg font-semibold text-gray-900 mb-2">
                                                    <span class="text-yellow-400 cursor-pointer">
                                                        {{$post->user->name}}
                                                    </span>
                                                    <span class="text-white font-normal">followed you!</span>
                                                </h2>
                                                <div class="flex items-center gap-3 flex-wrap">
                                                    <a href="" 
                                                    onclick="event.stopPropagation()"
                                                    class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center">
                                                            <img src="{{ $post->user->image ? asset('storage/' . $post->user->image) : asset('images/person-placeholder.png') }}" alt="" class="w-full h-full object-cover">
                                                        </div>
                                                        <span class="text-gray-300 text-sm font-medium"></span>
                                                    </a>
                                                    <span class="text-gray-500">•</span>
                                                    <time class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</time>
                                                </div>
                                            </div>
                                            
                                     
                                        </div>

                                    </div>
                                </div>
                        </div>
                    </article>
                @endif
            @endforeach

            @if(count($activities) == 0)
                <div>
                    <p class="text-white">You don't follow anyone, yet.</p>
                </div>
            @endif
        </div>
    </div>
@endsection