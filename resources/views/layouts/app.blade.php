<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('title', 'Lele') }}</title>

    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>

<body class="bg-[#F5F6FA] flex">
    @include('layouts.sidebar')

    <main id="mainContent" class="flex-1 transition-all duration-300 ml-0 md:ml-64">
        @yield('content')
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
