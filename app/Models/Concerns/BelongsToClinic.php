<?php

namespace App\Models\Concerns;

use App\Models\Clinic;
use App\Models\Scopes\ScopedByClinic;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait BelongsToClinic
{
    protected static function bootBelongsToClinic(): void
    {
        static::addGlobalScope(new ScopedByClinic);

        static::creating(function ($model) {
            if (! $model->clinic_id && Auth::check()) {
                $model->clinic_id = Auth::user()->clinic_id;
            }
        });
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }
}
