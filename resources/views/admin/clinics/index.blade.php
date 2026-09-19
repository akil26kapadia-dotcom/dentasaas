<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clinics</h2>
            <button @click="$dispatch('open-new-clinic')"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> New Clinic
            </button>
        </div>
    </x-slot>

    @php
        $searchIndex = $clinics->map(fn ($c) => strtolower($c->name . ' ' . $c->email . ' ' . $c->phone . ' ' . $c->plan . ' ' . $c->status))->values()->all();
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{
        newModalOpen: false,
        q: '',
        index: @js($searchIndex),
        matches(s) { return ! this.q || s.includes(this.q.toLowerCase().trim()); },
        visibleCount() { return this.index.filter((s) => this.matches(s)).length; },
        editModalOpen: false,
        editClinic: {},
        openEdit(clinic) {
            this.editClinic = clinic;
            this.editModalOpen = true;
        },
        resetModalOpen: false,
        resetClinic: {},
        customPassword: '',
        openReset(clinic) {
            this.resetClinic = clinic;
            this.customPassword = '';
            this.resetModalOpen = true;
        }
    }"
    x-on:open-new-clinic.window="newModalOpen = true"
    x-init="if (new URLSearchParams(location.search).get('new') === '1') { newModalOpen = true; history.replaceState(null, '', location.pathname); }">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        @error('admin_email')
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}
            </div>
        @enderror

        @if ($clinics->isEmpty())
            <div class="rounded-2xl border border-gray-200 bg-white p-5">
                <p class="text-center text-gray-400 py-16">No clinics yet.</p>
            </div>
        @else
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative w-full sm:max-w-xs">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400"></i>
                    <input type="search" x-model="q" id="clinic-search" placeholder="Search clinics…" aria-label="Search clinics"
                        class="w-full rounded-lg border-gray-300 pl-9 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <p class="text-sm text-gray-500"><span x-text="visibleCount()"></span> of {{ $clinics->count() }} {{ \Illuminate\Support\Str::plural('clinic', $clinics->count()) }}</p>
            </div>

            <p x-show="visibleCount() === 0" x-cloak class="rounded-2xl border border-gray-200 bg-white py-12 text-center text-sm text-gray-400">
                No clinics match your search.
            </p>

            <!-- Desktop table -->
            <div class="hidden md:block rounded-2xl border border-gray-200 bg-white p-5" x-show="visibleCount() > 0">
                <div class="overflow-x-auto">
                    <table id="clinics-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">Clinic</th>
                                <th class="py-2">Plan</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Doctors</th>
                                <th class="py-2">Patients</th>
                                <th class="py-2">Expires</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clinics as $clinic)
                                @php
                                    $days = $clinic->plan !== 'free' && $clinic->plan_expires_at
                                        ? (int) today()->diffInDays($clinic->plan_expires_at, false)
                                        : null;
                                @endphp
                                <tr class="border-t border-gray-100" x-show="matches(@js($searchIndex[$loop->index]))">
                                    <td class="py-3 pr-3">
                                        <span class="block font-medium text-gray-800">{{ $clinic->name }}</span>
                                        @if ($clinic->email)
                                            <span class="block text-xs text-gray-400">{{ $clinic->email }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <form method="POST" action="{{ route('admin.clinics.plan', $clinic) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="plan" onchange="this.form.submit()" aria-label="Plan for {{ $clinic->name }}"
                                                class="text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach ($plans as $planOption)
                                                    <option value="{{ $planOption->key }}"
                                                        {{ $clinic->plan === $planOption->key ? 'selected' : '' }}>
                                                        {{ $planOption->name }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-3">
                                        <form method="POST" action="{{ route('admin.clinics.status', $clinic) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" title="Click to change status">
                                                <x-status-badge :status="$clinic->status" />
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-3">{{ $clinic->doctors_count }}</td>
                                    <td class="py-3">{{ $clinic->patients_count }}</td>
                                    <td class="py-3 whitespace-nowrap">
                                        {{ $clinic->plan_expires_at?->format('d M Y') ?? '—' }}
                                        @if ($days !== null && $days < 0)
                                            <span class="ml-1 rounded-full bg-red-50 px-2 py-0.5 text-[11px] font-medium text-red-600">Expired</span>
                                        @elseif ($days !== null && $days <= 14)
                                            <span class="ml-1 rounded-full bg-amber-50 px-2 py-0.5 text-[11px] font-medium text-amber-700">{{ $days }}d left</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="flex items-center gap-1">
                                            @include('admin.clinics._actions', ['clinic' => $clinic, 'labels' => false])
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
                @foreach ($clinics as $clinic)
                    @php
                        $days = $clinic->plan !== 'free' && $clinic->plan_expires_at
                            ? (int) today()->diffInDays($clinic->plan_expires_at, false)
                            : null;
                    @endphp
                    <div class="rounded-2xl border border-gray-200 bg-white p-4" x-show="matches(@js($searchIndex[$loop->index]))">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex min-w-0 items-center gap-3">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-sm font-semibold text-indigo-600">
                                    {{ strtoupper(mb_substr($clinic->name, 0, 1)) }}
                                </span>
                                <span class="min-w-0">
                                    <span class="block truncate font-medium text-gray-900">{{ $clinic->name }}</span>
                                    @if ($clinic->email)
                                        <span class="block truncate text-xs text-gray-400">{{ $clinic->email }}</span>
                                    @endif
                                </span>
                            </div>
                            <form method="POST" action="{{ route('admin.clinics.status', $clinic) }}" class="shrink-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Tap to change status">
                                    <x-status-badge :status="$clinic->status" />
                                </button>
                            </form>
                        </div>

                        <dl class="mt-4 grid grid-cols-3 gap-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400">Doctors</dt>
                                <dd class="font-medium text-gray-800">{{ $clinic->doctors_count }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Patients</dt>
                                <dd class="font-medium text-gray-800">{{ $clinic->patients_count }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400">Expires</dt>
                                <dd class="font-medium text-gray-800">
                                    {{ $clinic->plan_expires_at?->format('d M') ?? '—' }}
                                    @if ($days !== null && $days < 0)
                                        <span class="block text-[11px] font-medium text-red-600">Expired</span>
                                    @elseif ($days !== null && $days <= 14)
                                        <span class="block text-[11px] font-medium text-amber-700">{{ $days }}d left</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        <form method="POST" action="{{ route('admin.clinics.plan', $clinic) }}" class="mt-4">
                            @csrf
                            @method('PATCH')
                            <label class="mb-1 block text-xs text-gray-400" for="plan-{{ $clinic->id }}">Plan</label>
                            <select id="plan-{{ $clinic->id }}" name="plan" onchange="this.form.submit()"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($plans as $planOption)
                                    <option value="{{ $planOption->key }}"
                                        {{ $clinic->plan === $planOption->key ? 'selected' : '' }}>
                                        {{ $planOption->name }}</option>
                                @endforeach
                            </select>
                        </form>

                        <div class="mt-4 grid grid-cols-2 gap-2">
                            @include('admin.clinics._actions', ['clinic' => $clinic, 'labels' => true])
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- New clinic modal -->
        <div x-show="newModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="newModalOpen = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">New Clinic</h3>

                <form method="POST" action="{{ route('admin.clinics.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="new_clinic_name" value="Clinic Name" />
                        <x-text-input id="new_clinic_name" name="name" class="block mt-1 w-full" required />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="new_clinic_phone" value="Phone" />
                            <x-text-input id="new_clinic_phone" name="phone" class="block mt-1 w-full" />
                        </div>
                        <div>
                            <x-input-label for="new_clinic_email" value="Clinic Email" />
                            <x-text-input id="new_clinic_email" type="email" name="email"
                                class="block mt-1 w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="new_clinic_plan" value="Plan" />
                        <select id="new_clinic_plan" name="plan"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($plans as $planOption)
                                <option value="{{ $planOption->key }}">{{ $planOption->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="border-gray-100">

                    <p class="text-xs font-medium text-gray-500 uppercase">Clinic Admin</p>

                    <div>
                        <x-input-label for="admin_name" value="Admin Name" />
                        <x-text-input id="admin_name" name="admin_name" class="block mt-1 w-full" required />
                    </div>

                    <div>
                        <x-input-label for="admin_email" value="Admin Email" />
                        <x-text-input id="admin_email" type="email" name="admin_email" class="block mt-1 w-full"
                            required />
                        <p class="text-xs text-gray-400 mt-1">Login credentials will be emailed to this address.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="newModalOpen = false"
                            class="px-4 py-2 text-sm text-gray-600">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Create
                            Clinic</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit clinic modal -->
        <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="editModalOpen = false"
                class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-semibold text-lg text-gray-900 mb-4">Edit Clinic</h3>

                <form method="POST" :action="`{{ url('admin/clinics') }}/${editClinic.id}`" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label value="Clinic Name" />
                        <input type="text" name="name" x-model="editClinic.name"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div>
                        <x-input-label value="Tagline" />
                        <input type="text" name="tagline" x-model="editClinic.tagline"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <x-input-label value="Address" />
                        <textarea name="address" x-model="editClinic.address" rows="2"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Phone" />
                            <input type="text" name="phone" x-model="editClinic.phone"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <x-input-label value="Email" />
                            <input type="email" name="email" x-model="editClinic.email"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <x-input-label value="GSTIN" />
                        <input type="text" name="gst" x-model="editClinic.gst"
                            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Plan" />
                            <select name="plan" x-model="editClinic.plan"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($plans as $planOption)
                                    <option value="{{ $planOption->key }}">{{ $planOption->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Status" />
                            <select name="status" x-model="editClinic.status"
                                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['active', 'inactive', 'pending'] as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="editModalOpen = false"
                            class="px-4 py-2 text-sm text-gray-600">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Reset password modal -->
        <div x-show="resetModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4"
            style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="resetModalOpen = false"
                class="bg-white rounded-2xl shadow-theme-lg w-full max-w-md p-6">
                <h3 class="font-semibold text-lg text-gray-900 mb-1">Reset Password</h3>
                <p class="text-sm text-gray-500 mb-4">for <span x-text="resetClinic.name"></span>'s admin account</p>

                <form method="POST" :action="`{{ url('admin/clinics') }}/${resetClinic.id}/reset-password`">
                    @csrf
                    @method('PATCH')

                    <x-input-label value="New Password (optional)" />
                    <input type="text" name="password" x-model="customPassword" minlength="8"
                        placeholder="Leave blank to auto-generate"
                        class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">We'll try to email this to the clinic admin — and show it to
                        you here either way, in case email delivery fails.</p>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="resetModalOpen = false"
                            class="px-4 py-2 text-sm text-gray-600">Cancel</button>
                        <button type="submit"
                            class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Reset
                            Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
