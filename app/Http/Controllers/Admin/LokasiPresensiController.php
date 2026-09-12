<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LokasiPresensiRequest;
use App\Models\LokasiPresensi;

class LokasiPresensiController extends Controller
{
    public function index()
    {
        $lokasi = LokasiPresensi::latest()->paginate(10);

        return view('admin.lokasi-presensi.index', compact('lokasi'));
    }

    public function create()
    {
        return view('admin.lokasi-presensi.create');
    }

    public function store(LokasiPresensiRequest $request)
    {
        LokasiPresensi::create($request->validated());

        return redirect()->route('admin.lokasi-presensi.index')->with('success', 'Lokasi presensi berhasil ditambahkan.');
    }

    public function edit(LokasiPresensi $lokasiPresensi)
    {
        return view('admin.lokasi-presensi.edit', ['lokasi' => $lokasiPresensi]);
    }

    public function update(LokasiPresensiRequest $request, LokasiPresensi $lokasiPresensi)
    {
        $lokasiPresensi->update($request->validated());

        return redirect()->route('admin.lokasi-presensi.index')->with('success', 'Lokasi presensi berhasil diperbarui.');
    }

    public function destroy(LokasiPresensi $lokasiPresensi)
    {
        $lokasiPresensi->delete();

        return back()->with('success', 'Lokasi presensi berhasil dihapus.');
    }
}
