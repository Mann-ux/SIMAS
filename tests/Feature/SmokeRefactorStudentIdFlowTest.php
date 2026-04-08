<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeRefactorStudentIdFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_student_without_changing_nis(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin-smoke@example.com',
        ]);

        $academicYear = AcademicYear::create([
            'name' => '2025/2026',
            'is_active' => true,
        ]);

        $classroom = Classroom::create([
            'name' => 'X-1',
            'tingkat' => 'X',
            'academic_year_id' => $academicYear->id,
            'wali_kelas_id' => null,
        ]);

        $student = Student::create([
            'nis' => 'SISWA-001',
            'name' => 'Nama Lama',
            'jenis_kelamin' => 'L',
            'classroom_id' => $classroom->id,
        ]);

        $response = $this->actingAs($admin)->put(route('students.update', $student->id), [
            'nis' => 'SISWA-001',
            'name' => 'Nama Baru',
            'jenis_kelamin' => 'L',
            'classroom_id' => $classroom->id,
        ]);

        $response->assertRedirect(route('students.index'));
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'nis' => 'SISWA-001',
            'name' => 'Nama Baru',
        ]);
    }

    public function test_admin_cannot_update_student_with_duplicate_nis(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin-dup@example.com',
        ]);

        $academicYear = AcademicYear::create([
            'name' => '2025/2026',
            'is_active' => true,
        ]);

        $classroom = Classroom::create([
            'name' => 'XI-1',
            'tingkat' => 'XI',
            'academic_year_id' => $academicYear->id,
            'wali_kelas_id' => null,
        ]);

        $studentA = Student::create([
            'nis' => 'SISWA-A',
            'name' => 'Siswa A',
            'jenis_kelamin' => 'L',
            'classroom_id' => $classroom->id,
        ]);

        $studentB = Student::create([
            'nis' => 'SISWA-B',
            'name' => 'Siswa B',
            'jenis_kelamin' => 'P',
            'classroom_id' => $classroom->id,
        ]);

        $response = $this->from(route('students.edit', $studentB->id))
            ->actingAs($admin)
            ->put(route('students.update', $studentB->id), [
                'nis' => $studentA->nis,
                'name' => 'Siswa B Updated',
                'jenis_kelamin' => 'P',
                'classroom_id' => $classroom->id,
            ]);

        $response->assertRedirect(route('students.edit', $studentB->id));
        $response->assertSessionHasErrors(['nis']);
    }

    public function test_pengurus_store_and_classroom_add_students_use_student_id(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin-classroom@example.com',
        ]);

        $sekretaris = User::factory()->create([
            'role' => 'sekretaris',
            'email' => 'sekretaris-smoke@example.com',
        ]);

        $academicYear = AcademicYear::create([
            'name' => '2025/2026',
            'is_active' => true,
        ]);

        $classroom = Classroom::create([
            'name' => 'XII-2',
            'tingkat' => 'XII',
            'academic_year_id' => $academicYear->id,
            'wali_kelas_id' => null,
            'sekretaris_id' => $sekretaris->id,
        ]);

        $student1 = Student::create([
            'nis' => 'SISWA-101',
            'name' => 'Siswa 101',
            'jenis_kelamin' => 'L',
            'classroom_id' => $classroom->id,
        ]);

        $student2 = Student::create([
            'nis' => 'SISWA-102',
            'name' => 'Siswa 102',
            'jenis_kelamin' => 'P',
            'classroom_id' => $classroom->id,
        ]);

        $this->actingAs($sekretaris)
            ->post(route('pengurus.absen.store'), [
                'attendances' => [
                    $student1->id => ['status' => 'Hadir'],
                    $student2->id => ['status' => 'Izin', 'keterangan' => 'Keperluan keluarga'],
                ],
            ])
            ->assertRedirect(route('pengurus.absen.create'));

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student1->id,
            'status' => 'Hadir',
            'academic_year_id' => $academicYear->id,
            'recorded_by_id' => $sekretaris->id,
        ]);

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student2->id,
            'status' => 'Izin',
            'keterangan' => 'Keperluan keluarga',
            'academic_year_id' => $academicYear->id,
            'recorded_by_id' => $sekretaris->id,
        ]);

        $newStudent = Student::create([
            'nis' => 'SISWA-200',
            'name' => 'Siswa Baru',
            'jenis_kelamin' => 'L',
            'classroom_id' => null,
        ]);

        $this->actingAs($admin)
            ->post(route('classrooms.add_students', $classroom->id), [
                'student_id' => [$newStudent->id],
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('students', [
            'id' => $newStudent->id,
            'classroom_id' => $classroom->id,
        ]);

        $this->assertGreaterThan(0, Attendance::count());
    }
}
