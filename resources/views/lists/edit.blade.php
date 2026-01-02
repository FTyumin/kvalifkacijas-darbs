@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-black text-white flex justify-center px-6 py-20">
    <div class="w-full max-w-xl">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-white mb-2">Edit <span class="text-yellow-500">{{ $list->name }}</span></h1>
        </div>

        {{-- Card --}}
        <div class="bg-gray-900/70 backdrop-blur border border-gray-700 rounded-2xl p-8 shadow-xl">

            <form action="{{ route('lists.update', $list) }}" method="POST" class="space-y-6">
                @method('PATCH')
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        List name
                    </label>
                    <input type="text" id="name" name="name" value="{{ $list->name }}"
                        required
                        class="w-full px-4 py-3 rounded-lg bg-gray-800/60 border border-gray-600
                               text-white placeholder-gray-400
                               focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                        placeholder="e.g. Best Sci-Fi Movies"
                    >
                    @error('name')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 rounded-lg bg-gray-800/60 border border-gray-600
                               text-white placeholder-gray-400
                               focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                        placeholder="What is this list about?"
                    >{{ $list->description }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Privacy --}}
                <div class="flex items-center justify-between rounded-xl bg-gray-800/40 border border-gray-700 px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-white">Public list</p>
                        <p class="text-xs text-gray-400">
                            Public lists are visible to other users
                        </p>
                    </div>

                    <input type="hidden" name="is_private" value="0">
                    <input type="checkbox" id="is_private" name="is_private" value="1"
                        class="w-5 h-5 rounded border-gray-600 bg-gray-700
                               text-yellow-400 focus:ring-yellow-400"
                    >
                </div>

                {{-- Submit --}}
                <div class="pt-4 flex justify-end">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl
                               bg-yellow-400 text-gray-900 font-semibold
                               hover:bg-yellow-300 transition
                               focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 focus:ring-offset-black"
                    >
                        Edit List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection