<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ketidakhadiran;
use Illuminate\Http\Request;

class KetidakhadiranController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $search = $request->input('search');

        $statusCounts = Ketidakhadiran::selectRaw('status_pengajuan, count(*) as count')
            ->groupBy('status_pengajuan')
            ->pluck('count', 'status_pengajuan');

        $countPending = $statusCounts['pending'] ?? 0;
        $countApproved = $statusCounts['approved'] ?? 0;
        $countRejected = $statusCounts['rejected'] ?? 0;

        $pengajuan = Ketidakhadiran::with('pegawai')
            ->when($status, fn ($q) => $q->where('status_pengajuan', $status))
            ->when($search, fn ($q) => $q->where(function ($sub) use ($search) {
                $sub->where('deskripsi', 'like', "%{$search}%")
                    ->orWhere('keterangan', 'like', "%{$search}%")
                    ->orWhereHas('pegawai', function ($pq) use ($search) {
                        $pq->where('nama', 'like', "%{$search}%")
                           ->orWhere('nrg', 'like', "%{$search}%");
                    });
            }))
            ->latest('tanggal')
            ->paginate(10)
            ->withQueryString();

        return view('admin.ketidakhadiran.index', compact(
            'pengajuan',
            'countPending',
            'countApproved',
            'countRejected',
            'status',
            'search'
        ));
    }

    public function approve(Ketidakhadiran $ketidakhadiran)
    {
        $ketidakhadiran->update(['status_pengajuan' => 'approved']);

        return back()->with('success', 'Pengajuan disetujui.');
    }

    public function reject(Ketidakhadiran $ketidakhadiran)
    {
        $ketidakhadiran->update(['status_pengajuan' => 'rejected']);

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function download(Ketidakhadiran $ketidakhadiran)
    {
        if (!$ketidakhadiran->file) {
            abort(404, 'File lampiran tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $ketidakhadiran->file);
        if (!file_exists($filePath)) {
            abort(404, 'File fisik lampiran tidak ditemukan di penyimpanan server.');
        }

        $filename = $ketidakhadiran->download_filename;

        return response()->download($filePath, $filename);
    }
}
