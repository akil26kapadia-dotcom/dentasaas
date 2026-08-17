<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DentaSaaS') }} - Login</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Brand panel -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-between p-12"
            style="background-color:#0b1e3d;">
            <div class="absolute inset-0 opacity-40"
                style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 44px 44px;">
            </div>
            <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full opacity-20 blur-3xl"
                style="background-color:#465fff;"></div>

            <a href="{{ route('home') }}" class="relative flex items-center gap-2 font-semibold text-lg text-white">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-white"
                    style="background-color:#465fff;">
                    <i class="fa-solid fa-tooth"></i>
                </span>
                DentaSaaS
            </a>

            <div class="relative">
                <h1 class="text-4xl font-extrabold text-white leading-tight">
                    Welcome back to
                    <em class="not-italic bg-clip-text text-transparent"
                        style="background-image: linear-gradient(90deg, #4f6df5, #7c9bff); font-style: italic;">your
                        clinic</em>
                </h1>
                <p class="mt-4 text-white/60 max-w-sm">
                    Appointments, patients, invoices and treatment plans — everything you need, in one place.
                </p>

                <ul class="mt-8 space-y-4">
                    @foreach ([['icon' => 'fa-calendar-check', 'text' => 'Book & confirm appointments over WhatsApp'], ['icon' => 'fa-file-invoice', 'text' => 'GST-ready invoices with instant PDF export'], ['icon' => 'fa-shield-halved', 'text' => 'Every clinic\'s data fully isolated & secure']] as $item)
                        <li class="flex items-center gap-3 text-sm text-white/80">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10">
                                <i class="fa-solid {{ $item['icon'] }}"></i>
                            </span>
                            {{ $item['text'] }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="relative text-sm text-white/40">आपकी मुस्कान, हमारी ज़िम्मेदारी</p>
        </div>

        <!-- Form panel -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-4 py-12 bg-gray-50">
            <div class="w-full max-w-md">
                <div class="mb-8 text-center lg:hidden">
                    <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl text-white text-2xl mb-4"
                        style="background-color:#465fff;">
                        <i class="fa-solid fa-tooth"></i>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-900">DentaSaaS</h1>
                </div>

                <div class="bg-white rounded-2xl shadow-theme-lg border border-gray-100 p-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Sign in</h2>
                        <p class="text-sm text-gray-500 mt-1">Enter your credentials to access your dashboard.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 focus:ring-2"
                                    style="accent-color:#465fff;" name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-medium hover:underline" style="color:#465fff;"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-white transition hover:opacity-90"
                            style="background-color:#465fff;">
                            {{ __('Log in') }}
                        </button>
                    </form>
                </div>

                <p class="mt-6 text-sm text-gray-500 text-center">
                    Don't have an account?
                    <a href="{{ route('request-access') }}" class="font-medium hover:underline"
                        style="color:#465fff;">Request Access</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
