<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Super Admin Dashboard
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Clinics</p>
                <p class="text-3xl font-semibold text-gray-900 dark:text-white mt-1">{{ $stats['total_clinics'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">Active Clinics</p>
                <p class="text-3xl font-semibold text-gray-900 dark:text-white mt-1">{{ $stats['active_clinics'] }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">Pending Access Requests</p>
                <p class="text-3xl font-semibold text-gray-900 dark:text-white mt-1">{{ $stats['pending_requests'] }}</p>
            </div>
        </div>
    </div>
</x-admin-layout>
