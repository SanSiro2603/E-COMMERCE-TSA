# Perencanaan Implementasi Keamanan & Manajemen Akun

## 📋 Struktur Perencanaan Fitur

- [ ] **1. Google OAuth 2.0 (Khusus Customer)**
  - *Catatan: Cek terlebih dahulu apakah fitur atau konfigurasi ini sudah tersedia di project saat ini.*
  - Pemeriksaan kelayakan konfigurasi Google Cloud Console.
  - Pemeriksaan rute dan *controller* Google Socialite eksisting.

- [ ] **2. CAPTCHA (Khusus Customer)**
  - *Catatan: Cek terlebih dahulu apakah komponen login sudah menerapkan CAPTCHA.*
  - Validasi form login terkait verifikasi CAPTCHA (FCaptcha/reCAPTCHA).

- [ ] **3. Rate Limiting**
  - Pengecekan limit akses pada rute-rute sensitif (seperti `/login`, `/register`).
  - Menerapkan *ThrottleRequests* middleware.
  - Uji penanganan ketika *request* melebihi batas batas ambang (*Too Many Requests*).

- [ ] **4. 2FA TOTP dengan Google Authenticator (Khusus Admin & Super Admin)**
  - Setup sistem TOTP rahasia untuk Admin dan Super Admin.
  - Implementasi *middleware* atau *flow* verifikasi kode 2FA setelah proses pengecekan kredensial password.
  - UI untuk *bind* aplikasi Google Authenticator melalui QR Code.

- [ ] **5. Role-Based Access Control (RBAC)**
  - Penerapan batasan akses (Gate/Policy/Middleware) antara User, Admin, dan Super Admin.
  - Memastikan *route group* masing-masing aktor aman dari ekskalasi hak akses (*privilege escalation*).

- [ ] **6. Fitur Manajemen Akun Admin (Khusus Super Admin)**
  - [ ] **a.** Menambah akun admin baru.
  - [ ] **b.** Mengubah detail akun admin (nama, email, dll).
  - [ ] **c.** Mengaktifkan / menonaktifkan status login akun admin (Active/Inactive).
  - [ ] **d.** Mengatur / mengubah role akun admin.
  - [ ] **e.** Mereset / memaksa ganti password akun admin.
  - [ ] **f.** Mereset sambungan 2FA admin agar admin bisa *scan* QR code baru.
  - [ ] **g.** Melihat status keamanan akun admin.

---

## 🧪 Rencana Pengujian (Testing)

| No | Skenario Pengujian | Hasil Diharapkan | Status |
|----|--------------------|------------------|--------|
| 1  | Pengujian autentikasi login customer | Customer dapat login dengan email & password valid. | ⬜ Pending |
| 2  | Pengujian login Google OAuth | Customer dapat login/register via akun Google tanpa mengisi form, masuk sebagai akun yang bersangkutan. | ⬜ Pending |
| 3  | Pengujian login admin & super admin | Bisa masuk ke dashboard khusus admin sesuai rolenya. | ⬜ Pending |
| 4  | Pengujian CAPTCHA | Gagal login jika CAPTCHA salah/dikosongkan, meskipun kredensial benar. | ⬜ Pending |
| 5  | Pengujian rate limiting | Akun atau IP diblokir sementara jika mencoba login berturut-turut melebihi batas (contoh: 5x gagal). | ⬜ Pending |
| 6  | Pengujian 2FA Google Authenticator | Admin/Super Admin tidak bisa masuk Dashboard sebelum memasukkan 6 digit kode OTP valid dari HP. | ⬜ Pending |
| 7  | Pengujian pembatasan akses berdasarkan role | Admin tidak bisa akses halaman Super Admin (e.g. Manajemen Admin), Customer tidak akses halaman Admin. | ⬜ Pending |
| 8  | Pengujian fitur manajemen akun admin | Super Admin berhasil Create, Read, Update, Nonaktifkan, Reset Password dan 2FA milik Admin lain. | ⬜ Pending |
| 9  | Pengujian logout | Sesi dihancurkan seluruhnya, mencegah penggunaan token lama, mengarahkan ke halaman tamu. | ⬜ Pending |
