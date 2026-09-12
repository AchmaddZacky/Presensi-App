<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = ['Manager', 'Staff', 'Supervisor', 'Kepala Bagian'];

        foreach ($data as $jabatan) {
            Jabatan::firstOrCreate(['jabatan' => $jabatan]);
        }
    }
}
