<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta charset="UTF-8" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/site.webmanifest') }}">

    <script type="module" crossorigin src="{{ asset('assets/js/main.js') }}"></script>
    <link rel="stylesheet" crossorigin href="{{ asset('assets/css/main.css') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <x-header-layout />
    <x-sidebar-layout />

    @yield('content')



</body>

</html>

<link rel="stylesheet" href="{{ asset('assets/css/plugin/pikaday.css') }}">
<script src="{{ asset('assets/js/plugin/pikaday.js') }}"></script>


<script src="{{ asset('assets/js/core/app-script.js') }}"></script>
