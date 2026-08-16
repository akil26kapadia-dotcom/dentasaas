<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Access Requests</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            @if ($requests->isEmpty())
                <div class="text-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 text-2xl mb-4">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <p class="text-gray-500">No pending access requests.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table id="access-requests-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">Name</th>
                                <th class="py-2">Clinic</th>
                                <th class="py-2">Email</th>
                                <th class="py-2">Phone</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr class="border-t border-gray-100">
                                    <td class="py-2 font-medium text-gray-800">{{ $request->name }}</td>
                                    <td class="py-2">{{ $request->clinic_name }}</td>
                                    <td class="py-2">{{ $request->email }}</td>
                                    <td class="py-2">{{ $request->phone ?: '—' }}</td>
                                    <td class="py-2">{{ $request->created_at->format('d M Y') }}</td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-3">
                                            <form method="POST"
                                                action="{{ route('admin.access-requests.approve', $request) }}"
                                                onsubmit="return confirm('Approve and create a clinic for {{ addslashes($request->clinic_name) }}?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-600 text-white text-xs font-medium hover:bg-green-700">
                                                    <i class="fa-solid fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.access-requests.deny', $request) }}"
                                                onsubmit="return confirm('Deny this request?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-xs font-medium hover:bg-gray-200">
                                                    <i class="fa-solid fa-xmark"></i> Deny
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
                if (document.getElementById('access-requests-table')) {
                    $('#access-requests-table').DataTable({
                        pageLength: 15
                    });
                }
            });
        </script>
    @endpush
</x-admin-layout>
