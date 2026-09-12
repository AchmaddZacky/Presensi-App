<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pegawaiId = $this->route('pegawai')?->id;
        
        // Cari ID user yang terkait dengan pegawai ini (jika sedang update)
        $userId = $pegawaiId ? \App\Models\User::where('id_pegawai', $pegawaiId)->value('id') : null;

        return [
            'nrg' => ['required', 'string', 'max:50', 'unique:pegawai,nrg,' . $pegawaiId],
            'nama' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'alamat' => ['nullable', 'string', 'max:225'],
            'no_handphone' => ['nullable', 'string', 'max:20'],
            'jabatan_id' => ['nullable', 'exists:jabatan,id'],
            'lokasi_presensi_id' => ['nullable', 'exists:lokasi_presensi,id'],
            'foto' => ['nullable', 'image', 'max:2048'],
            // Data Akun Login
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $userId],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:6'],
        ];
    }
}
