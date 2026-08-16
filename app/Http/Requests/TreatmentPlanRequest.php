<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'max:255'],
            'patient_id' => ['nullable', 'integer', 'exists:patients,id'],
            'doctor_name' => ['nullable', 'string', 'max:255'],
            'treatment' => ['required', 'string', 'max:255'],
            'total_sessions' => ['required', 'integer', 'min:1', 'max:50'],
            'status' => ['nullable', 'in:planned,in_progress,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
