<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use BelongsToClinic, HasFactory, SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'name',
        'phone',
        'email',
        'dob',
        'gender',
        'blood_group',
        'address',
        'allergies',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
        ];
    }
}
