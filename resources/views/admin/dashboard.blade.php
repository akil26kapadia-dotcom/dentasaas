<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Super Admin Dashboard
        </h2>
    </x-slot>

    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');
        $firstName = \Illuminate\Support\Str::before(auth()->user()->name, ' ');
        $expiringCount = $expiringSoon->count();
        $attention = $stats['pending_requests'] + $expiringCount + $expiredCount;

        $summary = [];
        if ($stats['pending_requests'] > 0) {
            $summary[] = $stats['pending_requests'] . ' access ' . \Illuminate\Support\Str::plural('request', $stats['pending_requests']) . ' waiting for review';
        }
        if ($expiringCount > 0) {
            $summary[] = $expiringCount . ' ' . \Illuminate\Support\Str::plural('plan', $expiringCount) . ' expiring within 14 days';
        }
        if ($expiredCount > 0) {
            $summary[] = $expiredCount . ' expired ' . \Illuminate\Support\Str::plural('plan', $expiredCount);
        }

        $kpis = [
            ['label' => 'Monthly revenue', 'value' => '₹' . number_format($stats['mrr']), 'sub' => 'from ' . $stats['paid_count'] . ' paid ' . \Illuminate\Support\Str::plural('clinic', $stats['paid_count']), 'icon' => 'fa-indian-rupee-sign', 'chip' => 'bg-indigo-50 text-indigo-600', 'href' => route('admin.billing.index')],
            ['label' => 'Active clinics', 'value' => $stats['active_clinics'], 'sub' => 'of ' . $stats['total_clinics'] . ' total', 'icon' => 'fa-hospital', 'chip' => 'bg-emerald-50 text-emerald-600', 'href' => route('admin.clinics.index')],
            ['label' => 'Paid clinics', 'value' => $stats['paid_count'], 'sub' => $stats['free_count'] . ' on the free plan', 'icon' => 'fa-crown', 'chip' => 'bg-amber-50 text-amber-600', 'href' => route('admin.plans.index')],
            ['label' => 'Pending requests', 'value' => $stats['pending_requests'], 'sub' => $stats['pending_requests'] > 0 ? 'awaiting your review' : 'nothing waiting', 'icon' => 'fa-user-plus', 'chip' => $stats['pending_requests'] > 0 ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-500', 'href' => route('admin.access-requests.index')],
        ];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Welcome banner -->
        <div class="relative overflow-hidden rounded-2xl p-6 sm:p-8 text-white"
            style="background: linear-gradient(135deg, #0b1e3d 0%, #1a2f66 55%, #3a4fd6 130%);">
            <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full opacity-40 blur-3xl"
                style="background:#465fff;"></div>
            <div class="pointer-events-none absolute -bottom-24 left-1/3 h-56 w-56 rounded-full opacity-20 blur-3xl"
                style="background:#7c9bff;"></div>

            <div class="relative">
                <p class="text-xs uppercase tracking-wider text-white/60">{{ now()->format('l, d M Y') }}</p>
                <h3 class="mt-1 text-2xl sm:text-3xl font-bold">{{ $greeting }}, {{ $firstName }}</h3>
                <p class="mt-2 max-w-2xl text-sm sm:text-base text-white/75">
                    @if ($attention === 0)
                        Everything is running smoothly. No requests are waiting and no plans are about to expire.
                    @else
                        {{ ucfirst(implode(', ', $summary)) }}.
                    @endif
                </p>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ route('admin.clinics.index', ['new' => 1]) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-100">
                        <i class="fa-solid fa-plus text-xs"></i> New clinic
                    </a>
                    <a href="{{ route('admin.access-requests.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-white/25 px-4 py-2 text-sm font-medium text-white hover:bg-white/10">
                        Review requests
                        @if ($stats['pending_requests'] > 0)
                            <span class="rounded-full bg-orange-500 px-1.5 text-xs font-semibold leading-5">{{ $stats['pending_requests'] }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.plans.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-white/25 px-4 py-2 text-sm font-medium text-white hover:bg-white/10">
                        Manage plans
                    </a>
                </div>

                <p class="mt-5 text-xs text-white/55">
                    {{ $stats['new_this_week'] }} new {{ \Illuminate\Support\Str::plural('clinic', $stats['new_this_week']) }} this week
                    &middot; {{ $stats['total_users'] }} {{ \Illuminate\Support\Str::plural('user', $stats['total_users']) }} across all clinics
                </p>
            </div>
        </div>

        <!-- KPIs -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
            @foreach ($kpis as $kpi)
                <a href="{{ $kpi['href'] }}"
                    class="group rounded-2xl border border-gray-200 bg-white p-4 sm:p-6 transition hover:-translate-y-0.5 hover:shadow-md">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $kpi['chip'] }}">
                        <i class="fa-solid {{ $kpi['icon'] }}"></i>
                    </span>
                    <p class="mt-3 text-2xl sm:text-3xl font-semibold text-gray-900">{{ $kpi['value'] }}</p>
                    <p class="text-sm font-medium text-gray-700">{{ $kpi['label'] }}</p>
                    <p class="text-xs text-gray-400">{{ $kpi['sub'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Recent clinics -->
            <div class="lg:col-span-2 rounded-2xl border border-gray-200 bg-white">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Recent clinics</h3>
                    <a href="{{ route('admin.clinics.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View all</a>
                </div>

                @if ($recentClinics->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-10">No clinics yet.</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recentClinics as $clinic)
                            @php
                                $days = $clinic->plan !== 'free' && $clinic->plan_expires_at
                                    ? (int) today()->diffInDays($clinic->plan_expires_at, false)
                                    : null;
                            @endphp
                            <li>
                                <a href="{{ route('admin.clinics.index') }}"
                                    class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50">
                                    <span
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-sm font-semibold text-indigo-600">
                                        {{ strtoupper(mb_substr($clinic->name, 0, 1)) }}
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block truncate text-sm font-medium text-gray-900">{{ $clinic->name }}</span>
                                        <span class="block text-xs text-gray-400">Joined {{ $clinic->created_at->format('d M Y') }}</span>
                                    </span>
                                    <span class="flex flex-col items-end gap-1 sm:flex-row sm:items-center sm:gap-2">
                                        @if ($days !== null)
                                            @if ($days < 0)
                                                <span class="rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-medium text-red-600">Expired</span>
                                            @elseif ($days <= 14)
                                                <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700">{{ $days }}d left</span>
                                            @endif
                                        @endif
                                        <span
                                            class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium uppercase text-indigo-700">{{ $clinic->plan }}</span>
                                        <x-status-badge :status="$clinic->status" />
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Needs attention -->
            <div class="rounded-2xl border border-gray-200 bg-white">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Needs attention</h3>
                    @if ($attention > 0)
                        <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-semibold text-orange-700">{{ $attention }}</span>
                    @endif
                </div>

                @if ($attention === 0)
                    <div class="px-5 py-10 text-center">
                        <span
                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 text-lg">
                            <i class="fa-solid fa-check"></i>
                        </span>
                        <p class="mt-3 text-sm font-medium text-gray-800">You're all caught up</p>
                        <p class="text-xs text-gray-400">New requests and expiring plans will show up here.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($pendingRequests as $request)
                            <a href="{{ route('admin.access-requests.index') }}"
                                class="flex items-start gap-3 px-5 py-3 hover:bg-gray-50">
                                <span
                                    class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-orange-100 text-xs text-orange-600">
                                    <i class="fa-solid fa-user-plus"></i>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-medium text-gray-900">{{ $request->clinic_name }}</span>
                                    <span class="block truncate text-xs text-gray-500">{{ $request->name }} &middot; {{ $request->created_at->diffForHumans() }}</span>
                                </span>
                                <span class="text-xs font-medium text-indigo-600">Review</span>
                            </a>
                        @endforeach

                        @foreach ($expiringSoon as $clinic)
                            <a href="{{ route('admin.clinics.index') }}"
                                class="flex items-start gap-3 px-5 py-3 hover:bg-gray-50">
                                <span
                                    class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-50 text-xs text-amber-600">
                                    <i class="fa-solid fa-hourglass-half"></i>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block truncate text-sm font-medium text-gray-900">{{ $clinic->name }}</span>
                                    <span class="block truncate text-xs text-gray-500">{{ ucfirst($clinic->plan) }} plan ends {{ $clinic->plan_expires_at->format('d M') }}</span>
                                </span>
                                <span class="text-xs font-medium text-indigo-600">Extend</span>
                            </a>
                        @endforeach

                        @if ($expiredCount > 0)
                            <a href="{{ route('admin.clinics.index') }}"
                                class="flex items-start gap-3 px-5 py-3 hover:bg-gray-50">
                                <span
                                    class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-50 text-xs text-red-600">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-medium text-gray-900">{{ $expiredCount }} expired {{ \Illuminate\Support\Str::plural('plan', $expiredCount) }}</span>
                                    <span class="block text-xs text-gray-500">Extend or downgrade these clinics</span>
                                </span>
                                <span class="text-xs font-medium text-indigo-600">Open</span>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
