<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KetidakhadiranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'keterangan' => ['required', 'in:izin,sakit,cuti'],
            'tanggal' => ['required', 'date'],
            'deskripsi' => ['nullable', 'string', 'max:225'],
            'file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ];
    }
}
