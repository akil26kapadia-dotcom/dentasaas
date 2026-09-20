<x-public-layout>
    <x-slot name="title">Contact DentaSaaS</x-slot>
    <x-slot name="metaDescription">Contact the DentaSaaS team on WhatsApp or phone for help getting started, plan questions or support with your dental clinic software.</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs(['Contact' => '/contact']) !!}
        {!! \App\Support\Seo::jsonLd([
            '@type' => 'ContactPage',
            'name' => 'Contact DentaSaaS',
            'url' => \App\Support\Seo::url('/contact'),
            'about' => ['@id' => \App\Support\Seo::url('/#organization')],
        ]) !!}
    @endpush

    @php $b = config('dentasaas.business'); @endphp

    <section class="py-16 sm:py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <span style="font-family: ui-monospace, Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.12em;" class="inline-block text-indigo-500 mb-4">[ CONTACT ]</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 leading-tight">Talk to us</h1>
            <p class="mt-4 text-lg text-gray-600">Questions about getting started, plans or anything in the product? The quickest way to reach us is WhatsApp.</p>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <a href="{{ \App\Support\Seo::whatsappUrl('Hi, I would like to know more about DentaSaaS.') }}" target="_blank" rel="noopener"
                    class="rounded-2xl border border-gray-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center text-xl mb-3"><i class="fa-brands fa-whatsapp"></i></span>
                    <p class="font-semibold text-gray-900">WhatsApp</p>
                    <p class="text-gray-600 mt-1">{{ $b['phone'] }}</p>
                    <p class="text-sm text-gray-500 mt-2">Best for quick questions and setup help.</p>
                </a>

                <a href="tel:{{ preg_replace('/[^+\d]/', '', $b['phone']) }}"
                    class="rounded-2xl border border-gray-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                    <span class="w-11 h-11 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-3"><i class="fa-solid fa-phone"></i></span>
                    <p class="font-semibold text-gray-900">Phone</p>
                    <p class="text-gray-600 mt-1">{{ $b['phone'] }}</p>
                    <p class="text-sm text-gray-500 mt-2">Call us during working hours.</p>
                </a>

                @if ($b['email'])
                    <a href="mailto:{{ $b['email'] }}"
                        class="rounded-2xl border border-gray-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg sm:col-span-2">
                        <span class="w-11 h-11 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-3"><i class="fa-regular fa-envelope"></i></span>
                        <p class="font-semibold text-gray-900">Email</p>
                        <p class="text-gray-600 mt-1">{{ $b['email'] }}</p>
                    </a>
                @endif
            </div>

            @if ($b['legal_name'] || $b['address'])
                <div class="mt-10 rounded-2xl border border-gray-200 bg-white p-6">
                    <p class="font-semibold text-gray-900">{{ $b['legal_name'] ?: $b['name'] }}</p>
                    @if ($b['address'])
                        <p class="text-gray-600 mt-1 whitespace-pre-line">{{ $b['address'] }}</p>
                    @endif
                </div>
            @endif

            <p class="mt-10 text-gray-600">
                Ready to try it? <a href="{{ route('request-access') }}" class="font-medium hover:underline" style="color:#465fff;">Request free access</a>
                and we will set up your clinic. You may also find your answer in the <a href="{{ route('faq') }}" class="font-medium hover:underline" style="color:#465fff;">FAQ</a>.
            </p>
        </div>
    </section>
</x-public-layout>
