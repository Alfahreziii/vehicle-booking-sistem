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
- [Troubleshooting](#troubleshooting)

---

## Deskripsi Aplikasi

Vehicle Booking System (VBS) adalah aplikasi web untuk memonitor dan mengelola pemesanan kendaraan operasional perusahaan tambang nikel. Aplikasi ini mencakup:

- Pemesanan kendaraan oleh admin/pool kendaraan
- Persetujuan berjenjang (minimal 2 level) oleh atasan
- Monitoring konsumsi BBM dan jadwal servis kendaraan
- Dashboard grafik pemakaian kendaraan (12 bulan terakhir)
- Laporan periodik yang dapat diekspor ke Excel dengan formatting
- Log aktivitas untuk setiap proses di sistem
- Auto-expire booking yang melewati tanggal keberangkatan tanpa disetujui

---

## Teknologi & Versi

| Komponen          | Versi  |
|-------------------|--------|
| PHP               | ^8.2   |
| Laravel           | ^11.0  |
| MySQL             | ^8.0   |
| Node.js           | ^18.0  |
| Tailwind CSS      | ^4.2   |
| Alpine.js         | ^3.15  |
| Vite              | ^6.0   |
| Spatie Permission | ^6.0   |
| Maatwebsite Excel | ^3.1   |
| Chart.js          | ^4.4   |

---

## Fitur Utama

### Admin
- ✅ Manajemen kendaraan (tambah, edit, detail, hapus soft delete)
- ✅ Manajemen driver beserta data SIM
- ✅ Manajemen pengguna & role
- ✅ Buat pemesanan kendaraan + tentukan driver & approver
- ✅ Pengecekan otomatis konflik waktu kendaraan & driver
- ✅ Selesaikan booking + input odometer akhir & log BBM sekaligus
- ✅ Batalkan booking dengan alasan
- ✅ Catat log pengisian BBM per kendaraan
- ✅ Dashboard grafik pemakaian kendaraan
- ✅ Laporan periodik + export Excel (dengan color coding & formatting)

### Approver
- ✅ Melihat daftar pemesanan yang perlu disetujui (card view)
- ✅ Proses persetujuan atau penolakan berjenjang (level 1, 2, dst)
- ✅ Melihat riwayat persetujuan dengan status tiap level
- ✅ Notifikasi email & database saat ada booking menunggu

### Profil
- ✅ Update nama & email
- ✅ Ganti password dengan konfirmasi
- ✅ Hapus akun dengan konfirmasi password

### Sistem
- ✅ Persetujuan berjenjang minimal 2 level (maks 5 level)
- ✅ Notifikasi otomatis ke approver berikutnya setelah level sebelumnya setuju
- ✅ Activity log di setiap proses (buat booking, setuju, tolak, export, dll)
- ✅ Cek konflik waktu kendaraan & driver secara otomatis
- ✅ Soft delete untuk kendaraan & booking
- ✅ Auto-expire booking via Laravel Scheduler (setiap jam)
- ✅ Kendaraan di-reserve saat booking dibuat (mencegah double booking)

---

## Instalasi

### 1. Clone atau ekstrak project

```bash
git clone https://github.com/Alfahreziii/vehicle-booking-sistem.git
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
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vehicle_booking_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="noreply@vehiclebooking.com"
MAIL_FROM_NAME="VBS Nikel Mining"
```

### 6. Buat database

```sql
CREATE DATABASE vehicle_booking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. Jalankan migration & seeder

Seeder hanya mengisi **data master** (role, region, user, kendaraan, driver). Data booking diisi manual melalui aplikasi.

```bash
php artisan migrate --seed
```

Jika ingin menambahkan data dummy booking untuk keperluan testing:

```bash
php artisan db:seed --class=BookingSeeder
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

> **Catatan:** Halaman login menampilkan kotak **Akun Demo** yang hanya muncul saat `APP_ENV=local`. Klik salah satu akun untuk mengisi form login secara otomatis. Tidak ada verifikasi email — login langsung masuk ke dashboard.

---

## Menjalankan Scheduler (Auto-Expire Booking)

Sistem memiliki fitur otomatis untuk membatalkan booking yang melewati tanggal keberangkatan tanpa disetujui.

### Di lokal (development)

Jalankan perintah ini di terminal terpisah — tidak perlu setup crontab apapun:

```bash
php artisan schedule:work
```

Jalankan bersamaan dengan server dan asset build:

```bash
# Terminal 1 — Laravel server
php artisan serve

# Terminal 2 — Vite assets
npm run dev

# Terminal 3 — Scheduler (opsional)
php artisan schedule:work
```

### Di server production

Tambahkan satu baris ini ke crontab server:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Test manual

```bash
php artisan bookings:expire-stale
```

---

## Konfigurasi Database

### Struktur Tabel Utama

| Tabel               | Deskripsi                                   |
|---------------------|---------------------------------------------|
| `users`             | Data pengguna sistem                        |
| `regions`           | Data region (kantor pusat, cabang, tambang) |
| `departments`       | Data departemen per region                  |
| `vehicles`          | Data kendaraan (milik & sewa)               |
| `drivers`           | Data driver beserta data SIM                |
| `bookings`          | Data pemesanan kendaraan                    |
| `booking_approvals` | Chain persetujuan berjenjang per booking    |
| `fuel_logs`         | Log pengisian BBM per kendaraan             |
| `service_schedules` | Jadwal servis kendaraan                     |
| `activity_logs`     | Log seluruh aktivitas sistem                |
| `notifications`     | Notifikasi database (Laravel)               |

### Data yang Diisi Seeder

| Seeder                 | Data yang Dibuat                                      |
|------------------------|-------------------------------------------------------|
| `RolePermissionSeeder` | 4 role (admin, approver, driver, viewer), 21 permission |
| `RegionSeeder`         | 8 region, 64 department (8 dept × 8 region)           |
| `UserSeeder`           | 3 admin, 8 approver, 10 driver, 5 viewer              |
| `VehicleSeeder`        | 20 kendaraan tersebar di semua region                 |
| `DriverSeeder`         | 10 driver dengan data SIM                             |
| `BookingSeeder`        | 5 booking dummy *(opsional, tidak dijalankan otomatis)* |

---

## Daftar Username & Password

> **Semua akun menggunakan password: `password`**
>
> Tidak ada verifikasi email. Login langsung aktif setelah seeder dijalankan.

### Admin

| Nama                | Email                          | Role  | Lokasi        |
|---------------------|--------------------------------|-------|---------------|
| Super Administrator | superadmin@nikelmining.co.id   | admin | Kantor Pusat  |
| Budi Santoso        | admin.pool@nikelmining.co.id   | admin | Kantor Pusat  |
| Dewi Rahayu         | admin.cabang@nikelmining.co.id | admin | Kantor Cabang |

### Approver

| Nama            | Email                              | Level   | Lokasi                 |
|-----------------|------------------------------------|---------|------------------------|
| Hendra Wijaya   | kabag.ops@nikelmining.co.id        | Level 1 | Kantor Pusat           |
| Siti Nurhaliza  | manager.ops@nikelmining.co.id      | Level 2 | Kantor Pusat           |
| Agus Prayitno   | kabag.morowali1@nikelmining.co.id  | Level 1 | Tambang Morowali 1     |
| Rini Oktaviani  | kabag.morowali2@nikelmining.co.id  | Level 1 | Tambang Morowali 2     |
| Joko Widodo     | kabag.konawe@nikelmining.co.id     | Level 1 | Tambang Konawe         |
| Fitri Handayani | kabag.halmahera1@nikelmining.co.id | Level 1 | Tambang Halmahera 1    |
| Bambang Susanto | kabag.halmahera2@nikelmining.co.id | Level 1 | Tambang Halmahera 2    |
| Yuni Astuti     | kabag.sulbar@nikelmining.co.id     | Level 1 | Tambang Sulawesi Barat |

### Driver

| Nama            | Email                      | Lokasi             |
|-----------------|----------------------------|--------------------|
| Wahyu Setiawan  | driver1@nikelmining.co.id  | Kantor Pusat       |
| Rizky Pratama   | driver2@nikelmining.co.id  | Kantor Pusat       |
| Eko Saputra     | driver3@nikelmining.co.id  | Kantor Cabang      |
| Dimas Kurniawan | driver4@nikelmining.co.id  | Kantor Cabang      |
| Fajar Nugroho   | driver5@nikelmining.co.id  | Tambang Morowali 1 |
| Hadi Subroto    | driver6@nikelmining.co.id  | Tambang Morowali 1 |
| Irwan Kusuma    | driver7@nikelmining.co.id  | Tambang Morowali 2 |
| Lutfi Hakim     | driver8@nikelmining.co.id  | Tambang Konawe     |
| Muhamad Ridwan  | driver9@nikelmining.co.id  | Tambang Halmahera 1|
| Nanang Hidayat  | driver10@nikelmining.co.id | Tambang Halmahera 2|

### Viewer / Pegawai

| Nama            | Email                         | Lokasi             |
|-----------------|-------------------------------|--------------------|
| Ahmad Fauzi     | ahmad.fauzi@nikelmining.co.id | Kantor Pusat       |
| Bagas Wicaksono | bagas.w@nikelmining.co.id     | Kantor Pusat       |
| Citra Lestari   | citra.l@nikelmining.co.id     | Kantor Cabang      |
| Doni Setiawan   | doni.s@nikelmining.co.id      | Tambang Morowali 1 |
| Eka Putri       | eka.p@nikelmining.co.id       | Tambang Morowali 2 |

---

## Panduan Penggunaan

### Alur Pemesanan Kendaraan

```
Admin buat booking
       ↓
Kendaraan & driver di-reserve otomatis (mencegah double booking)
Sistem kirim notifikasi ke Approver Level 1
       ↓
Approver Level 1 setuju / tolak
       ↓ (jika setuju)
Sistem kirim notifikasi ke Approver Level 2
       ↓
Approver Level 2 setuju / tolak
       ↓ (jika semua setuju)
Status booking → "Disetujui"
Notifikasi dikirim ke pemohon
       ↓
Kendaraan digunakan
       ↓
Admin selesaikan booking + input odometer akhir (+ BBM opsional)
       ↓
Status → "Selesai", kendaraan & driver dibebaskan

⚠ Jika tidak disetujui sampai tanggal berangkat:
Scheduler otomatis membatalkan booking setiap jam
```

---

### Panduan Admin

#### Membuat Pemesanan Baru
1. Login sebagai admin (contoh: `admin.pool@nikelmining.co.id`)
2. Klik menu **Pemesanan** di sidebar
3. Klik tombol **Buat Pemesanan**
4. Isi form: tujuan, destinasi, tanggal berangkat, estimasi kembali, kendaraan, driver
5. Pilih **minimal 2 approver** secara berurutan (level 1, level 2, dst)
6. Klik **Kirim Pemesanan**
7. Sistem otomatis mengirim notifikasi ke approver level 1

> **Catatan:** Kendaraan dan driver yang sudah memiliki booking aktif pada rentang waktu yang sama tidak akan bisa dipilih.

#### Menyelesaikan Booking
1. Buka halaman detail booking yang statusnya **Disetujui**
2. Klik tombol **Selesaikan**
3. Input odometer akhir kendaraan
4. Isi data BBM jika ada pengisian selama perjalanan (opsional)
5. Klik **Konfirmasi Selesai**

#### Membatalkan Booking
1. Buka halaman detail booking
2. Klik tombol **Batalkan**
3. Isi alasan pembatalan (minimal 10 karakter)
4. Klik **Ya, Batalkan**

> Kendaraan dan driver otomatis dibebaskan saat booking dibatalkan.

#### Export Laporan Excel
1. Klik menu **Laporan** di sidebar
2. Atur filter: tanggal mulai, tanggal akhir, status, region
3. Klik **Tampilkan** untuk preview data
4. Klik **Export Excel** untuk mengunduh file `.xlsx`

#### Tambah Log BBM
1. Buka menu **Kendaraan**
2. Klik detail kendaraan yang ingin dicatat
3. Klik tombol **Tambah BBM**
4. Isi form: tanggal, jumlah liter, harga/liter, odometer sebelum & sesudah, SPBU
5. Klik **Simpan Log BBM**

---

### Panduan Approver

#### Memproses Persetujuan
1. Login sebagai approver (contoh: `kabag.ops@nikelmining.co.id`)
2. Lihat badge merah di menu **Persetujuan** atau notifikasi di navbar
3. Klik **Proses Sekarang** pada booking yang menunggu
4. Review detail perjalanan, kendaraan, dan driver
5. Pilih **Setujui** atau **Tolak**
6. Isi catatan (wajib jika menolak, minimal 10 karakter)
7. Klik **Konfirmasi**

> **Catatan:** Approver hanya bisa memproses booking sesuai level-nya. Level 2 tidak dapat memproses sebelum Level 1 menyetujui.

---

### Panduan Profil

1. Klik nama pengguna di pojok kanan atas navbar
2. Pilih **Profil Saya**
3. Tersedia tiga aksi:
   - **Update nama & email** — simpan perubahan informasi dasar
   - **Ganti password** — masukkan password lama dan password baru
   - **Hapus akun** — konfirmasi dengan memasukkan password

---

### Status Booking

| Status         | Deskripsi                                              |
|----------------|--------------------------------------------------------|
| `Menunggu`     | Baru dibuat, menunggu approval level 1                 |
| `Direview`     | Sedang dalam proses persetujuan berjenjang             |
| `Disetujui`    | Semua level sudah menyetujui                           |
| `Ditolak`      | Salah satu level menolak                               |
| `Sedang Jalan` | Kendaraan sedang digunakan                             |
| `Selesai`      | Kendaraan sudah dikembalikan, odometer sudah diisi     |
| `Dibatalkan`   | Dibatalkan admin atau otomatis expire oleh sistem      |

---

### Cara Kerja BBM

Sistem mencatat konsumsi BBM melalui dua jalur:

**1. Manual via halaman Kendaraan**
Untuk pengisian BBM rutin yang tidak terkait perjalanan tertentu. Admin membuka detail kendaraan → klik Tambah BBM → isi form.

**2. Saat menyelesaikan booking**
Saat admin menyelesaikan booking, tersedia form opsional untuk mencatat BBM yang digunakan selama perjalanan. Data ini otomatis tersimpan di `fuel_logs` dan terhubung ke booking tersebut.

Semua log BBM bisa dilihat di halaman detail kendaraan, lengkap dengan efisiensi (km/liter) per pengisian.

---

## Troubleshooting

### Error: `Role middleware not found`
Pastikan middleware Spatie sudah didaftarkan di `bootstrap/app.php`:
```php
$middleware->alias([
    'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
    'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
]);
```

### Error: `Call to undefined relationship`
Pastikan semua model sudah memiliki method relasi yang lengkap. Jalankan:
```bash
php artisan cache:clear
php artisan config:clear
```

### Tailwind CSS tidak muncul
Pastikan tidak ada `postcss.config.js` atau sudah dikosongkan, lalu jalankan ulang:
```bash
npm run dev
```

> **Catatan:** Tailwind v4 menggunakan `@tailwindcss/vite` dan tidak memerlukan `tailwind.config.js` maupun `postcss.config.js`. Cukup `@import "tailwindcss"` di `app.css`.

### Notifikasi email tidak terkirim
Pastikan konfigurasi mail di `.env` sudah benar. Untuk testing lokal gunakan Mailtrap:
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

### Scheduler tidak berjalan di lokal
Jalankan di terminal terpisah — tidak perlu setup crontab:
```bash
php artisan schedule:work
```

### Reset database
```bash
php artisan migrate:fresh --seed
```

---

## Lisensi

Project ini dibuat untuk keperluan **Technical Test Internship**.  
© 2024 — PT Nikel Mining Indonesia