<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DentaSaaS') }} @isset($header) - {{ $header }} @endisset</title>

        <!-- Font Awesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

        <!-- DataTables (Tailwind theme) -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.tailwindcss.min.css">

        {{-- Alpine.js ships bundled via Vite (resources/js/app.js) — no CDN copy here to avoid a double-init. --}}

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.tailwindcss.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" x-transition.opacity></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-50 flex h-screen w-[260px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 transition-transform duration-200 ease-linear lg:static lg:translate-x-0">

                <div class="flex items-center gap-2 pt-6 pb-6 shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold text-lg" style="color: {{ tenant()?->theme_color ?? '#465fff' }}">
                        <i class="fa-solid fa-tooth"></i>
                        <span>{{ tenant()?->name ?? config('app.name') }}</span>
                    </a>
                </div>

                <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
                    <h3 class="mb-4 text-xs uppercase leading-5 text-gray-400">Menu</h3>

                    <nav class="flex flex-col gap-1 mb-6">
                        @foreach ([
                            ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => 'dashboard'],
                            ['label' => 'Appointments', 'icon' => 'fa-calendar-check', 'route' => 'appointments.index'],
                            ['label' => 'Patients', 'icon' => 'fa-users', 'route' => 'patients.index'],
                            ['label' => 'Services', 'icon' => 'fa-tooth', 'route' => 'services.index'],
                            ['label' => 'Invoices', 'icon' => 'fa-file-invoice', 'route' => 'invoices.index'],
                            ['label' => 'Prescriptions', 'icon' => 'fa-prescription-bottle-medical', 'route' => 'prescriptions.index'],
                            ['label' => 'Treatment Plans', 'icon' => 'fa-diagram-project', 'route' => 'treatment-plans.index'],
                            ['label' => 'Analytics', 'icon' => 'fa-chart-bar', 'route' => 'analytics.index'],
                            ['label' => 'Doctors', 'icon' => 'fa-user-doctor', 'route' => 'doctors.index'],
                            ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => 'settings.index'],
                        ] as $item)
                            @php $active = Route::has($item['route']) && request()->routeIs($item['route'].'*'); @endphp
                            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                               class="menu-item group {{ $active ? 'menu-item-active' : 'menu-item-inactive' }}">
                                <i class="fa-solid {{ $item['icon'] }} w-5 text-center {{ $active ? 'menu-item-icon-active' : 'menu-item-icon-inactive' }}"></i>
                                <span>{{ __($item['label']) }}</span>
                            </a>
                        @endforeach
                    </nav>

                    @if (tenant())
                        <div class="mb-6">
                            <x-plan-usage-bar :usage="app(\App\Services\DashboardService::class)->getPlanUsage(tenant())" />
                        </div>
                    @endif
                </div>
            </aside>

            <!-- Main column -->
            <div class="flex-1 flex flex-col overflow-hidden">

                <!-- Topbar -->
                <header class="sticky top-0 z-30 flex w-full border-b border-gray-200 bg-white">
                    <div class="flex grow items-center justify-between px-4 py-3 sm:px-6 lg:px-6">
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = ! sidebarOpen"
                                    class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 lg:hidden">
                                <i class="fa-solid fa-bars"></i>
                            </button>
                            <span class="hidden sm:block font-medium text-gray-700">{{ tenant()?->name }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Language toggle -->
                            @if (tenant())
                                <div class="hidden sm:flex items-center text-sm border border-gray-200 rounded-full overflow-hidden">
                                    @foreach (['en' => 'EN', 'hi' => 'HI'] as $code => $label)
                                        <form method="POST" action="{{ route('settings.language') }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="language" value="{{ $code }}">
                                            <button type="submit" class="px-2.5 py-1 {{ app()->getLocale() === $code ? 'bg-indigo-500 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                                                {{ $label }}
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Notification bell -->
                            @php
                                $unreadNotifications = auth()->user()?->unreadNotifications;
                                $recentNotifications = auth()->user()?->notifications()->latest()->take(10)->get();
                            @endphp
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = ! open"
                                        class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                                    @if ($unreadNotifications && $unreadNotifications->count() > 0)
                                        <span class="absolute top-0.5 right-0 z-10 h-2 w-2 rounded-full bg-orange-400">
                                            <span class="absolute -z-10 inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
                                        </span>
                                    @endif
                                    <i class="fa-regular fa-bell text-lg"></i>
                                </button>
                                <div x-show="open" x-cloak @click.outside="open = false"
                                     class="absolute right-0 mt-3 flex w-80 flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg z-50 sm:w-96">
                                    <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-3">
                                        <h5 class="font-semibold text-gray-800">{{ __('Notifications') }}</h5>
                                        <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-indigo-500 hover:text-indigo-600">{{ __('View all') }}</a>
                                    </div>

                                    <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                        @forelse ($recentNotifications ?? [] as $notification)
                                            <a href="{{ $notification->data['url'] ?? '#' }}"
                                               class="flex items-start gap-3 rounded-lg p-3 hover:bg-gray-100 {{ $notification->read_at ? '' : 'bg-indigo-50/60' }}">
                                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                                                    <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }} text-xs"></i>
                                                </span>
                                                <span class="min-w-0">
                                                    <span class="block truncate text-sm font-medium text-gray-800">{{ $notification->data['title'] ?? 'Notification' }}</span>
                                                    <span class="block truncate text-xs text-gray-500">{{ $notification->data['body'] ?? '' }}</span>
                                                    <span class="mt-0.5 block text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                                </span>
                                            </a>
                                        @empty
                                            <div class="px-3 py-6 text-center text-gray-400">{{ __('No notifications yet.') }}</div>
                                        @endforelse
                                    </div>

                                    @if ($unreadNotifications && $unreadNotifications->count() > 0)
                                        <form method="POST" action="{{ route('notifications.read-all') }}" class="mt-3 border-t border-gray-100 pt-3">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="flex w-full justify-center rounded-lg border border-gray-200 p-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                                {{ __('Mark all read') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- User dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = ! open" class="flex items-center gap-2">
                                    <span class="flex h-11 w-11 items-center justify-center rounded-full text-white text-sm font-semibold"
                                          style="background-color: {{ auth()->user()?->color ?? '#465fff' }}">
                                        {{ strtoupper(substr(auth()->user()?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()?->name }}</span>
                                    <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden sm:block" :class="open ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open" x-cloak @click.outside="open = false"
                                     class="absolute right-0 mt-3 flex w-56 flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg z-50">
                                    <div class="mb-2 border-b border-gray-100 pb-3 px-1">
                                        <span class="block text-sm font-medium text-gray-700">{{ auth()->user()?->name }}</span>
                                        <span class="block text-xs text-gray-400">{{ auth()->user()?->email }}</span>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="menu-item menu-item-inactive">
                                        <i class="fa-solid fa-user w-5 text-center menu-item-icon-inactive"></i>
                                        {{ __('Profile') }}
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="menu-item menu-item-inactive w-full text-left">
                                            <i class="fa-solid fa-right-from-bracket w-5 text-center menu-item-icon-inactive"></i>
                                            {{ __('Log Out') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                @if (tenant())
                    <x-announcement-banner />
                @endif

                @isset($header)
                    <div class="bg-white border-b border-gray-200">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </div>
                @endisset

                <main class="flex-1 overflow-y-auto">
                    @if (tenant())
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                            <x-upgrade-prompt :usage="app(\App\Services\DashboardService::class)->getPlanUsage(tenant())" />
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
