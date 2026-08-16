<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Treatment Plans</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $counts['total'] }} total &bull; {{ $counts['active'] }} active &bull; {{ $counts['done'] }} done
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <button @click="switchView('kanban')" :class="view === 'kanban' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300'" class="px-3 py-2 text-sm font-medium">
                        <i class="fa-solid fa-table-columns"></i> Kanban
                    </button>
                    <button @click="switchView('list')" :class="view === 'list' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300'" class="px-3 py-2 text-sm font-medium">
                        <i class="fa-solid fa-list"></i> List
                    </button>
                </div>
                <button @click="openNewPlan()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                    <i class="fa-solid fa-plus"></i> New Plan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="treatmentPlansPage()" x-init="init()">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <!-- KANBAN VIEW -->
        <div x-show="view === 'kanban'" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $columns = [
                    'planned' => ['label' => 'Planned', 'dot' => 'bg-purple-500', 'border' => 'border-purple-500'],
                    'in_progress' => ['label' => 'In Progress', 'dot' => 'bg-amber-500', 'border' => 'border-amber-500'],
                    'completed' => ['label' => 'Completed', 'dot' => 'bg-green-500', 'border' => 'border-green-500'],
                ];
            @endphp

            @foreach ($columns as $status => $col)
                @php $columnPlans = $plans->where('status', $status); @endphp
                <div class="rounded-xl border-2 border-dashed p-3 transition-colors"
                     :class="dragOverStatus === '{{ $status }}' ? '{{ $col['border'] }}' : 'border-transparent'"
                     @dragover.prevent="dragOverStatus = '{{ $status }}'"
                     @dragleave="dragOverStatus = null"
                     @drop.prevent="dropColumn('{{ $status }}')">
                    <div class="flex items-center gap-2 mb-4 px-1">
                        <span class="w-2.5 h-2.5 rounded-full {{ $col['dot'] }}"></span>
                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $col['label'] }}</span>
                        <span class="ml-auto text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 rounded-full px-2 py-0.5">{{ $columnPlans->count() }}</span>
                    </div>

                    <div class="space-y-3 min-h-[80px]">
                        @forelse ($columnPlans as $plan)
                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700 p-3 cursor-move"
                                 draggable="true"
                                 @dragstart="draggingId = {{ $plan->id }}"
                                 @dragend="draggingId = null">
                                <p class="font-semibold text-sm text-gray-900 dark:text-white">{{ $plan->patient_name }}</p>
                                <p class="text-xs mt-0.5" style="color:#1649FF;">{{ $plan->treatment }}</p>

                                <div class="mt-3">
                                    <div class="flex justify-between text-[10px] text-gray-500 dark:text-gray-400 mb-1">
                                        <span>{{ $plan->completed_count }}/{{ $plan->total_sessions }}</span>
                                        <span>{{ $plan->progress_pct }}%</span>
                                    </div>
                                    <div class="w-full h-1.5 rounded-full bg-gray-200 dark:bg-gray-700">
                                        <div class="h-1.5 rounded-full bg-green-500" style="width: {{ $plan->progress_pct }}%"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-[11px] text-gray-400">{{ $plan->doctor_name ?? '—' }}</span>
                                    <div class="flex items-center gap-3">
                                        <button type="button" @click="openDetail({{ $plan->id }})" title="View" class="text-indigo-600 hover:text-indigo-800">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <form method="POST" action="{{ route('treatment-plans.destroy', $plan) }}" onsubmit="return confirm('Delete this treatment plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete" class="text-red-500 hover:text-red-700">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            @if ($status === 'planned')
                                <button type="button" @click="openNewPlan()"
                                        class="w-full border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-lg py-6 text-sm text-gray-400 hover:border-indigo-300 hover:text-indigo-500">
                                    <i class="fa-solid fa-plus"></i> Add
                                </button>
                            @else
                                <p class="text-xs text-gray-400 text-center py-6">No plans</p>
                            @endif
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        <!-- LIST VIEW -->
        <div x-show="view === 'list'" x-cloak class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
            @if ($plans->isEmpty())
                <p class="text-center text-gray-400 py-16">No treatment plans yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table id="treatment-plans-table" class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-500 dark:text-gray-400">
                                <th class="py-2">Patient</th>
                                <th class="py-2">Treatment</th>
                                <th class="py-2">Progress</th>
                                <th class="py-2">Doctor</th>
                                <th class="py-2">Status</th>
                                <th class="py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $plan)
                                <tr class="border-t border-gray-100 dark:border-gray-700">
                                    <td class="py-2 font-medium text-gray-800 dark:text-gray-200">{{ $plan->patient_name }}</td>
                                    <td class="py-2">{{ $plan->treatment }}</td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 h-1.5 rounded-full bg-gray-200 dark:bg-gray-700">
                                                <div class="h-1.5 rounded-full bg-green-500" style="width: {{ $plan->progress_pct }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $plan->completed_count }}/{{ $plan->total_sessions }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2">{{ $plan->doctor_name ?? '—' }}</td>
                                    <td class="py-2"><x-status-badge :status="$plan->status" /></td>
                                    <td class="py-2">
                                        <div class="flex items-center gap-3">
                                            <button type="button" @click="openDetail({{ $plan->id }})" title="View" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <form method="POST" action="{{ route('treatment-plans.destroy', $plan) }}" onsubmit="return confirm('Delete this treatment plan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Delete" class="text-red-500 hover:text-red-700">
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

        @include('treatment-plans._new_plan_modal')
        @include('treatment-plans._detail_modal')
    </div>

    @push('scripts')
        <script>
            function treatmentPlansPage() {
                return {
                    view: 'kanban',
                    listTableInitialized: false,
                    newPlanModal: false,
                    detailModal: false,
                    detailPlan: null,
                    draggingId: null,
                    dragOverStatus: null,
                    newPlanForm: { patient_id: '', patient_name: '', doctor_name: '', treatment: '', total_sessions: 1, status: 'planned', notes: '' },
                    patients: @json($patients),

                    init() {
                        const self = this;
                        $(this.$refs.newPlanPatientSelect).select2({ width: '100%' }).on('change', function () {
                            self.newPlanForm.patient_id = this.value;
                            const p = self.patients.find(p => p.id == this.value);
                            if (p) self.newPlanForm.patient_name = p.name;
                        });
                    },

                    switchView(target) {
                        this.view = target;
                        if (target === 'list' && ! this.listTableInitialized) {
                            this.$nextTick(() => {
                                $('#treatment-plans-table').DataTable({ pageLength: 10 });
                                this.listTableInitialized = true;
                            });
                        }
                    },

                    openNewPlan() {
                        this.newPlanForm = { patient_id: '', patient_name: '', doctor_name: '', treatment: '', total_sessions: 1, status: 'planned', notes: '' };
                        $(this.$refs.newPlanPatientSelect).val(null).trigger('change');
                        this.newPlanModal = true;
                    },

                    openDetail(id) {
                        this.detailPlan = null;
                        this.detailModal = true;

                        fetch(`{{ url('treatment-plans') }}/${id}`, {
                            headers: { 'Accept': 'application/json' },
                        })
                        .then(r => r.json())
                        .then(data => { this.detailPlan = data; });
                    },

                    saveSession(session, field, value) {
                        session[field] = value;

                        fetch(`{{ url('treatment-plans') }}/${this.detailPlan.id}/sessions/${session.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ [field]: value }),
                        })
                        .then(r => r.json())
                        .then(data => {
                            Object.assign(session, data.session);
                            if (data.plan) {
                                this.detailPlan.status = data.plan.status;
                                this.detailPlan.completed_count = data.plan.completed_count;
                                this.detailPlan.progress_pct = data.plan.progress_pct;
                            }
                        });
                    },

                    togglePaid(session) {
                        this.saveSession(session, 'is_paid', ! session.is_paid);
                    },

                    dropColumn(status) {
                        if (! this.draggingId) return;

                        fetch(`{{ url('treatment-plans') }}/${this.draggingId}/drag`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ status }),
                        }).then(() => window.location.reload());

                        this.draggingId = null;
                        this.dragOverStatus = null;
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
