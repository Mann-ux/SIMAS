<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $email = trim((string) ($row['email'] ?? ''));
        $namaLengkap = trim((string) ($row['nama_lengkap'] ?? ''));
        $nip = trim((string) ($row['nip'] ?? ''));
        $passwordExcel = trim((string) ($row['password'] ?? ''));

        // Skip baris tanpa data minimum yang dibutuhkan.
        if ($email === '' || $namaLengkap === '') {
            return null;
        }

        if ($passwordExcel !== '') {
            $plainPassword = $passwordExcel;
        } else {
            // Ambil kata pertama dari nama lengkap sebagai awalan password.
            $namaDepan = explode(' ', preg_replace('/\s+/', ' ', $namaLengkap))[0] ?? 'User';
            $namaDepan = trim($namaDepan) !== '' ? trim($namaDepan) : 'User';

            // Ambil 4 karakter terakhir NIP jika tersedia dan panjangnya >= 4.
            $suffix = strlen($nip) >= 4 ? substr($nip, -4) : '123';

            $plainPassword = $namaDepan . $suffix;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'nip' => $nip !== '' ? $nip : null,
                'name' => $namaLengkap,
                'role' => 'wali_kelas',
                'password' => Hash::make($plainPassword),
            ]
        );

        return null;
    }
}
