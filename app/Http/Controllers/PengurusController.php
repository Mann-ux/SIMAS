<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengurusController extends Controller
{
    /**
     * Dashboard untuk Pengurus
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Cari kelas berdasarkan ketua_nis atau sekretaris_nis
        $classroom = Classroom::with('students')
            ->where('ketua_nis', $user->nis)
            ->orWhere('sekretaris_nis', $user->nis)
            ->first();

        if (!$classroom) {
            abort(403, 'Anda belum ditugaskan sebagai pengurus di kelas manapun.');
        }

        // Hardcode tanggal hari ini (No Time Machine)
        $today = Carbon::today()->timezone('Asia/Jakarta')->toDateString();
        $total_siswa = $classroom->students->count();

        // Kumpulin semua NIS murid
        $kumpulan_nis = $classroom->students->pluck('nis');

        // Tarik data absensi hari ini
        $attendances = Attendance::whereIn('student_nis', $kumpulan_nis)
            ->where('date', $today)
            ->get();

        // Kalkulasi Statistik buat Summary Card
        $recap_hari_ini = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        // Jejak Digital (Audit Trail)
        $last_update = Attendance::with('recorder')
            ->whereIn('student_nis', $kumpulan_nis)
            ->where('date', $today)
            ->latest('updated_at')
            ->first();

        return view('pengurus.dashboard', compact(
            'classroom', 'total_siswa', 'today', 'recap_hari_ini', 'last_update'
        ));
    }

    /**
     * Show the form for creating attendance record (Pengurus).
     */
    public function create(Request $request)
    {
        $user = $request->user();

        // Cari kelas berdasarkan ketua_nis atau sekretaris_nis
        $classroom = Classroom::where('ketua_nis', $user->nis)
            ->orWhere('sekretaris_nis', $user->nis)
            ->first();

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('pengurus.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai pengurus di kelas manapun. Silakan hubungi admin.');
        }

        // Hardcode tanggal hari ini (No Time Machine)
        $today = Carbon::today()->timezone('Asia/Jakarta')->toDateString();

        // Ambil semua siswa dari kelas, diurutkan berdasarkan no_absen
        $students = $classroom->students()->orderBy('nis', 'asc')->get();

        // Ambil data absensi hari ini
        $attendances = Attendance::with('recorder')
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
        $lastUpdate = Attendance::with('recorder')
            ->whereIn('student_nis', $students->pluck('nis'))
            ->where('date', $today)
            ->latest('updated_at')
            ->first();

        return view('pengurus.attendances.create', compact('classroom', 'students', 'today', 'attendances', 'recap', 'lastUpdate'));
    }

    /**
     * Store attendance record (Pengurus).
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Cari kelas berdasarkan ketua_nis atau sekretaris_nis
        $classroom = Classroom::where('ketua_nis', $user->nis)
            ->orWhere('sekretaris_nis', $user->nis)
            ->first();

        if (!$classroom) {
            return redirect()->route('pengurus.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai pengurus di kelas manapun.');
        }

        // Validasi input
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
        ]);

        // Hardcode tanggal hari ini (No Time Machine)
        $today = Carbon::today()->timezone('Asia/Jakarta')->toDateString();
        $attendanceData = $request->attendances;

        // Dapatkan academic_year_id dari kelas
        $academicYearId = $classroom->academic_year_id;

        // Looping untuk setiap siswa dan simpan/update absensi (Anti-Duplikasi)
        foreach ($attendanceData as $studentNis => $data) {
            Attendance::updateOrCreate(
                [
                    'student_nis' => $studentNis,
                    'date' => $today,
                ],
                [
                    'status' => $data['status'],
                    'academic_year_id' => $academicYearId,
                    'recorded_by' => $user->id,
                ]
            );
        }

        return redirect()->route('pengurus.absen.create')
            ->with('success', 'Absensi berhasil disimpan untuk hari ini (' . date('d F Y', strtotime($today)) . ')!');
    }

    /**
     * Show daily attendance recap (Pengurus).
     */
    public function recap(Request $request)
    {
        $user = $request->user();

        // Cari kelas berdasarkan ketua_nis atau sekretaris_nis
        $classroom = Classroom::where('ketua_nis', $user->nis)
            ->orWhere('sekretaris_nis', $user->nis)
            ->first();

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('pengurus.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai pengurus di kelas manapun. Silakan hubungi admin.');
        }

        // Hardcode tanggal hari ini (No Time Machine)
        $today = Carbon::today()->timezone('Asia/Jakarta')->toDateString();

        // Ambil semua siswa dari kelas
        $students = $classroom->students;

        // Ambil data absensi hari ini
        $attendances = Attendance::whereIn('student_nis', $students->pluck('nis'))
            ->where('date', $today)
            ->get();

        // Hitung rekap kehadiran hari ini
        $recap_hari_ini = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        // Nangkep input bulan & tahun dari filter form (default: bulan & tahun saat ini)
    $month = request('month', \Carbon\Carbon::today()->timezone('Asia/Jakarta')->format('m'));
    $year = request('year', \Carbon\Carbon::today()->timezone('Asia/Jakarta')->format('Y'));

       return view('pengurus.attendances.recap', compact('classroom', 'students', 'recap_hari_ini', 'today', 'month', 'year'));
    }

    /**
     * Export Excel (CSV) - Pengurus
     */
    public function export(Request $request)
    {
        $user = $request->user();

        // Ambil parameter bulan dan tahun (default: bulan dan tahun saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Cari kelas berdasarkan ketua_nis atau sekretaris_nis
        $classroom = Classroom::where('ketua_nis', $user->nis)
            ->orWhere('sekretaris_nis', $user->nis)
            ->first();

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->route('pengurus.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai pengurus di kelas manapun.');
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