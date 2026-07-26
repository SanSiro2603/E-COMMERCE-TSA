<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Google Authenticator (2FA)</title>
</head>
<body style="font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f8fafc; margin: 0; padding: 32px 16px; color: #1e293b;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
        <!-- Header -->
        <tr>
            <td style="padding: 32px 32px 24px 32px; background-color: #059669; text-align: center;">
                <h1 style="color: #ffffff; font-size: 20px; font-weight: 700; margin: 0;">Ecommerce TSA</h1>
                <p style="color: #a7f3d0; font-size: 13px; margin: 6px 0 0 0;">Keamanan Akun Super Admin</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 32px;">
                <h2 style="font-size: 18px; font-weight: 600; color: #0f172a; margin-top: 0;">Permintaan Reset Barcode 2FA</h2>
                <p style="font-size: 14px; line-height: 1.6; color: #475569; margin-bottom: 24px;">
                    Kami menerima permintaan untuk mereset Google Authenticator (2FA) akun Super Admin Anda. Klik tombol di bawah ini untuk menghapus barcode lama dan mendaftarkan barcode baru:
                </p>

                <div style="text-align: center; margin: 32px 0;">
                    <a href="{{ $url }}" style="display: inline-block; background-color: #059669; color: #ffffff; font-weight: 600; font-size: 14px; text-decoration: none; padding: 14px 28px; border-radius: 12px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);">
                        Reset Authenticator Sekarang
                    </a>
                </div>

                <div style="background-color: #f1f5f9; border-left: 4px solid #059669; padding: 14px 16px; border-radius: 8px; margin-bottom: 24px;">
                    <p style="font-size: 13px; color: #334155; margin: 0; line-height: 1.5;">
                        <strong>Catatan Keamanan:</strong> Link di atas hanya berlaku selama <strong>15 menit</strong>. Jika link hangus (kadaluarsa), Anda dapat meminta link reset baru dari halaman verifikasi 2FA.
                    </p>
                </div>

                <p style="font-size: 13px; line-height: 1.6; color: #64748b; margin-bottom: 0;">
                    Jika Anda tidak melakukan permintaan ini, mohon abaikan email ini. Akun Anda tetap aman.
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="padding: 20px 32px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center; font-size: 12px; color: #94a3b8;">
                &copy; {{ date('Y') }} Ecommerce TSA. Semua hak dilindungi.
            </td>
        </tr>
    </table>
</body>
</html>
