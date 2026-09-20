<x-public-layout>
    <x-slot name="title">Dental Clinic Management Blog</x-slot>
    <x-slot name="metaDescription">Practical guides for dental clinics in India: reducing no-shows, choosing practice software, keeping patient records private and getting billing right.</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs(['Blog' => '/blog']) !!}
        {!! \App\Support\Seo::jsonLd([
            '@type' => 'CollectionPage',
            'name' => 'DentaSaaS Blog',
            'url' => \App\Support\Seo::url('/blog'),
            'inLanguage' => 'en-IN',
            'isPartOf' => ['@id' => \App\Support\Seo::url('/#website')],
        ]) !!}
    @endpush

    <section class="py-16 sm:py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <span style="font-family: ui-monospace, Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.12em;" class="inline-block text-indigo-500 mb-4">[ BLOG ]</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 leading-tight">Guides for running a dental clinic</h1>
            <p class="mt-4 text-lg text-gray-600 max-w-2xl">Practical, plain-language articles on appointments, records, billing and privacy for dental clinics in India.</p>

            <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($posts as $post)
                    <a href="{{ route('blog.show', $post['slug']) }}"
                        class="group flex flex-col rounded-2xl border border-gray-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-lg">
                        <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500">{{ $post['category'] }}</span>
                        <h2 class="mt-2 text-xl font-bold text-gray-900 leading-snug group-hover:underline">{{ $post['title'] }}</h2>
                        <p class="mt-3 text-gray-600 leading-relaxed flex-1">{{ $post['excerpt'] }}</p>
                        <p class="mt-4 text-sm text-gray-500">
                            <time datetime="{{ $post['date'] }}">{{ \Illuminate\Support\Carbon::parse($post['date'])->format('j F Y') }}</time>
                            <span class="mx-1">&middot;</span>
                            <span class="font-medium" style="color:#465fff;">Read article &rarr;</span>
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @include('partials.cta-band')
</x-public-layout>
