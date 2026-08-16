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
    <section class="relative overflow-hidden" style="background-color:#0b1e3d;">
        <div class="absolute inset-0 opacity-40"
             style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 44px 44px;"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full opacity-20 blur-3xl" style="background-color:#465fff;"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-24 text-center">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-white leading-tight">
                Run Your Clinic
                <em class="not-italic bg-clip-text text-transparent" style="background-image: linear-gradient(90deg, #4f6df5, #7c9bff); font-style: italic;">Smarter</em>,
                Not Harder
            </h1>
            <p class="mt-6 text-lg text-white/70 max-w-2xl mx-auto">
                All-in-one dental SaaS — appointments, patients, invoices, prescriptions and treatment plans, built for modern clinics across India.
            </p>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
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
    </section>

    <!-- Trust bar -->
    <section class="bg-gray-50 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-wrap items-center justify-center gap-x-8 gap-y-2 text-sm text-gray-500 dark:text-gray-400">
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
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            @foreach ([
                ['value' => '2,500+', 'label' => 'Patients Managed'],
                ['value' => '₹0', 'label' => 'Setup Cost'],
                ['value' => '24hr', 'label' => 'Account Setup'],
                ['value' => '100%', 'label' => 'Data Isolated'],
            ] as $stat)
                <div>
                    <p class="text-3xl sm:text-4xl font-bold" style="color:#465fff;">{{ $stat['value'] }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="bg-gray-50 dark:bg-gray-800 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Everything your clinic needs</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-3">One platform for the whole front desk to back office.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    <div class="group bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700 border-t-4 border-t-transparent p-6 transition-all hover:-translate-y-1 hover:shadow-lg"
                         style="--hover-color:#465fff;" onmouseover="this.style.borderTopColor='#465fff'" onmouseout="this.style.borderTopColor='transparent'">
                        <span class="w-12 h-12 rounded-lg bg-indigo-50 dark:bg-gray-800 text-indigo-600 flex items-center justify-center text-xl mb-4">
                            <i class="fa-solid {{ $feature['icon'] }}"></i>
                        </span>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- How it works -->
    <section id="how-it-works" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">How it works</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-3">Live in your clinic within a day.</p>
            </div>

            <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div class="hidden lg:block absolute top-6 left-0 right-0 h-0.5 bg-gray-200 dark:bg-gray-700" style="margin: 0 12.5%;"></div>

                @foreach ([
                    ['step' => '1', 'title' => 'Request Access', 'desc' => 'Tell us about your clinic in a 1-minute form.'],
                    ['step' => '2', 'title' => 'Get Credentials', 'desc' => 'We set up your clinic and email your login.'],
                    ['step' => '3', 'title' => 'Add Your Team', 'desc' => 'Invite doctors and staff in a couple of clicks.'],
                    ['step' => '4', 'title' => 'Start Managing', 'desc' => 'Book appointments and run your clinic from day one.'],
                ] as $item)
                    <div class="relative text-center">
                        <div class="relative z-10 w-12 h-12 mx-auto rounded-full flex items-center justify-center text-white font-semibold" style="background-color:#465fff;">
                            {{ $item['step'] }}
                        </div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mt-4">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing teaser -->
    <section class="bg-gray-50 dark:bg-gray-800 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Plans for every clinic size</h2>
                <p class="text-gray-500 dark:text-gray-400 mt-3">Start free. Upgrade whenever you're ready.</p>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([
                    ['name' => 'Free', 'price' => '₹0', 'highlight' => false],
                    ['name' => 'Basic', 'price' => '₹299', 'highlight' => false],
                    ['name' => 'Premium', 'price' => '₹799', 'highlight' => true],
                    ['name' => 'Deluxe', 'price' => '₹1499', 'highlight' => false],
                ] as $plan)
                    <div class="rounded-xl border p-6 text-center bg-white dark:bg-gray-900 {{ $plan['highlight'] ? 'border-2' : 'border-gray-100 dark:border-gray-700' }}"
                         @if ($plan['highlight']) style="border-color:#465fff;" @endif>
                        @if ($plan['highlight'])
                            <span class="inline-block text-[10px] font-semibold uppercase tracking-wide text-white px-2 py-0.5 rounded-full mb-2" style="background-color:#465fff;">Most Popular</span>
                        @endif
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $plan['name'] }}</p>
                        <p class="text-2xl font-bold mt-1" style="color:#465fff;">{{ $plan['price'] }}<span class="text-xs text-gray-400 font-normal">/mo</span></p>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('pricing') }}" class="inline-flex items-center gap-2 font-medium hover:underline" style="color:#465fff;">
                    See Full Pricing <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Trusted by dentists across India</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ([
                    ['quote' => 'Reduced admin work by 70%.', 'name' => 'Dr. Arjun Mehta', 'place' => 'Ahmedabad'],
                    ['quote' => 'Best investment for my clinic.', 'name' => 'Dr. Sneha Patel', 'place' => 'Surat'],
                    ['quote' => 'Simple yet powerful.', 'name' => 'Dr. Rahul Kumar', 'place' => 'Vadodara'],
                ] as $t)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6">
                        <div class="text-amber-400 mb-3">
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        </div>
                        <p class="text-gray-700 dark:text-gray-200 italic">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                        <p class="mt-4 text-sm font-medium text-gray-900 dark:text-white">{{ $t['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $t['place'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

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
                    <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp <i class="fa-solid fa-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </section>
</x-public-layout>
