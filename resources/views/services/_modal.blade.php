@php
    $iconChoices = [
        'fa-tooth', 'fa-band-aid', 'fa-teeth', 'fa-teeth-open', 'fa-syringe', 'fa-tablets',
        'fa-pump-medical', 'fa-stethoscope', 'fa-user-doctor', 'fa-notes-medical', 'fa-briefcase-medical',
        'fa-kit-medical', 'fa-hand-holding-medical', 'fa-heart-pulse', 'fa-bacterium', 'fa-mortar-pestle',
        'fa-prescription', 'fa-prescription-bottle-medical', 'fa-vial', 'fa-x-ray',
    ];
@endphp

<div x-show="modalOpen" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center px-4"
     style="background-color: rgba(15,23,42,0.5);">
    <div @click.outside="modalOpen = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-4" x-text="editingId ? 'Edit Service' : 'Add Service'"></h3>

        <form method="POST" :action="editingId ? '{{ url('services') }}/' + editingId : '{{ route('services.store') }}'">
            @csrf
            <template x-if="editingId">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="service_name" value="Name (English)" />
                    <x-text-input id="service_name" name="name" class="block mt-1 w-full" x-model="form.name" required />
                </div>
                <div>
                    <x-input-label for="service_name_hi" value="Name (Hindi)" />
                    <x-text-input id="service_name_hi" name="name_hi" class="block mt-1 w-full" x-model="form.name_hi" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <x-input-label for="service_price" value="Price" />
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">₹</span>
                        <input id="service_price" name="price" type="number" min="0" step="0.01" x-model="form.price" required
                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 pl-7">
                    </div>
                </div>
                <div>
                    <x-input-label for="service_duration" value="Duration (min)" />
                    <x-text-input id="service_duration" name="duration_min" type="number" min="1" class="block mt-1 w-full" x-model="form.duration_min" required />
                </div>
            </div>

            <div class="mt-4">
                <x-input-label for="service_description" value="Description" />
                <textarea id="service_description" name="description" rows="2" x-model="form.description"
                    class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <div class="mt-4">
                <x-input-label value="Icon" />
                <input type="hidden" name="icon" x-model="form.icon">
                <div class="grid grid-cols-5 gap-2 mt-2">
                    @foreach ($iconChoices as $icon)
                        <button type="button" @click="form.icon = '{{ $icon }}'"
                                class="aspect-square rounded-lg border flex items-center justify-center text-lg"
                                :class="form.icon === '{{ $icon }}' ? 'border-indigo-600 ring-2 ring-indigo-500 text-indigo-600' : 'border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-300'">
                            <i class="fa-solid {{ $icon }}"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="hidden" name="is_active" value="0">
                <input id="service_active" type="checkbox" name="is_active" value="1" x-model="form.is_active"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                <label for="service_active" class="text-sm text-gray-600 dark:text-gray-300">Active</label>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save Service</button>
            </div>
        </form>
    </div>
</div>
