<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'key',
        'name',
        'price_monthly',
        'patients_limit',
        'appointments_limit',
        'invoices_limit',
        'doctors_limit',
        'pdf_export',
        'prescriptions',
        'analytics',
        'is_highlighted',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'pdf_export' => 'boolean',
            'prescriptions' => 'boolean',
            'is_highlighted' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class, 'plan', 'key');
    }

    public function toLimitsArray(): array
    {
        return [
            'patients' => $this->patients_limit,
            'appointments' => $this->appointments_limit,
            'invoices' => $this->invoices_limit,
            'doctors' => $this->doctors_limit,
            'pdf' => $this->pdf_export,
            'prescriptions' => $this->prescriptions,
            'analytics' => $this->analytics === 'none' ? false : $this->analytics,
        ];
    }
}
