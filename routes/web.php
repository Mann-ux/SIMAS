<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Group untuk Admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        $today = now()->toDateString();

        $total_siswa = \App\Models\Student::count();

        $rekapStatus = \App\Models\Attendance::query()
            ->whereDate('date', $today)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $hadir = (int) ($rekapStatus['Hadir'] ?? 0);
        $izin = (int) ($rekapStatus['Izin'] ?? 0);
        $sakit = (int) ($rekapStatus['Sakit'] ?? 0);
        $alpa = (int) ($rekapStatus['Alpa'] ?? 0);
        $izin_sakit = $izin + $sakit;

        $kelas_belum_absen = \App\Models\Classroom::with('user')
            ->whereDoesntHave('students.attendances', function ($query) use ($today) {
                $query->whereDate('date', $today);
            })
            ->get();

        $allClassrooms = \App\Models\Classroom::with([
            'user',
            'students',
            'students.attendances' => function ($query) use ($today) {
                $query->whereDate('date', $today);
            },
        ])->get();

        $classrooms_by_grade = collect(['X', 'XI', 'XII'])->mapWithKeys(function ($grade) use ($allClassrooms) {
            $filtered = $allClassrooms
                ->filter(function ($classroom) use ($grade) {
                    return strtoupper(strtok($classroom->name, ' ')) === $grade;
                })
                ->values();

            return [$grade => $filtered];
        });

        return view('admin.dashboard', compact(
            'today',
            'total_siswa',
            'hadir',
            'izin_sakit',
            'alpa',
            'kelas_belum_absen',
            'classrooms_by_grade'
        ));
    })->name('admin.dashboard');
    
    // Resource Routes
    Route::resource('classrooms', \App\Http\Controllers\ClassroomController::class);
    Route::resource('students', \App\Http\Controllers\StudentController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

// Route Group untuk Wali Kelas
Route::prefix('wali-kelas')->middleware(['auth', 'role:wali_kelas'])->group(function () {
    Route::get('/dashboard', function () {
        $classroom = \App\Models\Classroom::where('user_id', request()->user()?->id)->first();
        
        if ($classroom) {
            $total_siswa = $classroom->students->count();
            
            // Rekap absensi hari ini
            $hari_ini = date('Y-m-d');
            $attendances = \App\Models\Attendance::whereIn('student_nis', $classroom->students->pluck('nis'))
                ->where('date', $hari_ini)
                ->get();
            
            $recap_hari_ini = [
                'Hadir' => $attendances->where('status', 'Hadir')->count(),
                'Izin' => $attendances->where('status', 'Izin')->count(),
                'Sakit' => $attendances->where('status', 'Sakit')->count(),
                'Alpa' => $attendances->where('status', 'Alpa')->count(),
            ];
        } else {
            $total_siswa = 0;
            $recap_hari_ini = null;
        }
        
        return view('wali-kelas.dashboard', compact('classroom', 'total_siswa', 'recap_hari_ini'));
    })->name('wali-kelas.dashboard');
    
    // Absensi Routes
    Route::get('/absen', [\App\Http\Controllers\AttendanceController::class, 'create'])->name('wali-kelas.absen.create');
    Route::post('/absen', [\App\Http\Controllers\AttendanceController::class, 'store'])->name('wali-kelas.absen.store');
    
    // Rekap Bulanan Route
    Route::get('/recap', [\App\Http\Controllers\AttendanceController::class, 'recap'])->name('wali-kelas.recap');
    Route::get('/recap/export', [\App\Http\Controllers\AttendanceController::class, 'exportExcelWaliKelas'])->name('wali-kelas.recap.export');
});

// Route Group untuk Sekretaris
Route::prefix('sekretaris')->middleware(['auth', 'role:sekretaris'])->group(function () {
    Route::get('/dashboard', function () {
        $user = request()->user();
        $student = \App\Models\Student::where('nis', $user?->nis)->first();
        $classroom = $student?->classroom;
        $total_siswa = $classroom?->students()->count() ?? 0;

        // Rekap absensi hari ini
        $today = now()->toDateString();
        $attendances = collect();
        $recap_hari_ini = [
            'Hadir' => 0,
            'Izin' => 0,
            'Sakit' => 0,
            'Alpa' => 0,
        ];

        if ($classroom) {
            $attendances = \App\Models\Attendance::whereIn('student_nis', $classroom->students->pluck('nis'))
                ->where('date', $today)
                ->get();
            
            $recap_hari_ini = [
                'Hadir' => $attendances->where('status', 'Hadir')->count(),
                'Izin' => $attendances->where('status', 'Izin')->count(),
                'Sakit' => $attendances->where('status', 'Sakit')->count(),
                'Alpa' => $attendances->where('status', 'Alpa')->count(),
            ];
        }

        return view('sekretaris.dashboard', compact('classroom', 'total_siswa', 'recap_hari_ini', 'today'));
    })->name('sekretaris.dashboard');
    
    // Absensi Routes
    Route::get('/absen', [\App\Http\Controllers\AttendanceController::class, 'sekretarisCreate'])->name('sekretaris.absen.create');
    Route::post('/absen', [\App\Http\Controllers\AttendanceController::class, 'sekretarisStore'])->name('sekretaris.absen.store');
    
    // Rekap Bulanan Route
    Route::get('/recap', [\App\Http\Controllers\AttendanceController::class, 'rekapBulananSekretaris'])->name('sekretaris.recap');
    Route::get('/recap/export', [\App\Http\Controllers\AttendanceController::class, 'exportExcelSekretaris'])->name('sekretaris.recap.export');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
