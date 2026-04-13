Markdown
# 🏫 SIMAS (Sistem Manajemen Absensi) - SMAN 1 Kembang

SIMAS adalah aplikasi absensi siswa berbasis web yang dilengkapi dengan fitur **Geofencing (GPS)** dan **PWA (Progressive Web App)**. Aplikasi ini dirancang agar siswa hanya bisa melakukan presensi jika berada di dalam radius sekolah yang telah ditentukan, dan dapat diinstal layaknya aplikasi *native* di perangkat Android & iOS.

## ✨ Fitur Utama
- **Multi-Role Authentication:** Akses khusus untuk Admin, Wali Kelas, dan Siswa.
- **Dynamic Geofencing:** Pengaturan titik kordinat (Latitude & Longitude) dan radius absensi dapat diubah secara langsung (Real-time) melalui *dashboard* Admin menggunakan peta interaktif.
- **PWA Ready:** Aplikasi dapat diinstal ke *Homescreen* (Add to Home Screen) dan memiliki *Service Worker* untuk optimasi *caching*.
- **Responsive UI:** Tampilan yang dioptimalkan untuk perangkat *mobile* (Mobile-First Design).

## 🛠️ Tech Stack
- **Framework:** Laravel 
- **Frontend:** Tailwind CSS, Alpine.js, Blade
- **Package Manager:** Bun & Composer
- **Database:** MySQL

---

## 🚀 Panduan Instalasi (Untuk Tim IT / Server)

Pastikan server Anda sudah terinstal **PHP**, **MySQL**, **Composer**, dan **Bun** (sebagai pengganti Node.js/NPM).

**1. Clone Repository**
```bash
git clone [https://github.com/username-anda/repo-simas.git](https://github.com/username-anda/repo-simas.git)
cd repo-simas
2. Install Dependencies

Bash
composer install
bun install
bun run build
3. Setup Environment
Duplikat file .env.example menjadi .env.

Bash
cp .env.example .env
Sesuaikan konfigurasi database dan URL di file .env:

Cuplikan kode
APP_NAME="SIMAS SMAN 1 Kembang"
APP_ENV=production
APP_DEBUG=false
APP_URL=[https://simas.sman1kembang.sch.id](https://simas.sman1kembang.sch.id)

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=user_database_anda
DB_PASSWORD=password_database_anda
4. Generate Key & Storage Link

Bash
php artisan key:generate
php artisan storage:link
5. Migrate & Seed Database
Perintah ini akan membuat struktur tabel beserta data awal (Pengaturan GPS Sekolah & Akun Default).

Bash
php artisan migrate:fresh --seed
⚠️ PERHATIAN PENTING (System Requirements)
Aplikasi ini menggunakan API Geolocation dan Service Worker (PWA). Oleh karena itu, aplikasi WAJIB dijalankan menggunakan protokol keamanan HTTPS (SSL). Jika dijalankan menggunakan HTTP biasa, fitur deteksi lokasi dan instalasi aplikasi di HP siswa tidak akan berfungsi.

🔑 Akun Default (Testing)
Gunakan kredensial berikut untuk masuk ke dalam sistem setelah instalasi selesai:

Administrator

Email: admin.baru@sman1kembang.sch.id

Password: KembangKuat2026!

Wali Kelas (Guru)

Email: budi.santoso@sma.sch.id

Password: password

(Catatan: Segera ubah password admin setelah sistem berhasil di-deploy ke server)
