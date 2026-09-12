<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $pegawai = Pegawai::create([
            'nrg' => 'ADM001',
            'nama' => 'Administrator',
            'jenis_kelamin' => 'Laki-laki',
            'jabatan' => 'Manager',
        ]);

        User::create([
            'id_pegawai' => $pegawai->id,
            'username' => 'admin',
            'password' => Hash::make('password'),
            'status' => 'aktif',
            'role' => 'admin',
        ]);
    }
}
