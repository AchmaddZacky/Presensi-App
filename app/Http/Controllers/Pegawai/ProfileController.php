<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman pengaturan profil pegawai.
     */
    public function index()
    {
        $user = Auth::user();
        $pegawai = $user->pegawai()->with(['jabatan', 'lokasiPresensi'])->first();

        return view('pegawai.profile.index', compact('user', 'pegawai'));
    }

    /**
     * Perbarui data profil, username, foto, dan password pegawai.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan'],
            'no_handphone' => ['nullable', 'string', 'max:25'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan oleh pengguna lain.',
            'username.alpha_dash' => 'Username hanya boleh berupa huruf, angka, strip (-), dan garis bawah (_).',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'foto.max' => 'Ukuran file foto profil maksimal 2 MB.',
            'foto.image' => 'File harus berupa gambar (JPG, PNG, atau WEBP).',
        ]);

        // Verifikasi dan update password jika diisi
        if ($request->filled('password')) {
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai dengan kata sandi lama Anda.'])->withInput();
                }
            }
            $user->password = Hash::make($request->password);
        }

        // Perbarui username
        $user->username = $validated['username'];
        $user->save();

        if ($pegawai) {
            $pegawai->nama = $validated['nama'];
            if (isset($validated['jenis_kelamin'])) {
                $pegawai->jenis_kelamin = $validated['jenis_kelamin'];
            }
            $pegawai->no_handphone = $validated['no_handphone'] ?? null;
            $pegawai->alamat = $validated['alamat'] ?? null;

            // Opsi hapus foto
            if ($request->boolean('hapus_foto') && $pegawai->foto) {
                if (Storage::disk('public')->exists($pegawai->foto)) {
                    Storage::disk('public')->delete($pegawai->foto);
                }
                $pegawai->foto = null;
            }

            // Upload foto baru jika ada
            if ($request->hasFile('foto')) {
                if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto)) {
                    Storage::disk('public')->delete($pegawai->foto);
                }
                $pegawai->foto = $request->file('foto')->store('foto_pegawai', 'public');
            }

            $pegawai->save();
        }

        return redirect()->route('pegawai.profile.index')->with('success', 'Profil Anda dan data akun berhasil diperbarui!');
    }
}
