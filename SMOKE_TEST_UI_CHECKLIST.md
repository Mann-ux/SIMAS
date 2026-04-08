# Smoke Test UI Checklist — Refactor Student PK ke `id`

Tanggal: 2026-04-08  
Scope: Verifikasi alur utama pasca refactor `students.id` + `attendances.student_id`

## Prasyarat

- Aplikasi sudah running normal.
- Database sudah dalam kondisi migrasi terbaru.
- Ada minimal:
  - 1 akun **admin**
  - 1 akun **pengurus** (sekretaris/ketua)
  - 1 kelas dengan beberapa siswa

---

## A. Smoke Test Admin — Edit Siswa

### A1. Edit siswa tanpa ubah NIS (harus sukses)
1. Login sebagai admin.
2. Buka menu **Kelola Siswa**.
3. Pilih salah satu siswa lalu klik **Edit**.
4. Ubah **Nama** saja, biarkan **NIS tetap**.
5. Klik **Simpan Perubahan**.

**Expected:**
- Redirect sukses ke halaman list siswa.
- Muncul pesan sukses.
- Data nama berubah.
- Tidak ada error validasi unique NIS.

### A2. Edit siswa dengan NIS duplikat (harus gagal validasi)
1. Pastikan ada siswa A (NIS-A) dan siswa B (NIS-B).
2. Edit siswa B.
3. Ubah NIS siswa B menjadi NIS-A.
4. Simpan.

**Expected:**
- Tetap di form edit.
- Muncul error validasi di field NIS.
- Data di database tidak berubah.

### A3. Verifikasi method + route update benar
1. Di form edit siswa, submit normal.

**Expected:**
- Request update berjalan dengan method PUT/PATCH.
- Route parameter menggunakan **student id** (bukan NIS).

---

## B. Smoke Test Admin — Penempatan Siswa ke Kelas

### B1. Tambah siswa ke kelas via modal detail kelas
1. Login admin.
2. Buka **Kelola Kelas** > pilih satu kelas > **Detail**.
3. Klik **Tambah Murid**.
4. Pilih 1 siswa yang belum punya kelas.
5. Submit.

**Expected:**
- Muncul pesan sukses.
- Siswa muncul di tabel anggota kelas.
- Data siswa memiliki `classroom_id` sesuai kelas target.
- Payload menggunakan `student_id[]`.

### B2. Keluarkan siswa dari kelas
1. Dari halaman detail kelas, klik hapus/keluarkan pada salah satu siswa.

**Expected:**
- Muncul pesan sukses.
- Siswa hilang dari daftar kelas.
- `classroom_id` siswa menjadi null.

---

## C. Smoke Test Pengurus — Input Absensi

### C1. Input absensi harian (student_id key)
1. Login sebagai pengurus.
2. Buka halaman input absensi.
3. Isi status untuk minimal 2 siswa:
   - Siswa 1: Hadir
   - Siswa 2: Izin + isi keterangan
4. Simpan.

**Expected:**
- Redirect sukses ke halaman absensi pengurus.
- Muncul pesan sukses.
- Record attendance tersimpan per `student_id`.
- Keterangan tersimpan untuk status yang relevan.

### C2. Edit absensi di tanggal yang sama
1. Masih di tanggal sama, ubah status salah satu siswa.
2. Simpan lagi.

**Expected:**
- Data attendance ter-update (bukan duplikasi tidak terkontrol).
- Update timestamp berubah.

---

## D. Smoke Test Dashboard & Rekap

### D1. Dashboard Wali/Pengurus menampilkan NIS lewat relasi siswa
1. Buka dashboard wali/pengurus setelah ada data absensi.

**Expected:**
- Daftar “siswa perlu perhatian” tetap muncul normal.
- NIS tampil benar (diambil dari relasi student, bukan kolom legacy `student_nis`).

### D2. Rekap bulanan
1. Buka halaman rekap bulanan.
2. Pilih bulan/tahun yang ada data.

**Expected:**
- Rekap tampil normal tanpa error SQL.
- Hitungan status masuk akal.

---

## E. Regression Quick Check (Wajib)

- [ ] CRUD siswa tetap normal.
- [ ] Assign/remove siswa ke kelas normal.
- [ ] Input absensi pengurus normal.
- [ ] Dashboard wali/pengurus normal.
- [ ] Tidak ada error SQL yang menyebut `student_nis` pada query runtime.
- [ ] Tidak ada error validasi `ignore()` null saat update siswa.

---

## Exit Criteria

Smoke test dinyatakan **PASS** bila:
1. Semua skenario A–D berhasil sesuai expected.
2. Tidak ada error fatal/SQL exception di log.
3. Tidak ada regresi fungsi inti pada flow admin dan pengurus.
