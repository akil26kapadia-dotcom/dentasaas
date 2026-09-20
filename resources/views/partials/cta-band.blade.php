<section class="py-16" style="background-color:#0b1e3d;">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl sm:text-3xl font-bold text-white">{{ $heading ?? 'Ready to run your clinic smarter?' }}</h2>
        <p class="text-white/70 mt-3">{{ $text ?? 'Start on the free plan. No credit card and no expiry.' }}</p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('request-access') }}"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white w-full sm:w-auto"
                style="background-color:#465fff;">
                Request Free Access
            </a>
            <a href="{{ \App\Support\Seo::whatsappUrl('Hi, I would like to know more about DentaSaaS.') }}" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-medium text-white bg-green-500 hover:bg-green-600 w-full sm:w-auto">
                <i class="fa-brands fa-whatsapp"></i> Chat on WhatsApp
            </a>
        </div>
    </div>
</section>
