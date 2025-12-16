<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <title>Movie Platform</title>
  {!! ToastMagic::styles() !!}
 
   <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
  @livewireStyles
</head>
<body theme="dark" class="font-sans antialiased flex flex-col min-h-screen">
  @include('header')

  <main class="px-6 sm:px-4  py-8 flex-1 bg-gradient-to-b from-neutral-900 to-neutral-800">
    @yield('content')
  </main>

  @include('footer')
  
  @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])
    @stack('scripts')
    @livewireScripts
    {!! ToastMagic::scripts() !!}
</body>
</html>
