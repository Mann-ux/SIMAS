<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $nis = trim((string) ($row['nis'] ?? ''));
        $namaLengkap = trim((string) ($row['nama_lengkap'] ?? ''));

        // Skip baris kosong / tidak valid minimum
        if ($nis === '' || $namaLengkap === '') {
            return null;
        }

        $kelas = trim((string) ($row['kelas'] ?? ''));
        $classroomId = null;

        if ($kelas !== '') {
            $classroom = Classroom::where('name', $kelas)->first();
            $classroomId = $classroom?->id;
        }

        return new Student([
            'nis' => $nis,
            'name' => $namaLengkap,
            'jenis_kelamin' => $this->normalizeJenisKelamin($row['jenis_kelamin'] ?? null),
            'classroom_id' => $classroomId,
        ]);
    }

    /**
     * Gunakan NIS sebagai unique key agar import bersifat upsert.
     */
    public function uniqueBy(): string
    {
        return 'nis';
    }

    private function normalizeJenisKelamin(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            'l', 'laki-laki', 'laki laki', 'pria' => 'L',
            'p', 'perempuan', 'wanita' => 'P',
            default => null,
        };
    }
}
