# 🏟️ SportField Booking — Sistem Booking Lapangan Olahraga

> **Stack:** PHP Native · MySQL · Tailwind CSS CDN · Arsitektur MVC

---

## 📦 Instalasi

### Kebutuhan
- PHP 8.0+
- MySQL / MariaDB
- Apache dengan `mod_rewrite` aktif (Laragon / XAMPP)

### Langkah Setup

**1. Taruh proyek di web root**
```
Laragon : C:/laragon/www/booking-lapangan/
XAMPP   : C:/xampp/htdocs/booking-lapangan/
```

**2. Import database**
```bash
# Buka phpMyAdmin atau MySQL CLI:
mysql -u root -p < database.sql
```

**3. Sesuaikan konfigurasi database**

Edit file `app/config/database.php`:
```php
private static string $host   = 'localhost';
private static string $dbName = 'booking_lapangan_db';
private static string $user   = 'root';
private static string $pass   = 'TekniInformasi24';       // sesuaikan password MySQL
```

**4. Sesuaikan BASE_URL**

Edit file `app/config/app.php`:
```php
// Jika proyek ada di subfolder:
define('BASE_URL', '/booking-lapangan/public');

// Jika deploy ke root domain:
define('BASE_URL', '');
```

**5. Aktifkan mod_rewrite Apache**

Pastikan `AllowOverride All` di konfigurasi Apache (Laragon sudah otomatis).

**6. Buat folder uploads**
```
public/uploads/lapangan/    ← foto lapangan
public/uploads/payments/    ← bukti transfer
```
Pastikan kedua folder ini writable (`chmod 755` di Linux/Mac).

**7. Akses aplikasi**
```
http://localhost/booking-lapangan/public/
```

---

## 🔑 Akun Demo

| Role     | Email                    | Password |
|----------|--------------------------|----------|
| Admin    | admin@sportfield.com     | password |
| Customer | budi@email.com           | password |
| Customer | siti@email.com           | password |

---

## 📁 Struktur Folder

```
booking-lapangan/
├── app/
│   ├── config/          → Konfigurasi DB & konstanta
│   ├── controllers/     → Logic bisnis
│   ├── models/          → Query database (PDO)
│   ├── helpers/         → Auth, CSRF, upload, redirect
│   └── views/           → Template HTML + Tailwind CSS
├── public/              → Web root (document root Apache)
│   ├── index.php        → Front Controller
│   ├── css/custom.css
│   ├── js/app.js
│   └── uploads/
├── database.sql
└── README.md
```

---

## 🌐 Routing

Format URL: `BASE_URL/{controller}/{method}/{param}`

| URL                              | Controller → Method         |
|----------------------------------|-----------------------------|
| `/home`                          | HomeController::index       |
| `/lapangan`                      | LapanganController::index   |
| `/lapangan/detail/1`             | LapanganController::detail  |
| `/booking/form/1`                | BookingController::form     |
| `/booking/history`               | BookingController::history  |
| `/payment/upload/1`              | PaymentController::upload   |
| `/auth/login`                    | AuthController::login       |
| `/auth/logout`                   | AuthController::logout      |
| `/admin/dashboard`               | AdminController::dashboardIndex |
| `/admin/lapangan`                | AdminController::lapanganIndex  |
| `/admin/lapangan/create`         | AdminController::lapanganCreate |
| `/admin/lapangan/edit/1`         | AdminController::lapanganEdit   |
| `/admin/booking`                 | AdminController::bookingIndex   |
| `/admin/booking/detail/1`        | AdminController::bookingDetail  |
| `/admin/payment`                 | AdminController::paymentIndex   |

---

## ✅ Fitur

### Customer
- [x] Register & Login (bcrypt + session)
- [x] Katalog lapangan (search + filter kategori)
- [x] Detail lapangan + slot ketersediaan visual
- [x] Booking lapangan (pilih tanggal & jam, auto-hitung harga)
- [x] Upload bukti transfer
- [x] Riwayat booking dengan badge status
- [x] Detail booking dengan status tracker (step indicator)
- [x] Rating & ulasan bintang setelah selesai
- [x] Batalkan booking (status pending)

### Admin
- [x] Dashboard statistik (booking, revenue, pending)
- [x] CRUD lapangan + upload foto
- [x] Manajemen booking (filter status, update status)
- [x] Verifikasi/tolak bukti pembayaran

---

## 🔒 Keamanan

- CSRF token di semua form POST
- PDO Prepared Statements (no raw query)
- password_hash() / password_verify()
- htmlspecialchars() untuk semua output
- Validasi MIME + ekstensi + ukuran file upload
- Role guard (admin-only routes)
- Session regeneration setelah login

---

## 🐛 Troubleshooting

**Halaman 404 padahal file ada?**
→ Pastikan `mod_rewrite` aktif dan `AllowOverride All` di Apache config.

**Upload foto gagal?**
→ Cek permission folder `public/uploads/` (harus writable).

**CSS tidak muncul?**
→ Pastikan `BASE_URL` di `app.php` sesuai dengan path instalasi.

**Koneksi database error?**
→ Cek `host`, `dbName`, `user`, `pass` di `app/config/database.php`.
