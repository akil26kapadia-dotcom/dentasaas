<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription {{ $prescription->id }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #111827;
            font-size: 10pt;
        }
        table { border-collapse: collapse; }
        .topbar { height: 2px; background-color: #059669; }
        .container { padding: 8mm 12mm 4mm 12mm; }
        .clinic-name { font-size: 16pt; font-weight: bold; color: #0b1e3d; margin: 4px 0 2px; }
        .clinic-tagline { font-size: 8pt; font-style: italic; color: #059669; margin: 0 0 4px; }
        .clinic-meta { font-size: 7pt; color: #6b7280; line-height: 1.6; }
        .divider { height: 1px; background-color: #059669; margin: 6mm 0; }
        .meta-row td { font-size: 8pt; color: #6b7280; padding: 6px 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; }
        .meta-row strong { color: #111827; }
        .rx-table th { background-color: #0b1e3d; color: #ffffff; padding: 6px 8px; text-align: left; font-size: 8pt; }
        .rx-table td { padding: 7px 8px; font-size: 9pt; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .footer-band { background-color: #059669; padding: 6px 12mm; margin-top: 10mm; }
    </style>
</head>
<body>
    <div class="topbar"></div>

    <div class="container">
        <!-- Header -->
        <table style="width: 100%;">
            <tr>
                <td style="width: 62%; vertical-align: top;">
                    @if ($clinic->logo_path)
                        <img src="{{ public_path('storage/'.$clinic->logo_path) }}" style="width: 22mm; height: 22mm; object-fit: contain;">
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
                            {{ $clinic->phone }}{{ $clinic->phone && $clinic->email ? ' &bull; ' : '' }}{{ $clinic->email }}
                        @endif
                    </div>
                </td>
                <td style="width: 38%; vertical-align: top; text-align: right;">
                    <table style="width: 100%; background-color: #ecfdf5;">
                        <tr>
                            <td style="padding: 10px; text-align: center;">
                                <div style="font-size: 26pt; font-weight: bold; color: #059669; font-family: serif;">Rx</div>
                                <div style="font-size: 8pt; font-weight: bold; letter-spacing: 2px; color: #065f46;">PRESCRIPTION</div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table class="meta-row" style="width: 100%; margin-top: 6mm;">
            <tr>
                <td style="width: 34%;">Date: <strong>{{ $prescription->rx_date->format('d M Y') }}</strong></td>
                <td style="width: 33%;">Doctor: <strong>Dr. {{ $doctorName }}</strong></td>
                <td style="width: 33%; text-align: right;">Ref#: <strong>RX-{{ str_pad($prescription->id, 5, '0', STR_PAD_LEFT) }}</strong></td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Patient card -->
        <table style="width: 100%; background-color: #f3f4f6; margin-bottom: 8mm;">
            <tr>
                <td style="width: 55%; border-left: 3px solid #059669; padding: 6px 10px;">
                    <div style="font-size: 7pt; color: #6b7280; letter-spacing: 1px;">PATIENT</div>
                    <div style="font-size: 13pt; font-weight: bold; color: #111827;">{{ $prescription->patient_name }}</div>
                </td>
                <td style="width: 45%; padding: 6px 10px; vertical-align: top;">
                    @if ($prescription->diagnosis)
                        <div style="font-size: 7pt; color: #6b7280; letter-spacing: 1px;">DIAGNOSIS</div>
                        <div style="font-size: 9pt; color: #111827;">{{ $prescription->diagnosis }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <div style="font-size: 10pt; font-weight: bold; color: #111827; margin-bottom: 2px;">PRESCRIBED MEDICINES</div>
        <div style="height: 2px; background-color: #059669; width: 40mm; margin-bottom: 5mm;"></div>

        <!-- Medicines -->
        <table class="rx-table" style="width: 100%; margin-bottom: 6mm;">
            <thead>
                <tr>
                    <th style="width: 6%;">#</th>
                    <th style="width: 34%;">MEDICINE</th>
                    <th style="width: 16%;">DOSE</th>
                    <th style="width: 20%;">FREQUENCY</th>
                    <th style="width: 24%;">DURATION</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prescription->medicines as $i => $med)
                    <tr style="background-color: {{ $i % 2 === 0 ? '#ffffff' : '#f9fafb' }};">
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ $med['name'] }}</div>
                            @if (! empty($med['instructions']))
                                <div style="font-style: italic; color: #059669; font-size: 8pt;">{{ $med['instructions'] }}</div>
                            @endif
                        </td>
                        <td>{{ $med['dose'] }}</td>
                        <td>
                            <span style="background-color: #d1fae5; color: #065f46; font-size: 7pt; font-weight: bold; padding: 2px 6px;">
                                {{ $med['freq'] }}
                            </span>
                        </td>
                        <td>{{ $med['duration'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($prescription->notes)
            <table style="width: 100%; background-color: #ecfdf5; margin-bottom: 8mm;">
                <tr>
                    <td style="padding: 8px 10px; font-size: 8pt; color: #065f46;">
                        <strong>Special Instructions:</strong> {{ $prescription->notes }}
                    </td>
                </tr>
            </table>
        @endif

        <!-- Stamp + Signature -->
        <table style="width: 100%; margin-top: 14mm;">
            <tr>
                <td style="width: 45%; border-top: 1px dashed #9ca3af; padding-top: 4px; font-size: 8pt; color: #6b7280;">
                    <span style="font-weight: bold; color: #111827;">Dr. {{ $doctorName }}</span><br>
                    {{ $clinic->name }}
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%; text-align: right;">
                    <table style="margin-left: auto; border: 1px dashed #9ca3af;">
                        <tr>
                            <td style="padding: 14px 24px; font-size: 8pt; color: #9ca3af; text-align: center;">[ STAMP ]</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="topbar"></div>
    <div class="footer-band">
        <table style="width: 100%;">
            <tr>
                <td style="color: #ffffff; font-size: 7pt; text-align: center;">
                    Valid for 30 days from date of issue
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
