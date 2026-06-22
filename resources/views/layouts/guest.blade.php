<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900">

    <div class="flex-1 flex flex-col items-center justify-center pt-6 sm:pt-0 px-4">
        <div>
            <a href="/">
                <img src="{{ appLogo() }}" alt="{{ config('app.name') }}" width="250">
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

    <footer class="py-4 text-center text-xs text-gray-400 dark:text-gray-600">
        Desarrollado con &hearts; por <a href="https://wakedev.cl" target="_blank" class="hover:text-gray-600 dark:hover:text-gray-400 transition-colors">wakedev.cl</a>
    </footer>

</body>

</html>
