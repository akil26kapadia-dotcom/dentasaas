<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} @isset($header) - {{ $header }} @endisset</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

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
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
                 class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden" x-transition.opacity></div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0">

                <div class="flex items-center gap-2 h-16 px-6 border-b border-gray-100 dark:border-gray-700 shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-semibold text-lg" style="color: {{ tenant()?->theme_color ?? '#1649FF' }}">
                        <i class="fa-solid fa-tooth"></i>
                        <span>{{ tenant()?->name ?? config('app.name') }}</span>
                    </a>
                </div>

                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
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
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ $active ? 'bg-indigo-50 text-indigo-700 dark:bg-gray-700 dark:text-white' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700' }}">
                            <i class="fa-solid {{ $item['icon'] }} w-4 text-center"></i>
                            <span>{{ __($item['label']) }}</span>
                        </a>
                    @endforeach
                </nav>

                @if (tenant())
                    <div class="p-3 border-t border-gray-100 dark:border-gray-700">
                        <x-plan-usage-bar :usage="app(\App\Services\DashboardService::class)->getPlanUsage(tenant())" />
                    </div>
                @endif
            </aside>

            <!-- Main column -->
            <div class="flex-1 flex flex-col overflow-hidden">

                <!-- Topbar -->
                <header class="h-16 shrink-0 flex items-center justify-between gap-4 px-4 sm:px-6 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = ! sidebarOpen" class="lg:hidden text-gray-500 dark:text-gray-300">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        <span class="hidden sm:block font-medium text-gray-700 dark:text-gray-200">{{ tenant()?->name }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Language toggle -->
                        <div class="flex items-center text-sm border border-gray-200 dark:border-gray-600 rounded-full overflow-hidden">
                            <a href="{{ url()->current() }}?lang=en" class="px-2.5 py-1 {{ app()->getLocale() === 'en' ? 'bg-indigo-600 text-white' : 'text-gray-500 dark:text-gray-300' }}">EN</a>
                            <a href="{{ url()->current() }}?lang=hi" class="px-2.5 py-1 {{ app()->getLocale() === 'hi' ? 'bg-indigo-600 text-white' : 'text-gray-500 dark:text-gray-300' }}">HI</a>
                        </div>

                        <!-- Notification bell -->
                        @php
                            $unreadNotifications = auth()->user()?->unreadNotifications;
                            $recentNotifications = auth()->user()?->notifications()->latest()->take(10)->get();
                        @endphp
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="relative text-gray-500 dark:text-gray-300 hover:text-gray-700">
                                <i class="fa-regular fa-bell text-lg"></i>
                                @if ($unreadNotifications && $unreadNotifications->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] leading-none rounded-full px-1.5 py-0.5">{{ $unreadNotifications->count() }}</span>
                                @endif
                            </button>
                            <div x-show="open" x-cloak @click.outside="open = false"
                                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg shadow-lg py-2 text-sm z-50">
                                <div class="px-4 py-2 flex items-center justify-between border-b border-gray-100 dark:border-gray-700">
                                    <span class="font-medium text-gray-700 dark:text-gray-200">Notifications</span>
                                    <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800">View all</a>
                                </div>

                                <div class="max-h-80 overflow-y-auto">
                                    @forelse ($recentNotifications ?? [] as $notification)
                                        <a href="{{ $notification->data['url'] ?? '#' }}"
                                           class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->read_at ? '' : 'bg-indigo-50/50 dark:bg-gray-700/50' }}">
                                            <span class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-gray-700 text-indigo-600 flex items-center justify-center shrink-0">
                                                <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }} text-xs"></i>
                                            </span>
                                            <span class="flex-1 min-w-0">
                                                <span class="block font-medium text-gray-800 dark:text-gray-200 truncate">{{ $notification->data['title'] ?? 'Notification' }}</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400 truncate">{{ $notification->data['body'] ?? '' }}</span>
                                                <span class="block text-[10px] text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</span>
                                            </span>
                                        </a>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-400">No notifications yet.</div>
                                    @endforelse
                                </div>

                                @if ($unreadNotifications && $unreadNotifications->count() > 0)
                                    <form method="POST" action="{{ route('notifications.read-all') }}" class="border-t border-gray-100 dark:border-gray-700">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full text-center px-4 py-2 text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                            Mark all read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <!-- User dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold"
                                          style="background-color: {{ auth()->user()?->color ?? '#1649FF' }}">
                                        {{ strtoupper(substr(auth()->user()?->name ?? '?', 0, 1)) }}
                                    </span>
                                    <span class="hidden sm:block text-sm text-gray-600 dark:text-gray-300">{{ auth()->user()?->name }}</span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </header>

                @isset($header)
                    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
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
