<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat 1 data AcademicYear
        $academicYear = AcademicYear::create([
            'name' => '2025/2026 Ganjil',
            'is_active' => true,
        ]);

        // 2. Buat 3 data User
        $admin = User::create([
            'name' => 'Admin TU',
            'email' => 'admin@smk.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $waliKelas = User::create([
            'name' => 'Bapak Wali',
            'email' => 'guru@smk.com',
            'nip' => '19900101',
            'password' => Hash::make('password'),
            'role' => 'wali_kelas',
        ]);

        $sekretaris = User::create([
            'name' => 'Sekretaris Budi',
            'email' => 'sekretaris@smk.com',
            'nis' => '1001',
            'password' => Hash::make('password'),
            'role' => 'sekretaris',
        ]);

        // 3. Buat 2 Guru Tambahan (Wali Kelas) dengan updateOrCreate
        $pakWahid = User::updateOrCreate(
            ['email' => 'wahidx2@sma.com'],
            [
                'name' => 'Pak Wahid',
                'password' => Hash::make('password'),
                'role' => 'wali_kelas',
                'nip' => '19850515',
            ]
        );

        $buSiti = User::updateOrCreate(
            ['email' => 'sitixi3@sma.com'],
            [
                'name' => 'Bu Siti',
                'password' => Hash::make('password'),
                'role' => 'wali_kelas',
                'nip' => '19880320',
            ]
        );

        // 4. Buat 3 data Classroom dengan updateOrCreate
        $classroom = Classroom::create([
            'name' => 'XII RPL 1',
            'user_id' => $waliKelas->id,
            'academic_year_id' => $academicYear->id,
        ]);

        $classroomX2 = Classroom::updateOrCreate(
            ['name' => 'X 2'],
            [
                'user_id' => $pakWahid->id,
                'academic_year_id' => $academicYear->id,
            ]
        );

        $classroomXI3 = Classroom::updateOrCreate(
            ['name' => 'XI 3'],
            [
                'user_id' => $buSiti->id,
                'academic_year_id' => $academicYear->id,
            ]
        );

        // 5. Buat 6 data Student untuk kelas XII RPL 1
        $students = [
            ['nis' => '1001', 'name' => 'Budi Santoso'],
            ['nis' => '1002', 'name' => 'Andi Hermawan'],
            ['nis' => '1003', 'name' => 'Siti Aminah'],
            ['nis' => '1004', 'name' => 'Joko Anwar'],
            ['nis' => '1005', 'name' => 'Rina Nose'],
            ['nis' => '1006', 'name' => 'Ahmad Dhani'],
        ];

        foreach ($students as $student) {
            Student::create([
                'nis' => $student['nis'],
                'name' => $student['name'],
                'classroom_id' => $classroom->id,
            ]);
        }

        // 6. Generate 5 siswa random untuk kelas X 2 dengan Faker
        $faker = fake('id_ID'); // Gunakan locale Indonesia
        
        for ($i = 0; $i < 5; $i++) {
            Student::create([
                'nis' => $faker->unique()->numerify('20###'), // NIS mulai 20xxx untuk kelas X
                'name' => $faker->name(),
                'classroom_id' => $classroomX2->id,
            ]);
        }

        // 7. Generate 5 siswa random untuk kelas XI 3 dengan Faker
        for ($i = 0; $i < 5; $i++) {
            Student::create([
                'nis' => $faker->unique()->numerify('21###'), // NIS mulai 21xxx untuk kelas XI
                'name' => $faker->name(),
                'classroom_id' => $classroomXI3->id,
            ]);
        }
    }
}
