<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'DentaSaaS') }} - Super Admin</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.tailwindcss.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.tailwindcss.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
                 class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden" x-transition.opacity></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-50 flex h-screen w-[260px] flex-col overflow-y-hidden bg-gray-900 px-5 transition-transform duration-200 ease-linear lg:static lg:translate-x-0">

                <div class="flex items-center gap-2 pt-6 pb-6 border-b border-gray-800 shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-semibold text-lg text-white">
                        <i class="fa-solid fa-user-shield text-indigo-400"></i>
                        <span>Super Admin</span>
                    </a>
                </div>

                <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar pt-6">
                    <h3 class="mb-4 text-xs uppercase leading-5 text-gray-500">Menu</h3>

                    <nav class="flex flex-col gap-1">
                        @foreach ([
                            ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => 'admin.dashboard'],
                            ['label' => 'Clinics', 'icon' => 'fa-hospital', 'route' => 'admin.clinics.index'],
                            ['label' => 'Access Requests', 'icon' => 'fa-user-plus', 'route' => 'admin.access-requests.index'],
                            ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => 'admin.settings.index'],
                        ] as $item)
                            @php $active = Route::has($item['route']) && request()->routeIs($item['route'].'*'); @endphp
                            <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                               class="menu-item {{ $active ? 'bg-indigo-500/15 text-indigo-400' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}">
                                <i class="fa-solid {{ $item['icon'] }} w-5 text-center"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="sticky top-0 z-30 flex w-full items-center justify-between gap-4 border-b border-gray-200 bg-white px-4 py-3 sm:px-6">
                    <button @click="sidebarOpen = ! sidebarOpen"
                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 text-gray-500 lg:hidden">
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="relative ml-auto" x-data="{ open: false }">
                        <button @click="open = ! open" class="flex items-center gap-2">
                            <span class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-500 text-white text-sm font-semibold">
                                {{ strtoupper(substr(auth()->user()?->name ?? '?', 0, 1)) }}
                            </span>
                            <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()?->name }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-400 hidden sm:block" :class="open ? 'rotate-180' : ''"></i>
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
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
