<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Analytics</h2>
            <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                @foreach (['this_month' => 'This Month', 'last_3_months' => 'Last 3 Months', 'this_year' => 'This Year'] as $key => $label)
                    <a href="{{ route('analytics.index', ['period' => $key]) }}"
                       class="px-3 py-2 text-sm font-medium {{ $period === $key ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        <!-- Stats cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $cards = [
                    ['label' => 'Total Revenue', 'icon' => 'fa-indian-rupee-sign', 'value' => '₹' . number_format($stats['total_revenue'], 2)],
                    ['label' => 'New Patients', 'icon' => 'fa-users', 'value' => number_format($stats['total_patients'])],
                    ['label' => 'Appointments', 'icon' => 'fa-calendar-check', 'value' => number_format($stats['appointments_count'])],
                    ['label' => 'Avg. Invoice Value', 'icon' => 'fa-file-invoice', 'value' => '₹' . number_format($stats['avg_invoice_value'], 2)],
                ];
            @endphp
            @foreach ($cards as $card)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                    <span class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-gray-700 text-indigo-600 flex items-center justify-center">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </span>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white mt-3">{{ $card['value'] }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        <!-- Monthly revenue -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Monthly Revenue (Last 12 Months)</h3>
            <canvas id="monthly-revenue-chart" height="90"></canvas>
        </div>

        @if ($level === 'full')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Appointment Trend (Last 30 Days)</h3>
                    <canvas id="appointment-trend-chart" height="110"></canvas>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Status Breakdown</h3>
                    <canvas id="status-donut-chart" height="110"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Top Services</h3>
                @if (empty($topServices))
                    <p class="text-sm text-gray-400">No service revenue recorded yet.</p>
                @else
                    @php $maxRevenue = max(array_column($topServices, 'revenue')) ?: 1; @endphp
                    <div class="space-y-4">
                        @foreach ($topServices as $index => $service)
                            <div class="flex items-center gap-4">
                                <span class="w-6 text-sm font-semibold text-gray-400">#{{ $index + 1 }}</span>
                                <div class="flex-1">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium text-gray-800 dark:text-gray-200">{{ $service['service_name'] }}</span>
                                        <span class="text-gray-500 dark:text-gray-400">{{ $service['count'] }} sold &bull; ₹{{ number_format($service['revenue'], 2) }}</span>
                                    </div>
                                    <div class="w-full h-1.5 rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div class="h-1.5 rounded-full" style="width: {{ ($service['revenue'] / $maxRevenue) * 100 }}%; background-color:#465fff;"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Chart(document.getElementById('monthly-revenue-chart'), {
                    type: 'bar',
                    data: {
                        labels: @json($monthlyRevenue['labels']),
                        datasets: [{
                            label: 'Revenue',
                            data: @json($monthlyRevenue['data']),
                            backgroundColor: '#465fff',
                            borderRadius: 4,
                        }],
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } },
                    },
                });

                @if ($level === 'full')
                    new Chart(document.getElementById('appointment-trend-chart'), {
                        type: 'line',
                        data: {
                            labels: @json($appointmentTrend['labels']),
                            datasets: [{
                                label: 'Appointments',
                                data: @json($appointmentTrend['data']),
                                borderColor: '#465fff',
                                backgroundColor: 'rgba(22, 73, 255, 0.1)',
                                tension: 0.3,
                                fill: true,
                            }],
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                        },
                    });

                    new Chart(document.getElementById('status-donut-chart'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($statusBreakdown['labels']),
                            datasets: [{
                                data: @json($statusBreakdown['data']),
                                backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                            }],
                        },
                        options: {
                            plugins: { legend: { position: 'bottom' } },
                        },
                    });
                @endif
            });
        </script>
    @endpush
</x-app-layout>
