@extends('layouts.app')

@section('content')
    <div class="mx-14 p-10">
        <h1 class="text-white text-2xl my-10">Create a Movie list</h1>
        <form action="{{ route('lists.store') }}" class="space-y-6 px-16 w-100" method="POST">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                    Name
                </label>
                <div class="relative">
                    
                    <input 
                        type="name" 
                        id="name" 
                        name="name" 
                        required 
                        autocomplete="name"
                        class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                        placeholder="Enter list's name"
                    >
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                    Description
                </label>
                <div class="relative">
                    
                    <textarea 
                        type="description" 
                        id="description" 
                        name="description" 
                        required 
                        autocomplete="description"
                        class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                        placeholder="Enter list's description"
                        rows="5" cols="20"
                    >
                    </textarea>
                </div>
                @error('name')
                    <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-10 items-center ">
                <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                    Public
                </label>
                
                <input type="checkbox" id="is_public" name="is_public" checked />
           
            </div>

            <button type="submit">
                <p class="text-white text-2xl">Create List </p>
            </button>

        </form>
    </div>
@endsection