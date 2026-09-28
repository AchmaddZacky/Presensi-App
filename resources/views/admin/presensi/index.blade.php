@extends('layouts.admin')

@section('admin-content')
<div class="mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-2">
        <div>
            <h3 class="font-mono m-0" style="color: var(--app-text); font-weight: 700;">Rekap Presensi</h3>
            <p class="text-dim m-0" style="font-size: 0.95rem;">
                Monitoring kehadiran pegawai dengan filter mode mingguan & bulanan serta detail jarak absensi.
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.presensi.export', request()->query()) }}" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm" style="border-radius: 8px; font-weight: 500;">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </a>
        </div>
    </div>
</div>

<!-- Mode Switcher Navigation -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: var(--app-surface);">
    <div class="card-body p-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <!-- Mode Tabs -->
            <ul class="nav nav-pills gap-2" id="rekapModeTab">
                <li class="nav-item">
                    <a class="nav-link px-4 py-2 {{ $mode === 'mingguan' ? 'active' : '' }}" 
                       href="{{ route('admin.presensi.index', array_merge(request()->query(), ['mode' => 'mingguan', 'tanggal_mingguan' => $tanggalMingguan])) }}"
                       style="border-radius: 8px; font-weight: 600; {{ $mode === 'mingguan' ? 'background: var(--app-accent); color: #fff;' : 'background: var(--app-surface-light); color: var(--app-text);' }}">
                        <i class="bi bi-calendar-week me-1"></i> Mode Mingguan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-4 py-2 {{ $mode === 'bulanan' ? 'active' : '' }}" 
                       href="{{ route('admin.presensi.index', array_merge(request()->query(), ['mode' => 'bulanan', 'periode_bulan' => $periodeBulan])) }}"
                       style="border-radius: 8px; font-weight: 600; {{ $mode === 'bulanan' ? 'background: var(--app-accent); color: #fff;' : 'background: var(--app-surface-light); color: var(--app-text);' }}">
                        <i class="bi bi-calendar-month me-1"></i> Mode Bulanan
                    </a>
                </li>
            </ul>

            <!-- Quick Navigation Controls -->
            <div class="d-flex align-items-center gap-2">
                @if($mode === 'mingguan')
                    <a href="{{ route('admin.presensi.index', ['mode' => 'mingguan', 'tanggal_mingguan' => $prevPeriod, 'id_pegawai' => $idPegawai]) }}" 
                       class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;">
                        <i class="bi bi-chevron-left me-1"></i> Minggu Lalu
                    </a>
                    <a href="{{ route('admin.presensi.index', ['mode' => 'mingguan', 'tanggal_mingguan' => $todayPeriod, 'id_pegawai' => $idPegawai]) }}" 
                       class="btn btn-light btn-sm px-3 border" style="border-radius: 6px; font-weight: 500;">
                        Minggu Ini
                    </a>
                    <a href="{{ route('admin.presensi.index', ['mode' => 'mingguan', 'tanggal_mingguan' => $nextPeriod, 'id_pegawai' => $idPegawai]) }}" 
                       class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;">
                        Minggu Depan <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                @else
                    <a href="{{ route('admin.presensi.index', ['mode' => 'bulanan', 'periode_bulan' => $prevPeriod, 'id_pegawai' => $idPegawai]) }}" 
                       class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;">
                        <i class="bi bi-chevron-left me-1"></i> Bulan Lalu
                    </a>
                    <a href="{{ route('admin.presensi.index', ['mode' => 'bulanan', 'periode_bulan' => $todayPeriod, 'id_pegawai' => $idPegawai]) }}" 
                       class="btn btn-light btn-sm px-3 border" style="border-radius: 6px; font-weight: 500;">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.presensi.index', ['mode' => 'bulanan', 'periode_bulan' => $nextPeriod, 'id_pegawai' => $idPegawai]) }}" 
                       class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;">
                        Bulan Depan <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Filter Controls Box -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: var(--app-surface);">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('admin.presensi.index') }}" class="row g-3 align-items-end">
            <input type="hidden" name="mode" value="{{ $mode }}">

            @if($mode === 'mingguan')
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">
                        <i class="bi bi-calendar-event me-1"></i> Tanggal Acuan Minggu
                    </label>
                    <input type="date" name="tanggal_mingguan" class="form-control form-control-swiss" value="{{ $tanggalMingguan }}">
                    <div class="form-text text-muted" style="font-size: 0.78rem;">Sistem otomatis mengambil Senin s/d Minggu</div>
                </div>
            @else
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">
                        <i class="bi bi-calendar-month me-1"></i> Pilih Bulan & Tahun
                    </label>
                    <input type="month" name="periode_bulan" class="form-control form-control-swiss" value="{{ $periodeBulan }}">
                    <div class="form-text text-muted" style="font-size: 0.78rem;">Rekapitulasi dari awal sampai akhir bulan</div>
                </div>
            @endif

            <div class="col-md-4">
                <label class="form-label fw-semibold text-secondary" style="font-size: 0.85rem;">
                    <i class="bi bi-person me-1"></i> Filter Pegawai
                </label>
                <select name="id_pegawai" class="form-select form-control-swiss">
                    <option value="">-- Semua Pegawai --</option>
                    @foreach($pegawaiList as $p)
                        <option value="{{ $p->id }}" {{ $idPegawai == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} (NRG: {{ $p->nrg }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-swiss flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Terapkan Filter
                </button>
                <a href="{{ route('admin.presensi.index', ['mode' => $mode]) }}" class="btn btn-outline-secondary" style="border-radius: 8px;" title="Reset Filter">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>

        <div class="mt-3 pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge" style="background: var(--app-accent-light); color: var(--app-accent); font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">
                    <i class="bi bi-clock-history me-1"></i> 
                    @if($mode === 'mingguan')
                        Mode Mingguan
                    @else
                        Mode Bulanan
                    @endif
                </span>
                <span class="text-dark fw-semibold" style="font-size: 0.9rem;">
                    Periode: {{ \Carbon\Carbon::parse($start)->locale('id')->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($end)->locale('id')->translatedFormat('d F Y') }}
                </span>
            </div>
            @if($idPegawai)
                @php
                    $selectedPegawai = $pegawaiList->firstWhere('id', $idPegawai);
                @endphp
                @if($selectedPegawai)
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                        <i class="bi bi-person-check text-primary me-1"></i> {{ $selectedPegawai->nama }}
                    </span>
                @endif
            @endif
        </div>
    </div>
</div>

<!-- Summary Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background: var(--app-surface);">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="text-dim" style="font-size: 0.8rem; font-weight: 500;">Total Kehadiran</div>
                    <div class="fw-bold" style="font-size: 1.5rem; color: var(--app-text);">{{ $presensi->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background: var(--app-surface);">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="text-dim" style="font-size: 0.8rem; font-weight: 500;">Presensi Lengkap</div>
                    <div class="fw-bold text-success" style="font-size: 1.5rem;">{{ $presensi->whereNotNull('jam_keluar')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background: var(--app-surface);">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-warning-subtle text-warning-emphasis rounded-circle" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div>
                    <div class="text-dim" style="font-size: 0.8rem; font-weight: 500;">Belum Pulang</div>
                    <div class="fw-bold text-warning" style="font-size: 1.5rem;">{{ $presensi->whereNull('jam_keluar')->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm p-3" style="border-radius: 12px; background: var(--app-surface);">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle" style="width: 48px; height: 48px; font-size: 1.4rem;">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <div class="text-dim" style="font-size: 0.8rem; font-weight: 500;">Rata-rata Jarak Masuk</div>
                    @php
                        $validJarak = $presensi->whereNotNull('jarak_masuk')->avg('jarak_masuk');
                    @endphp
                    <div class="fw-bold text-info" style="font-size: 1.5rem;">
                        {{ $validJarak ? round($validJarak) . ' m' : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius: 12px; background: var(--app-surface);">
    <div class="d-flex d-md-none align-items-center justify-content-between px-3 py-2 bg-light border-bottom text-muted" style="font-size: 0.78rem;">
        <span><i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser tabel ke samping</span>
        <span class="badge bg-white text-secondary border">Tabel Interaktif</span>
    </div>
    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-hover align-middle mb-0" style="min-width: 750px;">
            <thead style="background: var(--app-surface-light); border-bottom: 2px solid var(--app-border);">
                <tr class="text-secondary" style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th class="py-3 px-3 text-center" style="width: 50px; white-space: nowrap;">#</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Pegawai</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Tanggal</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Presensi Masuk</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Presensi Pulang</th>
                    <th class="py-3 px-3 text-center" style="white-space: nowrap;">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($presensi as $item)
                <tr style="border-bottom: 1px solid var(--app-border);">
                    <td class="text-center text-muted fw-medium px-3">{{ $loop->iteration }}</td>
                    <td class="px-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ ($item->pegawai && $item->pegawai->foto) ? asset('storage/'.$item->pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode($item->pegawai->nama ?? 'Pegawai').'&background=2563eb&color=fff' }}" 
                                 width="36"
                                 height="36"
                                 class="rounded-circle border" 
                                 style="width: 36px; height: 36px; min-width: 36px; object-fit: cover;" 
                                 alt="Avatar">
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ $item->pegawai->nama ?? '-' }}</div>
                                <div class="text-muted" style="font-size: 0.78rem;">
                                    NRG: {{ $item->pegawai->nrg ?? '-' }} &bull; {{ $item->pegawai->jabatan->nama_jabatan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3">
                        <div class="fw-medium text-dark" style="font-size: 0.88rem;">
                            {{ $item->tanggal_masuk->locale('id')->translatedFormat('l, d M Y') }}
                        </div>
                    </td>
                    <td class="px-3">
                        <div class="d-flex align-items-center gap-2">
                            <div>
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
                            </div>
                            @if($item->foto_masuk)
                                <button type="button" class="btn btn-sm btn-outline-primary border-0 p-1 ms-auto" 
                                        onclick="showPhoto('{{ asset('storage/'.$item->foto_masuk) }}', 'Foto Masuk - {{ $item->pegawai->nama ?? '' }} ({{ $item->jam_masuk }})')"
                                        title="Lihat Foto Masuk">
                                    <img src="{{ asset('storage/'.$item->foto_masuk) }}" 
                                         width="38" 
                                         height="38" 
                                         loading="lazy" 
                                         decoding="async" 
                                         class="rounded border" 
                                         style="width: 38px; height: 38px; min-width: 38px; aspect-ratio: 1/1; object-fit: cover;" 
                                         alt="Foto Masuk">
                                </button>
                            @endif
                        </div>
                    </td>
                    <td class="px-3">
                        <div class="d-flex align-items-center gap-2">
                            <div>
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
                            </div>
                            @if($item->foto_keluar)
                                <button type="button" class="btn btn-sm btn-outline-warning border-0 p-1 ms-auto" 
                                        onclick="showPhoto('{{ asset('storage/'.$item->foto_keluar) }}', 'Foto Pulang - {{ $item->pegawai->nama ?? '' }} ({{ $item->jam_keluar }})')"
                                        title="Lihat Foto Pulang">
                                    <img src="{{ asset('storage/'.$item->foto_keluar) }}" 
                                         width="38" 
                                         height="38" 
                                         loading="lazy" 
                                         decoding="async" 
                                         class="rounded border" 
                                         style="width: 38px; height: 38px; min-width: 38px; aspect-ratio: 1/1; object-fit: cover;" 
                                         alt="Foto Pulang">
                                </button>
                            @endif
                        </div>
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
                    <td colspan="6" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-calendar-x" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <h5 class="mt-3 mb-1 fw-bold text-secondary">Tidak Ada Data Presensi</h5>
                            <p class="text-dim mb-0" style="font-size: 0.9rem;">
                                Belum ada presensi yang tercatat untuk periode {{ \Carbon\Carbon::parse($start)->locale('id')->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($end)->locale('id')->translatedFormat('d F Y') }}.
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function showPhoto(url, title) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                imageUrl: url,
                imageAlt: title,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Tutup',
                footer: '<a href="' + url + '" target="_blank">Buka gambar di tab baru</a>'
            });
        } else {
            window.open(url, '_blank');
        }
    }
</script>
@endsection
