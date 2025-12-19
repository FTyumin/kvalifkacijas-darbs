@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen text-white overflow-x-hidden">
   
    
    <div class="relative z-10 min-h-screen flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center p-12 relative overflow-hidden">
            <!-- Background Pattern -->
              
            <img src="{{ asset('images/unsplash.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover will-change-transform"
                alt="Movie theater"
            />

            <div class="absolute inset-0 bg-gradient-to-br from-black/80 via-black/70 to-purple-900/50"></div>

            <!-- Dark Overlay for readability -->
        <!-- <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-purple-900/50 z-0"></div> -->
            
            <div class="relative z-10 text-center">
                <!-- Logo -->
                <div class="flex items-center justify-center gap-3 mb-8">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="yellow" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold">Movie Platform</h1>
                </div>
                
                <h2 class="text-3xl font-bold mb-4">Welcome Back!</h2>
                <p class="text-xl text-white/80 mb-8 max-w-md">
                    Sign in to continue your movie journey and discover new favorites.
                </p>
                
                <!-- Features -->
                <div class="space-y-4 text-left max-w-md">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-400/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span>Access your personal watchlist</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-400/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span>Rate and review movies</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-400/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span>Get personalized recommendations</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
            <div class="w-full max-w-md">
                
                <!-- Header -->
                <div class="text-center mb-8 lg:hidden">
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-white">Movie Platform</span>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
                    <p class="text-gray-400">Sign in to your account</p>
                </div>

                <!-- Login Form Card -->
                <div class="bg-gray-800/50 border border-gray-700 rounded-2xl p-8 shadow-2xl">
                    <h3 class="text-2xl font-bold text-white mb-6 text-center hidden lg:block">Sign In</h3>
                    
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-lg">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-red-400 font-medium">Please fix the following errors:</span>
                            </div>
                            <ul class="list-disc list-inside text-red-300 text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                    </svg>
                                </div>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    required 
                                    autocomplete="email" 
                                    autofocus
                                    class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="Enter your email"
                                >
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    required 
                                    autocomplete="current-password"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('password') border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="Enter your password"
                                >
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="remember" 
                                    name="remember"
                                    {{ old('remember') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2"
                                >
                                <label for="remember" class="ml-2 text-sm text-gray-300">
                                    Remember me
                                </label>
                            </div>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="w-full  text-white font-semibold py-3 px-6 rounded-lg bg-yellow-500 hover:bg-yellow-400"
                        >
                            Sign In
                        </button>

                    </form>
                </div>

                <!-- Sign Up Link -->
                <p class="mt-8 text-center text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-medium transition-colors">
                        Sign up here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection