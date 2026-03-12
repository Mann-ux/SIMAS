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
    Route::delete('/classrooms/{classroom}/students/{student}', [\App\Http\Controllers\ClassroomController::class, 'removeStudent'])->name('classrooms.remove_student');
    Route::post('/classrooms/{classroom}/students', [\App\Http\Controllers\ClassroomController::class, 'addStudents'])->name('classrooms.add_students');
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

// Route Group untuk Pengurus (Sekretaris & Ketua Kelas)
Route::prefix('pengurus')->name('pengurus.')->middleware(['auth', 'role:sekretaris,ketua_kelas'])->group(function () {
    Route::get('/absen', [\App\Http\Controllers\PengurusController::class, 'index'])->name('dashboard');
    Route::get('/absen/tambah', [\App\Http\Controllers\PengurusController::class, 'create'])->name('absen.create');
    Route::post('/absen/simpan', [\App\Http\Controllers\PengurusController::class, 'store'])->name('absen.store');
    
    Route::get('/rekap', [\App\Http\Controllers\PengurusController::class, 'recap'])->name('recap');
    
    // Ini jalan tol baru buat tombol Export lu
    Route::get('/rekap/export', [\App\Http\Controllers\PengurusController::class, 'export'])->name('recap.export');
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
