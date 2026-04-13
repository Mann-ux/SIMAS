<?php

namespace App\Exports;

use App\Models\Classroom;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class MasterAttendanceExport implements WithMultipleSheets
{
    use Exportable;

    protected string $periode;
    protected string $tingkat;
    protected string $rombel;
    protected string $month;
    protected string $year;

    public function __construct($periode, $tingkat = 'all', $rombel = 'all')
    {
        $this->periode = (string) ($periode ?: now()->format('Y-m'));
        $this->tingkat = (string) ($tingkat ?: 'all');
        $this->rombel = (string) ($rombel ?: 'all');

        try {
            $parsed = Carbon::createFromFormat('Y-m', $this->periode);
            $this->month = $parsed->format('m');
            $this->year = $parsed->format('Y');
        } catch (\Throwable $th) {
            $this->month = now()->format('m');
            $this->year = now()->format('Y');
        }
    }

    public function sheets(): array
    {
        $query = Classroom::query()->orderBy('tingkat')->orderBy('name');

        if ($this->tingkat !== 'all') {
            $query->where('tingkat', $this->tingkat);
        }

        if ($this->rombel !== 'all') {
            $rombel = $this->rombel;

            // Karena belum ada kolom rombel khusus, filter diturunkan dari nama kelas (contoh: X-1, XI-2)
            $query->where(function ($q) use ($rombel) {
                $q->where('name', 'like', '%-' . $rombel)
                  ->orWhere('name', 'like', '% ' . $rombel)
                  ->orWhere('name', $rombel);
            });
        }

        $classrooms = $query->get();
        $sheets = [];

        foreach ($classrooms as $classroom) {
            $dataAbsensi = $classroom->students()
                ->with(['attendances' => function ($attendanceQuery) {
                    $attendanceQuery->whereYear('date', $this->year)
                        ->whereMonth('date', $this->month);
                }])
                ->orderBy('name', 'asc')
                ->get();

            $sheets[] = new AttendanceExport(
                $dataAbsensi,
                $this->month,
                $this->year,
                $classroom->name
            );
        }

        if (empty($sheets)) {
            $sheets[] = new AttendanceExport(new Collection(), $this->month, $this->year, 'Data Kosong');
        }

        return $sheets;
    }
}
