<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // ─────────────────────────────────────────
        // 1. Buat AcademicYear aktif
        // ─────────────────────────────────────────
        $academicYear = AcademicYear::create([
            'name'      => '2025/2026 Ganjil',
            'is_active' => true,
        ]);

        // ─────────────────────────────────────────
        // 2. Buat User Admin
        // ─────────────────────────────────────────
        User::create([
            'nip'      => '199001012015011001',
            'name'     => 'Admin Sekolah',
            'email'    => 'admin@sma.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // ─────────────────────────────────────────
        // 3. Buat User Guru (Wali Kelas)
        // ─────────────────────────────────────────
        $guru = User::create([
            'nip'      => '197512152006041001',
            'name'     => 'Budi Santoso, S.Pd',
            'email'    => 'budi.santoso@sma.sch.id',
            'password' => Hash::make('password'),
            'role'     => 'wali_kelas',
        ]);

        // ─────────────────────────────────────────
        // 4. Buat 5 data Student menggunakan Faker
        // ─────────────────────────────────────────
        $students = [];
        for ($i = 1; $i <= 5; $i++) {
            $nis = '20240' . str_pad($i, 5, '0', STR_PAD_LEFT);
            $students[] = Student::create([
                'nis'  => $nis,
                'name' => $faker->name('male'),
                // classroom_id diisi nanti setelah Classroom dibuat
            ]);
        }

        // ─────────────────────────────────────────
        // 5. Buat User Ketua (dari Student ke-1)
        // ─────────────────────────────────────────
        $studentKetua = $students[0];
        $userKetua = User::create([
            'nip'      => $studentKetua->nis, // nis dipakai sebagai identifier
            'nis'      => $studentKetua->nis,
            'name'     => $studentKetua->name,
            'email'    => $studentKetua->nis . '@student.sma.id',
            'password' => Hash::make('password'),
            'role'     => 'ketua_kelas',
        ]);

        // ─────────────────────────────────────────
        // 6. Buat User Sekretaris (dari Student ke-2)
        // ─────────────────────────────────────────
        $studentSekretaris = $students[1];
        $userSekretaris = User::create([
            'nip'      => $studentSekretaris->nis,
            'nis'      => $studentSekretaris->nis,
            'name'     => $studentSekretaris->name,
            'email'    => $studentSekretaris->nis . '@student.sma.id',
            'password' => Hash::make('password'),
            'role'     => 'sekretaris',
        ]);

        // ─────────────────────────────────────────
        // 7. Buat Classroom
        // ─────────────────────────────────────────
        $classroom = Classroom::create([
            'name'             => 'X-1',
            'tingkat'          => 'X',
            'academic_year_id' => $academicYear->id,
            'wali_kelas_id'    => $guru->id,
            'ketua_id'         => $userKetua->id,
            'sekretaris_id'    => $userSekretaris->id,
        ]);

        // ─────────────────────────────────────────
        // 8. Update semua Student agar classroom_id terisi
        // ─────────────────────────────────────────
        foreach ($students as $student) {
            $student->update(['classroom_id' => $classroom->id]);
        }
    }
}