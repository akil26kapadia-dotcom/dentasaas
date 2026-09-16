<x-public-layout>
    <x-slot name="title">Dental Clinic Management Software</x-slot>

    @push('meta')
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "SoftwareApplication",
            "name": "DentaSaaS",
            "applicationCategory": "BusinessApplication",
            "operatingSystem": "Web",
            "description": "All-in-one dental clinic management software for appointments, patients, invoices, prescriptions and treatment plans.",
            "offers": [
                {"@@type": "Offer", "price": "0", "priceCurrency": "INR", "name": "Free"},
                {"@@type": "Offer", "price": "299", "priceCurrency": "INR", "name": "Basic"},
                {"@@type": "Offer", "price": "799", "priceCurrency": "INR", "name": "Premium"},
                {"@@type": "Offer", "price": "1499", "priceCurrency": "INR", "name": "Deluxe"}
            ]
        }
        </script>
    @endpush

    <!-- Hero -->
    <style>
        @keyframes heroDrift1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(40px, 60px) scale(1.12); }
            66% { transform: translate(-30px, 20px) scale(0.95); }
        }
        @keyframes heroDrift2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-60px, -40px) scale(1.15); }
        }
        @keyframes heroDrift3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(35px, -50px) scale(1.08); }
        }
        @keyframes toothFloat {
            0% { transform: translateY(0) rotate(0deg); opacity: 0; }
            10% { opacity: var(--tooth-opacity, 0.14); }
            90% { opacity: var(--tooth-opacity, 0.14); }
            100% { transform: translateY(-140px) rotate(18deg); opacity: 0; }
        }
        @keyframes gridPulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.65; }
        }
        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-orb { position: absolute; border-radius: 9999px; filter: blur(64px); pointer-events: none; }
        .hero-orb-1 { top: -6rem; right: -6rem; width: 24rem; height: 24rem; background: #465fff; opacity: 0.22; animation: heroDrift1 22s ease-in-out infinite; }
        .hero-orb-2 { bottom: -8rem; left: -6rem; width: 20rem; height: 20rem; background: #7c9bff; opacity: 0.16; animation: heroDrift2 18s ease-in-out infinite; }
        .hero-orb-3 { top: 30%; left: 50%; width: 16rem; height: 16rem; background: #22d3ee; opacity: 0.12; animation: heroDrift3 26s ease-in-out infinite; }
        .hero-grid { animation: gridPulse 6s ease-in-out infinite; }
        .hero-tooth { position: absolute; color: #ffffff; pointer-events: none; animation: toothFloat linear infinite; }
        .hero-fade-up { opacity: 0; animation: heroFadeUp 0.7s ease-out forwards; }
        @media (prefers-reduced-motion: reduce) {
            .hero-orb, .hero-grid, .hero-tooth { animation: none !important; }
            .hero-fade-up { opacity: 1; animation: none !important; transform: none !important; }
        }
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease-out, transform 0.6s ease-out; }
        .reveal.is-visible { opacity: 1; transform: translateY(0); }
        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
        }
        .eyebrow-tag { font-family: ui-monospace, "SFMono-Regular", Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.12em; }
        .node-divider { position: relative; height: 1px; background: linear-gradient(90deg, transparent, rgba(70,95,255,0.25) 12%, rgba(70,95,255,0.25) 88%, transparent); }
        .node-divider span { position: absolute; top: 50%; width: 9px; height: 9px; border-radius: 9999px; border: 1.5px solid #465fff; background: #fff; transform: translateY(-50%); }
        .node-divider span:first-child { left: 6%; }
        .node-divider span:last-child { right: 6%; }
        .banner-panel { position: relative; overflow: hidden; }
        .banner-mock { border-radius: 1rem; background: #fff; box-shadow: 0 20px 45px -20px rgba(15,23,42,0.25); overflow: hidden; }
    </style>

    <!-- Hero -->
    <section class="relative overflow-hidden" style="background-color:#0b1e3d;">
        <div class="hero-grid absolute inset-0 opacity-40"
            style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 44px 44px;">
        </div>

        <!-- Organic swoosh shape, Axle-inspired -->
        <svg class="absolute top-0 right-0 w-[60%] max-w-3xl h-auto opacity-60 pointer-events-none" viewBox="0 0 800 700" preserveAspectRatio="none" aria-hidden="true">
            <defs>
                <linearGradient id="heroSwoosh" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#465fff" stop-opacity="0.5" />
                    <stop offset="100%" stop-color="#7c9bff" stop-opacity="0.12" />
                </linearGradient>
            </defs>
            <path d="M420,0 C560,90 480,230 630,300 C760,360 720,520 800,620 L800,0 Z" fill="url(#heroSwoosh)" />
        </svg>

        <div class="hero-orb hero-orb-1"></div>
        <div class="hero-orb hero-orb-2"></div>
        <div class="hero-orb hero-orb-3"></div>

        <i class="hero-tooth fa-solid fa-tooth" aria-hidden="true" style="left:8%; bottom:-2rem; font-size:1.75rem; --tooth-opacity:0.16; animation-duration:16s; animation-delay:0s;"></i>
        <i class="hero-tooth fa-solid fa-tooth" aria-hidden="true" style="left:22%; bottom:-2rem; font-size:1rem; --tooth-opacity:0.12; animation-duration:12s; animation-delay:3s;"></i>
        <i class="hero-tooth fa-solid fa-tooth" aria-hidden="true" style="right:14%; bottom:-2rem; font-size:2.25rem; --tooth-opacity:0.1; animation-duration:20s; animation-delay:6s;"></i>
        <i class="hero-tooth fa-solid fa-tooth" aria-hidden="true" style="right:30%; bottom:-2rem; font-size:1.25rem; --tooth-opacity:0.14; animation-duration:14s; animation-delay:9s;"></i>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-24">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="text-center lg:text-left">
                    <span class="hero-fade-up eyebrow-tag inline-block text-indigo-300/90 mb-5" style="animation-delay:0s;">[ THE ALL-IN-ONE DENTAL OS ]</span>

                    <h1 class="hero-fade-up text-4xl sm:text-5xl lg:text-[3.4rem] font-extrabold text-white leading-[1.08]" style="animation-delay:0.08s;">
                        Run your clinic
                        <span class="block bg-clip-text text-transparent"
                            style="background-image: linear-gradient(90deg, #4f6df5, #7c9bff);">smarter, not harder.</span>
                    </h1>
                    <p class="hero-fade-up mt-6 text-lg text-white/70 max-w-xl mx-auto lg:mx-0" style="animation-delay:0.18s;">
                        All-in-one dental SaaS — appointments, patients, invoices, prescriptions and treatment plans, built for
                        modern clinics across India.
                    </p>

                    <div class="hero-fade-up mt-10 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4" style="animation-delay:0.3s;">
                        <a href="{{ route('request-access') }}"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white w-full sm:w-auto"
                            style="background-color:#465fff;">
                            Request Free Access
                        </a>
                        <a href="#features"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white border border-white/30 hover:bg-white/10 w-full sm:w-auto">
                            Explore Features <i class="fa-solid fa-arrow-right text-sm"></i>
                        </a>
                    </div>
                </div>

                <!-- Product preview mockup -->
                <div class="hero-fade-up relative" style="animation-delay:0.42s;">
                    <div class="absolute inset-0 -z-10 blur-3xl opacity-30"
                        style="background: radial-gradient(ellipse at center, #465fff, transparent 70%);"></div>

                    <div class="banner-mock text-left">
                        <div class="flex items-center gap-1.5 px-4 py-3 border-b border-gray-100 bg-gray-50">
                            <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                            <span class="ml-3 text-xs text-gray-400">app.dentasaas.in/dashboard</span>
                        </div>

                        <div class="p-4 sm:p-6">
                            <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
                                @foreach ([['icon' => 'fa-users', 'label' => 'Total Patients', 'value' => '248'], ['icon' => 'fa-calendar-check', 'label' => "Today's Appointments", 'value' => '12'], ['icon' => 'fa-indian-rupee-sign', 'label' => 'Monthly Revenue', 'value' => '₹86,400'], ['icon' => 'fa-hourglass-half', 'label' => 'Pending', 'value' => '3']] as $card)
                                    <div class="rounded-xl border border-gray-100 p-3 sm:p-4">
                                        <span
                                            class="flex h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600 text-xs sm:text-sm">
                                            <i class="fa-solid {{ $card['icon'] }}"></i>
                                        </span>
                                        <p class="mt-2 sm:mt-3 text-base sm:text-lg font-bold text-gray-900">
                                            {{ $card['value'] }}</p>
                                        <p class="text-[11px] sm:text-xs text-gray-500">{{ $card['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="rounded-xl border border-gray-100 p-4">
                                <p class="text-xs font-medium text-gray-500 mb-3">Revenue — Last 6 Months</p>
                                <div class="flex items-end gap-3 h-20 sm:h-24">
                                    @foreach ([40, 55, 35, 70, 60, 90] as $h)
                                        <div class="flex-1 rounded-t"
                                            style="height: {{ $h }}%; background-color:#465fff;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="node-divider max-w-7xl mx-auto"><span></span><span></span></div>

    <!-- Trust bar -->
    <section class="bg-gray-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-2 text-sm text-gray-500">
                <span><i class="fa-solid fa-lock"></i> bcrypt Passwords</span>
                <span><i class="fa-solid fa-hospital"></i> Data Isolated</span>
                <span><i class="fa-solid fa-mobile-screen"></i> Mobile-First</span>
                <span><i class="fa-solid fa-language"></i> EN + हिंदी</span>
                <span><i class="fa-solid fa-receipt"></i> GST Invoices</span>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="reveal grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            @foreach ([['value' => '2,500+', 'label' => 'Patients Managed'], ['value' => '₹0', 'label' => 'Setup Cost'], ['value' => '24hr', 'label' => 'Account Setup'], ['value' => '100%', 'label' => 'Data Isolated']] as $stat)
                <div>
                    <p class="text-3xl sm:text-4xl font-bold" style="color:#465fff;">{{ $stat['value'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Everything your clinic needs</h2>
                <p class="text-gray-500 mt-3">One platform for the whole front desk to back office.</p>
            </div>

            <div class="reveal grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([
        ['icon' => 'fa-calendar-check', 'title' => 'Smart Appointments', 'desc' => 'Book, confirm and remind patients over WhatsApp in one click.'],
        ['icon' => 'fa-users', 'title' => 'Patient Records', 'desc' => 'Complete history — appointments, invoices, prescriptions, plans.'],
        ['icon' => 'fa-file-invoice', 'title' => 'GST Invoicing', 'desc' => 'Branded, GST-ready invoices with instant PDF export.'],
        ['icon' => 'fa-tooth', 'title' => 'Services Catalogue', 'desc' => 'Price and manage every treatment your clinic offers.'],
        ['icon' => 'fa-chart-line', 'title' => 'Revenue Analytics', 'desc' => 'Track revenue, appointment trends and top services.'],
        ['icon' => 'fa-user-doctor', 'title' => 'Multi-Doctor', 'desc' => 'Add your whole team with role-based access.'],
        ['icon' => 'fa-mobile-screen', 'title' => 'Mobile-First Design', 'desc' => 'Runs beautifully on the front-desk tablet or your phone.'],
        ['icon' => 'fa-language', 'title' => 'English + Hindi', 'desc' => 'Switch the entire interface between English and हिंदी.'],
        ['icon' => 'fa-shield-halved', 'title' => 'Enterprise Security', 'desc' => 'bcrypt password hashing and fully isolated clinic data.'],
    ] as $feature)
                    <div class="group rounded-2xl border border-gray-200 bg-white border-t-4 border-t-transparent p-6 transition-all hover:-translate-y-1 hover:shadow-lg"
                        style="--hover-color:#465fff;" onmouseover="this.style.borderTopColor='#465fff'"
                        onmouseout="this.style.borderTopColor='transparent'">
                        <span
                            class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-4">
                            <i class="fa-solid {{ $feature['icon'] }}"></i>
                        </span>
                        <h3 class="font-semibold text-gray-900">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-500 mt-2">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="node-divider max-w-7xl mx-auto"><span></span><span></span></div>

    <!-- Feature spotlights, Zentist-inspired pastel banners -->
    <section class="banner-panel py-20" style="background: linear-gradient(135deg, #eef1ff 0%, #f7f9ff 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal grid lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <div class="banner-mock text-left max-w-md">
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Invoice #INV-2026-0148</span>
                            <span class="text-[10px] font-semibold uppercase tracking-wide text-white px-2 py-0.5 rounded-full" style="background-color:#22c55e;">Paid</span>
                        </div>
                        <div class="p-4 space-y-2">
                            @foreach ([['Root Canal — Molar', '₹4,500'], ['Consultation', '₹500'], ['Fluoride Treatment', '₹800']] as $line)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">{{ $line[0] }}</span>
                                    <span class="text-gray-900 font-medium">{{ $line[1] }}</span>
                                </div>
                            @endforeach
                            <div class="pt-2 mt-2 border-t border-gray-100 flex items-center justify-between text-sm font-semibold">
                                <span class="text-gray-900">Grand Total</span>
                                <span style="color:#465fff;">₹5,800</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-1 lg:order-2 text-center lg:text-left">
                    <span class="eyebrow-tag inline-block text-indigo-500 mb-4">[ BILLING ]</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">Invoicing that just works</h2>
                    <p class="text-gray-600 mt-4 max-w-md mx-auto lg:mx-0">
                        Build a GST-ready invoice from your service catalogue in seconds, mark it paid, and hand your
                        patient a branded PDF — no spreadsheets, no accountant on speed dial.
                    </p>
                    <ul class="mt-6 space-y-2 text-sm text-gray-700 inline-block text-left">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> Auto-numbered, GST-ready invoices</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> One-click branded PDF export</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> Paid / unpaid tracking built in</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <div class="node-divider max-w-7xl mx-auto"><span></span><span></span></div>

    <section class="banner-panel py-20" style="background: linear-gradient(135deg, #eafaf3 0%, #f6fdf9 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <span class="eyebrow-tag inline-block text-emerald-600 mb-4">[ FOLLOW-UPS ]</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight">Never miss a follow-up</h2>
                    <p class="text-gray-600 mt-4 max-w-md mx-auto lg:mx-0">
                        Automatic WhatsApp reminders confirm appointments and cut no-shows — patients get a nudge,
                        your chairs stay full, and your front desk stops making reminder calls.
                    </p>
                    <ul class="mt-6 space-y-2 text-sm text-gray-700 inline-block text-left">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> WhatsApp confirmations &amp; reminders</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> Fewer no-shows, fuller schedule</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-green-500"></i> Zero manual follow-up calls</li>
                    </ul>
                </div>
                <div>
                    <div class="banner-mock text-left max-w-md ml-auto">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <span class="text-xs font-semibold text-gray-700">Tomorrow's Appointments</span>
                        </div>
                        <div class="p-4 space-y-3">
                            @foreach ([['Riya Shah', '10:30 AM', 'fa-check', '#22c55e'], ['Karan Desai', '11:15 AM', 'fa-check', '#22c55e'], ['Meera Joshi', '2:00 PM', 'fa-clock', '#f59e0b']] as $appt)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-700">{{ $appt[0] }}</span>
                                    <span class="text-gray-400 text-xs">{{ $appt[1] }}</span>
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full text-white text-[10px]" style="background-color:{{ $appt[3] }};">
                                        <i class="fa-solid {{ $appt[2] }}"></i>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="node-divider max-w-7xl mx-auto"><span></span><span></span></div>

    <!-- How it works -->
    <section id="how-it-works" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900">How it works</h2>
                <p class="text-gray-500 mt-3">Live in your clinic within a day.</p>
            </div>

            <div class="reveal relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="hidden lg:block absolute top-6 left-0 right-0 h-0.5 bg-gray-200" style="margin: 0 12.5%;">
                </div>

                @foreach ([['step' => '1', 'title' => 'Request Access', 'desc' => 'Tell us about your clinic in a 1-minute form.'], ['step' => '2', 'title' => 'Get Credentials', 'desc' => 'We set up your clinic and email your login.'], ['step' => '3', 'title' => 'Add Your Team', 'desc' => 'Invite doctors and staff in a couple of clicks.'], ['step' => '4', 'title' => 'Start Managing', 'desc' => 'Book appointments and run your clinic from day one.']] as $item)
                    <div class="relative text-center">
                        <div class="relative z-10 w-12 h-12 mx-auto rounded-full flex items-center justify-center text-white font-semibold"
                            style="background-color:#465fff;">
                            {{ $item['step'] }}
                        </div>
                        <h3 class="font-semibold text-gray-900 mt-4">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-500 mt-2">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="node-divider max-w-7xl mx-auto"><span></span><span></span></div>

    <!-- Pricing teaser -->
    <section class="bg-gray-50 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Plans for every clinic size</h2>
                <p class="text-gray-500 mt-3">Start free. Upgrade whenever you're ready.</p>
            </div>

            <div class="reveal grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([['name' => 'Free', 'price' => '₹0', 'highlight' => false], ['name' => 'Basic', 'price' => '₹299', 'highlight' => false], ['name' => 'Premium', 'price' => '₹799', 'highlight' => true], ['name' => 'Deluxe', 'price' => '₹1499', 'highlight' => false]] as $plan)
                    <div class="rounded-xl border p-6 text-center bg-white {{ $plan['highlight'] ? 'border-2' : 'border-gray-100' }}"
                        @if ($plan['highlight']) style="border-color:#465fff;" @endif>
                        @if ($plan['highlight'])
                            <span
                                class="inline-block text-[10px] font-semibold uppercase tracking-wide text-white px-2 py-0.5 rounded-full mb-2"
                                style="background-color:#465fff;">Most Popular</span>
                        @endif
                        <p class="font-semibold text-gray-900">{{ $plan['name'] }}</p>
                        <p class="text-2xl font-bold mt-1" style="color:#465fff;">{{ $plan['price'] }}<span
                                class="text-xs text-gray-400 font-normal">/mo</span></p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 font-medium hover:underline"
                    style="color:#465fff;">
                    See Full Pricing <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Trusted by dentists across India</h2>
            </div>

            <div class="reveal grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ([['quote' => 'Reduced admin work by 70%.', 'name' => 'Dr. Arjun Mehta', 'place' => 'Ahmedabad', 'initials' => 'AM', 'bg' => '#465fff'], ['quote' => 'Best investment for my clinic.', 'name' => 'Dr. Sneha Patel', 'place' => 'Surat', 'initials' => 'SP', 'bg' => '#22c55e'], ['quote' => 'Simple yet powerful.', 'name' => 'Dr. Rahul Kumar', 'place' => 'Vadodara', 'initials' => 'RK', 'bg' => '#f59e0b']] as $t)
                    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                        <div class="text-amber-400 mb-3 text-xs">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-gray-700 italic">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                        <div class="flex items-center gap-3 mt-5">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-white text-xs font-semibold" style="background-color:{{ $t['bg'] }};">
                                {{ $t['initials'] }}
                            </span>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $t['name'] }}</p>
                                <p class="text-xs text-gray-400">{{ $t['place'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="node-divider max-w-7xl mx-auto"><span></span><span></span></div>

    <!-- CTA -->
    <section class="py-20" style="background-color:#0b1e3d;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white">Ready to Modernise Your Clinic?</h2>
            <p class="text-white/70 mt-3">Join clinics already running smarter with DentaSaaS.</p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('request-access') }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white w-full sm:w-auto"
                    style="background-color:#465fff;">
                    Request Free Access
                </a>
                <a href="https://wa.me/918488055253" target="_blank" rel="noopener"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white bg-green-500 hover:bg-green-600 w-full sm:w-auto">
                    <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp <i
                        class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.15 });

                document.querySelectorAll('.reveal').forEach((el) => revealObserver.observe(el));
            } else {
                document.querySelectorAll('.reveal').forEach((el) => el.classList.add('is-visible'));
            }
        </script>
    @endpush
</x-public-layout>
