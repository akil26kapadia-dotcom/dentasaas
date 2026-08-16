<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">New Prescription</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="prescriptionForm()" x-init="init()">
        @error('plan_limit')
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('prescriptions.store') }}" class="space-y-6">
            @csrf

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label value="Patient" />
                        <select x-ref="patientSelect" style="width:100%" class="block mt-1">
                            <option value="">Search patient by name or phone…</option>
                            @foreach ($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} — {{ $patient->phone }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="patient_id" x-model="patient_id">
                    </div>

                    <div>
                        <x-input-label for="patient_name" value="Patient Name" />
                        <x-text-input id="patient_name" name="patient_name" class="block mt-1 w-full" x-model="patient_name" required />
                        <x-input-error :messages="$errors->get('patient_name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="doctor_name" value="Doctor" />
                        <select id="doctor_name" name="doctor_name" x-model="doctor_name"
                                class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->name }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="rx_date" value="Date" />
                        <x-text-input id="rx_date" type="date" name="rx_date" class="block mt-1 w-full" value="{{ old('rx_date', now()->format('Y-m-d')) }}" required />
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="diagnosis" value="Diagnosis" />
                        <textarea id="diagnosis" name="diagnosis" rows="2"
                            class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('diagnosis') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Medicines</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Name</th>
                                <th class="py-2 w-24">Dose</th>
                                <th class="py-2 w-28">Frequency</th>
                                <th class="py-2 w-28">Duration</th>
                                <th class="py-2">Instructions</th>
                                <th class="py-2 w-10"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(med, index) in medicines" :key="index">
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2 pr-2">
                                        <input type="text" :name="`medicines[${index}][name]`" x-model="med.name"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="py-2 pr-2">
                                        <input type="text" :name="`medicines[${index}][dose]`" x-model="med.dose" placeholder="500mg"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="py-2 pr-2">
                                        <select :name="`medicines[${index}][freq]`" x-model="med.freq"
                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="OD">OD</option>
                                            <option value="BD">BD</option>
                                            <option value="TDS">TDS</option>
                                            <option value="QID">QID</option>
                                            <option value="SOS">SOS</option>
                                        </select>
                                    </td>
                                    <td class="py-2 pr-2">
                                        <input type="text" :name="`medicines[${index}][duration]`" x-model="med.duration" placeholder="5 days"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="py-2 pr-2">
                                        <input type="text" :name="`medicines[${index}][instructions]`" x-model="med.instructions" placeholder="After food"
                                               class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </td>
                                    <td class="py-2">
                                        <button type="button" @click="removeMedicine(index)" class="text-red-500 hover:text-red-700">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <button type="button" @click="addMedicine()" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                    <i class="fa-solid fa-plus"></i> Add Medicine
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <x-input-label for="notes" value="Special Instructions" />
                <textarea id="notes" name="notes" rows="3"
                    class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('prescriptions.index') }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</a>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save Prescription</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            function prescriptionForm() {
                return {
                    patient_id: '',
                    patient_name: '{{ old('patient_name') }}',
                    doctor_name: '{{ old('doctor_name') }}',
                    patients: @json($patients),
                    medicines: [{ name: '', dose: '', freq: 'OD', duration: '', instructions: '' }],

                    init() {
                        const self = this;
                        $(this.$refs.patientSelect).select2({ width: '100%' }).on('change', function () {
                            self.onPatientChange(this.value);
                        });
                    },

                    onPatientChange(id) {
                        this.patient_id = id;
                        const p = this.patients.find(p => p.id == id);
                        if (p) this.patient_name = p.name;
                    },

                    addMedicine() {
                        this.medicines.push({ name: '', dose: '', freq: 'OD', duration: '', instructions: '' });
                    },

                    removeMedicine(index) {
                        if (this.medicines.length > 1) this.medicines.splice(index, 1);
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
