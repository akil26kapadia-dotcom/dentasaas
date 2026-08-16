<?php

namespace App\Services;

use App\Models\Clinic;

class InvoiceService
{
    public function calculateTotals(array $items, float $tax, float $disc): array
    {
        $subtotal = collect($items)->sum(function ($item) {
            return (float) ($item['qty'] ?? 1) * (float) ($item['price'] ?? 0);
        });

        $discountAmount = round($subtotal * ($disc / 100), 2);
        $taxableAmount = $subtotal - $discountAmount;
        $taxAmount = round($taxableAmount * ($tax / 100), 2);
        $grandTotal = round($taxableAmount + $taxAmount, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'grand_total' => $grandTotal,
        ];
    }

    public function generateInvoiceNo(Clinic $clinic): string
    {
        $count = $clinic->invoices()->count() + 1;

        return 'INV'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);
    }
}
