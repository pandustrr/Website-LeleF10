<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'admin',
            'password' => 'admin123', // <-- SIMPAN SEBAGAI PLAIN TEXT
            'tempat_tanggal_lahir' => 'Jakarta, 01-01-1990',
            'alamat' => 'Jl. Admin No. 1',
            'nomor_telepon' => '081234567890',
            'role' => 'admin',
        ]);

        User::create([
            'username' => 'karyawan',
            'password' => 'karyawan123', // <-- SIMPAN SEBAGAI PLAIN TEXT
            'tempat_tanggal_lahir' => 'Bandung, 02-02-1995',
            'alamat' => 'Jl. Karyawan No. 2',
            'nomor_telepon' => '082345678901',
            'role' => 'karyawan',
        ]);
    }
}
