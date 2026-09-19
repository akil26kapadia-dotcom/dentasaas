<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Access Requests</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        @if ($requests->isEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <div class="text-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 text-2xl mb-4">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <p class="font-medium text-gray-800">No pending access requests</p>
                    <p class="mt-1 text-sm text-gray-400">New sign-ups from the website will appear here for approval.</p>
                </div>
            </div>
        @else
            <p class="mb-4 text-sm text-gray-500">
                {{ $requests->count() }} pending {{ \Illuminate\Support\Str::plural('request', $requests->count()) }}.
                Approving creates the clinic and emails the login details.
            </p>

            <!-- Desktop table -->
            <div class="hidden md:block rounded-2xl border border-gray-200 bg-white p-5">
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
                                    <td class="py-3 font-medium text-gray-800">{{ $request->name }}</td>
                                    <td class="py-3">{{ $request->clinic_name }}</td>
                                    <td class="py-3">{{ $request->email }}</td>
                                    <td class="py-3">{{ $request->phone ?: '—' }}</td>
                                    <td class="py-3 whitespace-nowrap">{{ $request->created_at->format('d M Y') }}</td>
                                    <td class="py-3">
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
            </div>

            <!-- Mobile cards -->
            <div class="space-y-3 md:hidden">
                @foreach ($requests as $request)
                    <div class="rounded-2xl border border-gray-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate font-semibold text-gray-900">{{ $request->clinic_name }}</p>
                                <p class="truncate text-sm text-gray-500">{{ $request->name }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-gray-400">{{ $request->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="mt-3 space-y-2 text-sm">
                            <a href="mailto:{{ $request->email }}" class="flex items-center gap-2 text-gray-600">
                                <i class="fa-regular fa-envelope w-4 text-center text-gray-400"></i>
                                <span class="truncate">{{ $request->email }}</span>
                            </a>
                            @if ($request->phone)
                                <a href="tel:{{ $request->phone }}" class="flex items-center gap-2 text-gray-600">
                                    <i class="fa-solid fa-phone w-4 text-center text-gray-400"></i>
                                    <span>{{ $request->phone }}</span>
                                </a>
                            @endif
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-2">
                            <form method="POST" action="{{ route('admin.access-requests.approve', $request) }}"
                                onsubmit="return confirm('Approve and create a clinic for {{ addslashes($request->clinic_name) }}?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-600 px-3 py-2.5 text-sm font-medium text-white hover:bg-green-700">
                                    <i class="fa-solid fa-check"></i> Approve
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.access-requests.deny', $request) }}"
                                onsubmit="return confirm('Deny this request?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gray-100 px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                    <i class="fa-solid fa-xmark"></i> Deny
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
