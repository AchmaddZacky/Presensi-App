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
        if ($request->input('mode') === 'harian') {
            return redirect()->route('admin.presensi.harian', $request->query());
        }
        if ($request->input('mode') === 'bulanan') {
            return redirect()->route('admin.presensi.bulanan', $request->query());
        }
        return redirect()->route('admin.presensi.mingguan', $request->query());
    }

    public function harian(Request $request)
    {
        $idPegawai = $request->input('id_pegawai');
        $idLokasi = $request->input('id_lokasi');
        $search = $request->input('search');

        if ($request->filled('date')) {
            try {
                $parsedDate = Carbon::parse($request->input('date'));
                $tahun = $parsedDate->year;
                $bulan = $parsedDate->month;
                $tanggal = $parsedDate->day;
            } catch (\Exception $e) {
                $tahun = (int) now()->year;
                $bulan = (int) now()->month;
                $tanggal = (int) now()->day;
            }
        } else {
            $tahun = (int) $request->input('tahun', now()->year);
            $bulan = (int) $request->input('bulan', now()->month);
            $tanggal = (int) $request->input('tanggal', now()->day);
        }

        if ($bulan < 1 || $bulan > 12) $bulan = (int) now()->month;
        if ($tahun < 2000 || $tahun > 2100) $tahun = (int) now()->year;

        $daysInMonth = Carbon::create($tahun, $bulan, 1)->daysInMonth;
        if ($tanggal < 1 || $tanggal > $daysInMonth) {
            $tanggal = min(max(1, $tanggal), $daysInMonth);
        }

        $selectedDateObj = Carbon::createFromDate($tahun, $bulan, $tanggal);
        $selectedDate = $selectedDateObj->toDateString();

        $presensi = $this->reportService->getRekap($idPegawai, $selectedDate, $selectedDate, $idLokasi, $search);
        $pegawaiList = Pegawai::select('id', 'nama', 'nrg', 'lokasi_presensi_id')
            ->when($idLokasi, fn ($q) => $q->where('lokasi_presensi_id', $idLokasi))
            ->orderBy('nama')
            ->get();
        $lokasiList = LokasiPresensi::select('id', 'nama_lokasi')->orderBy('nama_lokasi')->get();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Navigasi cepat H-1, Hari Ini, H+1
        $prevDate = $selectedDateObj->copy()->subDay();
        $nextDate = $selectedDateObj->copy()->addDay();
        $todayDate = now();

        // Statistik ringkas harian
        $totalHadir = $presensi->count();
        $totalLengkap = $presensi->filter(fn($p) => !empty($p->jam_keluar))->count();
        $totalBelumPulang = $presensi->filter(fn($p) => empty($p->jam_keluar))->count();

        return view('admin.presensi.harian', compact(
            'presensi',
            'tanggal',
            'bulan',
            'tahun',
            'daysInMonth',
            'selectedDate',
            'selectedDateObj',
            'namaBulan',
            'pegawaiList',
            'lokasiList',
            'idPegawai',
            'idLokasi',
            'search',
            'prevDate',
            'nextDate',
            'todayDate',
            'totalHadir',
            'totalLengkap',
            'totalBelumPulang'
        ));
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
