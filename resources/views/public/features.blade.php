<x-public-layout>
    <x-slot name="title">Features: Dental Clinic Management Software</x-slot>
    <x-slot name="metaDescription">Everything a dental clinic needs in one place: appointment scheduling, patient records, GST-ready invoicing, prescriptions, treatment plans and analytics. Free plan available.</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs(['Features' => '/features']) !!}
    @endpush

    <section class="py-16 sm:py-20 text-center px-4" style="background: linear-gradient(180deg, rgba(70,95,255,0.07), transparent);">
        <div class="max-w-3xl mx-auto">
            <span class="eyebrow-tag inline-block text-indigo-500 mb-4" style="font-family: ui-monospace, Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.12em;">[ FEATURES ]</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 leading-tight">Everything your dental clinic needs, in one place</h1>
            <p class="mt-5 text-lg text-gray-600">
                DentaSaaS brings appointments, patient records, invoices, prescriptions, treatment plans and analytics
                together, so your team works from one screen instead of registers, chats and spreadsheets.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('request-access') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white w-full sm:w-auto" style="background-color:#465fff;">Request Free Access</a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium border border-gray-300 text-gray-800 hover:bg-gray-50 w-full sm:w-auto">See pricing</a>
            </div>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pages as $slug => $page)
                    <a href="{{ route('features.show', $slug) }}"
                        class="group flex flex-col rounded-2xl border border-gray-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl mb-4">
                            <i class="fa-solid {{ $page['icon'] }}"></i>
                        </span>
                        <h2 class="font-semibold text-gray-900 text-lg">{{ $page['name'] }}</h2>
                        <p class="text-sm text-gray-600 mt-2 flex-1">{{ $page['summary'] }}</p>
                        <span class="mt-4 inline-flex items-center gap-2 text-sm font-medium" style="color:#465fff;">
                            Learn more <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">And the everyday details</h2>
                <p class="text-gray-600 mt-3">The smaller things that make daily work smoother.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach (config('features.more') as $item)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6">
                        <span class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                            <i class="fa-solid {{ $item['icon'] }}"></i>
                        </span>
                        <h3 class="font-semibold text-gray-900">{{ $item['title'] }}</h3>
                        <p class="text-sm text-gray-600 mt-1.5">{{ $item['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.cta-band')
</x-public-layout>
