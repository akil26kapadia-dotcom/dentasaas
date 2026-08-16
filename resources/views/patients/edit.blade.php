<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Patient</h2>
    </x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
            <form method="POST" action="{{ route('patients.update', $patient) }}">
                @csrf
                @method('PUT')
                @include('patients._form')

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('patients.show', $patient) }}" class="px-4 py-2 text-sm text-gray-600">Cancel</a>
                    <button type="submit"
                        class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Update
                        Patient</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
