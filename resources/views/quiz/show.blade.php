@extends('layouts.app')

@section('title', 'Preference quiz')

@section('content')
<div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
    <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-600/20 to-purple-600/20 rounded-full blur-3xl animate-pulse"></div>
    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-br from-purple-600/20 to-pink-600/20 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    <div class="absolute top-1/3 left-1/2 transform -translate-x-1/2 w-96 h-96 bg-gradient-to-br from-pink-600/10 to-blue-600/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 4s;"></div>
</div>

<div class="relative z-10 min-h-screen flex items-center justify-center py-12 px-6 bg-black">
    <div class="w-full max-w-4xl">
        
        {{-- Header Section --}}
        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Tell us about your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">movie preferences</span>
            </h1>
            <p class="text-xl text-gray-400 max-w-2xl mx-auto">
                Help us personalize your experience by selecting your favorite genres. We'll recommend movies you'll love!
            </p>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
        <div class="mb-8 p-4 bg-green-500/10 border border-green-500/50 rounded-xl backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="text-green-400 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-8 p-4 bg-red-500/10 border border-red-500/50 rounded-xl backdrop-blur-sm">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-red-600/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <span class="text-red-400 font-medium block mb-2">Please fix the following errors:</span>
                    <ul class="list-disc list-inside text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-2xl p-8 md:p-10 hover:bg-gray-800/60 transition-colors">
            <form method="POST" action="{{ route('quiz.store') }}">
                @csrf

                {{-- Instructions --}}
                <div class="mb-8 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-white text-sm font-medium mb-1">Pro Tip</p>
                            <p class="text-white text-sm">Select at least 3 genres to get better recommendations. You can always update your preferences later!</p>
                        </div>
                    </div>
                </div>

                {{-- Genre Selection Title --}}
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-white mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                        Select Your Favorite Genres
                    </h3>
                    <p class="text-gray-400 text-sm">Choose all the genres you enjoy watching</p>
                </div>

                {{-- Genre Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mb-8">
                    @foreach($genres as $genre)
                    <label class="relative cursor-pointer group ">
                        <input 
                            type="checkbox" 
                            name="genres[]" 
                            value="{{ $genre->id }}" 
                            class="peer"
                        >
                        
                        {{-- Genre Card --}}
                        <div class="h-full p-4 x rounded-xl transition-all duration-300 
                                    hover:border-gray-500 hover:bg-gray-700/50
                                    peer-checked:border-blue-500 peer-checked:bg-blue-500/10">
                            
                            {{-- Genre Icon (based on genre name) --}}
                            <div class="flex flex-col items-center text-center">
                                <div class="w-12 h-12 mb-3 rounded-lg flex items-center justify-center opacity-70 peer-checked:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                </div>
                                
                                <span class="text-sm font-semibold text-gray-300 peer-checked:text-white transition-colors">
                                    {{ $genre->name }}
                                </span>
                            </div>

                            {{-- Checkmark --}}
                            <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity duration-150 pointer-events-none" aria-hidden="true">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>

                {{-- Selected Count Display --}}
                <div class="mb-6 p-4 bg-gray-700/30 border border-gray-600 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-purple-600/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-300">Selected Genres</p>
                                <p class="text-xs text-gray-500">Minimum 3 recommended</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-white" id="selectedCount">0</p>
                            <p class="text-xs text-gray-500">genres</p>
                        </div>
                    </div>
                </div>

                @error('genres')
                <p class="mb-4 text-sm text-red-400">{{ $message }}</p>
                @enderror

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-700">
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold py-4 px-8 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-gray-800 transform hover:scale-105 inline-flex items-center justify-center gap-3 text-lg"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save My Preferences
                    </button>
                    
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="sm:w-auto bg-gray-700/50 hover:bg-gray-700 text-white font-semibold py-4 px-8 rounded-xl transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:ring-offset-gray-800 inline-flex items-center justify-center gap-2"
                    >
                        Skip for Now
                    </a>
                </div>
            </form>
        </div>

        {{-- Additional Info --}}
        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                You can update your preferences anytime from your 
                <a href="" class="text-blue-400 hover:text-blue-300 transition-colors">profile settings</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // console.log('cyka blyat')
    const checkboxes = document.querySelectorAll('input[name="genres[]"]');
    const countDisplay = document.getElementById('selectedCount');
    
    function updateCount() {
        const checkedCount = document.querySelectorAll('input[name="genres[]"]:checked').length;
        countDisplay.textContent = checkedCount;
        
        // Add animation
        countDisplay.classList.add('scale-125');
        setTimeout(() => {
            countDisplay.classList.remove('scale-125');
        }, 200);
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCount);
    });
    console.log(checkboxes);
    // checkboxes.forEach(checkbox => {
    //     checkbox.addEventListener('change', () => {
    //         // Find the parent label and then the visual card div
    //         const card = checkbox.parentElement.querySelector('div');
            
    //         if (checkbox.checked) {
    //             card.classList.add('bg-green-500/20', 'border-green-500');
    //             card.classList.remove('border-blue-500', 'bg-blue-500/10');
    //         } else {
    //             card.classList.remove('bg-green-500/20', 'border-green-500');
    //         }
    //     });
    // });
    
    // Initial count
    updateCount();
});
</script>
@endpush

@endsection