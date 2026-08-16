<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DentaSaaS') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">

        <nav class="border-b border-gray-100 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2 font-semibold text-lg text-indigo-600">
                    <i class="fa-solid fa-tooth"></i>
                    <span>{{ config('app.name', 'DentaSaaS') }}</span>
                </a>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 dark:text-gray-300">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 dark:text-gray-300">Log in</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Get Started</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        <footer class="border-t border-gray-100 dark:border-gray-700 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-sm text-gray-500 dark:text-gray-400 flex flex-col sm:flex-row justify-between gap-2">
                <span>&copy; {{ now()->year }} {{ config('app.name', 'DentaSaaS') }}. All rights reserved.</span>
                <span>clinic.designflowstudio.space</span>
            </div>
        </footer>

        <!-- Floating WhatsApp button -->
        <a href="https://wa.me/918488055253" target="_blank" rel="noopener"
           class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center shadow-lg text-2xl">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    </body>
</html>
