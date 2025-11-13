<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password - Ecommerce TSA</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: #333;
    }
    .container {
      max-width: 560px;
      margin: 30px auto;
      background: #ffffff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }
    .header {
      background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
      padding: 32px 24px;
      text-align: center;
      color: white;
    }
    .header img {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid rgba(255,255,255,0.2);
    }
    .header h1 {
      margin: 16px 0 0;
      font-size: 20px;
      font-weight: 700;
    }
    .header p {
      margin: 8px 0 0;
      font-size: 13px;
      opacity: 0.9;
    }
    .content {
      padding: 32px 28px;
      text-align: center;
    }
    .content h2 {
      font-size: 18px;
      font-weight: 600;
      color: #1a1a1a;
      margin-bottom: 12px;
    }
    .content p {
      font-size: 14px;
      line-height: 1.6;
      color: #555;
      margin-bottom: 20px;
    }
    .reset-btn {
      display: inline-block;
      background: linear-gradient(to right, #2E7D32, #4CAF50);
      color: white;
      font-weight: 600;
      font-size: 14px;
      padding: 14px 32px;
      border-radius: 10px;
      text-decoration: none;
      box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
      transition: all 0.3s ease;
    }
    .reset-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
    }
    .info {
      background: #f0f7f0;
      border: 1px dashed #81C784;
      border-radius: 10px;
      padding: 16px;
      margin: 24px 0;
      font-size: 12px;
      color: #2E7D32;
    }
    .info strong {
      color: #1B5E20;
    }
    .footer {
      background: #f5f5f5;
      padding: 24px;
      text-align: center;
      font-size: 11px;
      color: #777;
    }
    .footer a {
      color: #2E7D32;
      text-decoration: underline;
    }
    .security {
      margin-top: 24px;
      font-size: 12px;
      color: #999;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <img src="images/logo.png" alt="Ecommerce TSA" />
      <h1>Ecommerce TSA</h1>
      <p>Platform Belanja Hewan</p>
    </div>

    <!-- Content -->
    <div class="content">
      <h2>Permintaan Reset Password</h2>
      <p>
        Kami menerima permintaan untuk mereset kata sandi akun Anda di <strong>Ecommerce TSA</strong>.
      </p>
      <p>
        Klik tombol di bawah ini untuk membuat kata sandi baru. Link ini hanya berlaku <strong>10 menit</strong>.
      </p>

      <a href="{{ $url }}" class="reset-btn">
        Reset Kata Sandi
      </a>

      <div class="info">
        <p>
          <strong>Link tidak bisa diklik?</strong><br>
          Salin URL berikut ke browser Anda:
        </p>
        <p style="word-break: break-all; font-family: monospace; font-size: 11px; margin: 8px 0;">
          {{ $url }}
        </p>
      </div>

      <p class="security">
        Jika Anda <strong>tidak</strong> meminta reset password, abaikan email ini.<br>
        Akun Anda tetap aman dan tidak ada perubahan yang dilakukan.
      </p>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>
        © {{ date('Y') }} <strong>Ecommerce TSA</strong>. All rights reserved.<br>
        <a href="{{ url('/') }}">ecommercetsa.com</a> • 
        <a href="mailto:support@ecommercetsa.com">support@ecommercetsa.com</a>
      </p>
      <p style="margin-top: 12px; color: #aaa;">
        Email ini dikirim otomatis. Mohon jangan balas.
      </p>
    </div>
  </div>
</body>
</html>