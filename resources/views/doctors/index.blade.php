<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Doctors</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    {{ $doctorsUsed }} / {{ $doctorsLimit === -1 ? 'Unlimited' : $doctorsLimit }} on your plan
                </p>
            </div>
            <button @click="modalOpen = true; editingId = null; form = { name: '', email: '', role: 'doctor', specialty: '', color: '#1649FF' }"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> Add Doctor
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="{
            modalOpen: false,
            editingId: null,
            form: { name: '', email: '', role: 'doctor', specialty: '', color: '#1649FF' },
            edit(doctor) {
                this.editingId = doctor.id;
                this.form = { name: doctor.name, email: doctor.email, role: doctor.role, specialty: doctor.specialty ?? '', color: doctor.color };
                this.modalOpen = true;
            }
         }">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @error('plan_limit')
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">{{ $message }}</div>
        @enderror

        @if ($doctors->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 dark:bg-gray-700 text-indigo-600 text-2xl mb-4">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-4">No doctors added yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($doctors as $doctor)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <span class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold"
                                      style="background-color: {{ $doctor->color }}">
                                    {{ strtoupper(substr($doctor->name, 0, 1)) }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $doctor->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $doctor->specialty ?: 'General' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                {{ ucfirst($doctor->role) }}
                            </span>

                            <form method="POST" action="{{ route('doctors.toggle', $doctor) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $doctor->is_active ? 'bg-green-500' : 'bg-gray-300' }}"
                                        title="{{ $doctor->is_active ? 'Active' : 'Inactive' }}">
                                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition {{ $doctor->is_active ? 'translate-x-4' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-sm">
                            <button type="button"
                                    @click="edit(@js(['id' => $doctor->id, 'name' => $doctor->name, 'email' => $doctor->email, 'role' => $doctor->role, 'specialty' => $doctor->specialty, 'color' => $doctor->color]))"
                                    class="text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>

                            @if ($doctor->id !== auth()->id())
                                <form method="POST" action="{{ route('doctors.destroy', $doctor) }}" onsubmit="return confirm('Deactivate this doctor?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fa-solid fa-user-slash"></i> Deactivate
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @include('doctors._modal')
    </div>
</x-app-layout>
