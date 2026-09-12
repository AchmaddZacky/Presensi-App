<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiPresensi;
use App\Models\Pegawai;
use App\Models\Presensi;
use App\Services\PresensiReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresensiController extends Controller
{
    public function __construct(protected PresensiReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        if ($request->input('mode') === 'bulanan') {
            return redirect()->route('admin.presensi.bulanan', $request->query());
        }
        return redirect()->route('admin.presensi.mingguan', $request->query());
    }

    public function mingguan(Request $request)
    {
        $idPegawai = $request->input('id_pegawai');
        $idLokasi = $request->input('id_lokasi');
        $search = $request->input('search');

        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        if ($bulan < 1 || $bulan > 12) $bulan = (int) now()->month;
        if ($tahun < 2000 || $tahun > 2100) $tahun = (int) now()->year;

        $weeks = $this->reportService->getWeeksInMonth($tahun, $bulan);

        $today = now()->toDateString();
        $defaultWeek = 1;
        foreach ($weeks as $wNum => $wData) {
            if ($today >= $wData['start'] && $today <= $wData['end']) {
                $defaultWeek = $wNum;
                break;
            }
        }

        $mingguKe = (int) $request->input('minggu_ke', $defaultWeek);
        if (!isset($weeks[$mingguKe])) {
            $mingguKe = isset($weeks[$defaultWeek]) ? $defaultWeek : 1;
        }

        $start = $weeks[$mingguKe]['start'];
        $end = $weeks[$mingguKe]['end'];

        $presensi = $this->reportService->getRekap($idPegawai, $start, $end, $idLokasi, $search);
        $pegawaiList = Pegawai::select('id', 'nama', 'nrg', 'lokasi_presensi_id')->when($idLokasi, fn ($q) => $q->where('lokasi_presensi_id', $idLokasi))->orderBy('nama')->get();
        $lokasiList = LokasiPresensi::select('id', 'nama_lokasi')->orderBy('nama_lokasi')->get();

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('admin.presensi.mingguan', compact(
            'presensi',
            'start',
            'end',
            'mingguKe',
            'bulan',
            'tahun',
            'weeks',
            'namaBulan',
            'pegawaiList',
            'lokasiList',
            'idPegawai',
            'idLokasi',
            'search'
        ));
    }

    public function bulanan(Request $request)
    {
        $idPegawai = $request->input('id_pegawai');
        $idLokasi = $request->input('id_lokasi');
        $search = $request->input('search');

        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        if ($request->filled('periode_bulan')) {
            try {
                $parsed = Carbon::createFromFormat('Y-m', $request->input('periode_bulan'));
                $bulan = $parsed->month;
                $tahun = $parsed->year;
            } catch (\Exception $e) {}
        }

        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int) now()->month;
        }
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = (int) now()->year;
        }

        $refDate = Carbon::createFromDate($tahun, $bulan, 1);
        $start = $refDate->copy()->startOfMonth()->toDateString();
        $end = $refDate->copy()->endOfMonth()->toDateString();

        $presensi = $this->reportService->getRekap($idPegawai, $start, $end, $idLokasi, $search);
        $pegawaiList = Pegawai::select('id', 'nama', 'nrg', 'lokasi_presensi_id')->when($idLokasi, fn ($q) => $q->where('lokasi_presensi_id', $idLokasi))->orderBy('nama')->get();
        $lokasiList = LokasiPresensi::select('id', 'nama_lokasi')->orderBy('nama_lokasi')->get();

        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('admin.presensi.bulanan', compact(
            'presensi',
            'start',
            'end',
            'bulan',
            'tahun',
            'namaBulan',
            'pegawaiList',
            'lokasiList',
            'idPegawai',
            'idLokasi',
            'search'
        ));
    }
}
