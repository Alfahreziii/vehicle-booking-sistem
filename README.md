# Vehicle Booking System (VBS)
### Aplikasi Pemesanan Kendaraan — PT Nikel Mining Indonesia

---

## Daftar Isi
- [Deskripsi Aplikasi](#deskripsi-aplikasi)
- [Teknologi & Versi](#teknologi--versi)
- [Fitur Utama](#fitur-utama)
- [Instalasi](#instalasi)
- [Konfigurasi Database](#konfigurasi-database)
- [Daftar Username & Password](#daftar-username--password)
- [Panduan Penggunaan](#panduan-penggunaan)
- [Struktur Project](#struktur-project)

---

## Deskripsi Aplikasi

Vehicle Booking System (VBS) adalah aplikasi web untuk memonitor dan mengelola pemesanan kendaraan operasional perusahaan tambang nikel. Aplikasi ini mencakup:

- Pemesanan kendaraan oleh admin/pool kendaraan
- Persetujuan berjenjang (minimal 2 level) oleh atasan
- Monitoring konsumsi BBM dan jadwal servis
- Dashboard grafik pemakaian kendaraan
- Laporan periodik yang dapat diekspor ke Excel
- Log aktivitas untuk setiap proses di sistem

---

## Teknologi & Versi

| Komponen         | Versi         |
|------------------|---------------|
| PHP              | ^8.2          |
| Laravel          | ^11.0         |
| MySQL            | ^8.0          |
| Node.js          | ^18.0         |
| Tailwind CSS     | ^4.2          |
| Alpine.js        | ^3.15         |
| Vite             | ^6.0          |
| Spatie Permission| ^6.0          |
| Maatwebsite Excel| ^3.1          |

---

## Fitur Utama

### Admin
- ✅ Manajemen kendaraan (tambah, edit, detail, hapus)
- ✅ Manajemen driver (tambah, edit, detail, hapus)
- ✅ Manajemen pengguna & role
- ✅ Buat pemesanan kendaraan + tentukan driver & approver
- ✅ Selesaikan & batalkan pemesanan
- ✅ Catat log pengisian BBM
- ✅ Dashboard grafik pemakaian kendaraan
- ✅ Laporan periodik + export Excel

### Approver
- ✅ Melihat daftar pemesanan yang perlu disetujui
- ✅ Proses persetujuan atau penolakan berjenjang (level 1, 2, dst)
- ✅ Melihat riwayat persetujuan
- ✅ Notifikasi email & database saat ada booking baru

### Sistem
- ✅ Persetujuan berjenjang minimal 2 level (maks 5 level)
- ✅ Notifikasi otomatis ke approver berikutnya setelah level sebelumnya setuju
- ✅ Activity log di setiap proses (buat booking, setuju, tolak, export, dll)
- ✅ Cek ketersediaan kendaraan & driver secara otomatis
- ✅ Soft delete untuk kendaraan & booking

---

## Instalasi

### 1. Clone atau ekstrak project

```bash
# Clone dari repository
git clone https://github.com/your-repo/vehicle-booking-system.git
cd vehicle-booking-system
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies

```bash
npm install
```

### 4. Salin file environment

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Konfigurasi `.env`

Edit file `.env` sesuai konfigurasi lokal:

```env
APP_NAME="Vehicle Booking System"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_booking_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@vehiclebooking.com"
MAIL_FROM_NAME="VBS Nikel Mining"
```

### 6. Buat database

```sql
CREATE DATABASE vehicle_booking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Jalankan migration & seeder

```bash
php artisan migrate --seed
```

### 8. Build assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 9. Jalankan aplikasi

```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

---

## Konfigurasi Database

### Struktur Tabel Utama

| Tabel                | Deskripsi                                    |
|----------------------|----------------------------------------------|
| `users`              | Data pengguna sistem                         |
| `regions`            | Data region (kantor pusat, cabang, tambang)  |
| `departments`        | Data departemen per region                   |
| `vehicles`           | Data kendaraan (milik & sewa)                |
| `drivers`            | Data driver beserta data SIM                 |
| `bookings`           | Data pemesanan kendaraan                     |
| `booking_approvals`  | Chain persetujuan berjenjang per booking     |
| `fuel_logs`          | Log pengisian BBM                            |
| `service_schedules`  | Jadwal servis kendaraan                      |
| `activity_logs`      | Log seluruh aktivitas sistem                 |
| `notifications`      | Notifikasi database (Laravel)                |

---

## Daftar Username & Password

> **Semua akun menggunakan password: `password`**

### Admin

| Nama                 | Email                                  | Role  | Lokasi         |
|----------------------|----------------------------------------|-------|----------------|
| Super Administrator  | superadmin@nikelmining.co.id           | admin | Kantor Pusat   |
| Budi Santoso         | admin.pool@nikelmining.co.id           | admin | Kantor Pusat   |
| Dewi Rahayu          | admin.cabang@nikelmining.co.id         | admin | Kantor Cabang  |

### Approver

| Nama             | Email                                   | Level    | Lokasi                   |
|------------------|-----------------------------------------|----------|--------------------------|
| Hendra Wijaya    | kabag.ops@nikelmining.co.id             | Level 1  | Kantor Pusat             |
| Siti Nurhaliza   | manager.ops@nikelmining.co.id           | Level 2  | Kantor Pusat             |
| Agus Prayitno    | kabag.morowali1@nikelmining.co.id       | Level 1  | Tambang Morowali 1       |
| Rini Oktaviani   | kabag.morowali2@nikelmining.co.id       | Level 1  | Tambang Morowali 2       |
| Joko Widodo      | kabag.konawe@nikelmining.co.id          | Level 1  | Tambang Konawe           |
| Fitri Handayani  | kabag.halmahera1@nikelmining.co.id      | Level 1  | Tambang Halmahera 1      |
| Bambang Susanto  | kabag.halmahera2@nikelmining.co.id      | Level 1  | Tambang Halmahera 2      |
| Yuni Astuti      | kabag.sulbar@nikelmining.co.id          | Level 1  | Tambang Sulawesi Barat   |

### Driver

| Nama             | Email                              | Lokasi         |
|------------------|------------------------------------|----------------|
| Wahyu Setiawan   | driver1@nikelmining.co.id          | Kantor Pusat   |
| Rizky Pratama    | driver2@nikelmining.co.id          | Kantor Pusat   |
| Eko Saputra      | driver3@nikelmining.co.id          | Kantor Cabang  |
| Dimas Kurniawan  | driver4@nikelmining.co.id          | Kantor Cabang  |
| Fajar Nugroho    | driver5@nikelmining.co.id          | Tambang Morowali 1 |

### Viewer / Pegawai

| Nama             | Email                                | Lokasi         |
|------------------|--------------------------------------|----------------|
| Ahmad Fauzi      | ahmad.fauzi@nikelmining.co.id        | Kantor Pusat   |
| Bagas Wicaksono  | bagas.w@nikelmining.co.id            | Kantor Pusat   |
| Citra Lestari    | citra.l@nikelmining.co.id            | Kantor Cabang  |

---

## Panduan Penggunaan

### Alur Pemesanan Kendaraan

```
Admin buat booking
       ↓
Sistem kirim notifikasi ke Approver Level 1
       ↓
Approver Level 1 setuju/tolak
       ↓ (jika setuju)
Sistem kirim notifikasi ke Approver Level 2
       ↓
Approver Level 2 setuju/tolak
       ↓ (jika semua setuju)
Status booking → "Disetujui"
Notifikasi dikirim ke pemohon
       ↓
Kendaraan digunakan
       ↓
Admin selesaikan booking + input odometer akhir
       ↓
Status → "Selesai"
```

---

### Panduan Admin

#### Membuat Pemesanan Baru
1. Login sebagai admin (contoh: `admin.pool@nikelmining.co.id`)
2. Klik menu **Pemesanan** di sidebar
3. Klik tombol **Buat Pemesanan**
4. Isi form: tujuan, destinasi, tanggal, kendaraan, driver
5. Pilih **minimal 2 approver** secara berurutan (level 1, level 2, dst)
6. Klik **Kirim Pemesanan**
7. Sistem akan otomatis mengirim notifikasi ke approver level 1

#### Menyelesaikan Booking
1. Buka halaman detail booking yang statusnya **Disetujui** atau **Sedang Jalan**
2. Klik tombol **Selesaikan**
3. Input odometer akhir kendaraan
4. Klik **Konfirmasi Selesai**

#### Membatalkan Booking
1. Buka halaman detail booking
2. Klik tombol **Batalkan**
3. Isi alasan pembatalan (minimal 10 karakter)
4. Klik **Ya, Batalkan**

#### Export Laporan Excel
1. Klik menu **Laporan** di sidebar
2. Atur filter: tanggal mulai, tanggal akhir, status, region
3. Klik **Tampilkan** untuk preview data
4. Klik **Export Excel** untuk mengunduh file `.xlsx`

#### Tambah Log BBM
1. Buka menu **Kendaraan**
2. Klik detail kendaraan yang ingin dicatat
3. Klik tombol **Tambah BBM**
4. Isi form: tanggal, jumlah liter, harga/liter, odometer, SPBU
5. Klik **Simpan Log BBM**

---

### Panduan Approver

#### Memproses Persetujuan
1. Login sebagai approver (contoh: `kabag.ops@nikelmining.co.id`)
2. Lihat notifikasi di navbar atau klik menu **Persetujuan**
3. Klik **Proses Sekarang** pada booking yang menunggu
4. Review detail perjalanan, kendaraan, dan driver
5. Pilih **Setujui** atau **Tolak**
6. Isi catatan (wajib jika menolak)
7. Klik **Konfirmasi**

> **Catatan**: Approver hanya bisa memproses booking sesuai level-nya. Level 2 tidak bisa memproses sebelum Level 1 menyetujui.

---

### Status Booking

| Status        | Deskripsi                                      |
|---------------|------------------------------------------------|
| `Menunggu`    | Baru dibuat, menunggu approval level 1         |
| `Direview`    | Sedang dalam proses persetujuan berjenjang     |
| `Disetujui`   | Semua level sudah menyetujui                   |
| `Ditolak`     | Salah satu level menolak                       |
| `Sedang Jalan`| Kendaraan sedang digunakan                    |
| `Selesai`     | Kendaraan sudah dikembalikan                   |
| `Dibatalkan`  | Dibatalkan oleh admin                          |

---

## Struktur Project

```
vehicle-booking-system/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── BookingController.php
│   │   │   │   ├── VehicleController.php
│   │   │   │   ├── DriverController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── ReportController.php
│   │   │   ├── Approver/
│   │   │   │   └── ApprovalController.php
│   │   │   └── DashboardController.php
│   │   └── Requests/
│   │       ├── StoreBookingRequest.php
│   │       └── UpdateApprovalRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Region.php
│   │   ├── Department.php
│   │   ├── Vehicle.php
│   │   ├── Driver.php
│   │   ├── Booking.php
│   │   ├── BookingApproval.php
│   │   ├── FuelLog.php
│   │   ├── ServiceSchedule.php
│   │   └── ActivityLog.php
│   ├── Services/
│   │   ├── BookingService.php
│   │   ├── ApprovalService.php
│   │   ├── NotificationService.php
│   │   └── ActivityLogService.php
│   ├── Exports/
│   │   └── BookingExport.php
│   └── Notifications/
│       ├── BookingSubmittedNotification.php
│       ├── BookingApprovedNotification.php
│       └── BookingRejectedNotification.php
│
├── database/
│   ├── migrations/          (10 file migration)
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolePermissionSeeder.php
│       ├── RegionSeeder.php
│       ├── UserSeeder.php
│       ├── VehicleSeeder.php
│       ├── DriverSeeder.php
│       └── BookingSeeder.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── sidebar.blade.php
│       │   └── navbar.blade.php
│       ├── admin/
│       │   ├── bookings/    (index, create, show)
│       │   ├── vehicles/    (index, create, edit, show)
│       │   ├── drivers/     (index, create, edit, show)
│       │   ├── users/       (index, create, edit)
│       │   └── reports/     (index)
│       ├── approver/
│       │   └── approvals/   (index, show)
│       └── dashboard.blade.php
│
├── routes/
│   └── web.php
│
├── .env.example
├── composer.json
├── package.json
├── vite.config.js
└── README.md
```

---

## Troubleshooting

### Error: `Role middleware not found`
Pastikan middleware Spatie sudah didaftarkan di `bootstrap/app.php`:
```php
$middleware->alias([
    'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
]);
```

### Error: `Call to undefined relationship`
Pastikan semua model sudah memiliki method relasi yang lengkap. Jalankan:
```bash
php artisan cache:clear
php artisan config:clear
```

### Tailwind CSS tidak muncul
Pastikan tidak ada file `postcss.config.js` atau sudah dikosongkan, lalu jalankan ulang:
```bash
npm run dev
```

### Reset database
```bash
php artisan migrate:fresh --seed
```

---

## Lisensi

Project ini dibuat untuk keperluan **Technical Test Internship**.  
© 2024 — PT Nikel Mining Indonesia