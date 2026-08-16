<x-public-layout>
    <x-slot name="title">Pricing</x-slot>
    <x-slot name="metaDescription">Simple, transparent pricing for DentaSaaS — Free, Basic ₹299, Premium ₹799 and Deluxe ₹1499 per month. Choose the plan that fits your clinic.</x-slot>

    @push('meta')
        <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "BreadcrumbList",
            "itemListElement": [
                {"@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ route('home') }}"},
                {"@@type": "ListItem", "position": 2, "name": "Pricing", "item": "{{ route('pricing') }}"}
            ]
        }
        </script>
    @endpush

    @php
        $planMeta = [
            'free' => ['label' => 'Free', 'price' => 0, 'highlight' => false, 'whatsapp' => 'Hi, I want to start with the FREE plan of DentaSaaS.'],
            'basic' => ['label' => 'Basic', 'price' => 299, 'highlight' => false, 'whatsapp' => 'Hi, I am interested in DentaSaaS BASIC plan ₹299/month. Please help me get started.'],
            'premium' => ['label' => 'Premium', 'price' => 799, 'highlight' => true, 'whatsapp' => 'Hi, I am interested in DentaSaaS PREMIUM plan ₹799/month. Please help me get started.'],
            'deluxe' => ['label' => 'Deluxe', 'price' => 1499, 'highlight' => false, 'whatsapp' => 'Hi, I am interested in DentaSaaS DELUXE plan ₹1499/month. Please help me get started.'],
        ];
    @endphp

    <div x-data="{ billing: 'monthly' }">
        <!-- Header -->
        <section class="pt-20 pb-12 text-center px-4">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white">Simple, Transparent Pricing</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-3 max-w-xl mx-auto">No hidden fees. Upgrade, downgrade or cancel anytime via WhatsApp.</p>

            <div class="inline-flex items-center gap-1 mt-8 bg-gray-100 dark:bg-gray-800 rounded-full p-1">
                <button @click="billing = 'monthly'" :class="billing === 'monthly' ? 'bg-white dark:bg-gray-900 shadow text-gray-900 dark:text-white' : 'text-gray-500'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition">Monthly</button>
                <button @click="billing = 'annual'" :class="billing === 'annual' ? 'bg-white dark:bg-gray-900 shadow text-gray-900 dark:text-white' : 'text-gray-500'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition">
                    Annual
                    <span class="ml-1 text-[10px] font-semibold text-green-600 bg-green-100 rounded-full px-1.5 py-0.5">Save 20%</span>
                </button>
            </div>
        </section>

        <!-- Plan cards -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($planMeta as $key => $meta)
                    <div class="relative rounded-2xl border p-6 bg-white dark:bg-gray-900 {{ $meta['highlight'] ? 'border-2 shadow-lg' : 'border-gray-100 dark:border-gray-700' }}"
                         @if ($meta['highlight']) style="border-color:#465fff;" @endif>
                        @if ($meta['highlight'])
                            <span class="absolute -top-3 left-1/2 -translate-x-1/2 text-[10px] font-semibold uppercase tracking-wide text-white px-3 py-1 rounded-full" style="background-color:#465fff;">
                                Most Popular
                            </span>
                        @endif

                        <p class="font-semibold text-gray-900 dark:text-white text-lg">{{ $meta['label'] }}</p>

                        <p class="mt-2">
                            <span class="text-3xl font-bold text-gray-900 dark:text-white">
                                ₹<span x-text="{{ $meta['price'] }} === 0 ? 0 : (billing === 'annual' ? Math.round({{ $meta['price'] }} * 0.8) : {{ $meta['price'] }})"></span>
                            </span>
                            <span class="text-sm text-gray-400">/mo</span>
                        </p>
                        <p class="text-xs text-green-600 h-4" x-show="billing === 'annual' && {{ $meta['price'] }} > 0" x-cloak>
                            Billed annually &bull; Save 20%
                        </p>
                        <p class="text-xs text-transparent h-4" x-show="! (billing === 'annual' && {{ $meta['price'] }} > 0)">&nbsp;</p>

                        <ul class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                            @php $limits = $plans[$key]; @endphp
                            <li><i class="fa-solid fa-check text-green-500 w-4"></i> {{ $limits['patients'] === -1 ? 'Unlimited' : $limits['patients'] }} patients</li>
                            <li><i class="fa-solid fa-check text-green-500 w-4"></i> {{ $limits['appointments'] === -1 ? 'Unlimited' : $limits['appointments'] }} appointments/mo</li>
                            <li><i class="fa-solid fa-check text-green-500 w-4"></i> {{ $limits['doctors'] === -1 ? 'Unlimited' : $limits['doctors'] }} doctor{{ $limits['doctors'] === 1 ? '' : 's' }}</li>
                            <li>
                                <i class="fa-solid {{ $limits['pdf'] ? 'fa-check text-green-500' : 'fa-xmark text-gray-300' }} w-4"></i>
                                PDF export
                            </li>
                            <li>
                                <i class="fa-solid {{ $limits['prescriptions'] ? 'fa-check text-green-500' : 'fa-xmark text-gray-300' }} w-4"></i>
                                Prescriptions
                            </li>
                            <li>
                                <i class="fa-solid {{ $limits['analytics'] ? 'fa-check text-green-500' : 'fa-xmark text-gray-300' }} w-4"></i>
                                Analytics {{ is_string($limits['analytics']) ? '('.ucfirst($limits['analytics']).')' : '' }}
                            </li>
                        </ul>

                        <a href="https://wa.me/918488055253?text={{ urlencode($meta['whatsapp']) }}" target="_blank" rel="noopener"
                           class="mt-6 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg font-medium text-sm text-white"
                           style="background-color: {{ $meta['highlight'] ? '#465fff' : '#0b1e3d' }};">
                            <i class="fa-brands fa-whatsapp"></i> Get Started
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Comparison table -->
        <section class="bg-gray-50 dark:bg-gray-800 py-20">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-10">Compare Plans</h2>

                <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left border-b border-gray-100 dark:border-gray-700">
                                <th class="py-3 px-4 text-gray-500 dark:text-gray-400">Feature</th>
                                @foreach ($planMeta as $meta)
                                    <th class="py-3 px-4 text-center text-gray-900 dark:text-white">{{ $meta['label'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $rows = [
                                    'Patients limit' => fn ($p) => $p['patients'] === -1 ? 'Unlimited' : $p['patients'],
                                    'Appointments/mo' => fn ($p) => $p['appointments'] === -1 ? 'Unlimited' : $p['appointments'],
                                    'Invoices/mo' => fn ($p) => $p['invoices'] === -1 ? 'Unlimited' : $p['invoices'],
                                    'Doctors' => fn ($p) => $p['doctors'] === -1 ? 'Unlimited' : $p['doctors'],
                                    'PDF Export' => fn ($p) => $p['pdf'],
                                    'Prescriptions' => fn ($p) => $p['prescriptions'],
                                    'Analytics' => fn ($p) => $p['analytics'] === false ? false : ucfirst($p['analytics']),
                                    'Logo Upload' => fn ($p) => true,
                                    'Multi-Branch' => fn ($p) => false,
                                ];
                            @endphp
                            @foreach ($rows as $label => $resolver)
                                <tr class="border-b border-gray-50 dark:border-gray-800 last:border-0">
                                    <td class="py-3 px-4 text-gray-600 dark:text-gray-300">{{ $label }}</td>
                                    @foreach ($planMeta as $key => $meta)
                                        @php $value = $resolver($plans[$key]); @endphp
                                        <td class="py-3 px-4 text-center">
                                            @if (is_bool($value))
                                                <i class="fa-solid {{ $value ? 'fa-check text-green-500' : 'fa-xmark text-gray-300' }}"></i>
                                            @else
                                                <span class="text-gray-700 dark:text-gray-200">{{ $value }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- FAQ -->
    <section class="py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-10">Frequently Asked Questions</h2>

            <div class="space-y-3" x-data="{ open: null }">
                @foreach ([
                    ['q' => 'Is there a free trial?', 'a' => 'The Free plan is a permanent free tier — no trial expiry, no credit card required.'],
                    ['q' => 'Can I upgrade anytime?', 'a' => 'Yes, contact us on WhatsApp and we will upgrade your plan the same day.'],
                    ['q' => 'Is my clinic data private?', 'a' => 'Yes, every clinic\'s data is fully isolated — no other clinic can ever see your records.'],
                    ['q' => 'Do you provide training?', 'a' => 'Yes, we provide free onboarding and training over WhatsApp for every new clinic.'],
                    ['q' => 'GST invoice support?', 'a' => 'Yes, all plans include GST-ready invoices with PDF export (Basic and above).'],
                    ['q' => 'What payment methods do you accept?', 'a' => 'UPI, bank transfer, or simply message us on WhatsApp to arrange payment.'],
                ] as $index => $faq)
                    <div class="border border-gray-100 dark:border-gray-700 rounded-lg overflow-hidden">
                        <button @click="open = open === {{ $index }} ? null : {{ $index }}"
                                class="w-full flex items-center justify-between px-5 py-4 text-left font-medium text-gray-800 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800">
                            {{ $faq['q'] }}
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform" :class="open === {{ $index }} ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open === {{ $index }}" x-cloak
                             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                             class="px-5 pb-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Bottom CTA -->
    <section class="py-20 text-center" style="background-color:#0b1e3d;">
        <div class="max-w-2xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-white">Start Managing Your Clinic Today</h2>
            <a href="https://wa.me/918488055253?text={{ urlencode('Hi, I would like to know more about DentaSaaS.') }}" target="_blank" rel="noopener"
               class="mt-8 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white bg-green-500 hover:bg-green-600">
                <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp <i class="fa-solid fa-arrow-right text-sm"></i>
            </a>
        </div>
    </section>
</x-public-layout>
