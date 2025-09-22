@extends('layouts.app')
@section('content')
<section class="relative min-h-[55vh] md:min-h-[45vh] lg:min-h-[40vh] isolate">
  <!-- Image -->
  <img
    src="{{ asset('images/cinema.webp') }}"
    alt="movie theater"
    class="absolute inset-0 h-full w-full object-cover"
  />

  <div class="absolute inset-0 bg-black/50"></div>
  <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-black/60 to-transparent"></div>

  <!-- Content -->
  <div class="relative mx-auto max-w-5xl px-6 py-20 sm:py-28 md:py-36">
    <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-white">
      Honest Reviews. Zero Spoilers.
    </h1>
    <p class="mt-4 max-w-2xl text-white/80 md:text-lg">
      Movies & shows reviewed with care, context, and clarity.
    </p>
    <div class="mt-8 flex gap-3">
      <a href="#latest" class="rounded-2xl bg-white/10 px-5 py-3 text-white backdrop-blur hover:bg-white/20 transition">
        Latest Reviews
      </a>
      <a href="#search" class="rounded-2xl bg-white text-gray-900 px-5 py-3 hover:bg-gray-100 transition">
        Find a Title
      </a>
    </div>
  </div>

  

</section>

<div class="mt-10 mx-10">
    <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl">Biggest movies <span class="text-blue-600 dark:text-blue-500">Right Now</span></h1>

    <div class="grid grid-cols-3 gap-x-3 gap-y-10">
        @for ($i = 0; $i < 6; $i++)
            <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <a href="#">
                    <img class="aspect-[3/2] w-full rounded-t-lg object-cover" src="{{ asset('images/cinema.webp') }}" alt="Movie poster placeholder" />
                </a>
                <div class="p-5">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Movie Name
                        </h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        Movie description
                    </p>
                    <a href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus-visible:ring-blue-800">
                        Read more
                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                        </svg>
                    </a>
                </div>
            </div>   
        @endfor
    </div>

</div>



@endsection