<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-init')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DentaSaaS') }} - Super Admin</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    @include('partials.chart-theme')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50">
    @php
        $pendingRequests = \App\Models\AccessRequest::where('status', 'pending')->count();
        $adminUnread = auth()->user()?->unreadNotifications;
        $adminRecent = auth()->user()?->notifications()->latest()->take(8)->get();
    @endphp

    <div x-data="{ sidebarOpen: false }" class="app-shell flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" x-transition.opacity></div>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 flex h-screen w-[260px] flex-col overflow-y-hidden bg-gray-900 px-5 transition-transform duration-200 ease-linear lg:static lg:translate-x-0">

            <div class="flex items-center gap-2 pt-6 pb-6 border-b border-gray-800 shrink-0">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 font-semibold text-lg text-white">
                    <i class="fa-solid fa-user-shield text-indigo-400"></i>
                    <span>Super Admin</span>
                </a>
            </div>

            <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar pt-6">
                <h3 class="mb-4 text-xs uppercase leading-5 text-gray-500">Menu</h3>

                <nav class="flex flex-col gap-1">
                    @foreach ([['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => 'admin.dashboard'], ['label' => 'Clinics', 'icon' => 'fa-hospital', 'route' => 'admin.clinics.index'], ['label' => 'Access Requests', 'icon' => 'fa-user-plus', 'route' => 'admin.access-requests.index'], ['label' => 'Billing', 'icon' => 'fa-indian-rupee-sign', 'route' => 'admin.billing.index'], ['label' => 'Plans & Pricing', 'icon' => 'fa-layer-group', 'route' => 'admin.plans.index'], ['label' => 'Announcements', 'icon' => 'fa-bullhorn', 'route' => 'admin.announcements.index']] as $item)
                        @php $active = Route::has($item['route']) && request()->routeIs($item['route'].'*'); @endphp
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                            class="menu-item {{ $active ? 'bg-indigo-500/15 text-indigo-400' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                            <i class="fa-solid {{ $item['icon'] }} w-5 text-center"></i>
                            <span>{{ $item['label'] }}</span>
                            @if ($item['route'] === 'admin.access-requests.index' && $pendingRequests > 0)
                                <span class="nav-badge">{{ $pendingRequests }}</span>
                            @endif
                        </a>
                    @endforeach
                </nav>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="sticky top-0 z-30 flex w-full items-center justify-between gap-4 border-b border-gray-200 bg-white px-4 py-3 sm:px-6">
                <button @click="sidebarOpen = ! sidebarOpen"
                    class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 lg:hidden">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="ml-auto flex items-center gap-3">
                <x-theme-toggle />
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = ! open" aria-label="Notifications"
                        class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                        @if ($adminUnread && $adminUnread->count() > 0)
                            <span class="absolute top-0.5 right-0 z-10 h-2.5 w-2.5 rounded-full bg-orange-400">
                                <span
                                    class="absolute -z-10 inline-flex h-full w-full animate-ping rounded-full bg-orange-400 opacity-75"></span>
                            </span>
                        @endif
                        <i class="fa-regular fa-bell text-lg"></i>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false"
                        class="fixed inset-x-4 top-20 z-50 flex flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg sm:absolute sm:inset-x-auto sm:right-0 sm:top-auto sm:mt-3 sm:w-96">
                        <div class="mb-3 flex items-center justify-between border-b border-gray-100 pb-3">
                            <h5 class="font-semibold text-gray-800">Notifications</h5>
                            @if ($adminUnread && $adminUnread->count() > 0)
                                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-medium text-indigo-500 hover:text-indigo-600">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse ($adminRecent ?? [] as $notification)
                                <a href="{{ $notification->data['url'] ?? '#' }}"
                                    class="flex items-start gap-3 rounded-lg p-3 hover:bg-gray-100 {{ $notification->read_at ? '' : 'bg-indigo-50/60' }}">
                                    <span
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-500">
                                        <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }} text-xs"></i>
                                    </span>
                                    <span class="min-w-0">
                                        <span class="block truncate text-sm font-medium text-gray-800">{{ $notification->data['title'] ?? 'Notification' }}</span>
                                        <span class="block truncate text-xs text-gray-500">{{ $notification->data['body'] ?? '' }}</span>
                                        <span class="mt-0.5 block text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                    </span>
                                </a>
                            @empty
                                <div class="px-3 py-8 text-center text-sm text-gray-400">You're all caught up.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = ! open" class="flex items-center gap-2">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-500 text-white text-sm font-semibold">
                            {{ strtoupper(substr(auth()->user()?->name ?? '?', 0, 1)) }}
                        </span>
                        <span
                            class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()?->name }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden sm:block"
                            :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open = false"
                        class="absolute right-0 mt-3 flex w-56 flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg z-50">
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
            </header>

            @isset($header)
                <div class="bg-white border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="flex-1 overflow-y-auto">
                {{ $slot }}
            </main>

            <!-- Mobile tab bar -->
            <nav class="app-tabbar" aria-label="Admin navigation">
                <a href="{{ route('admin.dashboard') }}"
                    class="app-tab {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <i class="fa-solid fa-gauge"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.clinics.index') }}"
                    class="app-tab {{ request()->routeIs('admin.clinics.*') ? 'is-active' : '' }}">
                    <i class="fa-solid fa-hospital"></i><span>Clinics</span>
                </a>
                <a href="{{ route('admin.access-requests.index') }}"
                    class="app-tab {{ request()->routeIs('admin.access-requests.*') ? 'is-active' : '' }}">
                    <i class="fa-solid fa-user-plus"></i><span>Requests</span>
                    @if ($pendingRequests > 0)
                        <span class="tab-badge">{{ $pendingRequests }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.billing.index') }}"
                    class="app-tab {{ request()->routeIs('admin.billing.*') ? 'is-active' : '' }}">
                    <i class="fa-solid fa-indian-rupee-sign"></i><span>Billing</span>
                </a>
                <button type="button" class="app-tab" :class="sidebarOpen ? 'is-active' : ''"
                    @click="sidebarOpen = ! sidebarOpen">
                    <i class="fa-solid fa-bars-staggered"></i><span>More</span>
                </button>
            </nav>
        </div>
    </div>

    @include('partials.tabbar-styles')

    <x-credentials-modal />

    @stack('scripts')
</body>

</html>
