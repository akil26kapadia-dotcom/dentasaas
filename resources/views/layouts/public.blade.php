<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title.' - '.config('app.name', 'DentaSaaS') : config('app.name', 'DentaSaaS').' - Dental Clinic Management Software' }}</title>
        <meta name="description" content="{{ $metaDescription ?? 'DentaSaaS — all-in-one dental clinic management software for appointments, patients, invoices, prescriptions and treatment plans.' }}">
        <meta name="keywords" content="{{ $metaKeywords ?? 'dental clinic software, dental practice management, clinic appointment software, dental SaaS India' }}">
        <link rel="canonical" href="{{ url()->current() }}">

        <meta property="og:title" content="{{ isset($title) ? $title.' - '.config('app.name', 'DentaSaaS') : config('app.name', 'DentaSaaS') }}">
        <meta property="og:description" content="{{ $metaDescription ?? 'All-in-one dental clinic management software — appointments, patients, invoices, prescriptions and treatment plans.' }}">
        <meta property="og:image" content="{{ $ogImage ?? asset('favicon.ico') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ isset($title) ? $title.' - '.config('app.name', 'DentaSaaS') : config('app.name', 'DentaSaaS') }}">
        <meta name="twitter:description" content="{{ $metaDescription ?? 'All-in-one dental clinic management software for modern practices.' }}">
        <meta name="twitter:image" content="{{ $ogImage ?? asset('favicon.ico') }}">

        @stack('meta')

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100" x-data="{ scrolled: false, mobileNav: false }" @scroll.window="scrolled = window.scrollY > 20">

        <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 transition-shadow duration-300"
             :class="scrolled ? 'shadow-sm' : ''">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-lg text-gray-900">
                    <i class="fa-solid fa-tooth" style="color:#465fff;"></i>
                    <span>{{ config('app.name', 'DentaSaaS') }}</span>
                </a>

                <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                    <a href="{{ route('home') }}#features" class="hover:text-indigo-500">Features</a>
                    <a href="{{ route('home') }}#how-it-works" class="hover:text-indigo-500">How It Works</a>
                    <a href="{{ route('pricing') }}" class="hover:text-indigo-500">Pricing</a>
                    <a href="{{ route('home') }}#contact" class="hover:text-indigo-500">Contact</a>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ Auth::user()->role === 'superadmin' ? route('admin.dashboard') : route('dashboard') }}"
                           class="text-sm font-medium px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                            Sign In
                        </a>
                        <a href="https://wa.me/918488055253" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 text-sm font-medium bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp Us <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    @endauth
                </div>

                <button @click="mobileNav = ! mobileNav" class="md:hidden text-gray-700">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>

            <div x-show="mobileNav" x-cloak x-transition class="md:hidden bg-white border-t border-gray-100 shadow-lg">
                <div class="px-4 py-4 space-y-3 text-sm font-medium text-gray-700">
                    <a href="{{ route('home') }}#features" class="block">Features</a>
                    <a href="{{ route('home') }}#how-it-works" class="block">How It Works</a>
                    <a href="{{ route('pricing') }}" class="block">Pricing</a>
                    <a href="{{ route('home') }}#contact" class="block">Contact</a>
                    <hr>
                    @auth
                        <a href="{{ Auth::user()->role === 'superadmin' ? route('admin.dashboard') : route('dashboard') }}" class="block">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block">Sign In</a>
                        <a href="https://wa.me/918488055253" target="_blank" rel="noopener" class="block text-green-600">WhatsApp Us</a>
                    @endauth
                </div>
            </div>
        </nav>

        <main>
            {{ $slot }}
        </main>

        <footer style="background-color:#0b1e3d;" class="text-white/70 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div>
                    <div class="flex items-center gap-2 font-semibold text-lg text-white mb-3">
                        <i class="fa-solid fa-tooth" style="color:#4f6df5;"></i>
                        <span>{{ config('app.name', 'DentaSaaS') }}</span>
                    </div>
                    <p class="text-sm">All-in-one dental clinic management software built for modern practices across India.</p>
                </div>

                <div>
                    <h4 class="text-white font-medium mb-3">Product</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}#features" class="hover:text-white">Features</a></li>
                        <li><a href="{{ route('home') }}#how-it-works" class="hover:text-white">How It Works</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white">Pricing</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-medium mb-3">Support</h4>
                    <ul class="space-y-2 text-sm">
                        @auth
                            <li><a href="{{ Auth::user()->role === 'superadmin' ? route('admin.dashboard') : route('dashboard') }}" class="hover:text-white">Dashboard</a></li>
                        @else
                            <li><a href="{{ route('request-access') }}" class="hover:text-white">Request Access</a></li>
                            <li><a href="{{ route('login') }}" class="hover:text-white">Sign In</a></li>
                        @endauth
                        <li><a href="https://wa.me/918488055253" target="_blank" rel="noopener" class="hover:text-white">WhatsApp Support</a></li>
                    </ul>
                </div>

                <div id="contact">
                    <h4 class="text-white font-medium mb-3">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li><i class="fa-brands fa-whatsapp"></i> <a href="https://wa.me/918488055253" target="_blank" rel="noopener" class="hover:text-white">+91 84880 55253</a></li>
                        <li><i class="fa-solid fa-globe"></i> clinic.designflowstudio.space</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm flex flex-col sm:flex-row justify-between gap-2">
                    <span>&copy; {{ now()->year }} {{ config('app.name', 'DentaSaaS') }}. All rights reserved.</span>
                    <span>आपकी मुस्कान, हमारी ज़िम्मेदारी</span>
                </div>
            </div>
        </footer>

        <!-- Floating WhatsApp button -->
        <a href="https://wa.me/918488055253" target="_blank" rel="noopener"
           class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center shadow-lg text-2xl">
            <i class="fa-brands fa-whatsapp"></i>
        </a>

        @stack('scripts')
    </body>
</html>
