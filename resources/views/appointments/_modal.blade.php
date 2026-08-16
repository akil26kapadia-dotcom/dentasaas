<div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4"
    style="background-color: rgba(15,23,42,0.5);">
    <div @click.outside="modalOpen = false"
        class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="font-semibold text-lg text-gray-900 mb-4" x-text="editingId ? 'Edit Appointment' : 'New Appointment'">
        </h3>

        <div class="space-y-4">
            <div>
                <x-input-label value="Patient" />
                <select x-ref="patientSelect" class="block mt-1 w-full" style="width:100%">
                    <option value="">Search patient by name or phone…</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} — {{ $patient->phone }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="appt_patient_name" value="Patient Name" />
                <x-text-input id="appt_patient_name" class="block mt-1 w-full" x-model="form.patient_name" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label value="Service" />
                    <select
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        x-model="form.service_id" @change="onServiceChange($event.target.value)">
                        <option value="">Select service</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}
                                (₹{{ number_format($service->price, 0) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label value="Doctor" />
                    <select
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        x-model="form.user_id" @change="onDoctorChange($event.target.value)">
                        <option value="">Unassigned</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="appt_date" value="Date" />
                    <x-text-input id="appt_date" type="date" class="block mt-1 w-full" x-model="form.appt_date"
                        required />
                </div>
                <div>
                    <x-input-label for="appt_time" value="Time" />
                    <x-text-input id="appt_time" type="time" class="block mt-1 w-full" x-model="form.appt_time"
                        required />
                </div>
            </div>

            <div>
                <x-input-label for="appt_notes" value="Notes" />
                <textarea id="appt_notes" rows="2" x-model="form.notes"
                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <p x-show="formError" x-cloak x-text="formError" class="text-sm text-red-600"></p>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm text-gray-600">Cancel</button>
            <button type="button" @click="submit()"
                class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save
                Appointment</button>
        </div>
    </div>
</div>
