<div class="md:col-span-full">
    <form action="{{ route('reviews.store') }}" method="POST" class="mt-16 mx-auto space-y-6 mb-12">
        @csrf

        <h3 class="text-3xl text-white font-semibold">Write a Review for <span class="text-green-600">{{ $movie->name }}</span></h3>
        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
        {{-- Star Rating --}}
        <fieldset class="flex items-center space-x-1">
            <legend class="sr-only">Rating</legend>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="1" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="1"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="2" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="2"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="3" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="3"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="4" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="4"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
            
            <label class="relative cursor-pointer">
                <input type="radio" name="rating" value="5" class="peer sr-only" />
                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-300 transition-colors star-svg" 
                    data-rating="5"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.153c.969 0 1.371 1.24.588 1.81l-3.36 2.44a1 1 0 00-.364 1.118l1.287 3.951c.3.921-.755 1.688-1.54 1.118l-3.36-2.44a1 1 0 00-1.176 0l-3.36 2.44c-.784.57-1.838-.197-1.54-1.118l1.286-3.951a1 1 0 00-.364-1.118L2.98 9.377c-.783-.57-.38-1.81.588-1.81h4.153a1 1 0 00.95-.69l1.286-3.95z" />
                </svg>
            </label>
        </fieldset>
        @error('rating')
        <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
        @if (session('warning'))
        <div class="alert alert-warning">
            <p class="text-red-600 text-sm">{{ session('warning') }}</p>
        </div>
        @endif
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        {{-- Review Text --}}
        <div>
        <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">
            Your Review
        </label>
        <textarea
            id="comment"
            name="comment"
            rows="4"
            class="block w-full px-4 py-2 border border-gray-300 rounded-lg 
                focus:ring-blue-500 focus:border-blue-500 transition
                disabled:opacity-50 disabled:pointer-events-none"
            placeholder="Share your thoughts about this movie"
        >{{ old('comment') }}</textarea>
        @error('comment')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex flex-row gap-3 items-center text-white">
            <input type="checkbox" name="spoiler"> <p>Contains spoilers</p>
        </div>

        {{-- Submit --}}
        <button
        type="submit"
        class="inline-block px-6 py-3 bg-blue-600 text-white font-medium 
                rounded-lg hover:bg-blue-700 transition">
        Submit Review
        </button>
        </form>

</div>
