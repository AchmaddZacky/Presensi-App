@extends('layouts.admin')

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Lokasi Presensi</h4>
    <a href="{{ route('admin.lokasi-presensi.create') }}" class="btn btn-primary btn-sm">+ Tambah</a>
</div>
<div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius: 14px; border: 1px solid #e2e8f0; background: #ffffff;">
    <div class="d-flex d-md-none align-items-center justify-content-between px-3 py-2 bg-light border-bottom text-muted" style="font-size: 0.78rem;">
        <span><i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser tabel ke samping</span>
        <span class="badge bg-white text-secondary border">Tabel Interaktif</span>
    </div>
    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-bordered table-hover bg-white mb-0" style="min-width: 700px;">
            <thead class="table-light"><tr><th style="width: 40px; white-space: nowrap;">#</th><th style="white-space: nowrap;">Nama Lokasi</th><th style="white-space: nowrap;">Zona Waktu</th><th style="white-space: nowrap;">Radius (m)</th><th style="white-space: nowrap;">Jam Masuk</th><th style="white-space: nowrap;">Jam Pulang</th><th style="width: 140px; white-space: nowrap;">Aksi</th></tr></thead>
            <tbody>
            @foreach ($lokasi as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td style="white-space: nowrap;">{{ $item->nama_lokasi }}</td>
                    <td style="white-space: nowrap;"><span class="badge bg-light text-dark border">{{ $item->zona_waktu ?? 'WIB' }}</span></td>
                    <td style="white-space: nowrap;">{{ $item->radius }} m</td>
                    <td style="white-space: nowrap;">{{ $item->jam_masuk }}</td>
                    <td style="white-space: nowrap;">{{ $item->jam_pulang }}</td>
                    <td style="white-space: nowrap;">
                        <a href="{{ route('admin.lokasi-presensi.edit', $item) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.lokasi-presensi.destroy', $item) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(event, this, '{{ $item->nama_lokasi }}')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
{{ $lokasi->links() }}
@endsection
