<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classrooms = Classroom::with('user')
            ->withCount('students')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('admin.classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gurus = User::where('role', 'wali_kelas')->orderBy('name', 'asc')->get();
        return view('admin.classrooms.create', compact('gurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name',
            'tingkat' => 'required|in:X,XI,XII',
            'user_id' => 'required|exists:users,id',
        ]);

        // Ambil academic_year yang aktif
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        
        $validated['academic_year_id'] = $activeAcademicYear->id;

        Classroom::create($validated);

        return redirect()->route('classrooms.index')
            ->with('success', 'Kelas berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        // Load relasi students biar bisa milih ketua/sekretaris
        $classroom->load('students', 'user');
        
        // Ambil data guru buat dropdown wali kelas
        $users = User::where('role', 'wali_kelas')->orderBy('name', 'asc')->get();
        
        // Ambil siswa yang belum punya kelas untuk modal tambah murid
        $availableStudents = \App\Models\Student::whereNull('classroom_id')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('admin.classrooms.show', compact('classroom', 'users', 'availableStudents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classroom $classroom)
    {
        $gurus = User::where('role', 'wali_kelas')->orderBy('name', 'asc')->get();
        return view('admin.classrooms.edit', compact('classroom', 'gurus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classrooms,name,' . $classroom->id,
            'tingkat' => 'required|in:X,XI,XII',
            'user_id' => 'required|exists:users,id',
            'ketua_nis' => 'nullable|exists:students,nis',
            'sekretaris_nis' => 'nullable|exists:students,nis',
            'ketua_password' => 'nullable|string|min:6',
            'sekretaris_password' => 'nullable|string|min:6',
        ]);

        // Simpan NIS lama untuk pembersihan role
        $oldKetuaNis = $classroom->ketua_nis;
        $oldSekretarisNis = $classroom->sekretaris_nis;

        // 1. Update data utama ke tabel classrooms
        $classroom->update([
            'name' => $validated['name'],
            'tingkat' => $validated['tingkat'],
            'user_id' => $validated['user_id'],
            'ketua_nis' => $validated['ketua_nis'] ?? null,
            'sekretaris_nis' => $validated['sekretaris_nis'] ?? null,
        ]);

        // 2. Logic Akun Ketua Kelas
        if (!empty($validated['ketua_nis'])) {
            $ketuaNis = $validated['ketua_nis'];
            $ketuaUser = User::where('nis', $ketuaNis)->first();

            // Default password diset menjadi 'password'
            $pwdKetua = $request->filled('ketua_password') ? $request->ketua_password : 'password';

            if (!$ketuaUser) {
                // Create user baru jika belum ada
                $student = Student::where('nis', $ketuaNis)->first();
                User::create([
                    'name' => $student->name,
                    'nis' => $ketuaNis,
                    'email' => $ketuaNis . '@student.com',
                    'password' => Hash::make($pwdKetua),
                    'role' => 'ketua_kelas',
                ]);
            } else {
                // Update role jika user sudah ada
                $updateData = ['role' => 'ketua_kelas'];
                if ($request->filled('ketua_password')) {
                    $updateData['password'] = Hash::make($request->ketua_password);
                }
                $ketuaUser->update($updateData);
            }
        }

        // 3. Logic Akun Sekretaris
        if (!empty($validated['sekretaris_nis'])) {
            $sekretarisNis = $validated['sekretaris_nis'];
            $sekretarisUser = User::where('nis', $sekretarisNis)->first();

            // Default password diset menjadi 'password'
            $pwdSekretaris = $request->filled('sekretaris_password') ? $request->sekretaris_password : 'password';

            if (!$sekretarisUser) {
                // Create user baru jika belum ada
                $student = Student::where('nis', $sekretarisNis)->first();
                User::create([
                    'name' => $student->name,
                    'nis' => $sekretarisNis,
                    'email' => $sekretarisNis . '@student.com',
                    'password' => Hash::make($pwdSekretaris),
                    'role' => 'sekretaris',
                ]);
            } else {
                // Update role jika user sudah ada
                $updateData = ['role' => 'sekretaris'];
                if ($request->filled('sekretaris_password')) {
                    $updateData['password'] = Hash::make($request->sekretaris_password);
                }
                $sekretarisUser->update($updateData);
            }
        }

        // 4. Pembersihan Role (Reset ke 'siswa' jika jabatan dicabut)
        if ($oldKetuaNis && $oldKetuaNis != ($validated['ketua_nis'] ?? null)) {
            User::where('nis', $oldKetuaNis)->update(['role' => 'siswa']);
        }

        if ($oldSekretarisNis && $oldSekretarisNis != ($validated['sekretaris_nis'] ?? null)) {
            User::where('nis', $oldSekretarisNis)->update(['role' => 'siswa']);
        }

        return redirect()->route('classrooms.show', $classroom->id)
            ->with('success', 'Kelas dan Pengurus berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return redirect()->route('classrooms.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }

    /**
     * Remove a student from the classroom.
     */
    public function removeStudent(Classroom $classroom, Student $student)
    {
        $student->update(['classroom_id' => null]);

        return back()->with('success', 'Siswa berhasil dikeluarkan dari kelas!');
    }

    /**
     * Add students to the classroom.
     */
    public function addStudents(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'student_nis' => 'required|array',
        ]);

        Student::whereIn('nis', $validated['student_nis'])
            ->update(['classroom_id' => $classroom->id]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke kelas!');
    }
}
