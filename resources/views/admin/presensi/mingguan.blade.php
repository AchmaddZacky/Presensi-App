@extends('layouts.admin')

@section('admin-content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
    <h4 class="m-0 fw-bold font-mono" style="font-size: clamp(1.2rem, 4vw, 1.5rem);">Rekap Presensi Mingguan</h4>
    <div class="d-flex gap-2 w-100 w-sm-auto">
        <a href="{{ route('admin.presensi.export-csv', array_merge(request()->query(), ['mode' => 'mingguan'])) }}" class="btn btn-outline-success btn-sm flex-fill flex-sm-grow-0 text-center">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
        <a href="{{ route('admin.presensi.export', array_merge(request()->query(), ['mode' => 'mingguan'])) }}" class="btn btn-outline-danger btn-sm flex-fill flex-sm-grow-0 text-center">
            <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
        </a>
    </div>
</div>

<form class="row g-2 align-items-center mb-3" method="GET" action="{{ route('admin.presensi.mingguan') }}">
    <div class="col-6 col-sm-4 col-md-auto">
        <select name="minggu_ke" class="form-select form-select-sm w-100" onchange="this.form.submit()" title="Pilih Minggu">
            @foreach ($weeks as $wNum => $wData)
                <option value="{{ $wNum }}" {{ $mingguKe == $wNum ? 'selected' : '' }}>
                    {{ $wData['label'] }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-sm-4 col-md-auto">
        <select name="bulan" class="form-select form-select-sm w-100" onchange="this.form.submit()" title="Pilih Bulan">
            @foreach ($namaBulan as $num => $nama)
                <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-sm-4 col-md-auto">
        <select name="tahun" class="form-select form-select-sm w-100" onchange="this.form.submit()" title="Pilih Tahun">
            @for ($y = now()->year + 1; $y >= 2023; $y--)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div class="col-6 col-sm-6 col-md-auto">
        <select name="id_lokasi" class="form-select form-select-sm w-100" onchange="this.form.submit()">
            <option value="">Semua Kantor</option>
            @foreach ($lokasiList as $lok)
                <option value="{{ $lok->id }}" {{ $idLokasi == $lok->id ? 'selected' : '' }}>{{ $lok->nama_lokasi }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-sm-6 col-md-auto">
        <select name="id_pegawai" class="form-select form-select-sm w-100" onchange="this.form.submit()">
            <option value="">Semua Pegawai</option>
            @foreach ($pegawaiList as $p)
                <option value="{{ $p->id }}" {{ $idPegawai == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-md-auto flex-grow-1">
        <div class="input-group input-group-sm">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama / NRG..." value="{{ $search ?? '' }}">
            <button class="btn btn-primary" type="submit" title="Cari"><i class="bi bi-search"></i></button>
            @if(!empty($search))
                <a href="{{ route('admin.presensi.mingguan', array_merge(request()->except('search'))) }}" class="btn btn-outline-secondary" title="Reset Pencarian"><i class="bi bi-x-lg"></i></a>
            @endif
        </div>
    </div>
    <div class="col-12 col-md-auto ms-auto text-muted small mt-1 mt-md-0">
        Periode: {{ $weeks[$mingguKe]['label'] ?? "Minggu $mingguKe" }} {{ $namaBulan[$bulan] }} {{ $tahun }}
    </div>
</form>

<!-- Table Card -->
<div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius: 14px; border: 1px solid #e2e8f0; background: #ffffff;">
    <div class="d-flex d-md-none align-items-center justify-content-between px-3 py-2 bg-light border-bottom text-muted" style="font-size: 0.78rem;">
        <span><i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser tabel ke samping</span>
        <span class="badge bg-white text-secondary border">Tabel Interaktif</span>
    </div>
    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-bordered table-hover mb-0" style="min-width: 850px;">
            <thead class="table-light">
                <tr>
                    <th style="width: 40px; white-space: nowrap;">#</th>
                    <th style="white-space: nowrap;">Pegawai</th>
                    <th style="white-space: nowrap;">Kantor</th>
                    <th style="white-space: nowrap;">Hari/Tgl</th>
                    <th style="white-space: nowrap;">Masuk</th>
                    <th class="text-center" style="white-space: nowrap;">Foto Masuk</th>
                    <th style="white-space: nowrap;">Jarak Masuk</th>
                    <th style="white-space: nowrap;">Pulang</th>
                    <th class="text-center" style="white-space: nowrap;">Foto Pulang</th>
                    <th style="white-space: nowrap;">Jarak Pulang</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($presensi as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td style="white-space: nowrap;">{{ $item->pegawai->nama ?? '-' }}</td>
                    <td style="white-space: nowrap;">{{ $item->pegawai->lokasiPresensi->nama_lokasi ?? '-' }}</td>
                    <td style="white-space: nowrap;">{{ $item->tanggal_masuk->format('d-m-Y') }}</td>
                    <td style="white-space: nowrap;">{{ $item->jam_masuk }}</td>
                    <td class="text-center" style="white-space: nowrap;">
                        @if($item->foto_masuk)
                            <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.8rem;" 
                                    onclick="showPhoto('{{ asset('storage/'.$item->foto_masuk) }}', 'Foto Masuk - {{ $item->pegawai->nama ?? '' }} ({{ $item->jam_masuk }})')">
                                <i class="bi bi-image me-1"></i> Lihat Foto
                            </button>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">{{ $item->jarak_masuk ? $item->jarak_masuk . ' m' : '-' }}</td>
                    <td style="white-space: nowrap;">{{ $item->jam_keluar ?? '-' }}</td>
                    <td class="text-center" style="white-space: nowrap;">
                        @if($item->foto_keluar)
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark py-0 px-2" style="font-size: 0.8rem;" 
                                    onclick="showPhoto('{{ asset('storage/'.$item->foto_keluar) }}', 'Foto Pulang - {{ $item->pegawai->nama ?? '' }} ({{ $item->jam_keluar }})')">
                                <i class="bi bi-image me-1"></i> Lihat Foto
                            </button>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td style="white-space: nowrap;">{{ $item->jarak_keluar ? $item->jarak_keluar . ' m' : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted py-3">Tidak ada data presensi pada periode ini.</td>
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
