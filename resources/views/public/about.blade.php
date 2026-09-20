<x-public-layout>
    <x-slot name="title">About DentaSaaS</x-slot>
    <x-slot name="metaDescription">DentaSaaS is dental clinic management software built for clinics in India: simple to learn, affordable to start, and careful with patient data.</x-slot>

    @push('meta')
        {!! \App\Support\Seo::breadcrumbs(['About' => '/about']) !!}
        {!! \App\Support\Seo::jsonLd([
            '@type' => 'AboutPage',
            'name' => 'About DentaSaaS',
            'url' => \App\Support\Seo::url('/about'),
            'about' => ['@id' => \App\Support\Seo::url('/#organization')],
        ]) !!}
    @endpush

    <section class="py-16 sm:py-20">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <span style="font-family: ui-monospace, Menlo, monospace; font-size: 0.7rem; letter-spacing: 0.12em;" class="inline-block text-indigo-500 mb-4">[ ABOUT ]</span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 leading-tight">Software that fits how Indian dental clinics really work</h1>
            <div class="prose-lite mt-8">
                <p>DentaSaaS is dental clinic management software. It puts appointments, patient records, invoices, prescriptions, treatment plans and basic analytics in one place, so a clinic can run on one screen instead of registers, chat groups and spreadsheets.</p>

                <h2>What we are building</h2>
                <p>Most dental practice software was designed for other markets and priced for large practices. Clinics in India have their own realities: WhatsApp is how patients communicate, GST invoices are a fact of life, staff may be more comfortable in Hindi, and a single-doctor clinic needs something it can afford and learn in an afternoon.</p>
                <p>DentaSaaS is built around those realities. It works in a browser on any device, the interface switches between English and Hindi, prices are in rupees, and there is a permanent free plan to start on.</p>

                <h2>How we think about it</h2>
                <ul>
                    <li><strong>Simple first.</strong> If a receptionist cannot use a screen after a short walk-through, we have made it too complicated.</li>
                    <li><strong>Honest about limits.</strong> We say plainly what DentaSaaS does not do: it is not an imaging system, it does not send WhatsApp messages on its own, and it does not file GST returns.</li>
                    <li><strong>Careful with patient data.</strong> Each clinic's data is kept separate, staff have individual logins, and passwords are stored hashed. Read our <a href="{{ route('privacy') }}">privacy policy</a> for details.</li>
                    <li><strong>Human support.</strong> You can reach us on WhatsApp, and we help every new clinic get set up at no extra charge.</li>
                </ul>

                <h2>Get in touch</h2>
                <p>Questions, feedback or a feature you would like to see? <a href="{{ route('contact') }}">Contact us</a>. To try DentaSaaS in your clinic, <a href="{{ route('request-access') }}">request free access</a>.</p>
            </div>
        </div>
    </section>

    @include('partials.cta-band')
</x-public-layout>
