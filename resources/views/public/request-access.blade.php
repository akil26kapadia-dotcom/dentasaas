<x-public-layout>
    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if (session('success'))
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 text-2xl mb-4">
                    <i class="fa-solid fa-check"></i>
                </div>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Request received</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">{{ session('success') }}</p>
                <a href="{{ url('/') }}" class="inline-block mt-6 text-indigo-600 hover:text-indigo-800 font-medium">Back to home</a>
            </div>
        @else
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Request Access</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Tell us about your clinic and we'll set up your DentaSaaS account.</p>

            <form method="POST" action="{{ route('request-access.store') }}" class="mt-8 space-y-4">
                @csrf

                <div>
                    <x-input-label for="name" value="Your Name" />
                    <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="clinic_name" value="Clinic Name" />
                    <x-text-input id="clinic_name" name="clinic_name" class="block mt-1 w-full" :value="old('clinic_name')" required />
                    <x-input-error :messages="$errors->get('clinic_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" value="Email" />
                    <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="phone" value="Phone (optional)" />
                    <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone')" />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="message" value="Message (optional)" />
                    <textarea id="message" name="message" rows="4"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Submit Request
                </button>
            </form>
        @endif
    </div>
</x-public-layout>
