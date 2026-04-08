<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents
{
    protected $data_absensi;
    protected $nomor_urut = 0;
    protected $judul_laporan;

    // Menangkap data yang dilempar dari Controller
    public function __construct($data_absensi, $month = null, $year = null)
    {
        $this->data_absensi = $data_absensi;
        $this->judul_laporan = $this->buildReportTitle($month, $year);
    }

    public function collection()
    {
        return $this->data_absensi;
    }

    // 1. Bikin Baris Judul Paling Atas
    public function headings(): array
    {
        return [
            [$this->judul_laporan],
            [''],
            ['NO', 'NIS', 'NAMA SISWA', 'H', 'I', 'S', 'A', 'PERSENTASE (%)'],
        ];
    }

    // 2. Mapping Data (Nyocokin data dari database ke kolom Excel)
    public function map($row): array
    {
        $this->nomor_urut++;

        $attendances = $row->attendances ?? collect();

        $totalHadir = (int) ($attendances->where('status', 'Hadir')->count() ?? 0);
        $totalIzin = (int) ($attendances->where('status', 'Izin')->count() ?? 0);
        $totalSakit = (int) ($attendances->where('status', 'Sakit')->count() ?? 0);
        $totalAlpa = (int) ($attendances->where('status', 'Alpa')->count() ?? 0);

        // Pastikan selalu angka, jangan pernah kosong/null
        $hadir = $totalHadir ?? 0;
        $izin = $totalIzin ?? 0;
        $sakit = $totalSakit ?? 0;
        $alpa = $totalAlpa ?? 0;

        $totalKehadiran = $hadir + $izin + $sakit + $alpa;
        $persentase = $totalKehadiran > 0
            ? round(($hadir / $totalKehadiran) * 100)
            : 0;

        return [
            $this->nomor_urut,
            $row->nis ?? '-',
            $row->name ?? '-',
            $hadir ?? 0,
            $izin ?? 0,
            $sakit ?? 0,
            $alpa ?? 0,
            $persentase . '%',
        ];
    }

    // 3. Styling Premium (Warna Header Navy & Teks Putih)
    public function styles(Worksheet $sheet)
    {
        return [
            // Style khusus baris header tabel (baris ke-3)
            3 => [
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['argb' => 'FF1E3A8A'] // Kode warna biru Navy
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $headerRow = 3;
                $firstDataRow = 4;
                $lastDataRow = $firstDataRow + $this->data_absensi->count() - 1;

                // Jika data kosong, biarkan rentang aman tetap valid
                if ($lastDataRow < $firstDataRow) {
                    $lastDataRow = $firstDataRow;
                }

                $grandTotalRow = $lastDataRow + 1;

                // Judul laporan
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', $this->judul_laporan);
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['argb' => Color::COLOR_WHITE],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1E3A8A'],
                    ],
                ]);

                // Freeze pane supaya header tabel tetap terlihat
                $sheet->freezePane('A4');

                // Center alignment untuk kolom H, I, S, A, dan Persentase
                $sheet->getStyle("D{$headerRow}:H{$grandTotalRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Border semua area tabel (header + data + total)
                $sheet->getStyle("A{$headerRow}:H{$grandTotalRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => Color::COLOR_BLACK],
                            ],
                        ],
                    ]);

                // Baris Grand Total
                $sheet->mergeCells("A{$grandTotalRow}:C{$grandTotalRow}");
                $sheet->setCellValue("A{$grandTotalRow}", 'TOTAL KESELURUHAN');
                $sheet->setCellValue("D{$grandTotalRow}", "=SUM(D{$firstDataRow}:D{$lastDataRow})");
                $sheet->setCellValue("E{$grandTotalRow}", "=SUM(E{$firstDataRow}:E{$lastDataRow})");
                $sheet->setCellValue("F{$grandTotalRow}", "=SUM(F{$firstDataRow}:F{$lastDataRow})");
                $sheet->setCellValue("G{$grandTotalRow}", "=SUM(G{$firstDataRow}:G{$lastDataRow})");
                $sheet->setCellValue("H{$grandTotalRow}", "=IFERROR(ROUND(D{$grandTotalRow}/SUM(D{$grandTotalRow}:G{$grandTotalRow})*100,0),0)&\"%\"");

                $sheet->getStyle("A{$grandTotalRow}:H{$grandTotalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE2E8F0'],
                    ],
                ]);

                // Tinggi baris data siswa supaya lebih lega
                for ($row = $firstDataRow; $row <= $lastDataRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(22);
                }

                // Conditional formatting: kolom Alpa (G) > 2 jadi merah terang
                $conditional = new Conditional();
                $conditional->setConditionType(Conditional::CONDITION_CELLIS)
                    ->setOperatorType(Conditional::OPERATOR_GREATERTHAN)
                    ->addCondition('2');

                $conditional->getStyle()->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF9999');

                $existingConditions = $sheet->getStyle("G{$firstDataRow}:G{$lastDataRow}")->getConditionalStyles();
                $existingConditions[] = $conditional;
                $sheet->getStyle("G{$firstDataRow}:G{$lastDataRow}")->setConditionalStyles($existingConditions);
            },
        ];
    }

    private function buildReportTitle($month = null, $year = null): string
    {
        if ($month === null || $year === null) {
            return 'REKAPITULASI ABSENSI';
        }

        $monthNames = [
            '01' => 'JANUARI',
            '02' => 'FEBRUARI',
            '03' => 'MARET',
            '04' => 'APRIL',
            '05' => 'MEI',
            '06' => 'JUNI',
            '07' => 'JULI',
            '08' => 'AGUSTUS',
            '09' => 'SEPTEMBER',
            '10' => 'OKTOBER',
            '11' => 'NOVEMBER',
            '12' => 'DESEMBER',
        ];

        $monthKey = str_pad((string) $month, 2, '0', STR_PAD_LEFT);
        $monthLabel = $monthNames[$monthKey] ?? strtoupper((string) $month);

        return 'REKAPITULASI ABSENSI ' . $monthLabel . ' ' . $year;
    }
}