@extends('layouts.app')

@section('content')
    <div class="h-64 grid grid-cols-4 mx-4 p-16">
      
            @forelse($lists as $list)
            <div class="relative flex flex-col my-6 bg-white shadow-sm border border-slate-200 rounded-lg w-96">
                <a href="{{ route('lists.show', $list) }}">
                    <div class="p-4">
                    <h5 class="mb-2 text-slate-800 text-xl font-semibold">
                    {{ $list->name }}
                    </h5>
                    <h5 class="mb-2 text-slate-800 text-md font-semibold"> 
                        {{ $list->user->name }}
                    </h5>
                    <p class="text-slate-600 leading-normal font-light">
                        {{ $list->description }}
                    </p>
                </div>
                </a>
                
            </div>
            @empty
                <p>No lists found</p>

            @endforelse


    </div>
@endsection