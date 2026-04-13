<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
     * Ambil semua siswa beserta relasi kelasnya.
     */
    public function collection()
    {
        return Student::with('classroom')
            ->orderBy('classroom_id')
            ->orderBy('name')
            ->get();
    }

    /**
     * Mapping kolom yang diekspor ke Excel.
     * Kolom nama_kelas diambil dari relasi, BUKAN kelas_id.
     */
    public function map($siswa): array
    {
        return [
            $siswa->nis,
            $siswa->name,
            $siswa->classroom?->name ?? '',   // nama kelas dari relasi
        ];
    }

    /**
     * Header baris pertama.
     */
    public function headings(): array
    {
        return [
            'nis',
            'nama',
            'nama_kelas',
        ];
    }

    /**
     * Style header: navy background, teks putih, bold.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF00236F'],
                ],
            ],
        ];
    }
}
