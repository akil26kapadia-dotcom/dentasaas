@props(['title', 'description', 'path', 'updated'])

<x-public-layout>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="metaDescription">{{ $description }}</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs([$title => $path]) !!}
    @endpush

    <section class="py-14 sm:py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <span style="font-family: ui-monospace, Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.12em;" class="inline-block text-indigo-500 mb-4">[ LEGAL ]</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">{{ $title }}</h1>
            <p class="text-sm text-gray-500 mt-2">Last updated: {{ $updated }}</p>

            <div class="prose-lite mt-8">
                {{ $slot }}

                @php $b = config('dentasaas.business'); @endphp
                <h2>Contact</h2>
                <p>
                    Questions about this page? Reach {{ $b['legal_name'] ?: $b['name'] }} on WhatsApp or phone at
                    <a href="{{ \App\Support\Seo::whatsappUrl() }}" target="_blank" rel="noopener">{{ $b['phone'] }}</a>
                    @if ($b['email']) or by email at <a href="mailto:{{ $b['email'] }}">{{ $b['email'] }}</a>@endif.
                    @if ($b['address'])
                        <br>Address: {!! nl2br(e($b['address'])) !!}
                    @endif
                    @if ($b['grievance_officer'])
                        <br>Grievance officer: {{ $b['grievance_officer'] }}
                    @endif
                </p>
            </div>
        </div>
    </section>
</x-public-layout>
