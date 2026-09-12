<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Http\Requests\PresensiRequest;
use App\Models\LokasiPresensi;
use App\Models\Presensi;
use App\Services\GeofencingService;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    public function __construct(protected GeofencingService $geofencingService)
    {
    }

    public function smartRedirect()
    {
        $pegawai = Auth::user()->pegawai;
        $presensiHariIni = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal_masuk', now()->toDateString())
            ->first();

        if (!$presensiHariIni) {
            return redirect()->route('pegawai.presensi.clock-in-form');
        }

        if (!$presensiHariIni->jam_keluar) {
            return redirect()->route('pegawai.presensi.clock-out-form');
        }

        return redirect()->route('pegawai.dashboard')->with('info', 'Presensi hari ini sudah lengkap (Masuk: ' . $presensiHariIni->jam_masuk . ', Pulang: ' . $presensiHariIni->jam_keluar . ').');
    }

    public function clockInForm()
    {
        $pegawai = Auth::user()->pegawai;
        $presensiHariIni = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal_masuk', now()->toDateString())
            ->first();

        if ($presensiHariIni) {
            if (!$presensiHariIni->jam_keluar) {
                return redirect()->route('pegawai.presensi.clock-out-form');
            }
            return redirect()->route('pegawai.dashboard')->with('info', 'Anda sudah menyelesaikan presensi masuk dan pulang hari ini.');
        }

        return view('pegawai.presensi.clock-in');
    }

    public function clockIn(PresensiRequest $request)
    {
        $pegawai = Auth::user()->pegawai;
        $lokasi = $pegawai->lokasiPresensi;

        $presensiHariIni = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal_masuk', now()->toDateString())
            ->first();

        if ($presensiHariIni) {
            return redirect()->route('pegawai.dashboard')->with('error', 'Anda sudah melakukan presensi masuk hari ini.');
        }

        if (!$lokasi) {
            return back()->withErrors(['lokasi' => 'Lokasi presensi belum diatur untuk akun Anda.']);
        }

        $jarak = $this->geofencingService->distanceInMeters(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $lokasi->latitude,
            (float) $lokasi->longitude
        );

        if ($jarak > $lokasi->radius) {
            return redirect()->route('pegawai.dashboard')->with('error', "Gagal Presensi Masuk: Anda berada di luar radius lokasi kantor. (Jarak Anda: " . round($jarak) . " meter, Radius: {$lokasi->radius} meter)");
        }

        if ($request->has('foto_base64') && !empty($request->foto_base64)) {
            $image_parts = explode(";base64,", $request->foto_base64);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_presensi/' . uniqid() . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
            $fotoPath = $fileName;
        } else {
            $fotoPath = $request->file('foto')->store('foto_presensi', 'public');
        }

        Presensi::create([
            'id_pegawai' => $pegawai->id,
            'tanggal_masuk' => now()->toDateString(),
            'jam_masuk' => now()->toTimeString(),
            'foto_masuk' => $fotoPath,
            'jarak_masuk' => round($jarak),
        ]);

        return redirect()->route('pegawai.dashboard')->with('success', 'Presensi masuk berhasil.');
    }

    public function clockOutForm()
    {
        $pegawai = Auth::user()->pegawai;
        $presensiHariIni = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal_masuk', now()->toDateString())
            ->first();

        if (!$presensiHariIni) {
            return redirect()->route('pegawai.presensi.clock-in-form')->with('warning', 'Silakan lakukan presensi masuk terlebih dahulu.');
        }

        if ($presensiHariIni->jam_keluar) {
            return redirect()->route('pegawai.dashboard')->with('info', 'Anda sudah melakukan presensi pulang hari ini.');
        }

        return view('pegawai.presensi.clock-out');
    }

    public function clockOut(PresensiRequest $request)
    {
        $pegawai = Auth::user()->pegawai;
        $lokasi = $pegawai->lokasiPresensi;

        $presensiHariIni = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal_masuk', now()->toDateString())
            ->first();

        if (!$presensiHariIni || $presensiHariIni->jam_keluar) {
            return redirect()->route('pegawai.dashboard')->with('error', 'Presensi pulang tidak valid atau sudah dilakukan.');
        }

        if (!$lokasi) {
            return back()->withErrors(['lokasi' => 'Lokasi presensi belum diatur untuk akun Anda.']);
        }

        $jarak = $this->geofencingService->distanceInMeters(
            (float) $request->latitude,
            (float) $request->longitude,
            (float) $lokasi->latitude,
            (float) $lokasi->longitude
        );

        if ($jarak > $lokasi->radius) {
            return redirect()->route('pegawai.dashboard')->with('error', "Gagal Presensi Pulang: Anda berada di luar radius lokasi kantor. (Jarak Anda: " . round($jarak) . " meter, Radius: {$lokasi->radius} meter)");
        }

        $presensi = Presensi::where('id_pegawai', $pegawai->id)
            ->where('tanggal_masuk', now()->toDateString())
            ->firstOrFail();

        if ($request->has('foto_base64') && !empty($request->foto_base64)) {
            $image_parts = explode(";base64,", $request->foto_base64);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_presensi/' . uniqid() . '.jpg';
            \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $image_base64);
            $fotoPath = $fileName;
        } else {
            $fotoPath = $request->file('foto')->store('foto_presensi', 'public');
        }

        $presensi->update([
            'tanggal_keluar' => now()->toDateString(),
            'jam_keluar' => now()->toTimeString(),
            'foto_keluar' => $fotoPath,
            'jarak_keluar' => round($jarak),
        ]);

        return redirect()->route('pegawai.dashboard')->with('success', 'Presensi pulang berhasil.');
    }
}
