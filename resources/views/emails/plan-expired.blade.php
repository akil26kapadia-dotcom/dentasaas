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
                            <h2 style="margin-top:0; color:#111827;">Your {{ ucfirst($expiredPlan) }} plan has expired</h2>
                            <p style="color:#374151;">
                                Hi {{ $clinic->name }}, your <strong>{{ ucfirst($expiredPlan) }}</strong> plan has expired and
                                your account has been moved to the <strong>Free</strong> plan.
                            </p>

                            <table role="presentation" width="100%" cellpadding="12" cellspacing="0" style="margin: 20px 0; background-color:#fef2f2; border-radius: 8px;">
                                <tr>
                                    <td style="color:#991b1b; font-size: 14px;">
                                        Some features and higher plan limits are no longer available until you renew.
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#374151;">
                                Renew: <a href="https://clinic.designflowstudio.space" style="color:#465fff;">clinic.designflowstudio.space</a><br>
                                Or WhatsApp us: <a href="https://wa.me/918488055253" style="color:#465fff;">+91 8488055253</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
