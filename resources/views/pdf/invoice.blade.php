<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #111827;
            font-size: 10pt;
        }

        table {
            border-collapse: collapse;
        }

        .topbar {
            height: 2px;
            background-color: #465fff;
        }

        .container {
            padding: 8mm 12mm 4mm 12mm;
        }

        .clinic-name {
            font-size: 16pt;
            font-weight: bold;
            color: #0b1e3d;
            margin: 4px 0 2px;
        }

        .clinic-tagline {
            font-size: 8pt;
            font-style: italic;
            color: #465fff;
            margin: 0 0 4px;
        }

        .clinic-meta {
            font-size: 7pt;
            color: #6b7280;
            line-height: 1.6;
        }

        .divider {
            height: 1px;
            background-color: #465fff;
            margin: 6mm 0;
        }

        .items-table th {
            background-color: #0b1e3d;
            color: #ffffff;
            padding: 6px 8px;
            text-align: left;
            font-size: 8pt;
        }

        .items-table td {
            padding: 6px 8px;
            font-size: 9pt;
            border-bottom: 1px solid #e5e7eb;
        }

        .footer-band {
            background-color: #0b1e3d;
            padding: 6px 12mm;
            margin-top: 10mm;
        }

        .watermark {
            position: fixed;
            top: 42%;
            left: 18%;
            font-size: 72pt;
            font-weight: bold;
            letter-spacing: 4px;
            transform: rotate(-28deg);
            opacity: 0.07;
        }
    </style>
</head>

<body>
    <div class="watermark" style="color: {{ $invoice->status === 'paid' ? '#059669' : '#dc2626' }};">
        {{ strtoupper($invoice->status) }}
    </div>

    <div class="topbar"></div>

    <div class="container">
        <!-- Header -->
        <table style="width: 100%;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    @if ($clinic->logo_path)
                        <img src="{{ public_path('storage/' . $clinic->logo_path) }}"
                            style="width: 22mm; height: 22mm; object-fit: contain;">
                    @endif
                    <div class="clinic-name">{{ $clinic->name }}</div>
                    @if ($clinic->tagline)
                        <div class="clinic-tagline">{{ $clinic->tagline }}</div>
                    @endif
                    <div class="clinic-meta">
                        @if ($clinic->address)
                            {{ $clinic->address }}<br>
                        @endif
                        @if ($clinic->phone || $clinic->email)
                            {{ $clinic->phone }}{{ $clinic->phone && $clinic->email ? ' &bull; ' : '' }}{{ $clinic->email }}<br>
                        @endif
                        @if ($clinic->gst)
                            GSTIN: {{ $clinic->gst }}
                        @endif
                    </div>
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <table style="width: 100%; background-color: #0b1e3d;">
                        <tr>
                            <td colspan="2"
                                style="background-color: #465fff; color: #ffffff; font-size: 9pt; font-weight: bold; padding: 6px 8px;">
                                INVOICE DETAILS
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #9ca3af; font-size: 8pt; padding: 3px 8px;">Invoice No</td>
                            <td style="color: #ffffff; font-size: 8pt; padding: 3px 8px; text-align: right;">
                                {{ $invoice->invoice_no }}</td>
                        </tr>
                        <tr>
                            <td style="color: #9ca3af; font-size: 8pt; padding: 3px 8px;">Date</td>
                            <td style="color: #ffffff; font-size: 8pt; padding: 3px 8px; text-align: right;">
                                {{ $invoice->invoice_date->format('d M Y') }}</td>
                        </tr>
                        <tr>
                            <td style="color: #9ca3af; font-size: 8pt; padding: 3px 8px;">Status</td>
                            <td style="padding: 3px 8px; text-align: right;">
                                <span
                                    style="background-color: {{ $invoice->status === 'paid' ? '#059669' : '#dc2626' }}; color: #ffffff; font-size: 7pt; font-weight: bold; padding: 2px 6px;">
                                    {{ strtoupper($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="color: #9ca3af; font-size: 8pt; padding: 3px 8px 8px;">Doctor</td>
                            <td style="color: #ffffff; font-size: 8pt; padding: 3px 8px 8px; text-align: right;">
                                {{ $doctorName }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Bill to -->
        <table style="width: 100%; background-color: #f3f4f6; margin-bottom: 6mm;">
            <tr>
                <td style="border-left: 3px solid #465fff; padding: 6px 10px;">
                    <div style="font-size: 7pt; color: #6b7280; letter-spacing: 1px;">BILL TO</div>
                    <div style="font-size: 13pt; font-weight: bold; color: #111827;">{{ $invoice->patient_name }}</div>
                    @if ($invoice->patient?->phone)
                        <div style="font-size: 8pt; color: #6b7280;">{{ $invoice->patient->phone }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Items -->
        <table class="items-table" style="width: 100%; margin-bottom: 6mm;">
            <thead>
                <tr>
                    <th style="width: 8%;">#</th>
                    <th style="width: 47%;">DESCRIPTION</th>
                    <th style="width: 12%; text-align: right;">QTY</th>
                    <th style="width: 15%; text-align: right;">RATE</th>
                    <th style="width: 18%; text-align: right;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invoice->items as $i => $item)
                    <tr style="background-color: {{ $i % 2 === 0 ? '#ffffff' : '#f9fafb' }};">
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $item['service'] }}</td>
                        <td style="text-align: right;">{{ $item['qty'] }}</td>
                        <td style="text-align: right;">₹{{ number_format($item['price'], 2) }}</td>
                        <td style="text-align: right; font-weight: bold;">
                            ₹{{ number_format($item['qty'] * $item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table style="width: 100%; margin-bottom: 6mm;">
            <tr>
                <td style="width: 55%;"></td>
                <td style="width: 45%;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 3px 0; font-size: 9pt; color: #6b7280;">Subtotal</td>
                            <td style="padding: 3px 0; font-size: 9pt; text-align: right;">
                                ₹{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; font-size: 9pt; color: #6b7280;">Discount
                                ({{ $invoice->discount_pct }}%)</td>
                            <td style="padding: 3px 0; font-size: 9pt; text-align: right; color: #dc2626;">-
                                ₹{{ number_format($invoice->discount_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 3px 0; font-size: 9pt; color: #6b7280;">GST ({{ $invoice->tax_pct }}%)
                            </td>
                            <td style="padding: 3px 0; font-size: 9pt; text-align: right;">+
                                ₹{{ number_format($invoice->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top: 5px;">
                                <table style="width: 100%; background-color: #465fff;">
                                    <tr>
                                        <td
                                            style="padding: 7px 10px; color: #ffffff; font-size: 10pt; font-weight: bold;">
                                            GRAND TOTAL</td>
                                        <td
                                            style="padding: 7px 10px; color: #ffffff; font-size: 13pt; font-weight: bold; text-align: right;">
                                            ₹{{ number_format($invoice->grand_total, 2) }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        @if ($invoice->notes)
            <table style="width: 100%; background-color: #fffbeb; margin-bottom: 6mm;">
                <tr>
                    <td style="padding: 8px 10px; font-size: 8pt; color: #92400e;">
                        <strong>Notes:</strong> {{ $invoice->notes }}
                    </td>
                </tr>
            </table>
        @endif

        @if ($invoice->status === 'unpaid')
            <table style="width: 100%; background-color: #ecfdf5; margin-bottom: 6mm;">
                <tr>
                    <td style="padding: 8px 10px; font-size: 8pt; color: #065f46;">
                        <strong>Payment:</strong> Please complete payment at your earliest convenience. For queries,
                        contact {{ $clinic->phone ?: '+91 8488055253' }}.
                    </td>
                </tr>
            </table>
        @endif

        <!-- Signatures -->
        <table style="width: 100%; margin-top: 14mm;">
            <tr>
                <td
                    style="width: 45%; border-top: 1px dashed #9ca3af; padding-top: 4px; font-size: 8pt; color: #6b7280;">
                    Patient Signature
                </td>
                <td style="width: 10%;"></td>
                <td
                    style="width: 45%; border-top: 1px dashed #9ca3af; padding-top: 4px; font-size: 8pt; color: #6b7280; text-align: right;">
                    Authorised Signatory<br>
                    <span style="font-weight: bold; color: #111827;">{{ $clinic->name }}</span><br>
                    {{ $doctorName }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="topbar"></div>
    <div class="footer-band">
        <table style="width: 100%;">
            <tr>
                <td style="width: 33%;"></td>
                <td style="width: 34%; color: #ffffff; font-size: 7pt; text-align: center;">
                    {{ $clinic->tagline ?: 'Thank you for choosing us' }}
                </td>
                <td style="width: 33%; color: #9ca3af; font-size: 7pt; text-align: right;">
                    Generated by DentaSaaS
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
