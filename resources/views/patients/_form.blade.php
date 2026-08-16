@php
    $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" value="Full Name" />
        <x-text-input id="name" name="name" class="block mt-1 w-full" :value="old('name', $patient->name)" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="phone" value="Phone" />
        <x-text-input id="phone" name="phone" class="block mt-1 w-full" :value="old('phone', $patient->phone)" />
        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="email" value="Email" />
        <x-text-input id="email" type="email" name="email" class="block mt-1 w-full" :value="old('email', $patient->email)" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="dob" value="Date of Birth" />
        <x-text-input id="dob" type="date" name="dob" class="block mt-1 w-full" :value="old('dob', optional($patient->dob)->format('Y-m-d'))" />
        <x-input-error :messages="$errors->get('dob')" class="mt-2" />
    </div>

    <div>
        <x-input-label value="Gender" />
        <div class="flex gap-3 mt-2">
            @foreach (['male', 'female', 'other'] as $g)
                <label
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border cursor-pointer text-sm
 {{ old('gender', $patient->gender) === $g ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-gray-200 text-gray-600' }}">
                    <input type="radio" name="gender" value="{{ $g }}" class="text-indigo-600"
                        {{ old('gender', $patient->gender) === $g ? 'checked' : '' }}>
                    {{ ucfirst($g) }}
                </label>
            @endforeach
        </div>
        <x-input-error :messages="$errors->get('gender')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="blood_group" value="Blood Group" />
        <select id="blood_group" name="blood_group"
            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Select</option>
            @foreach ($bloodGroups as $bg)
                <option value="{{ $bg }}"
                    {{ old('blood_group', $patient->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('blood_group')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="address" value="Address" />
        <textarea id="address" name="address" rows="2"
            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $patient->address) }}</textarea>
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="allergies" value="Allergies" />
        <textarea id="allergies" name="allergies" rows="2"
            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('allergies', $patient->allergies) }}</textarea>
        <x-input-error :messages="$errors->get('allergies')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" rows="3"
            class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $patient->notes) }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>
</div>
