@php
    $btn = $labels
        ? 'inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50'
        : 'flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 hover:text-gray-800';
    $danger = $labels
        ? 'inline-flex w-full items-center justify-center gap-2 rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50'
        : 'flex h-8 w-8 items-center justify-center rounded-md text-red-500 hover:bg-red-50 hover:text-red-700';
@endphp

<button type="button"
    @click="openEdit(@js(['id' => $clinic->id, 'name' => $clinic->name, 'tagline' => $clinic->tagline, 'address' => $clinic->address, 'phone' => $clinic->phone, 'email' => $clinic->email, 'gst' => $clinic->gst, 'plan' => $clinic->plan, 'status' => $clinic->status]))"
    title="Edit details" aria-label="Edit details" class="{{ $btn }}">
    <i class="fa-solid fa-pen"></i>@if ($labels)<span>Edit</span>@endif
</button>

<form method="POST" action="{{ route('admin.clinics.extend', $clinic) }}" class="{{ $labels ? 'contents' : '' }}">
    @csrf
    @method('PATCH')
    <button type="submit" title="Extend plan by 30 days" aria-label="Extend plan by 30 days" class="{{ $btn }}">
        <i class="fa-solid fa-calendar-plus text-blue-600"></i>@if ($labels)<span>+30 days</span>@endif
    </button>
</form>

<button type="button" @click="openReset({ id: {{ $clinic->id }}, name: @js($clinic->name) })"
    title="Reset password" aria-label="Reset password" class="{{ $btn }}">
    <i class="fa-solid fa-key text-amber-600"></i>@if ($labels)<span>Reset password</span>@endif
</button>

<form method="POST" action="{{ route('admin.clinics.impersonate', $clinic) }}" class="{{ $labels ? 'contents' : '' }}">
    @csrf
    <button type="submit" title="Log in as this clinic" aria-label="Log in as this clinic" class="{{ $btn }}">
        <i class="fa-solid fa-right-to-bracket text-indigo-600"></i>@if ($labels)<span>View as clinic</span>@endif
    </button>
</form>

<form method="POST" action="{{ route('admin.clinics.destroy', $clinic) }}"
    onsubmit="return confirm('Delete this clinic and all its data? This cannot be undone.');"
    class="{{ $labels ? 'col-span-2' : 'ml-1 border-l border-gray-200 pl-2' }}">
    @csrf
    @method('DELETE')
    <button type="submit" title="Delete clinic" aria-label="Delete clinic" class="{{ $danger }}">
        <i class="fa-solid fa-trash"></i>@if ($labels)<span>Delete clinic</span>@endif
    </button>
</form>
