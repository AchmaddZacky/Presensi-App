<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $pegawai = Auth::user()->pegawai;
        if ($pegawai) {
            $pegawai->loadMissing(['jabatan', 'lokasiPresensi']);
        }

        $presensiHariIni = $pegawai ? Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal_masuk', now()->toDateString())
            ->first() : null;

        return view('pegawai.dashboard', compact('pegawai', 'presensiHariIni'));
    }
}
