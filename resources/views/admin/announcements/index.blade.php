<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Announcements</h2>
            <button @click="openNew()" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> New Announcement
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="{
            modalOpen: false,
            editingId: null,
            form: { title: '', body: '', type: 'info', is_active: true, starts_at: '', ends_at: '' },
            openNew() {
                this.editingId = null;
                this.form = { title: '', body: '', type: 'info', is_active: true, starts_at: '', ends_at: '' };
                this.modalOpen = true;
            },
            openEdit(a) {
                this.editingId = a.id;
                this.form = { title: a.title, body: a.body, type: a.type, is_active: !!a.is_active, starts_at: a.starts_at ? a.starts_at.substring(0,16) : '', ends_at: a.ends_at ? a.ends_at.substring(0,16) : '' };
                this.modalOpen = true;
            }
         }">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-success-50 border border-green-200 px-4 py-3 text-sm text-success-600">{{ session('success') }}</div>
        @endif

        <p class="text-sm text-gray-500 mb-4">Active announcements appear as a banner at the top of every clinic's dashboard.</p>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 md:p-6">
            @if ($announcements->isEmpty())
                <p class="text-center text-gray-400 py-16">No announcements yet.</p>
            @else
                <div class="space-y-3">
                    @foreach ($announcements as $a)
                        @php
                            $typeColors = [
                                'info' => 'bg-blue-light-50 text-blue-light-600 border-blue-light-100',
                                'success' => 'bg-success-50 text-success-600 border-green-100',
                                'warning' => 'bg-warning-50 text-warning-600 border-amber-100',
                                'danger' => 'bg-error-50 text-error-600 border-red-100',
                            ];
                        @endphp
                        <div class="flex items-start justify-between gap-4 rounded-xl border {{ $typeColors[$a->type] }} p-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-semibold uppercase tracking-wide">{{ $a->type }}</span>
                                    @if (! $a->is_active)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Inactive</span>
                                    @endif
                                    @if ($a->starts_at || $a->ends_at)
                                        <span class="text-xs text-gray-400">
                                            {{ $a->starts_at?->format('d M Y') ?? 'now' }} &rarr; {{ $a->ends_at?->format('d M Y') ?? 'no end' }}
                                        </span>
                                    @endif
                                </div>
                                <p class="font-semibold text-gray-900 mt-1">{{ $a->title }}</p>
                                <p class="text-sm text-gray-600 mt-0.5">{{ $a->body }}</p>
                                <p class="text-xs text-gray-400 mt-1">by {{ $a->creator?->name ?? 'System' }} &bull; {{ $a->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <button type="button" @click="openEdit(@js($a))" title="Edit" class="text-gray-500 hover:text-gray-700">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.announcements.toggle', $a) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="{{ $a->is_active ? 'Deactivate' : 'Activate' }}" class="text-gray-500 hover:text-gray-700">
                                        <i class="fa-solid {{ $a->is_active ? 'fa-toggle-on text-success-500' : 'fa-toggle-off' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" onsubmit="return confirm('Delete this announcement?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete" class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- New / Edit modal -->
        <div x-show="modalOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center px-4"
             style="background-color: rgba(15,23,42,0.5);">
            <div @click.outside="modalOpen = false" class="bg-white rounded-2xl shadow-theme-lg w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <h3 class="font-semibold text-lg text-gray-900 mb-4" x-text="editingId ? 'Edit Announcement' : 'New Announcement'"></h3>

                <form method="POST" :action="editingId ? `{{ url('admin/announcements') }}/${editingId}` : `{{ route('admin.announcements.store') }}`" class="space-y-4">
                    @csrf
                    <template x-if="editingId"><input type="hidden" name="_method" value="PUT"></template>

                    <div>
                        <x-input-label value="Title" />
                        <input type="text" name="title" x-model="form.title" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>

                    <div>
                        <x-input-label value="Message" />
                        <textarea name="body" x-model="form.body" rows="3" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Type" />
                            <select name="type" x-model="form.type" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="danger">Danger</option>
                            </select>
                        </div>
                        <div class="flex items-end pb-2">
                            <label class="flex items-center gap-2 text-sm text-gray-700">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" x-model="form.is_active" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                Active
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Starts (optional)" />
                            <input type="datetime-local" name="starts_at" x-model="form.starts_at" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <x-input-label value="Ends (optional)" />
                            <input type="datetime-local" name="ends_at" x-model="form.ends_at" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm text-gray-600">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
