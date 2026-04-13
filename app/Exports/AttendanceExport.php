<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison; // Ini senjata rahasia biar 0 tetap jadi 0
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithEvents, WithTitle, WithStrictNullComparison
{
    protected $data_absensi;
    protected $nomor_urut = 0;
    protected $judul_laporan;
    protected $sheetTitle;
    protected $namaKelas;

    // Menangkap data yang dilempar dari Controller
    public function __construct($data_absensi, $month = null, $year = null, ?string $sheetTitle = null)
    {
        $this->data_absensi = $data_absensi;
        $this->sheetTitle = $sheetTitle;
        $this->namaKelas = $this->resolveClassName();
        $this->judul_laporan = $this->buildReportTitle($month, $year, $this->namaKelas);
    }

    public function collection()
    {
        return $this->data_absensi;
    }

    public function headings(): array
    {
        return [
            [$this->judul_laporan],
            [''],
            ['NO', 'NIS', 'NAMA SISWA', 'H', 'I', 'S', 'A', 'PERSENTASE (%)'],
        ];
    }

    // Mapping Data
    public function map($row): array
    {
        $this->nomor_urut++;

        $hadir = 0; $izin = 0; $sakit = 0; $alpa = 0;

        if (isset($row->attendances) && $row->attendances->count() > 0) {
            $hadir = (int) $row->attendances->where('status', 'Hadir')->count();
            $izin  = (int) $row->attendances->where('status', 'Izin')->count();
            $sakit = (int) $row->attendances->where('status', 'Sakit')->count();
            $alpa  = (int) $row->attendances->where('status', 'Alpa')->count();
        } else {
            $hadir = (int) ($row->hadir ?? 0);
            $izin  = (int) ($row->izin ?? 0);
            $sakit = (int) ($row->sakit ?? 0);
            $alpa  = (int) ($row->alpa ?? 0);
        }

        $totalKehadiran = $hadir + $izin + $sakit + $alpa;
        $persentase = $totalKehadiran > 0 ? round(($hadir / $totalKehadiran) * 100) : 0;

        return [
            $this->nomor_urut,
            $row->nis ?? '-',
            $row->name ?? '-',
            $hadir,
            $izin,
            $sakit,
            $alpa,
            $persentase . '%',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            3 => [
                'font' => ['bold' => true, 'color' => ['argb' => Color::COLOR_WHITE]],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID, 
                    'startColor' => ['argb' => 'FF1E3A8A'] 
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
                $lastRow = $firstDataRow + $this->data_absensi->count() - 1;

                if ($lastRow < $firstDataRow) {
                    $lastRow = $firstDataRow;
                }

                $totalRow = $lastRow + 1;

                // --- KALKULASI TOTAL MENGGUNAKAN PHP (Bypass Bug Excel Formula) ---
                $sumH = 0; $sumI = 0; $sumS = 0; $sumA = 0;
                foreach ($this->data_absensi as $row) {
                    if (isset($row->attendances) && $row->attendances->count() > 0) {
                        $sumH += (int) $row->attendances->where('status', 'Hadir')->count();
                        $sumI += (int) $row->attendances->where('status', 'Izin')->count();
                        $sumS += (int) $row->attendances->where('status', 'Sakit')->count();
                        $sumA += (int) $row->attendances->where('status', 'Alpa')->count();
                    } else {
                        $sumH += (int) ($row->hadir ?? 0);
                        $sumI += (int) ($row->izin ?? 0);
                        $sumS += (int) ($row->sakit ?? 0);
                        $sumA += (int) ($row->alpa ?? 0);
                    }
                }
                $totalAll = $sumH + $sumI + $sumS + $sumA;
                $percAll = $totalAll > 0 ? round(($sumH / $totalAll) * 100) . '%' : '0%';

                // Judul Laporan
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

                $sheet->freezePane('A4');

                // --- ALIGNMENT MUTLAK ---
                $sheet->getStyle("A3:H{$totalRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getStyle("A3:A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("B3:C{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle("D3:H{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Border tabel
                $sheet->getStyle("A{$headerRow}:H{$totalRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['argb' => Color::COLOR_BLACK],
                            ],
                        ],
                    ]);

                // --- BARIS GRAND TOTAL ---
                $sheet->mergeCells("A{$totalRow}:C{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL KESELURUHAN');
                
                // Tembak hasil PHP langsung ke cell (Tanpa pakai =SUM)
                $sheet->setCellValue("D{$totalRow}", $sumH);
                $sheet->setCellValue("E{$totalRow}", $sumI);
                $sheet->setCellValue("F{$totalRow}", $sumS);
                $sheet->setCellValue("G{$totalRow}", $sumA);
                $sheet->setCellValue("H{$totalRow}", $percAll);

                $sheet->getStyle("A{$totalRow}:H{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE2E8F0'], 
                    ],
                ]);
                
                $sheet->getStyle("A{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tinggi Baris
                for ($row = $firstDataRow; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(22);
                }
                $sheet->getRowDimension($totalRow)->setRowHeight(22);

                // Conditional formatting
                $conditional = new Conditional();
                $conditional->setConditionType(Conditional::CONDITION_CELLIS)
                    ->setOperatorType(Conditional::OPERATOR_GREATERTHAN)
                    ->addCondition('2');

                $conditional->getStyle()->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFFF9999');

                $existingConditions = $sheet->getStyle("G{$firstDataRow}:G{$lastRow}")->getConditionalStyles();
                $existingConditions[] = $conditional;
                $sheet->getStyle("G{$firstDataRow}:G{$lastRow}")->setConditionalStyles($existingConditions);
            },
        ];
    }

    private function buildReportTitle($month = null, $year = null, ?string $className = null): string
    {
        if ($month === null || $year === null) {
            return $className
                ? 'REKAPITULASI ABSENSI KELAS ' . $className
                : 'REKAPITULASI ABSENSI';
        }

        $monthNames = [
            '01' => 'JANUARI', '02' => 'FEBRUARI', '03' => 'MARET', '04' => 'APRIL',
            '05' => 'MEI', '06' => 'JUNI', '07' => 'JULI', '08' => 'AGUSTUS',
            '09' => 'SEPTEMBER', '10' => 'OKTOBER', '11' => 'NOVEMBER', '12' => 'DESEMBER',
        ];

        $monthKey = str_pad((string) $month, 2, '0', STR_PAD_LEFT);
        $monthLabel = $monthNames[$monthKey] ?? strtoupper((string) $month);

        $baseTitle = 'REKAPITULASI ABSENSI ' . $monthLabel . ' ' . $year;

        return $className
            ? $baseTitle . ' KELAS ' . $className
            : $baseTitle;
    }

    private function resolveClassName(): ?string
    {
        $sheetTitle = trim((string) ($this->sheetTitle ?? ''));

        if ($sheetTitle !== '' && strcasecmp($sheetTitle, 'Data Kosong') !== 0) {
            return $sheetTitle;
        }

        $firstRow = $this->data_absensi->first();

        if (!$firstRow) {
            return null;
        }

        $classroomName = $firstRow->classroom->name
            ?? $firstRow->classroom_name
            ?? $firstRow->class_name
            ?? null;

        return $classroomName ? (string) $classroomName : null;
    }

    public function title(): string
    {
        $title = trim((string) ($this->sheetTitle ?: 'Rekap'));
        $title = preg_replace('/[\\\\\/?\*\[\]:]/', '', $title) ?: 'Rekap';
        return mb_substr($title, 0, 31);
    }
}