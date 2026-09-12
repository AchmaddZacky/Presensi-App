<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayat = Presensi::where('id_pegawai', Auth::user()->pegawai->id)
            ->orderByDesc('tanggal_masuk')
            ->paginate(15);

        return view('pegawai.riwayat.index', compact('riwayat'));
    }
}
