<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Show the form for creating a new attendance record.
     */
    public function create(Request $request)
    {
        // Cari kelas yang dimiliki wali kelas yang sedang login
        $classroom = Classroom::where('user_id', auth()->id())->first();

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('wali-kelas.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai wali kelas. Silakan hubungi admin.');
        } 

        // Ambil semua siswa dari kelas tersebut
        $students = $classroom->students()->orderBy('name', 'asc')->get();

        // Tangkap parameter date dari URL, default hari ini (Mesin Waktu untuk Wali Kelas)
        $date = $request->query('date', now()->toDateString());

        // Ambil data absensi untuk tanggal yang dipilih
        $attendances = Attendance::with('recorder')
            ->whereIn('student_nis', $students->pluck('nis'))
            ->where('date', $date)
            ->get()
            ->keyBy('student_nis');

        // Hitung rekap kehadiran untuk tanggal yang dipilih
        $recap = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        // Ambil data absensi terakhir yang diupdate untuk tanggal yang dipilih
        $lastUpdate = Attendance::with('recorder')
            ->whereIn('student_nis', $students->pluck('nis'))
            ->where('date', $date)
            ->latest('updated_at')
            ->first();

        return view('wali-kelas.attendances.create', compact('classroom', 'students', 'date', 'attendances', 'recap', 'lastUpdate'));
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $attendanceData = $request->attendances;

        // Dapatkan classroom dan academic_year_id
        $classroom = Classroom::where('user_id', auth()->id())->first();
        $academicYearId = $classroom->academic_year_id;

        // Looping untuk setiap siswa dan simpan/update absensi
        foreach ($attendanceData as $studentNis => $data) {
            Attendance::updateOrCreate(
                [
                    'student_nis' => $studentNis,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'academic_year_id' => $academicYearId,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('wali-kelas.absen.create', ['date' => $date])
            ->with('success', 'Absensi berhasil disimpan untuk tanggal ' . date('d F Y', strtotime($date)) . '!');
    }

    /**
     * Show monthly attendance recap.
     */
    public function recap(Request $request)
    {
        // Ambil parameter bulan dan tahun (default: bulan dan tahun saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Cari kelas yang dimiliki wali kelas yang sedang login
        $classroom = Classroom::where('user_id', auth()->id())->first();

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('wali-kelas.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai wali kelas. Silakan hubungi admin.');
        }

        // Ambil siswa beserta absensi yang sudah difilter berdasarkan bulan dan tahun
        $students = $classroom->students()->with(['attendances' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }])->orderBy('name', 'asc')->get();

        return view('wali-kelas.attendances.recap', compact('classroom', 'students', 'month', 'year'));
    }

    /**
     * Show the form for creating attendance record (Sekretaris).
     */
    public function sekretarisCreate()
    {
        // Cari data diri sekretaris di model Student menggunakan NIS
        $sekretaris = \App\Models\Student::where('nis', auth()->user()->nis)->first();

        // Jika sekretaris tidak ditemukan di tabel students
        if (!$sekretaris) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Data siswa Anda tidak ditemukan. Silakan hubungi admin.');
        }

        // Dapatkan kelas dari sekretaris tersebut
        $classroom = $sekretaris->classroom;

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Anda belum terdaftar di kelas manapun. Silakan hubungi admin.');
        }

        // Ambil semua siswa dari kelas tersebut
        $students = $classroom->students()->orderBy('name', 'asc')->get();

        // Tanggal hari ini (hardcoded, tidak bisa manipulasi)
        $today = now()->toDateString();

        // Ambil data absensi HARI INI saja
        $attendances = \App\Models\Attendance::with('recorder')
            ->whereIn('student_nis', $students->pluck('nis'))
            ->where('date', $today)
            ->get()
            ->keyBy('student_nis');

        // Hitung rekap kehadiran hari ini
        $recap = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        // Ambil data absensi terakhir yang diupdate hari ini
        $lastUpdate = \App\Models\Attendance::with('recorder')
            ->whereIn('student_nis', $students->pluck('nis'))
            ->where('date', $today)
            ->latest('updated_at')
            ->first();

        return view('sekretaris.attendances.create', compact('classroom', 'students', 'today', 'attendances', 'recap', 'lastUpdate'));
    }

    /**
     * Store attendance record (Sekretaris).
     */
    public function sekretarisStore(Request $request)
    {
        // Cari data diri sekretaris
        $sekretaris = \App\Models\Student::where('nis', auth()->user()->nis)->first();

        if (!$sekretaris) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Data siswa Anda tidak ditemukan.');
        }

        // Validasi input
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        // Tanggal di-hardcode (hari ini)
        $date = now()->toDateString();
        $attendanceData = $request->attendances;

        // Dapatkan academic_year_id dari kelas sekretaris
        $classroom = $sekretaris->classroom;
        $academicYearId = $classroom->academic_year_id;

        // Looping untuk setiap siswa dan simpan/update absensi
        foreach ($attendanceData as $studentNis => $data) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'student_nis' => $studentNis,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'academic_year_id' => $academicYearId,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('sekretaris.absen.create')
            ->with('success', 'Absensi berhasil disimpan untuk hari ini (' . date('d F Y', strtotime($date)) . ')!');
    }

    /**
     * Show monthly attendance recap (Sekretaris).
     */
    public function rekapBulananSekretaris(Request $request)
    {
        // Ambil parameter bulan dan tahun (default: bulan dan tahun saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Cari data diri sekretaris di model Student menggunakan NIS
        $sekretaris = \App\Models\Student::where('nis', auth()->user()->nis)->first();

        // Jika sekretaris tidak ditemukan di tabel students
        if (!$sekretaris) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Data siswa Anda tidak ditemukan. Silakan hubungi admin.');
        }

        // Dapatkan kelas dari sekretaris tersebut
        $classroom = $sekretaris->classroom;

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Anda belum terdaftar di kelas manapun. Silakan hubungi admin.');
        }

        // Ambil siswa beserta absensi yang sudah difilter berdasarkan bulan dan tahun
        $students = $classroom->students()->with(['attendances' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }])->orderBy('name', 'asc')->get();

        return view('sekretaris.attendances.recap', compact('classroom', 'students', 'month', 'year'));
    }

    /**
     * Export Excel (CSV) - Wali Kelas
     */
    public function exportExcelWaliKelas(Request $request)
    {
        // Ambil parameter bulan dan tahun (default: bulan dan tahun saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Cari kelas yang dimiliki wali kelas yang sedang login
        $classroom = Classroom::where('user_id', Auth::id())->first();

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('wali-kelas.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai wali kelas.');
        }

        // Ambil siswa beserta absensi yang sudah difilter berdasarkan bulan dan tahun
        $students = $classroom->students()->with(['attendances' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }])->orderBy('name', 'asc')->get();

        // Nama bulan untuk filename
        $monthNames = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $filename = 'Rekap_Absensi_' . str_replace(' ', '_', $classroom->name) . '_' . $monthNames[$month] . '_' . $year . '.csv';

        // Set header untuk download CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Buat output stream
        $output = fopen('php://output', 'w');

        // Tulis UTF-8 BOM agar Excel bisa baca karakter Indonesia
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header CSV
        fputcsv($output, ['No', 'NIS', 'Nama Siswa', 'Hadir', 'Izin', 'Sakit', 'Alpa']);

        // Data siswa
        $no = 1;
        foreach ($students as $student) {
            $totalHadir = $student->attendances->where('status', 'Hadir')->count();
            $totalIzin = $student->attendances->where('status', 'Izin')->count();
            $totalSakit = $student->attendances->where('status', 'Sakit')->count();
            $totalAlpa = $student->attendances->where('status', 'Alpa')->count();

            fputcsv($output, [
                $no++,
                $student->nis,
                $student->name,
                $totalHadir,
                $totalIzin,
                $totalSakit,
                $totalAlpa
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Export Excel (CSV) - Sekretaris
     */
    public function exportExcelSekretaris(Request $request)
    {
        // Ambil parameter bulan dan tahun (default: bulan dan tahun saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Cari data diri sekretaris di model Student menggunakan NIS
        $sekretaris = \App\Models\Student::where('nis', Auth::user()->nis)->first();

        // Jika sekretaris tidak ditemukan di tabel students
        if (!$sekretaris) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Data siswa Anda tidak ditemukan.');
        }

        // Dapatkan kelas dari sekretaris tersebut
        $classroom = $sekretaris->classroom;

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Anda belum terdaftar di kelas manapun.');
        }

        // Ambil siswa beserta absensi yang sudah difilter berdasarkan bulan dan tahun
        $students = $classroom->students()->with(['attendances' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }])->orderBy('name', 'asc')->get();

        // Nama bulan untuk filename
        $monthNames = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];

        $filename = 'Rekap_Absensi_' . str_replace(' ', '_', $classroom->name) . '_' . $monthNames[$month] . '_' . $year . '.csv';

        // Set header untuk download CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Buat output stream
        $output = fopen('php://output', 'w');

        // Tulis UTF-8 BOM agar Excel bisa baca karakter Indonesia
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header CSV
        fputcsv($output, ['No', 'NIS', 'Nama Siswa', 'Hadir', 'Izin', 'Sakit', 'Alpa']);

        // Data siswa
        $no = 1;
        foreach ($students as $student) {
            $totalHadir = $student->attendances->where('status', 'Hadir')->count();
            $totalIzin = $student->attendances->where('status', 'Izin')->count();
            $totalSakit = $student->attendances->where('status', 'Sakit')->count();
            $totalAlpa = $student->attendances->where('status', 'Alpa')->count();

            fputcsv($output, [
                $no++,
                $student->nis,
                $student->name,
                $totalHadir,
                $totalIzin,
                $totalSakit,
                $totalAlpa
            ]);
        }

        fclose($output);
        exit;
    }
}
