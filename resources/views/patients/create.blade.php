<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">New Patient</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
            @error('plan_limit')
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
            @enderror

            <form method="POST" action="{{ route('patients.store') }}">
                @csrf
                @include('patients._form')

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('patients.index') }}" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save Patient</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
