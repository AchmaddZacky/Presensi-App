<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PresensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'foto' => ['nullable', 'image', 'max:5120'], // Boleh kosong jika pakai webcam
            'foto_base64' => ['nullable', 'string'], // Boleh kosong jika upload file biasa
        ];
    }
}
