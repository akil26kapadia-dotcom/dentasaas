<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Clinics</h2>
            <button @click="newModalOpen = true" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> New Clinic
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="{
            newModalOpen: false,
            editModalOpen: false,
            editClinic: {},
            openEdit(clinic) { this.editClinic = clinic; this.editModalOpen = true; }
         }">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @error('admin_email')
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            @if ($clinics->isEmpty())
                <p class="text-center text-gray-400 py-16">No clinics yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table id="clinics-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
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
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2 font-medium text-gray-800 dark:text-gray-200">{{ $clinic->name }}</td>
                                    <td class="py-2">
                                        <form method="POST" action="{{ route('admin.clinics.plan', $clinic) }}">
                                            @csrf
                                            @method('PATCH')
                                            <select name="plan" onchange="this.form.submit()"
                                                    class="text-xs rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                @foreach (['free', 'basic', 'premium', 'deluxe'] as $plan)
                                                    <option value="{{ $plan }}" {{ $clinic->plan === $plan ? 'selected' : '' }}>{{ ucfirst($plan) }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                    <td class="py-2">
                                        <form method="POST" action="{{ route('admin.clinics.status', $clinic) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit">
                                                <x-status-badge :status="$clinic->status" />
                                            </button>
                                        </form>
                                    </td>
                                    <td class="py-2">{{ $clinic->doctors_count }}</td>
                                    <td class="py-2">{{ $clinic->patients_count }}</td>
                                    <td class="py-2">{{ $clinic->plan_expires_at?->format('d M Y') ?? '—' }}</td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-3">
                                            <button type="button"
                                                    @click="openEdit(@js(['id' => $clinic->id, 'name' => $clinic->name, 'tagline' => $clinic->tagline, 'address' => $clinic->address, 'phone' => $clinic->phone, 'email' => $clinic->email, 'gst' => $clinic->gst, 'plan' => $clinic->plan, 'status' => $clinic->status]))"
                                                    title="Edit" class="text-gray-500 hover:text-gray-700">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>

                                            <form method="POST" action="{{ route('admin.clinics.extend', $clinic) }}" title="Extend 30 days">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fa-solid fa-calendar-plus"></i>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.clinics.reset-password', $clinic) }}" onsubmit="return confirm('Reset password and email the clinic admin?');" title="Reset Password">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-amber-600 hover:text-amber-800">
                                                    <i class="fa-solid fa-key"></i>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.clinics.destroy', $clinic) }}" onsubmit="return confirm('Delete this clinic and all its data? This cannot be undone.');" title="Delete">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">
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

        <!-- New clinic modal -->
        <div x-show="newModalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center px-4"
             style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="newModalOpen = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-4">New Clinic</h3>

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
                            <x-text-input id="new_clinic_email" type="email" name="email" class="block mt-1 w-full" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="new_clinic_plan" value="Plan" />
                        <select id="new_clinic_plan" name="plan" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach (['free', 'basic', 'premium', 'deluxe'] as $plan)
                                <option value="{{ $plan }}">{{ ucfirst($plan) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-700">

                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Clinic Admin</p>

                    <div>
                        <x-input-label for="admin_name" value="Admin Name" />
                        <x-text-input id="admin_name" name="admin_name" class="block mt-1 w-full" required />
                    </div>

                    <div>
                        <x-input-label for="admin_email" value="Admin Email" />
                        <x-text-input id="admin_email" type="email" name="admin_email" class="block mt-1 w-full" required />
                        <p class="text-xs text-gray-400 mt-1">Login credentials will be emailed to this address.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="newModalOpen = false" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Create Clinic</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit clinic modal -->
        <div x-show="editModalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center px-4"
             style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="editModalOpen = false" class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-semibold text-lg text-gray-900 dark:text-white mb-4">Edit Clinic</h3>

                <form method="POST" :action="`{{ url('admin/clinics') }}/${editClinic.id}`" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label value="Clinic Name" />
                        <input type="text" name="name" x-model="editClinic.name" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <x-input-label value="Tagline" />
                        <input type="text" name="tagline" x-model="editClinic.tagline" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <x-input-label value="Address" />
                        <textarea name="address" x-model="editClinic.address" rows="2" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Phone" />
                            <input type="text" name="phone" x-model="editClinic.phone" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <x-input-label value="Email" />
                            <input type="email" name="email" x-model="editClinic.email" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div>
                        <x-input-label value="GSTIN" />
                        <input type="text" name="gst" x-model="editClinic.gst" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Plan" />
                            <select name="plan" x-model="editClinic.plan" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['free', 'basic', 'premium', 'deluxe'] as $plan)
                                    <option value="{{ $plan }}">{{ ucfirst($plan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label value="Status" />
                            <select name="status" x-model="editClinic.status" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach (['active', 'inactive', 'pending'] as $status)
                                    <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 text-sm text-gray-600 dark:text-gray-300">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (document.getElementById('clinics-table')) {
                    $('#clinics-table').DataTable({ pageLength: 15 });
                }
            });
        </script>
    @endpush
</x-admin-layout>
