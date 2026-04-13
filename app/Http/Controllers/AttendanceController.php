<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Exports\MasterAttendanceExport;
use App\Models\Attendance;
use App\Models\Classroom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    /**
     * Export Excel Multi-Sheet (Admin) berdasarkan filter periode, tingkat, dan rombel.
     */
    public function exportExcelAdmin(Request $request)
    {
        $periode = $request->input('periode', now()->format('Y-m'));
        $tingkat = $request->input('tingkat', 'all');
        $rombel = $request->input('rombel', 'all');

        return Excel::download(
            new MasterAttendanceExport($periode, $tingkat, $rombel),
            'Rekap_Absensi_Lengkap.xlsx'
        );
    }

    /**
     * Show dashboard overview for class pengurus (ketua/sekretaris).
     */
    public function index()
    {
        $user = Auth::user();

        if (! $this->canManageAsPengurus($user)) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $classroom = $this->findPengurusClassroom($user);
        $today = Carbon::today('Asia/Jakarta')->toDateString();

        if (!$classroom) {
            $recap_hari_ini = [
                'Hadir' => 0,
                'Izin' => 0,
                'Sakit' => 0,
                'Alpa' => 0,
            ];

            return view('pengurus.dashboard', [
                'classroom' => null,
                'total_siswa' => 0,
                'recap_hari_ini' => $recap_hari_ini,
                'today' => $today,
            ])->with('error', 'Anda belum ditugaskan sebagai pengurus di kelas manapun. Silakan hubungi admin.');
        }

        $students = $classroom->students()->orderBy('name')->get();
        $total_siswa = $students->count();
        $attendances = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereDate('date', $today)
            ->get();

        $recap_hari_ini = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        $lastUpdate = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereDate('date', $today)
            ->latest('updated_at')
            ->first();

        $siswaPerluPerhatian = Attendance::with('student')
            ->whereIn('student_id', $students->pluck('id'))
            ->whereDate('date', $today)
            ->whereIn('status', ['Alpa', 'Izin', 'Sakit'])
            ->get();

        return view('pengurus.dashboard', compact('classroom', 'total_siswa', 'recap_hari_ini', 'today', 'lastUpdate', 'siswaPerluPerhatian'));
    }

    /**
     * Show the form for creating a new attendance record.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        // Query classroom based on role
        if ($role === 'wali_kelas') {
            $classroom = Classroom::where('wali_kelas_id', Auth::id())->first();
        } else {
            if (! $this->canManageAsPengurus($user)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke fitur ini.');
            }

            $classroom = $this->findPengurusClassroom($user);
        }

        // Jika tidak ada kelas
        if (!$classroom) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan ke kelas manapun. Silakan hubungi admin.');
        } 

        // Ambil semua siswa dari kelas tersebut (urut abjad)
        $students = $classroom->students()
            ->orderBy('name', 'asc')
            ->get();

        // Handle date based on role
        $isWali = $role === 'wali_kelas';
        if ($isWali) {
            $date = $request->query('date', now()->toDateString());
        } else {
            $date = Carbon::today()->timezone('Asia/Jakarta')->toDateString();
        }

        // Ambil data absensi untuk tanggal yang dipilih
        $attendances = Attendance::with('recorder')
            ->whereIn('student_id', $students->pluck('id'))
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');

        // Hitung rekap kehadiran untuk tanggal yang dipilih
        $recap = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        // Ambil data absensi terakhir yang diupdate untuk tanggal yang dipilih
        $lastUpdate = Attendance::with('recorder')
            ->whereIn('student_id', $students->pluck('id'))
            ->where('date', $date)
            ->latest('updated_at')
            ->first();

        $recorder = $lastUpdate?->recorder;
        $recorderRole = $recorder->role ?? null;
        $isGuruWali = in_array($recorderRole, ['guru', 'admin', 'wali_kelas'], true);
        $isPetugasKelas = in_array($recorderRole, ['pengurus', 'sekretaris', 'ketua_kelas'], true);
        $petugasName = $isPetugasKelas ? ($recorder->name ?? null) : null;

        // Determine view based on role
        $view = $isWali ? 'wali-kelas.attendances.create' : 'pengurus.attendances.create';

        $settings = \App\Models\Setting::whereIn('key', ['latitude_sekolah', 'longitude_sekolah', 'radius_meter'])->pluck('value', 'key')->toArray();
        $setting_latitude = $settings['latitude_sekolah'] ?? '-6.538249';
        $setting_longitude = $settings['longitude_sekolah'] ?? '110.752525';
        $setting_radius = $settings['radius_meter'] ?? '50';

        $data = compact('classroom', 'students', 'attendances', 'recap', 'lastUpdate', 'isGuruWali', 'isPetugasKelas', 'petugasName', 'setting_latitude', 'setting_longitude', 'setting_radius');
        if ($isWali) {
            $data['date'] = $date;
        } else {
            $data['today'] = $date;
        }

        return view($view, $data);
    }

    /**
     * Store a newly created attendance record in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        // Validasi input
        $validationRules = [
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
            'attendances.*.keterangan' => 'nullable|string|max:255',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ];

        // Tambahkan validasi date jika wali_kelas
        if ($role === 'wali_kelas') {
            $validationRules['date'] = 'required|date';
        }

        $request->validate($validationRules);

        // Handle date based on role
        if ($role === 'wali_kelas') {
            $date = $request->date;
            $classroom = Classroom::where('wali_kelas_id', Auth::id())->first();
        } else {
            if (! $this->canManageAsPengurus($user)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke fitur ini.');
            }

            $date = Carbon::today()->timezone('Asia/Jakarta')->toDateString();
            $classroom = $this->findPengurusClassroom($user);
        }

        if (!$classroom) {
            return redirect()->back()->with('error', 'Anda belum ditugaskan ke kelas manapun.');
        }

        $attendanceData = $request->attendances;
        $academicYearId = $classroom->academic_year_id;

        // Looping untuk setiap siswa dan simpan/update absensi
        foreach ($attendanceData as $studentId => $data) {
            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                    'academic_year_id' => $academicYearId,
                    'recorded_by_id' => Auth::id(),
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                ]
            );
        }

        // Redirect based on role
        if ($role === 'wali_kelas') {
            $route = 'wali-kelas.absen.create';
            $params = ['date' => $date];
        } else {
            $route = 'pengurus.absen.create';
            $params = [];
        }

        return redirect()->route($route, $params)
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

        $user = Auth::user();
        $role = $user->role;

        // Cari kelas berdasarkan role user login
        if ($role === 'wali_kelas') {
            $classroom = Classroom::where('wali_kelas_id', Auth::id())->first();
        } elseif ($role === 'sekretaris') {
            $classroom = Classroom::where('sekretaris_id', Auth::id())->first();
        } else {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        // Jika tidak ada kelas
        if (!$classroom) {
            $dashboardRoute = $role === 'sekretaris' ? 'sekretaris.dashboard' : 'wali-kelas.dashboard';

            return redirect()->route($dashboardRoute)
                ->with('error', 'Anda belum ditugaskan ke kelas manapun. Silakan hubungi admin.');
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
        $sekretaris = \App\Models\Student::where('nis', Auth::user()->nis)->first();

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
            ->whereIn('student_id', $students->pluck('id'))
            ->where('date', $today)
            ->get()
            ->keyBy('student_id');

        // Hitung rekap kehadiran hari ini
        $recap = [
            'Hadir' => $attendances->where('status', 'Hadir')->count(),
            'Izin' => $attendances->where('status', 'Izin')->count(),
            'Sakit' => $attendances->where('status', 'Sakit')->count(),
            'Alpa' => $attendances->where('status', 'Alpa')->count(),
        ];

        // Ambil data absensi terakhir yang diupdate hari ini
        $lastUpdate = \App\Models\Attendance::with('recorder')
            ->whereIn('student_id', $students->pluck('id'))
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
        $sekretaris = \App\Models\Student::where('nis', Auth::user()->nis)->first();

        if (!$sekretaris) {
            return redirect()->route('sekretaris.dashboard')
                ->with('error', 'Data siswa Anda tidak ditemukan.');
        }

        // Validasi input
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.status' => 'required|in:Hadir,Sakit,Izin,Alpa',
            'attendances.*.keterangan' => 'nullable|string|max:255',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        // Tanggal di-hardcode (hari ini)
        $date = now()->toDateString();
        $attendanceData = $request->attendances;

        // Dapatkan academic_year_id dari kelas sekretaris
        $classroom = $sekretaris->classroom;
        $academicYearId = $classroom->academic_year_id;

        // Looping untuk setiap siswa dan simpan/update absensi
        foreach ($attendanceData as $studentId => $data) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date' => $date,
                ],
                [
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'] ?? null,
                    'academic_year_id' => $academicYearId,
                    'recorded_by_id' => Auth::id(),
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
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
        $user = Auth::user();

        if (! $this->canManageAsPengurus($user)) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $classroom = $this->findPengurusClassroom($user);

        if (!$classroom) {
            return redirect()->route('pengurus.dashboard')
                ->with('error', 'Anda belum ditugaskan ke kelas manapun. Silakan hubungi admin.');
        }

        $students = $classroom->students()->with(['attendances' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }])->orderBy('name', 'asc')->get();

        return view('pengurus.attendances.recap', compact('classroom', 'students', 'month', 'year'));
    }

    /**
     * Export Excel (CSV) - Wali Kelas
     */
    public function exportExcelWaliKelas(Request $request)
    {
        // Ambil parameter bulan dan tahun (default: bulan dan tahun saat ini)
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $user = Auth::user();
        $role = $user->role;

        // Cari kelas berdasarkan role user login
        if ($role === 'wali_kelas') {
            $classroom = Classroom::where('wali_kelas_id', Auth::id())->first();
        } elseif ($role === 'sekretaris') {
            $classroom = Classroom::where('sekretaris_id', Auth::id())->first();
        } else {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses ke fitur ini.');
        }

        // Jika tidak ada kelas
        if (!$classroom) {
            $dashboardRoute = $role === 'sekretaris' ? 'sekretaris.dashboard' : 'wali-kelas.dashboard';

            return redirect()->route($dashboardRoute)
                ->with('error', 'Anda belum ditugaskan ke kelas manapun.');
        }

        $data = $classroom->students()->with(['attendances' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }])->orderBy('name', 'asc')->get();

        return Excel::download(new AttendanceExport($data, $month, $year), 'Rekap_Absensi_SMA.xlsx');
    }

    /**
     * Export Excel (CSV) - Sekretaris
     */
    public function exportExcelSekretaris(Request $request)
    {
        $user = Auth::user();

        if (! $this->canManageAsPengurus($user)) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $classroom = $this->findPengurusClassroom($user);

        if (!$classroom) {
            return redirect()->route('pengurus.dashboard')
                ->with('error', 'Anda belum ditugaskan ke kelas manapun.');
        }

        $data = $classroom->students()->with(['attendances' => function ($query) use ($month, $year) {
            $query->whereYear('date', $year)
                  ->whereMonth('date', $month);
        }])->orderBy('name', 'asc')->get();

        return Excel::download(new AttendanceExport($data, $month, $year), 'Rekap_Absensi_SMA.xlsx');
    }

    private function canManageAsPengurus($user): bool
    {
        if (in_array($user->role, ['sekretaris', 'ketua_kelas', 'pengurus'])) {
            return true;
        }

        if ($user->role === 'siswa' && $user->id) {
            return Classroom::where('ketua_id', $user->id)
                ->orWhere('sekretaris_id', $user->id)
                ->exists();
        }

        return false;
    }

    private function findPengurusClassroom($user): ?Classroom
    {
        $conditions = [];

        if ($user->id) {
            $conditions[] = ['column' => 'sekretaris_id', 'value' => $user->id];
            $conditions[] = ['column' => 'ketua_id', 'value' => $user->id];
        }

        if (empty($conditions)) {
            return null;
        }

        $clauses = $conditions;

        return Classroom::where(function ($query) use ($clauses) {
            $first = array_shift($clauses);
            $query->where($first['column'], $first['value']);

            foreach ($clauses as $clause) {
                $query->orWhere($clause['column'], $clause['value']);
            }
        })->with(['students', 'academicYear'])->first();
    }
}
