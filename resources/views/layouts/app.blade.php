<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    @yield('stylesheets')
</head>

<body class="bg-gray-100">

  <!-- navbar -->
  <nav class="absolute top-0 inset-x-0">
    <div class="navbar w-full md:w-4/5 max-w-6xl mx-auto mt-3">
      @yield('nav')
    </div>
  </nav>
  <!-- /navbar -->

  <div class="bg-white appcontainer mt-12 pt-12 px-8">

    <header class="mb-8 text-3xl text-center text-teal-800">
      @section('title')
        {{ config('app.name') }}
      @show
    </header>

    <main class="mx-auto">
      @yield('content')
    </main>

    <footer class="mt-12 mb-4">
      <p class="text-center text-gray-500 text-xs">
        &#169; 2020 KingStarter GbR
      </p>
    </footer>

  </div>

  @yield('scripts')
</body>

</html>
