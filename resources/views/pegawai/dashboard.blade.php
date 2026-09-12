@extends('layouts.pegawai')

@section('pegawai-content')
<x-clock-widget />

<div class="swiss-card mb-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
        <div>
            <h4 class="font-mono m-0 fw-bold" style="color: var(--app-text); font-size: clamp(1.2rem, 4vw, 1.5rem);">Halo, {{ $pegawai->nama }}</h4>
            <p class="text-dim mt-1 mb-0" style="font-size: 0.9rem;">
                {{ $pegawai->jabatan->nama_jabatan ?? '-' }} &bull; {{ $pegawai->lokasiPresensi->nama_lokasi ?? '-' }}
            </p>
        </div>
        <div>
            <span class="badge" style="background: var(--app-surface-light); color: var(--app-text); padding: 8px 12px; font-size: 0.85rem; border: 1px solid var(--app-border);">
                NRG: {{ $pegawai->nrg }}
            </span>
        </div>
    </div>
    
    <div class="mt-4 pt-3" style="border-top: 1px solid var(--app-border);">
        @if (!$presensiHariIni)
            <div class="alert mb-4" style="background: var(--app-accent-light); border: none; color: var(--app-accent); border-radius: 8px;">
                <i class="bi bi-info-circle me-2"></i> Anda belum melakukan presensi hari ini.
            </div>
            <a href="{{ route('pegawai.presensi.clock-in-form') }}" class="btn-swiss w-100 w-sm-auto text-center py-2 px-4">
                <i class="bi bi-box-arrow-in-right me-2"></i> Presensi Masuk
            </a>
        @elseif (!$presensiHariIni->jam_keluar)
            <div class="alert mb-4" style="background: #f0fdf4; border: none; color: var(--app-success); border-radius: 8px;">
                <i class="bi bi-check-circle me-2"></i> Anda telah presensi masuk pada <strong>{{ $presensiHariIni->jam_masuk }}</strong>
            </div>
            <a href="{{ route('pegawai.presensi.clock-out-form') }}" class="btn-swiss w-100 w-sm-auto text-center py-2 px-4" style="background: #f59e0b; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2);">
                <i class="bi bi-box-arrow-left me-2"></i> Presensi Pulang
            </a>
        @else
            <div class="alert" style="background: #f0fdf4; border: 1px solid #bbf7d0; color: var(--app-success); border-radius: 8px;">
                <div class="font-mono mb-2 fw-bold"><i class="bi bi-check-all me-2"></i> Presensi Hari Ini Selesai</div>
                <div class="d-flex flex-column flex-sm-row gap-2 gap-sm-4 mt-2" style="font-size: 0.95rem;">
                    <span><strong>Masuk:</strong> {{ $presensiHariIni->jam_masuk }}</span>
                    <span><strong>Pulang:</strong> {{ $presensiHariIni->jam_keluar }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
