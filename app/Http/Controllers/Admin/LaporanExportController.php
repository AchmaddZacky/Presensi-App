<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LokasiPresensi;
use App\Models\Pegawai;
use App\Services\PresensiReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanExportController extends Controller
{
    public function __construct(protected PresensiReportService $reportService)
    {
    }

    public function exportPdf(Request $request)
    {
        $mode = $request->input('mode', 'mingguan');
        $idPegawai = $request->input('id_pegawai');
        $idLokasi = $request->input('id_lokasi');
        $search = $request->input('search');

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        if ($mode === 'mingguan') {
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

            $labelPeriode = "Minggu $mingguKe (" . Carbon::parse($start)->format('d/m/Y') . " s/d " . Carbon::parse($end)->format('d/m/Y') . ") - " . $namaBulan[$bulan] . " " . $tahun;
        } elseif ($mode === 'bulanan') {
            $bulan = (int) $request->input('bulan', now()->month);
            $tahun = (int) $request->input('tahun', now()->year);

            if ($request->filled('periode_bulan')) {
                try {
                    $parsed = Carbon::createFromFormat('Y-m', $request->input('periode_bulan'));
                    $bulan = $parsed->month;
                    $tahun = $parsed->year;
                } catch (\Exception $e) {}
            }

            if ($bulan < 1 || $bulan > 12) $bulan = (int) now()->month;
            if ($tahun < 2000 || $tahun > 2100) $tahun = (int) now()->year;

            $refDate = Carbon::createFromDate($tahun, $bulan, 1);
            $start = $refDate->copy()->startOfMonth()->toDateString();
            $end = $refDate->copy()->endOfMonth()->toDateString();
            $labelPeriode = "Bulan " . $refDate->locale('id')->translatedFormat('F Y') . " (" . Carbon::parse($start)->format('d/m/Y') . " - " . Carbon::parse($end)->format('d/m/Y') . ")";
        } else {
            $start = $request->input('start_date', now()->startOfMonth()->toDateString());
            $end = $request->input('end_date', now()->endOfMonth()->toDateString());
            $labelPeriode = Carbon::parse($start)->locale('id')->translatedFormat('d F Y') . " s/d " . Carbon::parse($end)->locale('id')->translatedFormat('d F Y');
        }

        $presensi = $this->reportService->getRekap($idPegawai, $start, $end, $idLokasi, $search);
        $pegawaiFilter = $idPegawai ? Pegawai::find($idPegawai) : null;
        $lokasiFilter = $idLokasi ? LokasiPresensi::find($idLokasi) : null;

        $pdf = Pdf::loadView('admin.presensi.pdf', compact('presensi', 'start', 'end', 'mode', 'labelPeriode', 'pegawaiFilter', 'lokasiFilter', 'search'));

        return $pdf->download('laporan-presensi-' . $mode . '-' . $start . '-sd-' . $end . '.pdf');
    }
}
