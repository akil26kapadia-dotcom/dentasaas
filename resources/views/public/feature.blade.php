<x-public-layout>
    <x-slot name="title">{{ $page['title'] }}</x-slot>
    <x-slot name="metaDescription">{{ $page['description'] }}</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs(['Features' => '/features', $page['name'] => '/features/'.$slug]) !!}
        {!! \App\Support\Seo::jsonLd([
            '@type' => 'WebPage',
            'name' => $page['title'],
            'description' => $page['description'],
            'url' => \App\Support\Seo::url('/features/'.$slug),
            'inLanguage' => 'en-IN',
            'isPartOf' => ['@id' => \App\Support\Seo::url('/#website')],
            'about' => ['@type' => 'SoftwareApplication', 'name' => 'DentaSaaS', 'applicationCategory' => 'BusinessApplication'],
        ]) !!}
        {!! \App\Support\Seo::jsonLd([
            '@type' => 'FAQPage',
            'mainEntity' => collect($page['faqs'])->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ])->all(),
        ]) !!}
    @endpush

    <section class="pt-10 pb-14 sm:pb-20 px-4" style="background: linear-gradient(180deg, rgba(70,95,255,0.07), transparent);">
        <div class="max-w-4xl mx-auto">
            <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-8">
                <a href="{{ route('home') }}" class="hover:text-gray-800">Home</a>
                <span class="mx-1.5">/</span>
                <a href="{{ route('features') }}" class="hover:text-gray-800">Features</a>
                <span class="mx-1.5">/</span>
                <span class="text-gray-800">{{ $page['name'] }}</span>
            </nav>
            <span class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl mb-5">
                <i class="fa-solid {{ $page['icon'] }}"></i>
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 leading-tight">{{ $page['h1'] }}</h1>
            <p class="mt-5 text-lg text-gray-600 max-w-3xl">{{ $page['lead'] }}</p>
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('request-access') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white sm:w-auto" style="background-color:#465fff;">Request Free Access</a>
                <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium border border-gray-300 text-gray-800 hover:bg-gray-50">See pricing</a>
            </div>
        </div>
    </section>

    <section class="pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">What you get</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($page['benefits'] as $benefit)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6">
                        <h3 class="font-semibold text-gray-900 flex items-start gap-2.5">
                            <i class="fa-solid fa-circle-check text-green-500 mt-1"></i>
                            <span>{{ $benefit['title'] }}</span>
                        </h3>
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $benefit['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">How it works</h2>
            <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ min(count($page['steps']), 4) }} gap-6">
                @foreach ($page['steps'] as $i => $step)
                    <li class="relative rounded-2xl border border-gray-200 bg-white p-6">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full text-white text-sm font-semibold" style="background-color:#465fff;">{{ $i + 1 }}</span>
                        <h3 class="font-semibold text-gray-900 mt-4">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="prose-lite">
                @foreach ($page['sections'] as $section)
                    <h2>{{ $section['heading'] }}</h2>
                    @foreach ($section['body'] as $paragraph)
                        {!! \Illuminate\Support\Str::markdown($paragraph, ['html_input' => 'strip']) !!}
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Frequently asked questions</h2>
            <div class="space-y-3">
                @foreach ($page['faqs'] as $faq)
                    <details class="faq rounded-xl border border-gray-200 bg-white">
                        <summary class="flex items-center justify-between gap-4 px-5 py-4 font-medium text-gray-900">
                            {{ $faq['q'] }}
                            <i class="fa-solid fa-chevron-down faq-chevron text-xs text-gray-400 transition"></i>
                        </summary>
                        <p class="px-5 pb-5 text-gray-600 leading-relaxed">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related features</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach ($page['related'] as $relatedSlug)
                    @php $related = $others[$relatedSlug] ?? null; @endphp
                    @if ($related)
                        <a href="{{ route('features.show', $relatedSlug) }}" class="group rounded-2xl border border-gray-200 bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">
                            <span class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3"><i class="fa-solid {{ $related['icon'] }}"></i></span>
                            <p class="font-semibold text-gray-900">{{ $related['name'] }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $related['summary'] }}</p>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.cta-band')
</x-public-layout>
