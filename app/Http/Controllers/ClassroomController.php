<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $request->validate([
            'tingkat_kelas' => 'required|in:X,XI,XII',
            'rombel'        => 'required|string|max:50',
            'wali_kelas_id' => 'required|exists:users,id',
        ]);

        // Gabungkan tingkat + rombel menjadi nama kelas
        $name = $request->tingkat_kelas . '-' . $request->rombel;

        // Pastikan nama kelas unik
        if (Classroom::where('name', $name)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['rombel' => 'Kelas ' . $name . ' sudah ada.']);
        }

        // Ambil academic_year yang aktif
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();

        Classroom::create([
            'name'             => $name,
            'tingkat'          => $request->tingkat_kelas,
            'academic_year_id' => $activeAcademicYear->id,
            'wali_kelas_id'    => $request->wali_kelas_id,
        ]);

        return redirect()->route('classrooms.index')
            ->with('success', 'Kelas ' . $name . ' berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Classroom $classroom)
    {
        // Load relasi students biar bisa milih ketua/sekretaris (urut abjad)
        $classroom->load([
            'students' => function ($query) {
                $query->orderBy('name', 'asc');
            },
            'user',
        ]);
        
        // Ambil data guru buat dropdown wali kelas
        $users = User::where('role', 'wali_kelas')->orderBy('name', 'asc')->get();

        // Ambil siswa pada kelas ini untuk kandidat ketua/sekretaris
        $classroomStudents = Student::where('classroom_id', $classroom->id)
            ->orderBy('name', 'asc')
            ->get();

        $currentOfficerNis = User::whereIn('id', array_filter([$classroom->ketua_id, $classroom->sekretaris_id]))
            ->pluck('nis', 'id');

        $currentOfficerStudentIds = Student::whereIn('nis', $currentOfficerNis->filter()->values())
            ->pluck('id', 'nis');

        $selectedKetuaId = old('ketua_id', isset($currentOfficerNis[$classroom->ketua_id]) ? ($currentOfficerStudentIds[$currentOfficerNis[$classroom->ketua_id]] ?? null) : null);
        $selectedSekretarisId = old('sekretaris_id', isset($currentOfficerNis[$classroom->sekretaris_id]) ? ($currentOfficerStudentIds[$currentOfficerNis[$classroom->sekretaris_id]] ?? null) : null);
        
        // Ambil siswa yang belum punya kelas untuk modal tambah murid
        $availableStudents = \App\Models\Student::whereNull('classroom_id')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('admin.classrooms.show', compact(
            'classroom',
            'users',
            'availableStudents',
            'classroomStudents',
            'selectedKetuaId',
            'selectedSekretarisId'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        $validated = $request->validate([
            'tingkat_kelas'  => 'required|in:X,XI,XII',
            'rombel'         => 'required|integer|min:1|max:10',
            'wali_kelas_id'  => 'required|exists:users,id',
            'ketua_id'       => 'nullable|exists:students,id|different:sekretaris_id',
            'sekretaris_id'  => 'nullable|exists:students,id|different:ketua_id',
            'ketua_set_manual_password'      => 'nullable|boolean',
            'sekretaris_set_manual_password' => 'nullable|boolean',
            'ketua_password'      => 'nullable|string|min:6',
            'sekretaris_password' => 'nullable|string|min:6',
        ]);

        // Gabungkan tingkat + rombel menjadi nama kelas
        $name = $request->tingkat_kelas . '-' . $request->rombel;

        // Cek uniqueness nama kelas (kecuali kelas ini sendiri)
        if (Classroom::where('name', $name)->where('id', '!=', $classroom->id)->exists()) {
            return back()->withErrors(['rombel' => 'Kelas "' . $name . '" sudah ada.'])->withInput();
        }

        // Simpan ID lama untuk pembersihan role
        $oldKetuaId      = $classroom->ketua_id;
        $oldSekretarisId = $classroom->sekretaris_id;

        $requestedOfficerIds = array_filter([
            $validated['ketua_id'] ?? null,
            $validated['sekretaris_id'] ?? null,
        ]);

        $studentsById = Student::where('classroom_id', $classroom->id)
            ->whereIn('id', $requestedOfficerIds)
            ->get()
            ->keyBy('id');

        if (!empty($validated['ketua_id']) && !$studentsById->has((int) $validated['ketua_id'])) {
            return back()->withErrors(['ketua_id' => 'Ketua harus dipilih dari siswa di kelas ini.'])->withInput();
        }

        if (!empty($validated['sekretaris_id']) && !$studentsById->has((int) $validated['sekretaris_id'])) {
            return back()->withErrors(['sekretaris_id' => 'Sekretaris harus dipilih dari siswa di kelas ini.'])->withInput();
        }

        DB::transaction(function () use ($classroom, $request, $validated, $name, $studentsById, $oldKetuaId, $oldSekretarisId) {
            $ketuaUserId = null;
            $sekretarisUserId = null;

            if (!empty($validated['ketua_id'])) {
                $ketuaStudent = $studentsById->get((int) $validated['ketua_id']);
                $ketuaUser = $this->upsertOfficerUser(
                    $ketuaStudent,
                    'ketua_kelas',
                    $request->boolean('ketua_set_manual_password'),
                    $request->input('ketua_password')
                );
                $ketuaUserId = $ketuaUser->id;
            }

            if (!empty($validated['sekretaris_id'])) {
                $sekretarisStudent = $studentsById->get((int) $validated['sekretaris_id']);
                $sekretarisUser = $this->upsertOfficerUser(
                    $sekretarisStudent,
                    'sekretaris',
                    $request->boolean('sekretaris_set_manual_password'),
                    $request->input('sekretaris_password')
                );
                $sekretarisUserId = $sekretarisUser->id;
            }

            $classroom->update([
                'name'          => $name,
                'tingkat'       => $validated['tingkat_kelas'],
                'wali_kelas_id' => $validated['wali_kelas_id'],
                'ketua_id'      => $ketuaUserId,
                'sekretaris_id' => $sekretarisUserId,
            ]);

            if ($oldKetuaId && $oldKetuaId !== $ketuaUserId) {
                $this->resetOfficerRoleIfUnused($oldKetuaId);
            }

            if ($oldSekretarisId && $oldSekretarisId !== $sekretarisUserId) {
                $this->resetOfficerRoleIfUnused($oldSekretarisId);
            }
        });

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
            'student_id' => 'required|array',
        ]);

        Student::whereIn('id', $validated['student_id'])
            ->update(['classroom_id' => $classroom->id]);

        return back()->with('success', 'Siswa berhasil ditambahkan ke kelas!');
    }

    private function upsertOfficerUser(Student $student, string $role, bool $useManualPassword, ?string $manualPassword): User
    {
        $password = ($useManualPassword && !empty($manualPassword))
            ? Hash::make($manualPassword)
            : Hash::make('password');

        $user = User::where('nis', $student->nis)->first();

        if (!$user) {
            return User::create([
                'name'     => $student->name,
                'email'    => $this->buildUniqueStudentEmail($student->nis),
                'nis'      => $student->nis,
                'role'     => $role,
                'password' => $password,
            ]);
        }

        $updateData = [
            'name'     => $student->name,
            'role'     => $role,
            'password' => $password,
        ];

        if (empty($user->email)) {
            $updateData['email'] = $this->buildUniqueStudentEmail($student->nis, $user->id);
        }

        $user->update($updateData);

        return $user;
    }

    private function buildUniqueStudentEmail(string $nis, ?int $ignoreUserId = null): string
    {
        $baseEmail = $nis . '@student.sma.id';
        $candidate = $baseEmail;
        $counter = 1;

        while (User::query()
            ->where('email', $candidate)
            ->when($ignoreUserId, fn ($query) => $query->where('id', '!=', $ignoreUserId))
            ->exists()) {
            $candidate = $nis . '+' . $counter . '@student.sma.id';
            $counter++;
        }

        return $candidate;
    }

    private function resetOfficerRoleIfUnused(int $userId): void
    {
        $stillUsedAsOfficer = Classroom::where('ketua_id', $userId)
            ->orWhere('sekretaris_id', $userId)
            ->exists();

        if ($stillUsedAsOfficer) {
            return;
        }

        $user = User::find($userId);

        if ($user && in_array($user->role, ['ketua_kelas', 'sekretaris', 'siswa'], true)) {
            $user->update(['role' => 'siswa']);
        }
    }
}
