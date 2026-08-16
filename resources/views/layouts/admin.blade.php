<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Super Admin</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.tailwindcss.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.tailwindcss.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

            <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
                 class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden" x-transition.opacity></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                   class="fixed inset-y-0 left-0 z-40 w-64 flex flex-col bg-gray-900 text-gray-200 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0">

                <div class="flex items-center gap-2 h-16 px-6 border-b border-gray-800 shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 font-semibold text-lg text-white">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Super Admin</span>
                    </a>
                </div>

                <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                    @foreach ([
                        ['label' => 'Dashboard', 'icon' => 'fa-gauge', 'route' => 'admin.dashboard'],
                        ['label' => 'Clinics', 'icon' => 'fa-hospital', 'route' => 'admin.clinics.index'],
                        ['label' => 'Access Requests', 'icon' => 'fa-user-plus', 'route' => 'admin.access-requests.index'],
                        ['label' => 'Settings', 'icon' => 'fa-gear', 'route' => 'admin.settings.index'],
                    ] as $item)
                        @php $active = Route::has($item['route']) && request()->routeIs($item['route'].'*'); @endphp
                        <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                           class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ $active ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                            <i class="fa-solid {{ $item['icon'] }} w-4 text-center"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </aside>

            <div class="flex-1 flex flex-col overflow-hidden">
                <header class="h-16 shrink-0 flex items-center justify-between gap-4 px-4 sm:px-6 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <button @click="sidebarOpen = ! sidebarOpen" class="lg:hidden text-gray-500 dark:text-gray-300">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                {{ auth()->user()?->name }}
                                <i class="fa-solid fa-chevron-down text-xs"></i>
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
                </header>

                @isset($header)
                    <div class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
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
    </body>
</html>
