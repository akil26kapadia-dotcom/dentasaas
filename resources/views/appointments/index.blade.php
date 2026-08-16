<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Appointments</h2>
                <p class="text-sm text-gray-500 mt-1">{{ now()->format('l, d M Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if (Route::has('treatment-plans.index'))
                    <a href="{{ route('treatment-plans.index') }}"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Treatment Plans <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                @endif
                <button @click="openCreate()"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                    <i class="fa-solid fa-plus"></i> New Appointment
                </button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="appointmentsPage()" x-init="init()">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}</div>
        @endif

        <!-- Quick filter cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            @php
                $cards = [
                    [
                        'label' => 'All',
                        'count' => $counts['all'],
                        'href' => route('appointments.index'),
                        'active' => !request()->anyFilled(['date', 'status']),
                    ],
                    [
                        'label' => 'Today',
                        'count' => $counts['today'],
                        'href' => route('appointments.index', ['date' => 'today']),
                        'active' => request('date') === 'today',
                    ],
                    [
                        'label' => 'Pending',
                        'count' => $counts['pending'],
                        'href' => route('appointments.index', ['status' => 'pending']),
                        'active' => request('status') === 'pending',
                    ],
                    [
                        'label' => 'Confirmed',
                        'count' => $counts['confirmed'],
                        'href' => route('appointments.index', ['status' => 'confirmed']),
                        'active' => request('status') === 'confirmed',
                    ],
                ];
            @endphp
            @foreach ($cards as $card)
                <a href="{{ $card['href'] }}"
                    class="rounded-xl border p-4 transition {{ $card['active'] ? 'border-indigo-600 bg-indigo-50' : 'border-gray-100 bg-white hover:border-indigo-200' }}">
                    <p class="text-2xl font-semibold text-gray-900">{{ $card['count'] }}</p>
                    <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5">
            @if ($appointments->isEmpty())
                <div class="text-center py-16">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 text-2xl mb-4">
                        <i class="fa-solid fa-calendar-check"></i>
                    </div>
                    <p class="text-gray-500 mb-4">No appointments found.</p>
                    <button @click="openCreate()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                        <i class="fa-solid fa-plus"></i> New Appointment
                    </button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table id="appointments-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500">
                                <th class="py-2">#</th>
                                <th class="py-2">Patient</th>
                                <th class="py-2">Service</th>
                                <th class="py-2">Doctor</th>
                                <th class="py-2">Date</th>
                                <th class="py-2">Time</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $appt)
                                <tr class="border-t border-gray-100">
                                    <td class="py-2">{{ $loop->iteration }}</td>
                                    <td class="py-2 font-medium text-gray-800">{{ $appt->patient_name }}</td>
                                    <td class="py-2">{{ $appt->service_name }}</td>
                                    <td class="py-2">{{ $appt->doctor_name ?? '—' }}</td>
                                    <td class="py-2">{{ $appt->appt_date->format('d M Y') }}</td>
                                    <td class="py-2">
                                        {{ \Illuminate\Support\Carbon::parse($appt->appt_time)->format('h:i A') }}</td>
                                    <td class="py-2"><x-status-badge :status="$appt->status" /></td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-3">
                                            @if ($appt->status === 'pending')
                                                <button @click="updateStatus({{ $appt->id }}, 'confirmed')"
                                                    title="Confirm" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            @endif
                                            @if ($appt->status === 'confirmed')
                                                <button @click="updateStatus({{ $appt->id }}, 'completed')"
                                                    title="Mark Completed" class="text-green-600 hover:text-green-800">
                                                    <i class="fa-solid fa-check-double"></i>
                                                </button>
                                            @endif
                                            <button @click="openEdit(@js($appt))" title="Edit"
                                                class="text-gray-500 hover:text-gray-700">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <a href="{{ $appt->whatsapp_url }}" target="_blank" rel="noopener"
                                                title="WhatsApp" class="text-green-500 hover:text-green-700">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                            <button @click="destroy({{ $appt->id }})" title="Delete"
                                                class="text-red-500 hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        @include('appointments._modal')
        @include('appointments._whatsapp_prompt')
    </div>

    @push('scripts')
        <script>
            function appointmentsPage() {
                return {
                    modalOpen: false,
                    whatsappModalOpen: false,
                    whatsappUrl: '',
                    editingId: null,
                    formError: '',
                    patients: @json($patients),
                    services: @json($services),
                    doctors: @json($doctors),
                    form: {
                        patient_id: '',
                        patient_name: '',
                        service_id: '',
                        service_name: '',
                        user_id: '',
                        doctor_name: '',
                        appt_date: '',
                        appt_time: '',
                        notes: ''
                    },

                    init() {
                        if (document.getElementById('appointments-table')) {
                            $('#appointments-table').DataTable({
                                pageLength: 10
                            });
                        }

                        const self = this;
                        $(this.$refs.patientSelect).select2({
                            width: '100%',
                            dropdownParent: $(this.$refs.patientSelect).closest('.max-w-lg')
                        }).on('change', function() {
                            self.onPatientChange(this.value);
                        });
                    },

                    resetForm() {
                        this.form = {
                            patient_id: '',
                            patient_name: '',
                            service_id: '',
                            service_name: '',
                            user_id: '',
                            doctor_name: '',
                            appt_date: '',
                            appt_time: '',
                            notes: ''
                        };
                        $(this.$refs.patientSelect).val(null).trigger('change');
                    },

                    openCreate() {
                        this.editingId = null;
                        this.formError = '';
                        this.resetForm();
                        this.modalOpen = true;
                    },

                    openEdit(appt) {
                        this.editingId = appt.id;
                        this.formError = '';
                        this.form = {
                            patient_id: appt.patient_id ?? '',
                            patient_name: appt.patient_name,
                            service_id: appt.service_id ?? '',
                            service_name: appt.service_name,
                            user_id: appt.user_id ?? '',
                            doctor_name: appt.doctor_name ?? '',
                            appt_date: appt.appt_date ? appt.appt_date.substring(0, 10) : '',
                            appt_time: appt.appt_time ? appt.appt_time.substring(0, 5) : '',
                            notes: appt.notes ?? '',
                        };
                        this.modalOpen = true;
                    },

                    onPatientChange(id) {
                        const p = this.patients.find(p => p.id == id);
                        this.form.patient_id = id;
                        if (p) this.form.patient_name = p.name;
                    },

                    onServiceChange(id) {
                        const s = this.services.find(s => s.id == id);
                        if (s) this.form.service_name = s.name;
                    },

                    onDoctorChange(id) {
                        const d = this.doctors.find(d => d.id == id);
                        this.form.doctor_name = d ? d.name : '';
                    },

                    reload() {
                        window.location.reload();
                    },

                    submit() {
                        const url = this.editingId ? `{{ url('appointments') }}/${this.editingId}` :
                            '{{ route('appointments.store') }}';
                        const method = this.editingId ? 'PUT' : 'POST';

                        fetch(url, {
                                method: method,
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify(this.form),
                            })
                            .then(async (r) => ({
                                status: r.status,
                                data: await r.json()
                            }))
                            .then(({
                                status,
                                data
                            }) => {
                                if (status === 201) {
                                    this.modalOpen = false;
                                    this.whatsappUrl = data.whatsapp_url;
                                    this.whatsappModalOpen = true;
                                } else if (status === 200) {
                                    this.reload();
                                } else {
                                    this.formError = data.message || 'Please check the form and try again.';
                                }
                            })
                            .catch(() => {
                                this.formError = 'Something went wrong.';
                            });
                    },

                    updateStatus(id, status) {
                        fetch(`{{ url('appointments') }}/${id}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                status
                            }),
                        }).then(() => this.reload());
                    },

                    destroy(id) {
                        if (!confirm('Delete this appointment?')) return;

                        fetch(`{{ url('appointments') }}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                        }).then(() => this.reload());
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
