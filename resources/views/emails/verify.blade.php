<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f2f4f6; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; margin-top: 40px; border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td style="background-color: #4a90e2; padding: 20px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0;">Verify Your Email</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333;">Hi {{ $user->name }},</p>
                            <p style="font-size: 16px; color: #555;">
                                Thank you for signing up. Please verify your email address by clicking the button below.
                            </p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $verification_link }}" style="background-color: #4a90e2; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: bold;">Verify Email</a>
                            </p>
                            <p style="font-size: 14px; color: #777;">
                                If you didn’t create an account, no further action is required.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f9f9f9; padding: 20px; text-align: center; font-size: 12px; color: #aaa;">
                            &copy; {{ date('Y') }} Leading Edge International Aviation Academy • All rights reserved
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
