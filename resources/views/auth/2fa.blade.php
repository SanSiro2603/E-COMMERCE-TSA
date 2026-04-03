<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi 2FA - Ecommerce TSA</title>
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
            max-width: 320px;
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
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2 class="text-xl font-bold text-gray-900 mb-2">Verifikasi 2FA</h2>
        <p class="text-xs text-gray-600 mb-6">Masukkan 6 digit kode dari aplikasi Google Authenticator Anda.</p>

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf
            
            <div class="mb-4">
                <input type="text" name="one_time_password" required autofocus maxlength="6"
                    class="w-full text-center tracking-widest text-lg font-bold border border-gray-300 rounded-lg p-3 focus:border-green-500 focus:ring-0"
                    placeholder="000000" />
                @error('one_time_password')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary hover:scale-[1.02] transition-all">Verifikasi</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="text-xs text-red-500 hover:underline bg-transparent border-none cursor-pointer">Batal & Logout</button>
        </form>
    </div>
</body>
</html>
