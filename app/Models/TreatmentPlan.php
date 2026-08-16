<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentPlan extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'patient_name',
        'doctor_name',
        'treatment',
        'total_sessions',
        'notes',
        'status',
    ];

    protected $appends = [
        'completed_count',
        'progress_pct',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(TreatmentSession::class, 'plan_id');
    }

    protected function completedCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->sessions->where('status', 'completed')->count(),
        );
    }

    protected function progressPct(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total_sessions > 0
                ? (int) round(($this->completed_count / $this->total_sessions) * 100)
                : 0,
        );
    }
}
