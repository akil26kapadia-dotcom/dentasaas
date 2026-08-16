<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Settings</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        @if (session('success'))
            <div class="rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Clinic Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Clinic Info</h3>
                <form method="POST" action="{{ route('settings.clinic') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="clinic_name" value="Clinic Name" />
                        <x-text-input id="clinic_name" name="name" class="block mt-1 w-full" :value="old('name', $clinic->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="clinic_tagline" value="Tagline" />
                        <x-text-input id="clinic_tagline" name="tagline" class="block mt-1 w-full" :value="old('tagline', $clinic->tagline)" />
                    </div>

                    <div>
                        <x-input-label for="clinic_address" value="Address" />
                        <textarea id="clinic_address" name="address" rows="2"
                            class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $clinic->address) }}</textarea>
                    </div>

                    <div>
                        <x-input-label for="clinic_phone" value="Phone" />
                        <x-text-input id="clinic_phone" name="phone" class="block mt-1 w-full" :value="old('phone', $clinic->phone)" />
                    </div>

                    <div>
                        <x-input-label for="clinic_email" value="Email" />
                        <x-text-input id="clinic_email" type="email" name="email" class="block mt-1 w-full" :value="old('email', $clinic->email)" />
                    </div>

                    <div>
                        <x-input-label for="clinic_gst" value="GSTIN" />
                        <x-text-input id="clinic_gst" name="gst" class="block mt-1 w-full" :value="old('gst', $clinic->gst)" />
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Save
                    </button>
                </form>
            </div>

            <!-- Logo -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6"
                 x-data="{
                    logoUrl: @js($clinic->logo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($clinic->logo_path) : null),
                    uploading: false,
                    upload(file) {
                        if (! file) return;
                        this.uploading = true;
                        const formData = new FormData();
                        formData.append('logo', file);
                        fetch('{{ route('settings.logo.upload') }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                            body: formData,
                        })
                        .then(r => r.json())
                        .then(data => { this.logoUrl = data.logo_url; this.uploading = false; })
                        .catch(() => { this.uploading = false; });
                    },
                    remove() {
                        fetch('{{ route('settings.logo.remove') }}', {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                        })
                        .then(r => r.json())
                        .then(data => { this.logoUrl = data.logo_url; });
                    },
                 }">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Clinic Logo</h3>

                <div class="flex flex-col items-center">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                        <img x-show="logoUrl" :src="logoUrl" class="w-full h-full object-cover">
                        <i x-show="! logoUrl" class="fa-solid fa-tooth text-gray-400 text-xl"></i>
                    </div>

                    <label class="w-full border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg py-6 text-center cursor-pointer hover:border-indigo-300"
                           @dragover.prevent @drop.prevent="upload($event.dataTransfer.files[0])">
                        <input type="file" accept="image/*" class="hidden" @change="upload($event.target.files[0])">
                        <i class="fa-solid fa-cloud-arrow-up text-gray-400 text-xl mb-1"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="uploading ? 'Uploading…' : 'Click or drag to upload'"></p>
                        <p class="text-xs text-gray-400 mt-1">PNG or JPG, up to 2MB</p>
                    </label>

                    <button type="button" x-show="logoUrl" x-cloak @click="remove()" class="mt-3 text-sm text-red-500 hover:text-red-700">
                        Remove logo
                    </button>
                </div>
            </div>

            <!-- Language -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Language</h3>
                <div class="grid grid-cols-2 gap-4">
                    @foreach (['en' => ['label' => 'English', 'flag' => '🇺🇸'], 'hi' => ['label' => 'हिंदी', 'flag' => '🇮🇳']] as $code => $lang)
                        <form method="POST" action="{{ route('settings.language') }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="language" value="{{ $code }}">
                            <button type="submit"
                                    class="w-full rounded-xl py-8 text-center transition
                                           {{ $clinic->language === $code
                                                ? 'text-white'
                                                : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-300 hover:bg-gray-100' }}"
                                    @if ($clinic->language === $code) style="background: linear-gradient(135deg, #465fff, #4f6df5);" @endif>
                                <div class="text-2xl mb-2">{{ $lang['flag'] }}</div>
                                <div class="text-sm font-medium">{{ $lang['label'] }}</div>
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Change password -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Change Password</h3>
                <form method="POST" action="{{ route('settings.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="current_password" value="Current Password" />
                        <x-text-input id="current_password" type="password" name="current_password" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="new_password" value="New Password" />
                        <x-text-input id="new_password" type="password" name="password" class="block mt-1 w-full" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" value="Confirm New Password" />
                        <x-text-input id="password_confirmation" type="password" name="password_confirmation" class="block mt-1 w-full" />
                    </div>

                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Update Password
                    </button>
                </form>
            </div>

            <!-- Your plan -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Your Plan</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase bg-indigo-50 text-indigo-700">
                        {{ $clinic->plan }}
                    </span>
                </div>

                @if ($daysUntilExpiry !== null)
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        {{ $daysUntilExpiry >= 0 ? "Renews in {$daysUntilExpiry} days" : 'Your plan has expired' }}
                    </p>
                @endif

                <div class="space-y-3">
                    @foreach ($planUsage as $resource => $data)
                        @php $color = $data['pct'] >= 90 ? 'bg-red-500' : ($data['pct'] >= 70 ? 'bg-amber-500' : 'bg-green-500'); @endphp
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                                <span class="capitalize">{{ $resource }}</span>
                                <span>{{ $data['used'] }}/{{ $data['limit'] === -1 ? '∞' : $data['limit'] }}</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-gray-200 dark:bg-gray-700">
                                <div class="h-1.5 rounded-full {{ $color }}" style="width: {{ $data['pct'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ Route::has('settings.billing') ? route('settings.billing') : '#' }}"
                   class="w-full mt-6 inline-flex items-center justify-center px-4 py-2.5 rounded-lg font-medium text-white hover:opacity-90"
                   style="background-color:#465fff;">
                    Upgrade Plan <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
