<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .otp {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            background-color: #eef1f5;
            padding: 10px 20px;
            display: inline-block;
            border-radius: 5px;
            direction: ltr;
        }
        .footer {
            font-size: 12px;
            color: #999;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 style="margin-top: 0;">رمز التحقق الخاص بك</h2>
        <p>لقد طلبت رمز تحقق لتسجيل الدخول أو لإجراء عملية آمنة.</p>
        <p>رمز OTP الخاص بك هو:</p>
        <div class="otp">{{ $otp }}</div>
        <p>يرجى استخدام هذا الرمز خلال بضع دقائق قبل انتهاء صلاحيته.</p>

        <div class="footer">
            © {{ now()->year }} جميع الحقوق محفوظة
        </div>
    </div>
</body>
</html>
