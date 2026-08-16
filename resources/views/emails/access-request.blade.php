<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="background-color:#0b1e3d; padding: 24px 32px;">
                            <span style="color:#ffffff; font-size: 20px; font-weight: bold;">DentaSaaS</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin-top:0; color:#111827;">New Access Request</h2>
                            <p style="color:#374151;">A new clinic has requested access to DentaSaaS.</p>

                            <table role="presentation" width="100%" cellpadding="8" cellspacing="0" style="margin-top: 16px; border: 1px solid #e5e7eb; border-radius: 8px;">
                                <tr>
                                    <td style="color:#6b7280; width: 140px;">Clinic Name</td>
                                    <td style="color:#111827; font-weight: 600;">{{ $accessRequest->clinic_name }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Contact Name</td>
                                    <td style="color:#111827;">{{ $accessRequest->name }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Email</td>
                                    <td style="color:#111827;">{{ $accessRequest->email }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Phone</td>
                                    <td style="color:#111827;">{{ $accessRequest->phone ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280; vertical-align: top;">Message</td>
                                    <td style="color:#111827;">{{ $accessRequest->message ?? '—' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
