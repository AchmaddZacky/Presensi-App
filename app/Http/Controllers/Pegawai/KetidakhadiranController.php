<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\KetidakhadiranRequest;
use App\Models\Ketidakhadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KetidakhadiranController extends Controller
{
    public function create()
    {
        return view('pegawai.ketidakhadiran.create');
    }

    public function store(KetidakhadiranRequest $request)
    {
        $data = $request->validated();
        $data['id_pegawai'] = Auth::user()->pegawai->id;
        $data['status_pengajuan'] = 'pending';

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('file_ketidakhadiran', 'public');
        }

        Ketidakhadiran::create($data);

        return redirect()->route('pegawai.ketidakhadiran.riwayat')->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function riwayat(Request $request)
    {
        $pegawai = Auth::user()->pegawai;
        $pegawaiId = $pegawai ? $pegawai->id : 0;

        // Metric widget counts for this pegawai (single optimized query)
        $statusCounts = Ketidakhadiran::where('id_pegawai', $pegawaiId)
            ->selectRaw('status_pengajuan, count(*) as count')
            ->groupBy('status_pengajuan')
            ->pluck('count', 'status_pengajuan');

        $countPending = $statusCounts['pending'] ?? 0;
        $countApproved = $statusCounts['approved'] ?? 0;
        $countRejected = $statusCounts['rejected'] ?? 0;

        $query = Ketidakhadiran::where('id_pegawai', $pegawaiId)->with('pegawai');

        $status = $request->query('status');
        if (!empty($status) && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status_pengajuan', $status);
        }

        $search = $request->query('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->latest('tanggal')->latest('id')->paginate(10)->withQueryString();

        return view('pegawai.ketidakhadiran.riwayat', compact(
            'pengajuan',
            'countPending',
            'countApproved',
            'countRejected',
            'status',
            'search'
        ));
    }

    public function download(Ketidakhadiran $ketidakhadiran)
    {
        $pegawai = Auth::user()->pegawai;
        if (!$pegawai || $ketidakhadiran->id_pegawai !== $pegawai->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengunduh lampiran ini.');
        }

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
