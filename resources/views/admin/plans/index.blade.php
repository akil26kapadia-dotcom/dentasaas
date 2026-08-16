<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Plans &amp; Pricing</h2>
            <button @click="openNew()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> New Plan
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="{
            modalOpen: false,
            editingId: null,
            form: { key: '', name: '', price_monthly: 0, patients_limit: 0, appointments_limit: 0, invoices_limit: 0, doctors_limit: 0, pdf_export: false, prescriptions: false, analytics: 'none', is_highlighted: false, is_active: true, sort_order: 0 },
            openNew() {
                this.editingId = null;
                this.form = { key: '', name: '', price_monthly: 0, patients_limit: 0, appointments_limit: 0, invoices_limit: 0, doctors_limit: 0, pdf_export: false, prescriptions: false, analytics: 'none', is_highlighted: false, is_active: true, sort_order: 0 };
                this.modalOpen = true;
            },
            openEdit(plan) {
                this.editingId = plan.id;
                this.form = { ...plan };
                this.modalOpen = true;
            }
         }">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-success-50 border border-green-200 px-4 py-3 text-sm text-success-600">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-lg bg-error-50 border border-red-200 px-4 py-3 text-sm text-error-600">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($plans as $plan)
                <div class="relative rounded-2xl border border-gray-200 bg-white p-5 {{ $plan->is_highlighted ? 'ring-2' : '' }}"
                     @if ($plan->is_highlighted) style="--tw-ring-color:#465fff;" @endif>
                    @if ($plan->is_highlighted)
                        <span class="absolute -top-3 left-5 text-[10px] font-semibold uppercase tracking-wide text-white px-3 py-1 rounded-full" style="background-color:#465fff;">Most Popular</span>
                    @endif

                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $plan->name }}</p>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mt-0.5">{{ $plan->key }}</p>
                        </div>
                        @if (! $plan->is_active)
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Hidden</span>
                        @endif
                    </div>

                    <p class="mt-3">
                        <span class="text-2xl font-bold text-gray-900">₹{{ number_format($plan->price_monthly) }}</span>
                        <span class="text-sm text-gray-400">/mo</span>
                    </p>

                    <ul class="mt-4 space-y-1.5 text-xs text-gray-500">
                        <li>{{ $plan->patients_limit === -1 ? 'Unlimited' : $plan->patients_limit }} patients</li>
                        <li>{{ $plan->appointments_limit === -1 ? 'Unlimited' : $plan->appointments_limit }} appointments/mo</li>
                        <li>{{ $plan->doctors_limit === -1 ? 'Unlimited' : $plan->doctors_limit }} doctor{{ $plan->doctors_limit === 1 ? '' : 's' }}</li>
                        <li class="flex items-center gap-1.5">
                            <i class="fa-solid {{ $plan->pdf_export ? 'fa-check text-success-500' : 'fa-xmark text-gray-300' }} w-3"></i> PDF export
                        </li>
                        <li class="flex items-center gap-1.5">
                            <i class="fa-solid {{ $plan->prescriptions ? 'fa-check text-success-500' : 'fa-xmark text-gray-300' }} w-3"></i> Prescriptions
                        </li>
                        <li class="flex items-center gap-1.5">
                            <i class="fa-solid {{ $plan->analytics !== 'none' ? 'fa-check text-success-500' : 'fa-xmark text-gray-300' }} w-3"></i>
                            Analytics {{ $plan->analytics !== 'none' ? '('.ucfirst($plan->analytics).')' : '' }}
                        </li>
                    </ul>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 text-sm">
                        <span class="text-xs text-gray-400">{{ $plan->clinics_count }} {{ Str::plural('clinic', $plan->clinics_count) }}</span>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="openEdit(@js($plan))" title="Edit" class="text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-pen"></i>
                            </button>

                            @unless ($plan->key === 'free')
                                <form method="POST" action="{{ route('admin.plans.toggle', $plan) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $plan->is_active ? 'Hide from pricing page' : 'Show on pricing page' }}" class="text-gray-500 hover:text-gray-700">
                                        <i class="fa-solid {{ $plan->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" class="text-red-500 hover:text-red-700" {{ $plan->clinics_count > 0 ? 'disabled' : '' }}>
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- New / Edit modal -->
        <div x-show="modalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center px-4"
             style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="modalOpen = false" class="bg-white rounded-2xl shadow-theme-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-semibold text-lg text-gray-900 mb-4" x-text="editingId ? 'Edit Plan' : 'New Plan'"></h3>

                <form method="POST" :action="editingId ? `{{ url('admin/plans') }}/${editingId}` : `{{ route('admin.plans.store') }}`" class="space-y-4">
                    @csrf
                    <template x-if="editingId"><input type="hidden" name="_method" value="PUT"></template>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Key" />
                            <input type="text" name="key" x-model="form.key" :disabled="form.clinics_count > 0" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100" required>
                        </div>
                        <div>
                            <x-input-label value="Display Name" />
                            <input type="text" name="name" x-model="form.name" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Price / month (₹)" />
                            <input type="number" min="0" name="price_monthly" x-model="form.price_monthly" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <x-input-label value="Sort Order" />
                            <input type="number" min="0" name="sort_order" x-model="form.sort_order" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <p class="text-xs font-medium text-gray-500 uppercase">Limits &bull; use -1 for unlimited</p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Patients" />
                            <input type="number" min="-1" name="patients_limit" x-model="form.patients_limit" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <x-input-label value="Appointments / mo" />
                            <input type="number" min="-1" name="appointments_limit" x-model="form.appointments_limit" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <x-input-label value="Invoices / mo" />
                            <input type="number" min="-1" name="invoices_limit" x-model="form.invoices_limit" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <x-input-label value="Doctors" />
                            <input type="number" min="-1" name="doctors_limit" x-model="form.doctors_limit" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Analytics" />
                        <select name="analytics" x-model="form.analytics" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="none">None</option>
                            <option value="basic">Basic</option>
                            <option value="full">Full</option>
                        </select>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="pdf_export" value="0">
                            <input type="checkbox" name="pdf_export" value="1" x-model="form.pdf_export" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            PDF export
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="prescriptions" value="0">
                            <input type="checkbox" name="prescriptions" value="1" x-model="form.prescriptions" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Prescriptions
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="is_highlighted" value="0">
                            <input type="checkbox" name="is_highlighted" value="1" x-model="form.is_highlighted" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            "Most Popular" badge
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Visible on pricing page
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm text-gray-600">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save Plan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
