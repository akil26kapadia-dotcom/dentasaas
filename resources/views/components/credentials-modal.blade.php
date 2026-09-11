@if (session('generated_credentials'))
    @php $creds = session('generated_credentials'); @endphp
    <div x-data="{ open: true, copied: false }" x-show="open" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center px-4"
        style="background-color: rgba(15,23,42,0.5);">
        <div @click.outside="open = false" class="bg-white rounded-2xl shadow-theme-lg w-full max-w-md p-6">
            @if ($creds['mail_sent'])
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-success-50 text-success-500 text-xl mb-4">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
                <h3 class="font-semibold text-lg text-gray-900">Credentials emailed</h3>
                <p class="text-sm text-gray-500 mt-1">Login details were sent to <strong>{{ $creds['email'] }}</strong>.
                    They're
                    shown below too, in case the email doesn't arrive.</p>
            @else
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-warning-50 text-warning-500 text-xl mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="font-semibold text-lg text-gray-900">Email could not be sent</h3>
                <p class="text-sm text-gray-500 mt-1">We couldn't deliver the email (likely an SMTP issue on the
                    server).
                    Please copy these credentials and share them with <strong>{{ $creds['name'] }}</strong> manually —
                    WhatsApp works well.</p>
            @endif

            <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-2 text-sm">
                <div class="flex items-center justify-between gap-3">
                    <span class="text-gray-500">Email</span>
                    <span class="font-medium text-gray-900">{{ $creds['email'] }}</span>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <span class="text-gray-500">Password</span>
                    <span class="font-mono font-semibold text-gray-900" x-ref="password">{{ $creds['password'] }}</span>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="open = false" class="px-4 py-2 text-sm text-gray-600">Close</button>
                <button type="button"
                    @click="navigator.clipboard.writeText('Email: {{ $creds['email'] }}\nPassword: {{ $creds['password'] }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                    <i class="fa-solid" :class="copied ? 'fa-check' : 'fa-copy'"></i>
                    <span x-text="copied ? 'Copied!' : 'Copy Credentials'"></span>
                </button>
            </div>
        </div>
    </div>
@endif
