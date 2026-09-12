@extends('layouts.pegawai')

@section('pegawai-content')
<div class="mb-4">
    <div class="d-flex align-items-center gap-2 mb-1">
        <a href="{{ route('pegawai.ketidakhadiran.riwayat') }}" class="btn btn-sm btn-light border text-muted shadow-sm px-2 py-1" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="font-mono m-0 fw-bold" style="color: var(--app-text); font-size: clamp(1.2rem, 4vw, 1.5rem);">Ajukan Ketidakhadiran</h4>
    </div>
    <p class="text-dim m-0 ms-sm-4" style="font-size: 0.88rem;">Formulir permohonan izin, sakit, atau cuti kerja.</p>
</div>

<div class="card border-0 shadow-sm p-3 p-md-4" style="border-radius: 16px; background: #ffffff;">
    <form action="{{ route('pegawai.ketidakhadiran.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Jenis Ketidakhadiran <span class="text-danger">*</span></label>
            <select name="keterangan" class="form-select" style="border-radius: 10px; height: 44px;" required>
                <option value="izin" {{ old('keterangan') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ old('keterangan') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="cuti" {{ old('keterangan') == 'cuti' ? 'selected' : '' }}>Cuti</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Tanggal <span class="text-danger">*</span></label>
            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="form-control" style="border-radius: 10px; height: 44px;" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Keterangan / Alasan <span class="text-muted fw-normal">(Opsional)</span></label>
            <textarea name="deskripsi" rows="3" class="form-control" placeholder="Tuliskan alasan pengajuan Anda..." style="border-radius: 10px;">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold text-dark" style="font-size: 0.9rem;">Lampiran Dokumen / Surat <span class="text-muted fw-normal">(Opsional: JPG, PNG, PDF maks 2MB)</span></label>
            <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.png,.pdf" style="border-radius: 10px; height: 44px; padding-top: 8px;">
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end pt-2">
            <a href="{{ route('pegawai.ketidakhadiran.riwayat') }}" class="btn btn-light border py-2 px-4 w-100 w-sm-auto text-center" style="border-radius: 10px;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary py-2 px-4 w-100 w-sm-auto text-center shadow-sm" style="border-radius: 10px;">
                <i class="bi bi-send me-1"></i> Kirim Pengajuan
            </button>
        </div>
    </form>
</div>
@endsection
