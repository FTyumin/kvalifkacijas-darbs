<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  
  <title>Movie Platform</title>
  
 
   <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
  @livewireStyles
</head>
<body class="font-sans antialiased flex flex-col min-h-screen">
  @include('header')

  <main class="bg-black px-6 sm:px-4 lg:px-28 py-8 flex-1">
    @yield('content')
  </main>

  @include('footer')
  
  @vite([
    'resources/css/app.css',
    'resources/js/app.js',
    ])
    @stack('scripts')
    @livewireScripts
</body>
</html>
