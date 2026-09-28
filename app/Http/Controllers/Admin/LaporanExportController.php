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
        $params = $this->resolveExportParams($request);
        extract($params);

        $presensi = $this->reportService->getRekap($idPegawai, $start, $end, $idLokasi, $search);
        $pegawaiFilter = $idPegawai ? Pegawai::find($idPegawai) : null;
        $lokasiFilter = $idLokasi ? LokasiPresensi::find($idLokasi) : null;

        $pdf = Pdf::loadView('admin.presensi.pdf', compact('presensi', 'start', 'end', 'mode', 'labelPeriode', 'pegawaiFilter', 'lokasiFilter', 'search'));

        return $pdf->download('laporan-presensi-' . $mode . '-' . $start . '-sd-' . $end . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $params = $this->resolveExportParams($request);
        extract($params);

        $presensi = $this->reportService->getRekap($idPegawai, $start, $end, $idLokasi, $search);
        $filename = 'laporan-presensi-' . $mode . '-' . $start . '-sd-' . $end . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->streamDownload(function () use ($presensi) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Header Row
            fputcsv($handle, [
                'No',
                'Nama Pegawai',
                'NRG',
                'Jabatan',
                'Kantor',
                'Tanggal Presensi',
                'Jam Masuk',
                'Jarak Masuk (meter)',
                'Jam Pulang',
                'Jarak Pulang (meter)',
                'Status Kehadiran',
            ]);

            // Data Rows
            foreach ($presensi as $index => $item) {
                $status = !empty($item->jam_keluar) ? 'Lengkap' : 'Masuk Saja';
                fputcsv($handle, [
                    $index + 1,
                    $item->pegawai->nama ?? '-',
                    $item->pegawai->nrg ?? '-',
                    $item->pegawai->jabatan->nama_jabatan ?? '-',
                    $item->pegawai->lokasiPresensi->nama_lokasi ?? '-',
                    $item->tanggal_masuk ? $item->tanggal_masuk->format('d-m-Y') : '-',
                    $item->jam_masuk ?? '-',
                    $item->jarak_masuk !== null ? $item->jarak_masuk : '-',
                    $item->jam_keluar ?? '-',
                    $item->jarak_keluar !== null ? $item->jarak_keluar : '-',
                    $status,
                ]);
            }

            fclose($handle);
        }, $filename, $headers);
    }

    protected function resolveExportParams(Request $request): array
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

        if ($mode === 'harian') {
            if ($request->filled('date')) {
                try {
                    $parsed = Carbon::parse($request->input('date'));
                    $tahun = $parsed->year;
                    $bulan = $parsed->month;
                    $tanggal = $parsed->day;
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

            $dateObj = Carbon::createFromDate($tahun, $bulan, $tanggal);
            $start = $dateObj->toDateString();
            $end = $start;

            $labelPeriode = "Harian - " . $dateObj->locale('id')->translatedFormat('l, d F Y');
        } elseif ($mode === 'mingguan') {
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

        return compact('mode', 'idPegawai', 'idLokasi', 'search', 'start', 'end', 'labelPeriode');
    }
}
