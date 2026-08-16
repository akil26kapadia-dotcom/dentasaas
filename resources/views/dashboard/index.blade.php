<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6" x-data="{ patientModal: false, apptModal: false, invoiceModal: false }">

        @if (session('warning'))
            <div class="rounded-lg bg-amber-50 border border-amber-200 px-4 py-3 text-sm text-amber-800">{{ session('warning') }}</div>
        @endif

        <!-- Plan usage -->
        <x-plan-usage-bar :usage="$planUsage" class="max-w-md" />

        <!-- Stats cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $cards = [
                    ['label' => 'Total Patients', 'icon' => 'fa-users', 'value' => number_format($stats['total_patients']), 'trend' => $trends['total_patients']],
                    ['label' => "Today's Appointments", 'icon' => 'fa-calendar-check', 'value' => number_format($stats['today_appointments']), 'trend' => $trends['today_appointments']],
                    ['label' => 'Monthly Revenue', 'icon' => 'fa-indian-rupee-sign', 'value' => '₹'.number_format($stats['monthly_revenue'], 2), 'trend' => $trends['monthly_revenue']],
                    ['label' => 'Pending', 'icon' => 'fa-hourglass-half', 'value' => number_format($stats['pending_count']), 'trend' => $trends['pending_count']],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-800">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </span>
                    <div class="mt-5 flex items-end justify-between">
                        <div>
                            <span class="text-sm text-gray-500">{{ $card['label'] }}</span>
                            <h4 class="mt-2 text-title-sm font-bold text-gray-800">{{ $card['value'] }}</h4>
                        </div>
                        <span class="flex items-center gap-1 rounded-full py-0.5 pl-2 pr-2.5 text-sm font-medium {{ $card['trend'] >= 0 ? 'bg-success-50 text-success-600' : 'bg-error-50 text-error-600' }}">
                            <i class="fa-solid {{ $card['trend'] >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} text-xs"></i>
                            {{ number_format(abs($card['trend']), 1) }}%
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quick actions -->
        <div class="flex flex-wrap gap-3">
            <button @click="patientModal = true" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> New Patient
            </button>

            @if (Route::has('appointments.store'))
                <button @click="apptModal = true" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium hover:bg-gray-50">
                    <i class="fa-solid fa-plus"></i> New Appointment
                </button>
            @else
                <button disabled title="Coming soon" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 text-sm font-medium cursor-not-allowed">
                    <i class="fa-solid fa-plus"></i> New Appointment
                </button>
            @endif

            @if (Route::has('invoices.store'))
                <button @click="invoiceModal = true" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-200 text-sm font-medium hover:bg-gray-50">
                    <i class="fa-solid fa-plus"></i> New Invoice
                </button>
            @else
                <button disabled title="Coming soon" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 text-sm font-medium cursor-not-allowed">
                    <i class="fa-solid fa-plus"></i> New Invoice
                </button>
            @endif
        </div>

        <!-- Two columns -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 rounded-2xl border border-gray-200 bg-white p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Today's Schedule</h3>
                <div class="overflow-x-auto">
                    <table id="today-schedule-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Time</th>
                                <th class="py-2">Patient</th>
                                <th class="py-2">Service</th>
                                <th class="py-2">Doctor</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($todayAppointments as $appt)
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2">{{ \Illuminate\Support\Carbon::parse($appt->appt_time)->format('h:i A') }}</td>
                                    <td class="py-2">{{ $appt->patient_name }}</td>
                                    <td class="py-2">{{ $appt->service_name }}</td>
                                    <td class="py-2">{{ $appt->doctor_name ?? '—' }}</td>
                                    <td class="py-2"><x-status-badge :status="$appt->status" /></td>
                                    <td class="py-2">
                                        @if ($appt->patient_id && Route::has('patients.show'))
                                            <a href="{{ route('patients.show', $appt->patient_id) }}" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-400">No appointments scheduled for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Revenue (Last 6 Months)</h3>
                <canvas id="revenue-chart" height="220"></canvas>
            </div>
        </div>

        <!-- Recent activity -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Recent Activity</h3>
            <ul class="space-y-3">
                @forelse ($recentActivity as $item)
                    <li class="flex items-center gap-3 text-sm">
                        <span class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-gray-700 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid {{ $item['icon'] }} text-xs"></i>
                        </span>
                        <span class="text-gray-700 dark:text-gray-300 flex-1">{{ $item['description'] }}</span>
                        <span class="text-gray-400 text-xs whitespace-nowrap">{{ $item['created_at']->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="text-gray-400 text-sm">No recent activity.</li>
                @endforelse
            </ul>
        </div>

        <!-- Quick add patient modal -->
        <div x-show="patientModal" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center px-4"
             style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="patientModal = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-4">New Patient</h3>
                <form method="POST" action="{{ route('patients.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="quick_name" value="Name" />
                        <x-text-input id="quick_name" name="name" class="block mt-1 w-full" required />
                    </div>
                    <div>
                        <x-input-label for="quick_phone" value="Phone" />
                        <x-text-input id="quick_phone" name="phone" class="block mt-1 w-full" />
                    </div>
                    <div>
                        <x-input-label for="quick_email" value="Email" />
                        <x-text-input id="quick_email" type="email" name="email" class="block mt-1 w-full" />
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="patientModal = false" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save Patient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Chart(document.getElementById('revenue-chart'), {
                    type: 'bar',
                    data: {
                        labels: @json($chart['labels']),
                        datasets: [{
                            label: 'Revenue',
                            data: @json($chart['data']),
                            backgroundColor: '#465fff',
                            borderRadius: 4,
                        }],
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } },
                    },
                });

                $('#today-schedule-table').DataTable({
                    paging: true,
                    searching: false,
                    info: false,
                    lengthChange: false,
                    pageLength: 5,
                });
            });
        </script>
    @endpush
</x-app-layout>
