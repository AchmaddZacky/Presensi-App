<?php

namespace App\Services;

use App\Models\Presensi;
use Illuminate\Support\Carbon;

class PresensiReportService
{
    /**
     * Ambil rekap presensi pada rentang tanggal, opsional difilter per pegawai, lokasi kantor, dan pencarian kata kunci.
     */
    public function getRekap(?int $idPegawai, string $startDate, string $endDate, ?int $idLokasi = null, ?string $search = null)
    {
        return Presensi::with(['pegawai.lokasiPresensi', 'pegawai.jabatan'])
            ->when($idPegawai, fn ($q) => $q->where('id_pegawai', $idPegawai))
            ->when($idLokasi, fn ($q) => $q->whereHas('pegawai', fn ($pq) => $pq->where('lokasi_presensi_id', $idLokasi)))
            ->when($search, fn ($q) => $q->whereHas('pegawai', function ($pq) use ($search) {
                $pq->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('nrg', 'like', "%{$search}%");
                });
            }))
            ->whereBetween('tanggal_masuk', [$startDate, $endDate])
            ->orderBy('tanggal_masuk')
            ->get();
    }

    /**
     * Dapatkan daftar minggu dalam satu bulan tertentu (Minggu 1, Minggu 2, dst).
     */
    public function getWeeksInMonth(int $year, int $month): array
    {
        $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth()->startOfDay();

        $weeks = [];
        $weekNum = 1;
        $current = $startOfMonth->copy();

        while ($current->lte($endOfMonth)) {
            $weekStart = $current->copy();
            $weekEnd = $current->copy()->endOfWeek(Carbon::SUNDAY);
            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth->copy();
            }

            $weeks[$weekNum] = [
                'week' => $weekNum,
                'start' => $weekStart->toDateString(),
                'end' => $weekEnd->toDateString(),
                'label' => "Minggu $weekNum (" . $weekStart->format('d/m') . " - " . $weekEnd->format('d/m') . ")"
            ];

            $current = $current->copy()->endOfWeek(Carbon::SUNDAY)->addDay()->startOfDay();
            $weekNum++;
        }

        return $weeks;
    }
}
