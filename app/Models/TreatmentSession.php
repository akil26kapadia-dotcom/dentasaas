<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TreatmentSession extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'clinic_id',
        'plan_id',
        'session_no',
        'title',
        'scheduled_date',
        'scheduled_time',
        'doctor_name',
        'status',
        'notes',
        'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'is_paid' => 'boolean',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TreatmentPlan::class, 'plan_id');
    }
}
