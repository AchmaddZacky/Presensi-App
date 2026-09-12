@extends('layouts.pegawai')

@section('pegawai-content')
<div class="mb-4">
    <h3 class="font-mono m-0" style="color: var(--app-text); font-weight: 700; font-size: clamp(1.2rem, 4vw, 1.5rem);">Riwayat Presensi</h3>
    <p class="text-dim m-0" style="font-size: 0.88rem;">
        Catatan riwayat kehadiran dan jarak absensi Anda dari lokasi kantor.
    </p>
</div>

<div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius: 14px; background: var(--app-surface); border: 1px solid var(--app-border);">
    <div class="d-flex d-md-none align-items-center justify-content-between px-3 py-2 bg-light border-bottom text-muted" style="font-size: 0.78rem;">
        <span><i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser tabel ke samping</span>
        <span class="badge bg-white text-secondary border">Tabel Interaktif</span>
    </div>
    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-hover align-middle mb-0" style="min-width: 650px;">
            <thead style="background: var(--app-surface-light); border-bottom: 2px solid var(--app-border);">
                <tr class="text-secondary" style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th class="py-3 px-3 text-center" style="width: 50px; white-space: nowrap;">#</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Tanggal</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Presensi Masuk</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Presensi Pulang</th>
                    <th class="py-3 px-3 text-center" style="white-space: nowrap;">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($riwayat as $item)
                <tr style="border-bottom: 1px solid var(--app-border);">
                    <td class="text-center text-muted fw-medium px-3">{{ $loop->iteration }}</td>
                    <td class="px-3">
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">
                            {{ $item->tanggal_masuk->locale('id')->translatedFormat('l, d F Y') }}
                        </div>
                    </td>
                    <td class="px-3">
                        <div class="fw-bold text-primary" style="font-size: 0.95rem;">
                            <i class="bi bi-box-arrow-in-right me-1"></i>{{ $item->jam_masuk }}
                        </div>
                        <div class="mt-1">
                            @if(!is_null($item->jarak_masuk))
                                <span class="badge rounded-pill bg-light text-primary border" style="font-size: 0.75rem;">
                                    <i class="bi bi-geo-alt-fill me-1"></i>Jarak: {{ $item->jarak_masuk }} m
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.75rem;">-</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-3">
                        @if($item->jam_keluar)
                            <div class="fw-bold text-warning-emphasis" style="font-size: 0.95rem;">
                                <i class="bi bi-box-arrow-left me-1"></i>{{ $item->jam_keluar }}
                            </div>
                            <div class="mt-1">
                                @if(!is_null($item->jarak_keluar))
                                    <span class="badge rounded-pill bg-light text-warning-emphasis border" style="font-size: 0.75rem;">
                                        <i class="bi bi-geo-alt-fill me-1"></i>Jarak: {{ $item->jarak_keluar }} m
                                    </span>
                                @else
                                    <span class="text-muted" style="font-size: 0.75rem;">-</span>
                                @endif
                            </div>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 0.8rem;">
                                Belum Pulang
                            </span>
                        @endif
                    </td>
                    <td class="px-3 text-center">
                        @if($item->jam_keluar)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill" style="font-size: 0.8rem; font-weight: 600;">
                                <i class="bi bi-check-circle-fill me-1"></i> Selesai
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2 rounded-pill" style="font-size: 0.8rem; font-weight: 600;">
                                <i class="bi bi-clock me-1"></i> Berlangsung
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-clock-history" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <h5 class="mt-3 mb-1 fw-bold text-secondary">Belum Ada Riwayat</h5>
                            <p class="text-dim mb-0" style="font-size: 0.9rem;">Anda belum memiliki riwayat presensi yang tercatat.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $riwayat->links() }}
</div>
@endsection
