<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>2FA Setup - Ecommerce TSA</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2E7D32",
                    },
                    fontFamily: {
                        inter: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #f0eded 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        .btn-primary {
            background: linear-gradient(to right, #15803d, #22c55e);
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Setup 2-Factor Authentication</h2>
        <p class="text-sm text-gray-600 mb-4">Scan QR code di bawah menggunakan aplikasi Google Authenticator.</p>

        <div class="flex justify-center mb-4">
            {!! $qrCode !!}
        </div>

        <p class="text-xs text-gray-500 mb-4">Atau masukkan kode rahasia ini secara manual:<br><strong>{{ $secret }}</strong></p>

        <form action="{{ route('2fa.index') }}" method="GET">
            <button type="submit" class="btn-primary hover:scale-[1.02] transition-all">Lanjutkan ke Verifikasi</button>
        </form>
    </div>
</body>
</html>
