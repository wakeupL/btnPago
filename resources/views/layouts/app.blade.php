<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    @livewireStyles
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">

    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="py-4 text-center text-xs text-gray-400 dark:text-gray-600">
        Desarrollado con &hearts; por <a href="https://wakedev.cl" target="_blank" class="hover:text-gray-600 dark:hover:text-gray-400 transition-colors">wakedev.cl</a>
    </footer>

</body>
@livewireScripts
</html>
