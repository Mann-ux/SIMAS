# App Flow & Core Features — Sistem Absensi SMA

> Dokumen ini **murni berdasarkan codebase saat ini** pada proyek Laravel `simas-Copilot`.
> Digunakan sebagai blueprint/konteks utama untuk AI UI Generator.

---

## 🧩 Core Features (Fitur Inti)

### 1) Autentikasi + Smart Redirect (berdasarkan role & penugasan kelas)
**Referensi:**
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Requests/Auth/LoginRequest.php`
- `app/Services/SmartRedirectService.php`
- `routes/web.php`
- `resources/views/auth/login.blade.php`

**Ringkasan:**
- Form login menerima **Email atau NIS** dalam satu input `login`.
- Validasi login memakai `LoginRequest::authenticate()` dengan pemilihan field:
  - Jika input valid email → gunakan `email`
  - Jika bukan email → gunakan `nis`
- Setelah sukses login, session diregenerasi.
- `SmartRedirectService` menentukan halaman awal pengguna:
  - **Admin** → `admin.dashboard`
  - **Wali Kelas** → `wali-kelas.absen.create` jika punya kelas, jika tidak → `wali-kelas.dashboard`
  - **Petugas Kelas** (sekretaris/ketua/siswa yang ditugaskan) → `pengurus.dashboard`
  - Default → `dashboard` (view generic)

**Efek UX:**
- User langsung diarahkan ke **halaman kerja utama** sesuai perannya tanpa perlu memilih menu manual.

---

### 2) Landing Page Publik (Grid Kelas)
**Referensi:**
- `app/Http/Controllers/LandingPageController.php`
- `resources/views/landing.blade.php`
- `routes/web.php`

**Ringkasan:**
- Landing publik di `/` menampilkan **grid kartu kelas** berisi:
  - Nama kelas (`classroom->name`)
  - Tingkat (`classroom->tingkat`)
  - Jumlah siswa (`students_count`)
- Jika user sudah login → **langsung diarahkan via Smart Redirect**.
- Klik **kartu kelas** atau tombol **“Masuk Sekarang”** → menuju halaman login.

---

### 3) Dashboard Admin: Monitoring Absensi Harian
**Referensi:**
- `routes/web.php` (route admin dashboard inline)
- `resources/views/admin/dashboard.blade.php`

**Ringkasan Data yang dihitung:**
- **Total siswa** global (`Student::count()`)
- Rekap status absensi **hari ini**: Hadir, Izin, Sakit, Alpa
- **Kelas yang belum absen hari ini**
  - Query `Classroom::whereDoesntHave('students.attendances'...)`
- **Grouping kelas per tingkat** (X, XI, XII)

**Tampilan:**
- Header Command Center + statistik ringkas.
- Alert kelas belum absen.
- Tab per tingkat berisi rekap tiap kelas.

---

### 4) Manajemen Master Data (Admin)
**Referensi:**
- `routes/web.php`
- `app/Http/Controllers/ClassroomController.php`
- `app/Http/Controllers/StudentController.php`
- `app/Http/Controllers/UserController.php`
- `resources/views/admin/*`

#### a) Manajemen Kelas
- **CRUD** kelas `admin/classrooms`.
- Saat membuat kelas:
  - Input `tingkat_kelas` + `rombel` → disatukan menjadi `name` (contoh: X-1).
  - Wali kelas wajib dipilih (`wali_kelas_id`).
  - Otomatis menggunakan **academic year aktif** (`AcademicYear::where('is_active', true)`).

#### b) Penugasan Pengurus Kelas (Ketua & Sekretaris)
- Di halaman **detail kelas** (`admin/classrooms/{id}`):
  - Admin bisa memilih **ketua & sekretaris** dari siswa kelas tersebut.
  - Sistem otomatis membuat/menyinkronkan **User** untuk siswa terpilih.
  - Password bisa:
    - **Default:** `password`
    - **Manual:** input custom jika dicentang.

**Logika penting:**
- Jika pengurus diganti → role lama direset ke `siswa` jika tidak dipakai lagi (`resetOfficerRoleIfUnused()`).

#### c) Manajemen Siswa
- **CRUD** siswa `admin/students`.
- Tambah siswa manual atau via modal **Import Excel**.
- Bisa menambahkan siswa ke kelas dari halaman detail kelas.

#### d) Manajemen User (Admin & Wali Kelas)
- **CRUD** user `admin/users`.
- Role yang dikelola via form: `admin`, `wali_kelas`.
- Validasi NIP unik, email unik.

---

### 5) Import Excel/CSV Siswa (Massal)
**Referensi:**
- `app/Http/Controllers/StudentController.php`
- `app/Imports/StudentsImport.php`
- `resources/views/admin/students/index.blade.php`

**Format template CSV:**
- Header: `nis, nama_lengkap, jenis_kelamin, kelas`

**Mapping dan Normalisasi:**
- `nis` → `students.nis` (primary key)
- `nama_lengkap` → `students.name` (melalui accessor `nama_lengkap`)
- `jenis_kelamin` → `students.jenis_kelamin`
  - Normalisasi:
    - L, laki-laki, laki laki, pria → `L`
    - P, perempuan, wanita → `P`
- `kelas` → dicari pada `classrooms.name` → `classroom_id`

**Perilaku Import:**
- **Upsert berdasarkan `nis`** (data lama akan update).
- Baris kosong/invalid (tanpa NIS atau nama) di-skip.

---

### 6) Absensi Harian & Tracking Pengubah
**Referensi:**
- `app/Http/Controllers/AttendanceController.php`
- `resources/views/wali-kelas/attendances/create.blade.php`
- `resources/views/pengurus/attendances/create.blade.php`

**Fitur:**
- Input absensi untuk semua siswa di kelas.
- Status absensi: **Hadir, Izin, Sakit, Alpa**.
- **Tracking pengubah terakhir:**
  - View menampilkan siapa yang terakhir mengubah absensi (`recorded_by_id` → `recorder` relation).
  - Informasi waktu `updated_at`.
- Default status di form: **Hadir** jika belum ada data.

**Perbedaan Wali Kelas vs Petugas Kelas:**
- **Wali Kelas**:
  - Bisa memilih tanggal (komponen “Mesin Waktu”).
  - Dapat edit absensi masa lalu.
- **Petugas Kelas**:
  - Hanya boleh input untuk **hari ini**.

**Penyimpanan Data:**
- Menggunakan `updateOrCreate()` per siswa dan tanggal:
  - Key: `student_nis + date`
  - Value: `status, academic_year_id, recorded_by_id`

---

### 7) Rekap Bulanan + Export CSV
**Referensi:**
- `AttendanceController::recap()`
- `AttendanceController::rekapBulananSekretaris()`
- `AttendanceController::exportExcelWaliKelas()`
- `AttendanceController::exportExcelSekretaris()`
- `resources/views/wali-kelas/attendances/recap.blade.php`
- `resources/views/pengurus/attendances/recap.blade.php`

**Fitur:**
- Filter **bulan & tahun**.
- Menampilkan total Hadir/Izin/Sakit/Alpa per siswa.
- **Export CSV** (bukan xlsx) dengan UTF-8 BOM:
  - Nama file: `Rekap_Absensi_{Kelas}_{Bulan}_{Tahun}.csv`.

---

## 🔐 User Roles & Permissions (3 Role Utama)

### 1) Admin
**Middleware:** `auth + role:admin`
**Route Group:** `/admin/*`

**Akses:**
- Dashboard global absensi harian (`/admin/dashboard`)
- CRUD:
  - Kelas (`/admin/classrooms`)
  - Siswa (`/admin/students`)
  - User (admin/wali_kelas) (`/admin/users`)
- Import Excel siswa
- Penugasan Wali Kelas
- Penugasan Ketua/Sekretaris

**Batasan:**
- Semua data bisa diakses penuh.
- Tidak ada pembatasan tanggal absensi karena admin tidak menginput absensi langsung.

---

### 2) Guru Wali Kelas
**Middleware:** `auth + role:wali_kelas`
**Route Group:** `/wali-kelas/*`

**Akses:**
- Dashboard Wali Kelas (`/wali-kelas/dashboard`)
- Input absensi harian (`/wali-kelas/absen`)
- Rekap bulanan (`/wali-kelas/recap`)
- Export CSV rekap

**Batasan & Logika:**
- Hanya untuk kelas yang **ditugaskan ke wali_kelas_id**.
- Jika belum punya kelas → redirect ke dashboard dengan pesan error.
- Bisa memilih tanggal (edit historis).

---

### 3) Petugas Kelas (Sekretaris/Ketua Kelas)
**Middleware:** `auth + role:sekretaris,ketua_kelas,siswa`
**Route Group:** `/pengurus/*`

**Akses:**
- Dashboard Pengurus (`/pengurus/absen`)
- Input absensi **hari ini saja** (`/pengurus/absen/tambah`)
- Rekap bulanan (`/pengurus/rekap`)
- Export CSV rekap

**Batasan:**
- Akses dibatasi oleh **penugasan kelas** (via `ketua_id` / `sekretaris_id` di tabel kelas).
- Jika user berperan `siswa`, akses hanya diberikan bila **ditugaskan sebagai ketua/sekretaris**.

---

## 🧭 Detailed App Flow (Alur Aplikasi End-to-End)

### A) Landing Page Publik
1. User membuka `/`.
2. `LandingPageController@index`:
   - Jika user sudah login → **Smart Redirect** ke dashboard sesuai role.
   - Jika belum login:
     - Query semua kelas + count siswa.
     - Tampilkan **grid kartu kelas**.
3. User melihat daftar kelas, jumlah siswa, dan tingkat kelas.
4. Klik **kartu kelas** atau **“Masuk Sekarang”** → diarahkan ke halaman login.

---

### B) Login
1. Halaman login menampilkan input:
   - `Email / NIS`
   - Password
2. Form dikirim ke `POST /login`.
3. `LoginRequest`:
   - Tentukan field login (email atau nis).
   - Lakukan autentikasi.
4. Jika sukses:
   - Session diregenerasi.
   - `SmartRedirectService` menentukan tujuan:
     - Admin → `/admin/dashboard`
     - Wali Kelas:
       - Jika punya kelas → `/wali-kelas/absen`
       - Jika belum → `/wali-kelas/dashboard`
     - Petugas Kelas → `/pengurus/absen`
     - Default → `/dashboard`

---

### C) Alur Wali Kelas (Input & Edit Absensi)
1. Wali Kelas masuk ke `/wali-kelas/absen`.
2. Sistem mencari kelas berdasarkan `wali_kelas_id`.
3. Halaman menampilkan:
   - Info kelas + jumlah siswa
   - Komponen **Mesin Waktu** untuk memilih tanggal
   - Rekap status absensi pada tanggal terpilih
   - Status terakhir diupdate (oleh guru/petugas)
4. Wali Kelas memilih status per siswa:
   - Hadir/Izin/Sakit/Alpa
5. Submit → `AttendanceController@store`:
   - Validasi input.
   - Simpan per siswa menggunakan `updateOrCreate`.
   - Simpan `recorded_by_id`.
6. Setelah sukses:
   - Redirect kembali ke halaman absensi dengan parameter tanggal.

---

### D) Alur Petugas Kelas (Pengurus)
1. Petugas masuk ke `/pengurus/absen`.
2. Sistem mengecek:
   - Role `sekretaris/ketua_kelas` atau `siswa` yang ditugaskan.
3. Sistem mencari kelas berdasarkan `ketua_id` atau `sekretaris_id`.
4. Halaman menampilkan ringkasan hari ini:
   - Total siswa + rekap Hadir/Izin/Sakit/Alpa
5. Klik “Input Absensi Harian” → `/pengurus/absen/tambah`.
6. Di halaman input:
   - Tanggal **fixed hari ini** (tidak bisa diganti).
   - Status default “Hadir” jika belum ada data.
7. Submit → `AttendanceController@store`:
   - Simpan/update absensi hari ini.
8. Redirect ke halaman input + pesan sukses.

---

### E) Rekap Bulanan (Wali & Petugas)
1. Akses `/wali-kelas/recap` atau `/pengurus/rekap`.
2. Pilih bulan & tahun.
3. Sistem mengambil absensi per siswa untuk bulan tersebut.
4. Tampilkan total status per siswa.
5. Opsional:
   - Export CSV
   - Print laporan

---

### F) Admin Flow (Manajemen Data)
1. Admin login → `/admin/dashboard`:
   - Monitoring total siswa
   - Status absensi harian
   - Kelas yang belum absen
2. Admin mengelola data master:
   - Kelas (`/admin/classrooms`)
   - Siswa (`/admin/students`)
   - User (`/admin/users`)
3. Admin dapat:
   - Menentukan wali kelas
   - Menentukan ketua/sekretaris
   - Menambah/mengeluarkan siswa dari kelas
   - Import data siswa massal melalui Excel/CSV

---

## 🗂️ Struktur Data & Relasi (Database)

### Tabel `users`
- **id** (PK)
- nip (nullable, unique)
- name
- email (unique)
- nis (nullable, unique)
- role: `admin | wali_kelas | sekretaris | ketua_kelas | siswa`
- password, timestamps

### Tabel `academic_years`
- id
- name
- is_active

### Tabel `classrooms`
- id
- name
- tingkat (X, XI, XII)
- academic_year_id (FK → academic_years)
- wali_kelas_id (FK → users)
- ketua_id (FK → users)
- sekretaris_id (FK → users)

### Tabel `students`
- **nis** (PK, string)
- name
- jenis_kelamin (L/P)
- classroom_id (nullable, FK → classrooms)

### Tabel `attendances`
- id
- student_nis (FK → students)
- academic_year_id (FK → academic_years)
- date
- status (Hadir/Izin/Sakit/Alpa)
- recorded_by_id (FK → users)
