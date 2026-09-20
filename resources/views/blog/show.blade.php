@php
    $url = \App\Support\Seo::url('/blog/'.$post['slug']);
    $minutes = max(1, (int) ceil(str_word_count(strip_tags($html)) / 200));
    $published = \Illuminate\Support\Carbon::parse($post['date']);
@endphp
<x-public-layout>
    <x-slot name="title">{{ $post['title'] }}</x-slot>
    <x-slot name="metaDescription">{{ $post['description'] }}</x-slot>
    <x-slot name="ogType">article</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs(['Blog' => '/blog', $post['title'] => '/blog/'.$post['slug']]) !!}
        {!! \App\Support\Seo::jsonLd([
            '@type' => 'Article',
            'headline' => $post['title'],
            'description' => $post['description'],
            'datePublished' => $published->toDateString(),
            'dateModified' => $published->toDateString(),
            'inLanguage' => 'en-IN',
            'mainEntityOfPage' => $url,
            'image' => \App\Support\Seo::ogImage(),
            'author' => ['@id' => \App\Support\Seo::url('/#organization')],
            'publisher' => ['@id' => \App\Support\Seo::url('/#organization')],
        ]) !!}
        <meta property="article:published_time" content="{{ $published->toDateString() }}">
    @endpush

    <article class="py-12 sm:py-16">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-8">
                <a href="{{ route('home') }}" class="hover:text-gray-800">Home</a>
                <span class="mx-1.5">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-gray-800">Blog</a>
            </nav>

            <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500">{{ $post['category'] }}</span>
            <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">{{ $post['title'] }}</h1>
            <p class="mt-3 text-sm text-gray-500">
                <time datetime="{{ $published->toDateString() }}">{{ $published->format('j F Y') }}</time>
                <span class="mx-1">&middot;</span> {{ $minutes }} min read
            </p>

            <div class="prose-lite mt-8">{!! $html !!}</div>

            <div class="mt-12 rounded-2xl border border-gray-200 bg-white p-6">
                <p class="font-semibold text-gray-900">Want this handled for you?</p>
                <p class="text-gray-600 mt-1">DentaSaaS brings appointments, patient records, invoices and prescriptions into one place. The free plan has no expiry.</p>
                <a href="{{ route('request-access') }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 rounded-lg font-medium text-white" style="background-color:#465fff;">Request Free Access</a>
            </div>
        </div>
    </article>

    @if ($related->isNotEmpty())
        <section class="pb-16">
            <div class="max-w-5xl mx-auto px-4 sm:px-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Keep reading</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    @foreach ($related as $item)
                        <a href="{{ route('blog.show', $item['slug']) }}" class="rounded-2xl border border-gray-200 bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">
                            <span class="text-xs font-semibold uppercase tracking-wide text-indigo-500">{{ $item['category'] }}</span>
                            <p class="mt-2 font-semibold text-gray-900 leading-snug">{{ $item['title'] }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-public-layout>
