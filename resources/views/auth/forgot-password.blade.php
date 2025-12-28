@extends('layouts.app')

@section('content')

    <div class="min-h-screen flex items-center justify-center bg-black px-4">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-amber-500/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.04),transparent_60%)]"></div>
        </div>
        <div class="relative w-full max-w-xl rounded-2xl bg-gray-900/90 border border-gray-800 shadow-2xl p-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-12 w-12 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg class="h-6 w-6 text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.567 3-3.5 3S5 12.657 5 11 6.567 8 8.5 8 12 9.343 12 11zm7 3v-1a4 4 0 00-4-4h-1m2 8a4 4 0 00-4-4H8m9 8a4 4 0 00-4-4h-1m6-3h1a4 4 0 014 4v1"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-white">Reset your password</h1>
                    <p class="text-sm text-gray-400">We will email you a secure reset link.</p>
                </div>
            </div>
            <div class="mb-6 text-sm text-gray-300">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </div>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-white" />
                    <x-text-input id="email" class="block mt-2 w-full bg-gray-800 border border-gray-700 text-white placeholder-gray-500 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/40" type="email" name="email" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <x-primary-button class="bg-amber-500 hover:bg-amber-400 text-gray-900">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
