<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class RollingKelasImport implements ToCollection, WithHeadingRow
{
    /** Jumlah baris yang berhasil diupdate. */
    public int $updated = 0;

    /** Jumlah baris yang dilewati (NIS/kelas tidak valid). */
    public int $skipped = 0;

    /**
     * Proses setiap baris Excel.
     * TIDAK membuat data baru — hanya UPDATE classroom_id siswa yang sudah ada.
     */
    public function collection(Collection $rows): void
    {
        // Cache nama kelas → id agar tidak query database berulang
        $kelasCache = [];

        foreach ($rows as $row) {
            $nis       = trim((string) ($row['nis']        ?? ''));
            $namaKelas = trim((string) ($row['nama_kelas'] ?? ''));

            // Skip baris kosong
            if ($nis === '' || $namaKelas === '') {
                $this->skipped++;
                continue;
            }

            // Cari kelas di cache, lalu fallback ke DB
            if (!isset($kelasCache[$namaKelas])) {
                $kelas = Classroom::where('name', $namaKelas)
                    ->where('status', 1)
                    ->first();
                $kelasCache[$namaKelas] = $kelas?->id; // null jika tidak ditemukan
            }

            $classroomId = $kelasCache[$namaKelas];

            // Skip jika kelas tidak ditemukan di database
            if ($classroomId === null) {
                $this->skipped++;
                continue;
            }

            // Update hanya jika siswa dengan NIS tersebut ada — TIDAK CREATE baru
            $affected = Student::where('nis', $nis)
                ->update(['classroom_id' => $classroomId]);

            if ($affected > 0) {
                $this->updated++;
            } else {
                $this->skipped++; // NIS tidak ditemukan di DB
            }
        }
    }
}
