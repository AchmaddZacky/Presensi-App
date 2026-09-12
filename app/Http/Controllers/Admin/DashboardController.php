<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use App\Models\Pegawai;
use App\Models\Presensi;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::count();
        $hadirHariIni = Presensi::where('tanggal_masuk', Carbon::today()->toDateString())->count();
        $pengajuanPending = Ketidakhadiran::where('status_pengajuan', 'pending')->count();

        return view('admin.dashboard', compact('totalPegawai', 'hadirHariIni', 'pengajuanPending'));
    }
}
