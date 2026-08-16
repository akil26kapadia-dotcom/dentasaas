<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Services</h2>
            <button @click="modalOpen = true; editingId = null; form = { name: '', name_hi: '', price: '', duration_min: 30, description: '', icon: 'fa-tooth', is_active: true }"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                <i class="fa-solid fa-plus"></i> Add Service
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
         x-data="{
            modalOpen: false,
            editingId: null,
            form: { name: '', name_hi: '', price: '', duration_min: 30, description: '', icon: 'fa-tooth', is_active: true },
            edit(service) {
                this.editingId = service.id;
                this.form = {
                    name: service.name,
                    name_hi: service.name_hi ?? '',
                    price: service.price,
                    duration_min: service.duration_min,
                    description: service.description ?? '',
                    icon: service.icon,
                    is_active: !!service.is_active,
                };
                this.modalOpen = true;
            }
         }">

        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        @if ($services->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 text-center py-16">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 dark:bg-gray-700 text-indigo-600 text-2xl mb-4">
                    <i class="fa-solid fa-tooth"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 mb-4">No services added yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($services as $service)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-5">
                        <div class="flex items-start justify-between">
                            <span class="w-14 h-14 rounded-full bg-indigo-50 dark:bg-gray-700 text-indigo-600 flex items-center justify-center text-2xl">
                                <i class="fa-solid {{ $service->icon }}"></i>
                            </span>

                            <form method="POST" action="{{ route('services.toggle', $service) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $service->is_active ? 'bg-green-500' : 'bg-gray-300' }}"
                                        title="{{ $service->is_active ? 'Active' : 'Inactive' }}">
                                    <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition {{ $service->is_active ? 'translate-x-4' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                        </div>

                        <p class="mt-4 font-medium text-gray-900 dark:text-white">{{ $service->name }}</p>
                        @if ($service->name_hi)
                            <p class="text-sm text-gray-400">{{ $service->name_hi }}</p>
                        @endif

                        <div class="flex items-center justify-between mt-3">
                            <span class="text-lg font-semibold" style="color:#1649FF;">₹{{ number_format($service->price, 0) }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400"><i class="fa-regular fa-clock"></i> {{ $service->duration_min }} min</span>
                        </div>

                        <div class="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 text-sm">
                            <button type="button"
                                    @click="edit(@js(['id' => $service->id, 'name' => $service->name, 'name_hi' => $service->name_hi, 'price' => $service->price, 'duration_min' => $service->duration_min, 'description' => $service->description, 'icon' => $service->icon, 'is_active' => $service->is_active]))"
                                    class="text-gray-500 hover:text-gray-700">
                                <i class="fa-solid fa-pen"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('services.destroy', $service) }}" onsubmit="return confirm('Delete this service?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @include('services._modal')
    </div>
</x-app-layout>
