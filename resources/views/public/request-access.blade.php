<x-public-layout>
    <x-slot name="title">Request Access</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="rounded-2xl shadow-theme-lg overflow-hidden lg:flex">
            <!-- Info panel -->
            <div class="relative lg:w-2/5 overflow-hidden p-10 flex flex-col justify-between"
                style="background-color:#0b1e3d;">
                <div class="absolute inset-0 opacity-40"
                    style="background-image: linear-gradient(rgba(255,255,255,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 44px 44px;">
                </div>
                <div class="absolute -top-16 -right-16 w-72 h-72 rounded-full opacity-20 blur-3xl"
                    style="background-color:#465fff;"></div>

                <div class="relative">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl text-white text-xl mb-6"
                        style="background-color:#465fff;">
                        <i class="fa-solid fa-tooth"></i>
                    </span>
                    <h1 class="text-2xl sm:text-3xl font-bold text-white leading-tight">Get Started with DentaSaaS</h1>
                    <p class="mt-3 text-white/60 text-sm">Tell us about your clinic and we'll set your account up —
                        free, no credit card required.</p>

                    <ul class="mt-10 space-y-5">
                        @foreach ([['step' => '1', 'text' => 'Share a few details about your clinic'], ['step' => '2', 'text' => 'We set up your account the same day'], ['step' => '3', 'text' => 'Your login is emailed straight to you']] as $item)
                            <li class="flex items-start gap-3 text-sm text-white/80">
                                <span
                                    class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-white"
                                    style="background-color:#465fff;">{{ $item['step'] }}</span>
                                <span class="pt-0.5">{{ $item['text'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <p class="relative text-sm text-white/40 mt-10">आपकी मुस्कान, हमारी ज़िम्मेदारी</p>
            </div>

            <!-- Form panel -->
            <div class="lg:w-3/5 bg-white p-8 sm:p-10">
                @if (session('success'))
                    <div class="h-full flex flex-col items-center justify-center text-center py-12">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-success-50 text-success-500 text-2xl mb-4">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h2 class="text-2xl font-semibold text-gray-900">Request received</h2>
                        <p class="mt-2 text-gray-500 max-w-sm">{{ session('success') }}</p>
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center gap-2 mt-6 font-medium hover:underline"
                            style="color:#465fff;">
                            <i class="fa-solid fa-arrow-left text-sm"></i> Back to home
                        </a>
                    </div>
                @else
                    <h2 class="text-2xl font-bold text-gray-900">Request Access</h2>
                    <p class="mt-1 text-gray-500 text-sm">Fill in your details below — it takes less than a minute.
                    </p>

                    <form method="POST" action="{{ route('request-access.store') }}" class="mt-8 space-y-4">
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="name" value="Your Name" />
                                <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name')"
                                    required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="clinic_name" value="Clinic Name" />
                                <x-text-input id="clinic_name" name="clinic_name" class="block mt-1 w-full"
                                    :value="old('clinic_name')" required />
                                <x-input-error :messages="$errors->get('clinic_name')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="email" value="Email" />
                                <x-text-input id="email" type="email" name="email" class="block mt-1 w-full"
                                    :value="old('email')" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="phone" value="Phone (optional)" />
                                <x-text-input id="phone" name="phone" class="block mt-1 w-full"
                                    :value="old('phone')" />
                                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="message" value="Message (optional)" />
                            <textarea id="message" name="message" rows="4"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg font-medium text-white hover:opacity-90"
                            style="background-color:#465fff;">
                            Submit Request <i class="fa-solid fa-arrow-right text-sm"></i>
                        </button>

                        <p class="text-xs text-gray-400 text-center">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-medium hover:underline"
                                style="color:#465fff;">Sign in</a>
                        </p>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-public-layout>
