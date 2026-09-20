<x-public-layout>
    <x-slot name="title">FAQ: Dental Clinic Software Questions</x-slot>
    <x-slot name="metaDescription">Answers to common questions about DentaSaaS: plans and pricing, getting started, data privacy, WhatsApp messages, GST invoices, Hindi support and more.</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs(['FAQ' => '/faq']) !!}
        {!! \App\Support\Seo::jsonLd([
            '@type' => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ])->all(),
        ]) !!}
    @endpush

    <section class="py-16 sm:py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10">
                <span style="font-family: ui-monospace, Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.12em;" class="inline-block text-indigo-500 mb-4">[ FAQ ]</span>
                <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 leading-tight">Frequently asked questions</h1>
                <p class="mt-4 text-lg text-gray-600">Straight answers about how DentaSaaS works, what it costs and what it does not do.</p>
            </div>

            <div class="space-y-3">
                @foreach ($faqs as $faq)
                    <details class="faq rounded-xl border border-gray-200 bg-white">
                        <summary class="flex items-center justify-between gap-4 px-5 py-4 font-medium text-gray-900">
                            <h2 class="text-base font-medium">{{ $faq['q'] }}</h2>
                            <i class="fa-solid fa-chevron-down faq-chevron text-xs text-gray-400 transition"></i>
                        </summary>
                        <p class="px-5 pb-5 text-gray-600 leading-relaxed">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>

            <p class="mt-10 text-center text-gray-600">
                Still have a question?
                <a href="{{ route('contact') }}" class="font-medium hover:underline" style="color:#465fff;">Contact us</a>
                or
                <a href="{{ \App\Support\Seo::whatsappUrl('Hi, I have a question about DentaSaaS.') }}" target="_blank" rel="noopener" class="font-medium hover:underline" style="color:#465fff;">message us on WhatsApp</a>.
            </p>
        </div>
    </section>

    @include('partials.cta-band')
</x-public-layout>
