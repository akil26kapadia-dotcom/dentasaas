<div x-show="newPlanModal" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center px-4"
     style="background-color: rgba(15,23,42,0.5);">
    <div @click.outside="newPlanModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-4">New Treatment Plan</h3>

        <form method="POST" action="{{ route('treatment-plans.store') }}" class="space-y-4">
            @csrf

            <div>
                <x-input-label value="Patient" />
                <select x-ref="newPlanPatientSelect" style="width:100%" class="block mt-1">
                    <option value="">Search patient by name or phone…</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} — {{ $patient->phone }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="patient_id" x-model="newPlanForm.patient_id">
            </div>

            <div>
                <x-input-label for="plan_patient_name" value="Patient Name" />
                <x-text-input id="plan_patient_name" name="patient_name" class="block mt-1 w-full" x-model="newPlanForm.patient_name" required />
            </div>

            <div>
                <x-input-label for="plan_doctor" value="Doctor" />
                <select id="plan_doctor" name="doctor_name" x-model="newPlanForm.doctor_name"
                        class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select doctor</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->name }}">{{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="plan_treatment" value="Treatment" />
                <x-text-input id="plan_treatment" name="treatment" class="block mt-1 w-full" x-model="newPlanForm.treatment" placeholder="e.g. Root Canal" required />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="plan_total_sessions" value="Total Visits" />
                    <x-text-input id="plan_total_sessions" type="number" min="1" max="50" name="total_sessions" class="block mt-1 w-full" x-model.number="newPlanForm.total_sessions" required />
                </div>
                <div>
                    <x-input-label for="plan_status" value="Status" />
                    <select id="plan_status" name="status" x-model="newPlanForm.status"
                            class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="planned">Planned</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div>
                <x-input-label for="plan_notes" value="Notes" />
                <textarea id="plan_notes" name="notes" rows="2" x-model="newPlanForm.notes"
                    class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="newPlanModal = false" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Create Plan</button>
            </div>
        </form>
    </div>
</div>
