<?php

namespace App\Models;

use App\Models\Concerns\BelongsToClinic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use BelongsToClinic, HasFactory;

    protected $fillable = [
        'clinic_id',
        'patient_id',
        'patient_name',
        'invoice_no',
        'invoice_date',
        'items',
        'tax_pct',
        'discount_pct',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'grand_total',
        'status',
        'notes',
    ];

    protected $appends = [
        'formatted_total',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'invoice_date' => 'date',
            'tax_pct' => 'decimal:2',
            'discount_pct' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    protected function formattedTotal(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => '₹'.number_format((float) $this->grand_total, 2),
        );
    }
}
