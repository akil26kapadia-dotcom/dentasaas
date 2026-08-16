<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Patients</h2>
            <a href="{{ route('patients.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> Add Patient
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        <form method="GET" class="rounded-2xl border border-gray-200 bg-white p-4 mb-6 flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <x-input-label for="q" value="Search" />
                <x-text-input id="q" name="q" class="block mt-1 w-full" placeholder="Name, phone or email"
                    :value="request('q')" />
            </div>

            <div>
                <x-input-label for="gender" value="Gender" />
                <select id="gender" name="gender"
                    class="block mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    @foreach (['male', 'female', 'other'] as $g)
                        <option value="{{ $g }}" {{ request('gender') === $g ? 'selected' : '' }}>
                            {{ ucfirst($g) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="blood_group" value="Blood Group" />
                <select id="blood_group" name="blood_group"
                    class="block mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    @foreach (['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') === $bg ? 'selected' : '' }}>
                            {{ $bg }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit"
                class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm font-medium hover:bg-gray-900">Filter</button>
            @if (request()->anyFilled(['q', 'gender', 'blood_group']))
                <a href="{{ route('patients.index') }}" class="px-4 py-2 text-sm text-gray-500">Clear</a>
            @endif
        </form>

        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            @if ($patients->isEmpty())
                <div class="text-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 text-2xl mb-4">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <p class="text-gray-500 mb-4">No patients found.</p>
                    <a href="{{ route('patients.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                        <i class="fa-solid fa-plus"></i> Add your first patient
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table id="patients-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">Patient</th>
                                <th class="py-2">Phone</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Blood Group</th>
                                <th class="py-2">Created</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $palette = ['#465fff', '#059669', '#d97706', '#dc2626', '#7c3aed', '#0891b2'];
                            @endphp
                            @foreach ($patients as $patient)
                                <tr class="border-t border-gray-100">
                                    <td class="py-2">
                                        <a href="{{ route('patients.show', $patient) }}"
                                            class="flex items-center gap-3 hover:text-indigo-600">
                                            <span
                                                class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shrink-0"
                                                style="background-color: {{ $palette[$patient->id % count($palette)] }}">
                                                {{ strtoupper(substr($patient->name, 0, 1)) }}
                                            </span>
                                            <span class="font-medium text-gray-800">{{ $patient->name }}</span>
                                        </a>
                                    </td>
                                    <td class="py-2">{{ $patient->phone ?: '—' }}</td>
                                    <td class="py-2">{{ $patient->email ?: '—' }}</td>
                                    <td class="py-2">{{ $patient->blood_group ?: '—' }}</td>
                                    <td class="py-2">{{ $patient->created_at->format('d M Y') }}</td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('patients.show', $patient) }}"
                                                class="text-indigo-600 hover:text-indigo-800" title="View"><i
                                                    class="fa-solid fa-eye"></i></a>
                                            <a href="{{ route('patients.edit', $patient) }}"
                                                class="text-gray-500 hover:text-gray-700" title="Edit"><i
                                                    class="fa-solid fa-pen"></i></a>
                                            <form method="POST" action="{{ route('patients.destroy', $patient) }}"
                                                onsubmit="return confirm('Delete this patient?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700"
                                                    title="Delete"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (document.getElementById('patients-table')) {
                    $('#patients-table').DataTable({
                        pageLength: 10
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
