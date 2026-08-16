<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrescriptionRequest extends FormRequest
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
            'rx_date' => ['required', 'date'],
            'diagnosis' => ['nullable', 'string'],
            'medicines' => ['required', 'array', 'min:1'],
            'medicines.*.name' => ['required', 'string', 'max:255'],
            'medicines.*.dose' => ['required', 'string', 'max:100'],
            'medicines.*.freq' => ['required', 'in:OD,BD,TDS,QID,SOS'],
            'medicines.*.duration' => ['required', 'string', 'max:100'],
            'medicines.*.instructions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
