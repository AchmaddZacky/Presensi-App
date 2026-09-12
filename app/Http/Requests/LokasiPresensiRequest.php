<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LokasiPresensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lokasi' => ['required', 'string', 'max:50'],
            'alamat_lokasi' => ['required', 'string', 'max:225'],
            'tipe_lokasi' => ['nullable', 'string', 'max:50'],
            'latitude' => ['required', 'string', 'max:50'],
            'longitude' => ['required', 'string', 'max:50'],
            'radius' => ['required', 'integer', 'min:1'],
            'zona_waktu' => ['required', 'string', 'max:4'],
            'jam_masuk' => ['required', 'date_format:H:i'],
            'jam_pulang' => ['required', 'date_format:H:i', 'after:jam_masuk'],
        ];
    }
}
