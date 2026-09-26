<!DOCTYPE html>
<html lang="en-IN">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-init')
    @include('partials.fonts')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $siteName = config('app.name', 'DentaSaaS');
        $cleanTitle = isset($title) ? \Illuminate\Support\Str::squish((string) $title) : null;
        $pageTitle = $cleanTitle
            ? (str_contains($cleanTitle, $siteName) ? $cleanTitle : $cleanTitle.' - '.$siteName)
            : $siteName.' - Dental Clinic Management Software for India';
        $pageDescription = \Illuminate\Support\Str::squish((string) ($metaDescription ?? 'DentaSaaS is dental clinic management software for India: appointments, patient records, GST-ready invoices, prescriptions and treatment plans in one place. Start free.'));
        $canonicalUrl = \App\Support\Seo::canonical();
        $robotsContent = isset($robots) ? (string) $robots : (\App\Support\Seo::indexable() ? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' : 'noindex, nofollow');
        $ogImageUrl = isset($ogImage) ? (string) $ogImage : \App\Support\Seo::ogImage();
        $ogTypeValue = isset($ogType) ? (string) $ogType : 'website';
    @endphp
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="robots" content="{{ $robotsContent }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="en-IN" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">
    <meta name="theme-color" content="#0b1e3d">

    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $ogTypeValue }}">
    <meta property="og:locale" content="en_IN">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImageUrl }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $siteName }} - dental clinic management software">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $ogImageUrl }}">

    @if (config('dentasaas.seo.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('dentasaas.seo.google_site_verification') }}">
    @endif
    @if (config('dentasaas.seo.bing_site_verification'))
        <meta name="msvalidate.01" content="{{ config('dentasaas.seo.bing_site_verification') }}">
    @endif

    @stack('meta')

    {!! \App\Support\Seo::jsonLd([
        '@graph' => [
            array_filter([
                '@type' => 'Organization',
                '@id' => \App\Support\Seo::url('/#organization'),
                'name' => $siteName,
                'url' => \App\Support\Seo::url('/'),
                'logo' => \App\Support\Seo::url('android-chrome-512x512.png'),
                'description' => 'Dental clinic management software for clinics in India.',
                'areaServed' => 'IN',
                'email' => \App\Support\Seo::business('email'),
                'contactPoint' => [array_filter([
                    '@type' => 'ContactPoint',
                    'contactType' => 'customer support',
                    'telephone' => \App\Support\Seo::business('phone'),
                    'email' => \App\Support\Seo::business('email'),
                    'areaServed' => 'IN',
                    'availableLanguage' => ['English', 'Hindi'],
                ])],
            ]),
            [
                '@type' => 'WebSite',
                '@id' => \App\Support\Seo::url('/#website'),
                'url' => \App\Support\Seo::url('/'),
                'name' => $siteName,
                'inLanguage' => 'en-IN',
                'publisher' => ['@id' => \App\Support\Seo::url('/#organization')],
            ],
        ],
    ]) !!}

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if (config('dentasaas.seo.ga4_measurement_id') && \App\Support\Seo::indexable())
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('dentasaas.seo.ga4_measurement_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ config('dentasaas.seo.ga4_measurement_id') }}', { anonymize_ip: true });
        </script>
    @endif
</head>

<body class="font-sans antialiased bg-white text-gray-900" x-data="{ scrolled: false, mobileNav: false }"
    @scroll.window="scrolled = window.scrollY > 20">

    <nav class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 transition-shadow duration-300"
        :class="scrolled ? 'shadow-sm' : ''">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-semibold text-lg text-gray-900">
                <i class="fa-solid fa-tooth" style="color:#465fff;"></i>
                <span>{{ config('app.name', 'DentaSaaS') }}</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                <a href="{{ route('features') }}" class="hover:text-indigo-500">Features</a>
                <a href="{{ route('pricing') }}" class="hover:text-indigo-500">Pricing</a>
                <a href="{{ route('blog.index') }}" class="hover:text-indigo-500">Blog</a>
                <a href="{{ route('faq') }}" class="hover:text-indigo-500">FAQ</a>
                <a href="{{ route('contact') }}" class="hover:text-indigo-500">Contact</a>
            </div>

            <div class="hidden md:flex items-center gap-3">
                <x-theme-toggle />
                @auth
                    <a href="{{ Auth::user()->role === 'superadmin' ? route('admin.dashboard') : route('dashboard') }}"
                        class="text-sm font-medium px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-100">
                        Sign In
                    </a>
                    <a href="https://wa.me/919960457501" target="_blank" rel="noopener"
                        class="inline-flex items-center gap-2 text-sm font-medium bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp Us <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                @endauth
            </div>

            <div class="flex items-center gap-3 md:hidden">
                <x-theme-toggle />
                <button @click="mobileNav = ! mobileNav" class="text-gray-700" aria-label="Menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileNav" x-cloak x-transition class="md:hidden bg-white border-t border-gray-100 shadow-lg">
            <div class="px-4 py-4 space-y-3 text-sm font-medium text-gray-700">
                <a href="{{ route('features') }}" class="block">Features</a>
                <a href="{{ route('pricing') }}" class="block">Pricing</a>
                <a href="{{ route('blog.index') }}" class="block">Blog</a>
                <a href="{{ route('faq') }}" class="block">FAQ</a>
                <a href="{{ route('about') }}" class="block">About</a>
                <a href="{{ route('contact') }}" class="block">Contact</a>
                <hr>
                @auth
                    <a href="{{ Auth::user()->role === 'superadmin' ? route('admin.dashboard') : route('dashboard') }}"
                        class="block">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block">Sign In</a>
                    <a href="https://wa.me/919960457501" target="_blank" rel="noopener"
                        class="block text-green-600">WhatsApp Us</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <style>
        html { scroll-behavior: smooth; }
        [id="features"], [id="how-it-works"], [id="contact"] { scroll-margin-top: 4.5rem; }

        @keyframes footerGlowDrift1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-40px, 25px) scale(1.15); }
        }
        @keyframes footerGlowDrift2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(35px, -20px) scale(1.1); }
        }
        @keyframes footerBeamSweep {
            0% { left: -12rem; }
            100% { left: 100%; }
        }
        @keyframes footerToothRise {
            0% { transform: translateY(0) rotate(0deg); opacity: 0; }
            15% { opacity: var(--ft-o, 0.12); }
            85% { opacity: var(--ft-o, 0.12); }
            100% { transform: translateY(-220px) rotate(-16deg); opacity: 0; }
        }
        @keyframes tabFabRing {
            0% { transform: scale(1); opacity: 0.75; }
            100% { transform: scale(1.55); opacity: 0; }
        }

        .footer-glow { position: absolute; border-radius: 9999px; filter: blur(70px); pointer-events: none; }
        .footer-glow-1 { top: -8rem; left: 8%; width: 22rem; height: 22rem; background: #465fff; opacity: 0.2; animation: footerGlowDrift1 20s ease-in-out infinite; }
        .footer-glow-2 { bottom: -10rem; right: 10%; width: 26rem; height: 26rem; background: #7c9bff; opacity: 0.16; animation: footerGlowDrift2 24s ease-in-out infinite; }

        .footer-grid { position: absolute; inset: 0; pointer-events: none; opacity: 0.55;
            background-image: linear-gradient(rgba(255,255,255,0.045) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.045) 1px, transparent 1px);
            background-size: 44px 44px;
            -webkit-mask-image: linear-gradient(to bottom, #000, transparent 75%); mask-image: linear-gradient(to bottom, #000, transparent 75%); }

        .footer-beam { position: absolute; top: 0; left: 0; right: 0; height: 1px; pointer-events: none;
            background: linear-gradient(90deg, transparent, #465fff 25%, #7c9bff 50%, #465fff 75%, transparent); }
        .footer-beam::before { content: ""; position: absolute; top: -1px; left: -12rem; width: 12rem; height: 3px; filter: blur(1px);
            background: linear-gradient(90deg, transparent, #dbe3ff, transparent); animation: footerBeamSweep 7s linear infinite; }
        .footer-beam::after { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 9rem;
            background: radial-gradient(ellipse 60% 100% at 50% 0%, rgba(70,95,255,0.32), transparent 70%); }

        .footer-tooth { position: absolute; bottom: 7rem; color: #fff; pointer-events: none; animation: footerToothRise linear infinite; }

        .footer-logo-icon { display: inline-flex; align-items: center; justify-content: center; width: 2.25rem; height: 2.25rem; border-radius: 0.65rem;
            background: linear-gradient(135deg, rgba(70,95,255,0.4), rgba(124,155,255,0.12));
            border: 1px solid rgba(124,155,255,0.35); box-shadow: 0 0 22px rgba(70,95,255,0.45); }

        .footer-heading { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; color: #fff;
            font-family: ui-monospace, "SFMono-Regular", Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.14em; text-transform: uppercase; }
        .footer-heading::before { content: ""; width: 6px; height: 6px; border-radius: 9999px; background: #465fff; box-shadow: 0 0 10px #465fff; }

        .footer-link { position: relative; display: inline-block; transition: transform 0.2s ease, color 0.2s ease; }
        .footer-link::before { content: "\203A"; position: absolute; left: -0.9rem; top: 0; opacity: 0; color: #7c9bff; transition: opacity 0.2s ease; }
        .footer-link:hover { color: #fff; transform: translateX(0.35rem); }
        .footer-link:hover::before { opacity: 1; }

        .footer-chip { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.45rem 0.85rem; border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.14); background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.82); font-size: 0.85rem; text-decoration: none;
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease; -webkit-backdrop-filter: blur(6px); backdrop-filter: blur(6px); }
        .footer-chip i { color: #7c9bff; }
        .footer-chip-wa i { color: #4ade80; }
        a.footer-chip:hover { background: rgba(70,95,255,0.22); border-color: rgba(124,155,255,0.55); color: #fff; transform: translateY(-2px); }

        .footer-cta { display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 1.25rem; padding: 0.6rem 1.1rem; border-radius: 0.6rem;
            background: linear-gradient(135deg, #465fff, #6f86ff); color: #fff; font-size: 0.875rem; font-weight: 500; text-decoration: none;
            box-shadow: 0 10px 24px -8px rgba(70,95,255,0.7); transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .footer-cta:hover { transform: translateY(-2px); box-shadow: 0 14px 30px -8px rgba(70,95,255,0.9); }

        .footer-wordmark { position: relative; text-align: center; font-weight: 800; line-height: 0.95; letter-spacing: -0.045em;
            font-size: clamp(3.25rem, 15.5vw, 13rem); white-space: nowrap; user-select: none; pointer-events: none; padding-top: 0.5rem;
            background: linear-gradient(180deg, rgba(124,155,255,0.45) 0%, rgba(70,95,255,0.1) 65%, transparent 100%);
            -webkit-background-clip: text; background-clip: text; color: transparent; -webkit-text-fill-color: transparent; }

        .mobile-tabbar { position: fixed; left: 0; right: 0; bottom: 0; z-index: 60; display: flex; align-items: flex-end; justify-content: space-around;
            padding: 0.5rem 0.5rem calc(0.5rem + env(safe-area-inset-bottom));
            background: rgba(11,30,61,0.9); -webkit-backdrop-filter: blur(14px); backdrop-filter: blur(14px);
            border-top: 1px solid rgba(255,255,255,0.09); box-shadow: 0 -10px 30px -12px rgba(70,95,255,0.4); }
        .mobile-tabbar .tab { position: relative; flex: 1; display: flex; flex-direction: column; align-items: center; gap: 0.2rem; padding: 0.35rem 0;
            font-size: 0.68rem; font-weight: 500; color: rgba(255,255,255,0.55); text-decoration: none; transition: color 0.2s ease; }
        .mobile-tabbar .tab i { font-size: 1.1rem; }
        .mobile-tabbar .tab.is-active { color: #fff; }
        .mobile-tabbar .tab.is-active::before { content: ""; position: absolute; top: -0.5rem; width: 1.75rem; height: 3px; border-radius: 0 0 4px 4px;
            background: linear-gradient(90deg, #465fff, #7c9bff); box-shadow: 0 4px 12px rgba(70,95,255,0.8); }
        .mobile-tabbar .tab-center { margin-top: -1.7rem; color: rgba(255,255,255,0.75); }
        .mobile-tabbar .tab-fab { position: relative; display: flex; align-items: center; justify-content: center; width: 3.4rem; height: 3.4rem; border-radius: 9999px;
            background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff; box-shadow: 0 8px 20px -4px rgba(34,197,94,0.6), 0 0 0 4px #0b1e3d; }
        .mobile-tabbar .tab-fab i { font-size: 1.65rem; }
        .mobile-tabbar .tab-fab::after { content: ""; position: absolute; inset: 0; border-radius: 9999px; border: 2px solid rgba(34,197,94,0.6); animation: tabFabRing 2.4s ease-out infinite; }

        @media (max-width: 767px) {
            .footer-safe { padding-bottom: calc(4.75rem + env(safe-area-inset-bottom)); }
        }
        @media (min-width: 768px) {
            .mobile-tabbar { display: none; }
        }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .footer-glow, .footer-beam::before, .footer-tooth, .mobile-tabbar .tab-fab::after { animation: none !important; }
        }
    </style>
    <footer style="background-color:#0b1e3d;" class="footer-safe relative overflow-hidden text-white/70">
        <div class="footer-grid"></div>
        <div class="footer-glow footer-glow-1"></div>
        <div class="footer-glow footer-glow-2"></div>
        <div class="footer-beam"></div>

        <i class="footer-tooth fa-solid fa-tooth" aria-hidden="true" style="left:6%; font-size:1.5rem; --ft-o:0.12; animation-duration:18s; animation-delay:0s;"></i>
        <i class="footer-tooth fa-solid fa-tooth" aria-hidden="true" style="left:48%; font-size:1rem; --ft-o:0.1; animation-duration:14s; animation-delay:5s;"></i>
        <i class="footer-tooth fa-solid fa-tooth" aria-hidden="true" style="right:8%; font-size:2rem; --ft-o:0.09; animation-duration:22s; animation-delay:9s;"></i>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-16 grid grid-cols-2 lg:grid-cols-6 gap-x-6 gap-y-10 lg:gap-10">
            <div class="col-span-2">
                <div class="flex items-center gap-3 font-semibold text-lg text-white mb-4">
                    <span class="footer-logo-icon"><i class="fa-solid fa-tooth" style="color:#9db3ff;"></i></span>
                    <span>{{ config('app.name', 'DentaSaaS') }}</span>
                </div>
                <p class="text-sm max-w-xs">Dental clinic management software for India: appointments, patient records, GST-ready invoices, prescriptions and treatment plans in one place.
                </p>
                @guest
                    <a href="{{ route('request-access') }}" class="footer-cta">
                        Request Free Access <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                @endguest
                <div class="mt-5 flex flex-wrap gap-3 text-sm">
                    <a href="{{ \App\Support\Seo::whatsappUrl() }}" target="_blank" rel="noopener" class="footer-chip footer-chip-wa">
                        <i class="fa-brands fa-whatsapp"></i> {{ \App\Support\Seo::business('phone') }}
                    </a>
                    @if (\App\Support\Seo::business('email'))
                        <a href="mailto:{{ \App\Support\Seo::business('email') }}" class="footer-chip">
                            <i class="fa-regular fa-envelope"></i> {{ \App\Support\Seo::business('email') }}
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <h4 class="footer-heading">Product</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('features') }}" class="footer-link">Features</a></li>
                    <li><a href="{{ route('features.show', 'appointment-scheduling') }}" class="footer-link">Appointments</a></li>
                    <li><a href="{{ route('features.show', 'dental-billing-invoicing') }}" class="footer-link">Billing &amp; invoices</a></li>
                    <li><a href="{{ route('pricing') }}" class="footer-link">Pricing</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Resources</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('blog.index') }}" class="footer-link">Blog</a></li>
                    <li><a href="{{ route('faq') }}" class="footer-link">FAQ</a></li>
                    <li><a href="{{ route('home') }}#how-it-works" class="footer-link">How it works</a></li>
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Company</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('about') }}" class="footer-link">About</a></li>
                    <li><a href="{{ route('contact') }}" class="footer-link">Contact</a></li>
                    @auth
                        <li><a href="{{ Auth::user()->role === 'superadmin' ? route('admin.dashboard') : route('dashboard') }}"
                                class="footer-link">Dashboard</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="footer-link">Sign in</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="footer-heading">Legal</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('privacy') }}" class="footer-link">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="footer-link">Terms of Service</a></li>
                    <li><a href="{{ route('refund') }}" class="footer-link">Refund Policy</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-wordmark" aria-hidden="true">{{ config('app.name', 'DentaSaaS') }}</div>

        <div class="relative border-t border-white/10">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-sm flex flex-col sm:flex-row justify-between gap-2">
                <span>&copy; {{ now()->year }} {{ config('app.name', 'DentaSaaS') }}. All rights reserved.</span>
                <span>आपकी मुस्कान, हमारी ज़िम्मेदारी</span>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp button (desktop only; mobile uses the tab bar) -->
    <a href="https://wa.me/919960457501" target="_blank" rel="noopener" aria-label="Chat on WhatsApp"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-green-500 hover:bg-green-600 text-white hidden md:flex items-center justify-center shadow-lg text-2xl">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <!-- Mobile tab bar -->
    <nav class="mobile-tabbar" aria-label="Mobile navigation">
        <a href="{{ route('home') }}" class="tab {{ request()->routeIs('home') ? 'is-active' : '' }}">
            <i class="fa-solid fa-house"></i><span>Home</span>
        </a>
        <a href="{{ route('features') }}" class="tab {{ request()->routeIs('features*') ? 'is-active' : '' }}">
            <i class="fa-solid fa-grip"></i><span>Features</span>
        </a>
        <a href="https://wa.me/919960457501" target="_blank" rel="noopener" class="tab tab-center" aria-label="Chat on WhatsApp">
            <span class="tab-fab"><i class="fa-brands fa-whatsapp"></i></span><span>Chat</span>
        </a>
        <a href="{{ route('pricing') }}" class="tab {{ request()->routeIs('pricing') ? 'is-active' : '' }}">
            <i class="fa-solid fa-tags"></i><span>Pricing</span>
        </a>
        @auth
            <a href="{{ Auth::user()->role === 'superadmin' ? route('admin.dashboard') : route('dashboard') }}" class="tab">
                <i class="fa-solid fa-gauge"></i><span>Dashboard</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="tab {{ request()->routeIs('login') ? 'is-active' : '' }}">
                <i class="fa-solid fa-right-to-bracket"></i><span>Sign In</span>
            </a>
        @endauth
    </nav>

    @stack('scripts')
</body>

</html>
