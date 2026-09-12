<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\LokasiPresensi;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Jabatan
        $daftarJabatan = ['Manager', 'Staff', 'Supervisor', 'Kepala Bagian'];
        foreach ($daftarJabatan as $namaJabatan) {
            Jabatan::firstOrCreate(['jabatan' => $namaJabatan]);
        }

        $jabatanManager = Jabatan::where('jabatan', 'Manager')->first();
        $jabatanStaff = Jabatan::where('jabatan', 'Staff')->first();

        // 2. Data Lokasi Presensi Default
        $lokasi = LokasiPresensi::firstOrCreate(
            ['nama_lokasi' => 'Kantor Pusat'],
            [
                'alamat_lokasi' => 'Jl. Jenderal Sudirman No. 1, Jakarta Pusat',
                'tipe_lokasi' => 'Pusat',
                'latitude' => '-6.2088',
                'longitude' => '106.8456',
                'radius' => 100, // 100 meter
                'zona_waktu' => 'WIB',
                'jam_masuk' => '08:00:00',
                'jam_pulang' => '17:00:00',
            ]
        );

        // 3. Pegawai & Akun Admin
        $pegawaiAdmin = Pegawai::updateOrCreate(
            ['nrg' => 'ADM001'],
            [
                'nama' => 'Administrator',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Admin Utama No. 1',
                'no_handphone' => '081234567890',
                'jabatan_id' => $jabatanManager?->id,
                'lokasi_presensi_id' => $lokasi->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'id_pegawai' => $pegawaiAdmin->id,
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'admin',
            ]
        );

        // 4. Pegawai & Akun Pegawai 1
        $pegawai1 = Pegawai::updateOrCreate(
            ['nrg' => 'PGW001'],
            [
                'nama' => 'Pegawai 1',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Pegawai No. 1',
                'no_handphone' => '089876543210',
                'jabatan_id' => $jabatanStaff?->id,
                'lokasi_presensi_id' => $lokasi->id,
            ]
        );

        User::updateOrCreate(
            ['username' => 'pegawai1'],
            [
                'id_pegawai' => $pegawai1->id,
                'password' => Hash::make('password'),
                'status' => 'aktif',
                'role' => 'pegawai',
            ]
        );
    }
}
