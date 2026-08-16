@php
    $swatches = ['#465fff', '#DC2626', '#059669', '#D97706', '#7C3AED', '#0891B2', '#DB2777', '#4B5563'];
@endphp

<div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4"
    style="background-color: rgba(15,23,42,0.5);">
    <div @click.outside="modalOpen = false" class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
        <h3 class="font-semibold text-lg text-gray-900 mb-4" x-text="editingId ? 'Edit Doctor' : 'Add Doctor'"></h3>

        <form method="POST" :action="editingId ? '{{ url('doctors') }}/' + editingId : '{{ route('doctors.store') }}'">
            @csrf
            <template x-if="editingId">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="space-y-4">
                <div>
                    <x-input-label for="doctor_name" value="Name" />
                    <x-text-input id="doctor_name" name="name" class="block mt-1 w-full" x-model="form.name"
                        required />
                </div>

                <div>
                    <x-input-label for="doctor_email" value="Email" />
                    <x-text-input id="doctor_email" type="email" name="email" class="block mt-1 w-full"
                        x-model="form.email" required />
                </div>

                <div>
                    <x-input-label for="doctor_role" value="Role" />
                    <select id="doctor_role" name="role" x-model="form.role"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="admin">Admin</option>
                        <option value="doctor">Doctor</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>

                <div>
                    <x-input-label for="doctor_specialty" value="Specialty" />
                    <x-text-input id="doctor_specialty" name="specialty" class="block mt-1 w-full"
                        x-model="form.specialty" />
                </div>

                <div>
                    <x-input-label value="Color" />
                    <input type="hidden" name="color" x-model="form.color">
                    <div class="flex gap-2 mt-2">
                        @foreach ($swatches as $swatch)
                            <button type="button" @click="form.color = '{{ $swatch }}'"
                                class="w-7 h-7 rounded-full border-2"
                                :class="form.color === '{{ $swatch }}' ? 'border-gray-800' : 'border-transparent'"
                                style="background-color: {{ $swatch }};"></button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" @click="modalOpen = false"
                    class="px-4 py-2 text-sm text-gray-600">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save
                    Doctor</button>
            </div>
        </form>
    </div>
</div>
