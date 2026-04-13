<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'hero_kicker',     'value' => 'SISTEM INFORMASI MANAJEMEN ABSENSI'],
            ['key' => 'hero_headline',   'value' => 'Sistem Pengelolaan Presensi Siswa'],
            ['key' => 'hero_subheadline','value' => 'Kelola presensi siswa SMA dengan mudah, cepat, dan akurat dalam satu platform terintegrasi.'],
            ['key' => 'footer_desc',     'value' => 'Menghadirkan transparansi dan akurasi dalam pengelolaan kehadiran siswa melalui teknologi editorial yang mutakhir.'],
            ['key' => 'footer_email',    'value' => 'info@simas.sch.id'],
            ['key' => 'footer_address',  'value' => 'Jepara, Indonesia'],
            ['key' => 'latitude_sekolah','value' => '-6.538249'],
            ['key' => 'longitude_sekolah','value' => '110.752525'],
            ['key' => 'radius_meter',    'value' => '50'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
