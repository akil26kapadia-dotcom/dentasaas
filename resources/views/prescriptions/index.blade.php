<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Prescriptions</h2>
            <a href="{{ route('prescriptions.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> New Prescription
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            @if ($prescriptions->isEmpty())
                <div class="text-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-50 text-green-600 text-2xl mb-4">
                        <i class="fa-solid fa-prescription-bottle-medical"></i>
                    </div>
                    <p class="text-gray-500 mb-4">No prescriptions yet.</p>
                    <a href="{{ route('prescriptions.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                        <i class="fa-solid fa-plus"></i> Create your first prescription
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table id="prescriptions-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">#</th>
                                <th class="py-2">Patient</th>
                                <th class="py-2">Medicines</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Doctor</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prescriptions as $rx)
                                <tr class="border-t border-gray-100">
                                    <td class="py-2">{{ $loop->iteration }}</td>
                                    <td class="py-2 font-medium text-gray-800">{{ $rx->patient_name }}</td>
                                    <td class="py-2">{{ count($rx->medicines) }}
                                        medicine{{ count($rx->medicines) === 1 ? '' : 's' }}</td>
                                    <td class="py-2">{{ $rx->rx_date->format('d M Y') }}</td>
                                    <td class="py-2">{{ $rx->doctor_name ?? '—' }}</td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('prescriptions.pdf', $rx) }}" title="Download PDF"
                                                class="text-green-600 hover:text-green-800">
                                                <i class="fa-solid fa-file-pdf"></i>
                                            </a>
                                            <form method="POST" action="{{ route('prescriptions.destroy', $rx) }}"
                                                onsubmit="return confirm('Delete this prescription?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete"
                                                    class="text-red-500 hover:text-red-700">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
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
                if (document.getElementById('prescriptions-table')) {
                    $('#prescriptions-table').DataTable({
                        pageLength: 10
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
