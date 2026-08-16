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
                            <h2 style="margin-top:0; color:#111827;">Welcome to {{ $clinic->name }}</h2>
                            <p style="color:#374151;">
                                Your DentaSaaS account is ready. Use the credentials below to log in.
                            </p>

                            <table role="presentation" width="100%" cellpadding="12" cellspacing="0" style="margin: 20px 0; background-color:#eef2ff; border-radius: 8px;">
                                <tr>
                                    <td style="color:#374151;">
                                        <strong>Login:</strong> {{ $user->email }}<br>
                                        <strong>Password:</strong> {{ $password }}
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#374151;">
                                <a href="{{ url('/login') }}" style="color:#1649FF;">{{ url('/login') }}</a>
                            </p>

                            <p style="color:#9ca3af; font-size: 12px;">
                                For your security, please log in and change your password as soon as possible.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
