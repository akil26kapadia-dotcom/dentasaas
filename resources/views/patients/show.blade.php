<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Patient Profile</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium hover:bg-gray-50">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                <a href="{{ route('patients.index') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700">Back to list</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'info' }">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex flex-col items-center text-center">
                    <span class="w-20 h-20 rounded-full flex items-center justify-center text-white text-2xl font-semibold" style="background-color:#1649FF;">
                        {{ strtoupper(substr($patient->name, 0, 1)) }}
                    </span>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">{{ $patient->name }}</h3>
                    @if ($patient->blood_group)
                        <span class="mt-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700">
                            <i class="fa-solid fa-droplet mr-1"></i> {{ $patient->blood_group }}
                        </span>
                    @endif
                </div>

                <dl class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Phone</dt>
                        <dd class="text-gray-800 dark:text-gray-200">{{ $patient->phone ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="text-gray-800 dark:text-gray-200">{{ $patient->email ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Gender</dt>
                        <dd class="text-gray-800 dark:text-gray-200">{{ $patient->gender ? ucfirst($patient->gender) : '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Date of Birth</dt>
                        <dd class="text-gray-800 dark:text-gray-200">{{ optional($patient->dob)->format('d M Y') ?: '—' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Added</dt>
                        <dd class="text-gray-800 dark:text-gray-200">{{ $patient->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>

                @if ($patient->allergies)
                    <div class="mt-6">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Allergies</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $patient->allergies }}
                        </span>
                    </div>
                @endif

                @if ($patient->address)
                    <div class="mt-6">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Address</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $patient->address }}</p>
                    </div>
                @endif

                @if ($patient->notes)
                    <div class="mt-6">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Notes</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $patient->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Tabs -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex flex-wrap gap-1 border-b border-gray-100 dark:border-gray-700 mb-4">
                    @php
                        $tabs = [
                            'info' => 'Info',
                            'appointments' => 'Appointments ('.$patient->appointments->count().')',
                            'invoices' => 'Invoices ('.$patient->invoices->count().')',
                            'prescriptions' => 'Prescriptions ('.$patient->prescriptions->count().')',
                            'treatment_plans' => 'Treatment Plans ('.$patient->treatmentPlans->count().')',
                        ];
                    @endphp
                    @foreach ($tabs as $key => $label)
                        <button @click="tab = '{{ $key }}'"
                                :class="tab === '{{ $key }}' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 dark:text-gray-400'"
                                class="px-4 py-2 text-sm font-medium border-b-2 -mb-px">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div x-show="tab === 'info'">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Overview for {{ $patient->name }}. Use the tabs above to browse appointments, invoices, prescriptions and treatment plans for this patient.
                    </p>
                </div>

                <div x-show="tab === 'appointments'" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Date</th>
                                <th class="py-2">Time</th>
                                <th class="py-2">Service</th>
                                <th class="py-2">Doctor</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->appointments as $appt)
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2">{{ $appt->appt_date->format('d M Y') }}</td>
                                    <td class="py-2">{{ \Illuminate\Support\Carbon::parse($appt->appt_time)->format('h:i A') }}</td>
                                    <td class="py-2">{{ $appt->service_name }}</td>
                                    <td class="py-2">{{ $appt->doctor_name ?? '—' }}</td>
                                    <td class="py-2"><x-status-badge :status="$appt->status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-6 text-center text-gray-400">No appointments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="tab === 'invoices'" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Invoice #</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Total</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->invoices as $invoice)
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2">{{ $invoice->invoice_no }}</td>
                                    <td class="py-2">{{ $invoice->invoice_date->format('d M Y') }}</td>
                                    <td class="py-2">{{ $invoice->formatted_total }}</td>
                                    <td class="py-2"><x-status-badge :status="$invoice->status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-6 text-center text-gray-400">No invoices yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="tab === 'prescriptions'" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Date</th>
                                <th class="py-2">Doctor</th>
                                <th class="py-2">Diagnosis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->prescriptions as $rx)
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2">{{ $rx->rx_date->format('d M Y') }}</td>
                                    <td class="py-2">{{ $rx->doctor_name ?? '—' }}</td>
                                    <td class="py-2">{{ $rx->diagnosis ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-6 text-center text-gray-400">No prescriptions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div x-show="tab === 'treatment_plans'" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Treatment</th>
                                <th class="py-2">Progress</th>
                                <th class="py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($patient->treatmentPlans as $plan)
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2">{{ $plan->treatment }}</td>
                                    <td class="py-2">{{ $plan->completed_count }}/{{ $plan->total_sessions }} ({{ $plan->progress_pct }}%)</td>
                                    <td class="py-2"><x-status-badge :status="$plan->status" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="py-6 text-center text-gray-400">No treatment plans yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
