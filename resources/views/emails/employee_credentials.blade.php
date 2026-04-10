<!DOCTYPE html>
<html>

<head>
    <title>Your ICT Hub Account Credentials</title>
</head>

<body style="font-family: sans-serif; color: #333;">
    <p>Hello,</p>
    <p>Your <strong>Tayabas ICT Hub</strong> account has been created. Here are your login credentials:</p>
    <table style="border-collapse:collapse; margin: 16px 0;">
        <tr>
            <td style="padding: 6px 12px; font-weight:600;">Username:</td>
            <td style="padding: 6px 12px;">{{ $user->username }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight:600;">Password:</td>
            <td style="padding: 6px 12px;">{{ $password }}</td>
        </tr>
    </table>
    <p>For security, you will be required to change your password on first login.</p>
    <p>
        Login here:
        <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
    </p>
    <br>
    <p>Thank you,<br><strong>Tayabas ICT Hub</strong></p>
</body>

</html>
