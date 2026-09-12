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

        // 2. Data Lokasi Presensi (Kantor 1 & Kantor 2)
        $lokasi1 = LokasiPresensi::updateOrCreate(
            ['id' => 1],
            [
                'nama_lokasi' => 'Kantor 1',
                'alamat_lokasi' => 'Jalan Raya Cinangka No.88, Kelurahan Kedaung, Kecamatan Sawangan, Kota Depok',
                'tipe_lokasi' => 'TI',
                'latitude' => '-6.370839',
                'longitude' => '106.746012',
                'radius' => 300,
                'zona_waktu' => 'WIB',
                'jam_masuk' => '07:30:00',
                'jam_pulang' => '14:00:00',
            ]
        );

        $lokasi2 = LokasiPresensi::updateOrCreate(
            ['id' => 2],
            [
                'nama_lokasi' => 'Kantor 2',
                'alamat_lokasi' => 'JQP4+6JQ Cinangka, Kota Depok, Jawa Barat',
                'tipe_lokasi' => 'Perumahan',
                'latitude' => '-6.364722',
                'longitude' => '106.756638',
                'radius' => 500,
                'zona_waktu' => 'WIB',
                'jam_masuk' => '20:55:00',
                'jam_pulang' => '23:00:00',
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
                'lokasi_presensi_id' => $lokasi1->id,
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
                'lokasi_presensi_id' => $lokasi1->id,
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
