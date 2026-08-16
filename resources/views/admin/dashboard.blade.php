<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Super Admin Dashboard
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $cards = [
                    ['label' => 'Total Clinics', 'icon' => 'fa-hospital', 'value' => $stats['total_clinics']],
                    ['label' => 'Active Clinics', 'icon' => 'fa-circle-check', 'value' => $stats['active_clinics']],
                    [
                        'label' => 'Free / Paid',
                        'icon' => 'fa-scale-balanced',
                        'value' => $stats['free_count'] . ' / ' . $stats['paid_count'],
                    ],
                    ['label' => 'New This Week', 'icon' => 'fa-arrow-trend-up', 'value' => $stats['new_this_week']],
                    ['label' => 'Total Users', 'icon' => 'fa-users', 'value' => $stats['total_users']],
                    ['label' => 'Pending Requests', 'icon' => 'fa-user-plus', 'value' => $stats['pending_requests']],
                ];
            @endphp
            @foreach ($cards as $card)
                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <span class="w-10 h-10 rounded-lg bg-gray-900 text-white flex items-center justify-center">
                        <i class="fa-solid {{ $card['icon'] }}"></i>
                    </span>
                    <p class="text-2xl font-semibold text-gray-900 mt-3">{{ $card['value'] }}</p>
                    <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-900">Recent Clinics</h3>
                <a href="{{ route('admin.clinics.index') }}"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View all</a>
            </div>

            @if ($recentClinics->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">No clinics yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">Clinic</th>
                                <th class="py-2">Plan</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentClinics as $clinic)
                                <tr class="border-t border-gray-100">
                                    <td class="py-2 font-medium text-gray-800">{{ $clinic->name }}</td>
                                    <td class="py-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 uppercase">{{ $clinic->plan }}</span>
                                    </td>
                                    <td class="py-2"><x-status-badge :status="$clinic->status" /></td>
                                    <td class="py-2">{{ $clinic->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
