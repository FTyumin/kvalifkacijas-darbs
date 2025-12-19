@extends('layouts.app')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-black px-4">
        <div class="w-full max-w-xl rounded-2xl bg-gray-900 border border-gray-800 shadow-xl p-8">

            <h1 class="text-2xl font-semibold text-white mb-2">
                Reset your password
            </h1>

            <p class="text-sm text-gray-400 mb-6">
                Choose a new password for your account.
            </p>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div>
                    <x-input-label
                        for="email"
                        :value="__('Email address')"
                        class="text-white"
                    />

                    <x-text-input
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email', $request->email)"
                        required
                        autofocus
                        autocomplete="username"
                        class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700
                               text-white placeholder-gray-500
                               focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/50"
                    />

                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('New password')"
                        class="text-white"
                    />

                    <x-text-input id="password" type="password" name="password"
                        required
                        autocomplete="new-password"
                        class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700
                               text-white placeholder-gray-500
                               focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/50"
                    />

                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label
                        for="password_confirmation"
                        :value="__('Confirm password')"
                        class="text-white"
                    />

                    <x-text-input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="mt-1 block w-full rounded-lg bg-gray-900 border border-gray-700
                               text-white placeholder-gray-500
                               focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/50"
                    />

                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    class="w-full mt-4 rounded-lg bg-yellow-500 px-4 py-1 font-medium text-gray-900
                    hover:bg-yellow-400 transition"
                >
                    Reset password
                </button>
            </form>
        </div>
    </div>

@endsection