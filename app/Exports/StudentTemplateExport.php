<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class StudentTemplateExport implements WithHeadings, ShouldAutoSize, WithStyles
{
    /**
     * Menentukan judul kolom di baris pertama
     */
    public function headings(): array
    {
        return [
            'NIS',
            'NAMA LENGKAP',
            'JENIS KELAMIN',
            'KELAS'
        ];
    }
    /**
     * Styling Premium (Warna Header Navy & Teks Putih Bold)
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style khusus baris ke-1 (Header)
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['argb' => 'FF1E3A8A'] // Kode warna biru Navy
                ],
            ],
        ];
    }
}