<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PegawaiRequest;
use App\Models\Pegawai;
use App\Models\Jabatan;
use App\Models\LokasiPresensi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['jabatan', 'lokasiPresensi'])->latest()->paginate(10);

        return view('admin.pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        $lokasis = LokasiPresensi::all();
        return view('admin.pegawai.create', compact('jabatans', 'lokasis'));
    }

    public function store(PegawaiRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('foto_pegawai', 'public');
        }

        $pegawai = Pegawai::create($data);

        // Buat akun user untuk pegawai dengan input dari form
        User::create([
            'id_pegawai' => $pegawai->id,
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'status' => 'aktif',
            'role' => 'pegawai',
        ]);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai beserta akun login berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        $jabatans = Jabatan::all();
        $lokasis = LokasiPresensi::all();
        return view('admin.pegawai.edit', compact('pegawai', 'jabatans', 'lokasis'));
    }

    public function update(PegawaiRequest $request, Pegawai $pegawai)
    {
        $data = $request->validated();

        if ($request->hasFile('foto')) {
            if ($pegawai->foto) {
                Storage::disk('public')->delete($pegawai->foto);
            }
            $data['foto'] = $request->file('foto')->store('foto_pegawai', 'public');
        }

        $pegawai->update($data);

        // Update username user, dan password jika diisi
        if ($pegawai->user) {
            $userData = ['username' => $data['username']];
            
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }
            
            $pegawai->user->update($userData);
        }

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai beserta akun login berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->foto) {
            Storage::disk('public')->delete($pegawai->foto);
        }

        $pegawai->delete();

        return back()->with('success', 'Data pegawai berhasil dihapus.');
    }
}
