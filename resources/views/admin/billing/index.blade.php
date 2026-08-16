<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Revenue &amp; Billing</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $cards = [
                    ['label' => 'Monthly Recurring Revenue', 'icon' => 'fa-indian-rupee-sign', 'value' => '₹'.number_format($stats['mrr'])],
                    ['label' => 'Active Paid Clinics', 'icon' => 'fa-hospital', 'value' => $stats['active_paid_clinics']],
                    ['label' => 'Avg. Revenue / Clinic', 'icon' => 'fa-chart-line', 'value' => '₹'.number_format($stats['avg_revenue'])],
                    ['label' => 'Expiring in 7 Days', 'icon' => 'fa-clock', 'value' => $stats['expiring_soon']],
                ];
            @endphp
            @foreach ($cards as $card)
                <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100">
                        <i class="fa-solid {{ $card['icon'] }} text-gray-700"></i>
                    </div>
                    <p class="mt-4 text-title-sm font-bold text-gray-900">{{ $card['value'] }}</p>
                    <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                <h3 class="font-semibold text-gray-900 mb-4">New Clinics — Last 6 Months</h3>
                <canvas id="growthChart" height="110"></canvas>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Plan Distribution</h3>
                <div class="space-y-4">
                    @foreach ($planDistribution as $row)
                        @php $total = max($planDistribution->sum('count'), 1); @endphp
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1">
                                <span class="font-medium text-gray-700">{{ $row['plan']->name }}</span>
                                <span>{{ $row['count'] }}</span>
                            </div>
                            <div class="w-full h-1.5 rounded-full bg-gray-100">
                                <div class="h-1.5 rounded-full" style="width: {{ round($row['count'] / $total * 100) }}%; background-color:#465fff;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Expiring Soon (next 7 days)</h3>
                <a href="{{ route('admin.clinics.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">All clinics</a>
            </div>

            @if ($expiringSoon->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">No clinics expiring in the next 7 days.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">Clinic</th>
                                <th class="py-2">Plan</th>
                                <th class="py-2">Expires</th>
                                <th class="py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expiringSoon as $clinic)
                                <tr class="border-t border-gray-100">
                                    <td class="py-2 font-medium text-gray-800">{{ $clinic->name }}</td>
                                    <td class="py-2"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 uppercase">{{ $clinic->plan }}</span></td>
                                    <td class="py-2 text-warning-600">{{ $clinic->plan_expires_at->format('d M Y') }}</td>
                                    <td class="py-2">
                                        <form method="POST" action="{{ route('admin.clinics.extend', $clinic) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                                <i class="fa-solid fa-calendar-plus"></i> Extend 30 days
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @if ($expired->isNotEmpty())
            <div class="rounded-2xl border border-red-100 bg-error-50 p-5 md:p-6">
                <h3 class="font-semibold text-error-600 mb-4"><i class="fa-solid fa-triangle-exclamation"></i> Expired Plans Still Marked Active</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">Clinic</th>
                                <th class="py-2">Plan</th>
                                <th class="py-2">Expired On</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expired as $clinic)
                                <tr class="border-t border-red-100">
                                    <td class="py-2 font-medium text-gray-800">{{ $clinic->name }}</td>
                                    <td class="py-2 uppercase text-xs text-gray-500">{{ $clinic->plan }}</td>
                                    <td class="py-2 text-error-600">{{ $clinic->plan_expires_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-500 mt-3">These will be auto-downgraded to Free by the scheduled plan-expiry check.</p>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            new Chart(document.getElementById('growthChart'), {
                type: 'bar',
                data: {
                    labels: @json($growth['labels']),
                    datasets: [{
                        label: 'New Clinics',
                        data: @json($growth['data']),
                        backgroundColor: '#465fff',
                        borderRadius: 6,
                        maxBarThickness: 36,
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
                },
            });
        </script>
    @endpush
</x-admin-layout>
