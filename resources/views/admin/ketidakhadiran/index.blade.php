@extends('layouts.admin')

@section('admin-content')
<div class="mb-4">
    <h4 class="font-mono m-0 fw-bold" style="color: var(--app-text); font-size: clamp(1.2rem, 4vw, 1.5rem);">Pengajuan Ketidakhadiran</h4>
    <p class="text-dim m-0" style="font-size: 0.88rem;">Kelola dan tinjau permohonan izin atau sakit dari pegawai.</p>
</div>

<!-- 3 Status Widgets -->
<div class="row g-2 g-md-3 mb-4">
    <!-- Menunggu Persetujuan -->
    <div class="col-12 col-sm-4">
        <a href="{{ route('admin.ketidakhadiran.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 14px; background: #ffffff; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; border-radius: 12px; background: #fffbeb; color: #d97706; font-size: 1.35rem;">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div>
                        <div class="text-secondary fw-semibold" style="font-size: 0.82rem;">Menunggu Persetujuan</div>
                        <h2 class="m-0 fw-bold" style="color: #d97706; font-size: 1.75rem;">{{ $countPending }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Telah Disetujui -->
    <div class="col-12 col-sm-4">
        <a href="{{ route('admin.ketidakhadiran.index', array_merge(request()->except('status'), ['status' => 'approved'])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 14px; background: #ffffff; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; border-radius: 12px; background: #ecfdf5; color: #059669; font-size: 1.35rem;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-secondary fw-semibold" style="font-size: 0.82rem;">Telah Disetujui</div>
                        <h2 class="m-0 fw-bold" style="color: #059669; font-size: 1.75rem;">{{ $countApproved }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Telah Ditolak -->
    <div class="col-12 col-sm-4">
        <a href="{{ route('admin.ketidakhadiran.index', array_merge(request()->except('status'), ['status' => 'rejected'])) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 14px; background: #ffffff; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center flex-shrink-0" style="width: 46px; height: 46px; border-radius: 12px; background: #fef2f2; color: #dc2626; font-size: 1.35rem;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <div class="text-secondary fw-semibold" style="font-size: 0.82rem;">Telah Ditolak</div>
                        <h2 class="m-0 fw-bold" style="color: #dc2626; font-size: 1.75rem;">{{ $countRejected }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Filter Bar: Search + Status Dropdown -->
<div class="card border-0 shadow-sm p-3 mb-4" style="border-radius: 14px; background: #ffffff;">
    <form method="GET" action="{{ route('admin.ketidakhadiran.index') }}" class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between gap-2 gap-sm-3 m-0">
        <!-- Search Input -->
        <div class="position-relative flex-grow-1" style="min-width: 0;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 0.95rem;"></i>
            <input type="text" 
                   name="search" 
                   class="form-control form-control-sm ps-5 pe-5 w-100" 
                   placeholder="Cari nama, NRG, alasan..." 
                   value="{{ $search ?? '' }}"
                   style="border-radius: 10px; border: 1px solid #e2e8f0; height: 44px; font-size: 0.9rem;">
            @if(!empty($search))
                <a href="{{ route('admin.ketidakhadiran.index', request()->except('search')) }}" 
                   class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted text-decoration-none" 
                   title="Hapus Pencarian">
                    <i class="bi bi-x-circle-fill text-secondary"></i>
                </a>
            @endif
        </div>

        <!-- Status Dropdown -->
        <div style="min-width: 180px;">
            <select name="status" 
                    class="form-select form-select-sm px-3 w-100" 
                    onchange="this.form.submit()"
                    style="border-radius: 10px; border: 1px solid #3b82f6; height: 44px; font-size: 0.9rem; color: #1e293b; font-weight: 500;">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="approved" {{ ($status ?? '') == 'approved' ? 'selected' : '' }}>Telah Disetujui</option>
                <option value="rejected" {{ ($status ?? '') == 'rejected' ? 'selected' : '' }}>Telah Ditolak</option>
            </select>
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="card border-0 shadow-sm overflow-hidden mb-3" style="border-radius: 14px; border: 1px solid #e2e8f0; background: #ffffff;">
    <div class="d-flex d-md-none align-items-center justify-content-between px-3 py-2 bg-light border-bottom text-muted" style="font-size: 0.78rem;">
        <span><i class="bi bi-arrow-left-right me-1 text-primary"></i>Geser tabel ke samping</span>
        <span class="badge bg-white text-secondary border">Tabel Interaktif</span>
    </div>
    <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <table class="table table-hover align-middle mb-0" style="min-width: 780px;">
            <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                <tr class="text-secondary" style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                    <th class="py-3 px-3 text-center" style="width: 40px; white-space: nowrap;">#</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Pegawai</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Keterangan</th>
                    <th class="py-3 px-3" style="min-width: 160px;">Alasan</th>
                    <th class="py-3 px-3" style="white-space: nowrap;">Tanggal</th>
                    <th class="py-3 px-3 text-center" style="white-space: nowrap;">Lampiran</th>
                    <th class="py-3 px-3 text-center" style="white-space: nowrap;">Status</th>
                    <th class="py-3 px-3 text-center" style="width: 160px; white-space: nowrap;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($pengajuan as $item)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td class="text-center text-muted fw-medium px-3">{{ $loop->iteration + ($pengajuan->currentPage() - 1) * $pengajuan->perPage() }}</td>
                    <td class="px-3" style="white-space: nowrap;">
                        <div class="fw-bold text-dark" style="font-size: 0.92rem;">{{ $item->pegawai->nama ?? '-' }}</div>
                        <div class="text-muted" style="font-size: 0.78rem;">NRG: {{ $item->pegawai->nrg ?? '-' }}</div>
                    </td>
                    <td class="px-3" style="white-space: nowrap;">
                        <span class="badge {{ strtolower($item->keterangan) == 'sakit' ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-info-subtle text-info-emphasis border border-info-subtle' }} px-2 py-1" style="font-size: 0.8rem; font-weight: 600;">
                            {{ ucfirst($item->keterangan) }}
                        </span>
                    </td>
                    <td class="px-3" style="max-width: 250px;">
                        <div class="text-truncate" title="{{ $item->deskripsi ?? '-' }}">
                            {{ $item->deskripsi ?? '-' }}
                        </div>
                    </td>
                    <td class="px-3" style="white-space: nowrap;">
                        <span class="fw-medium text-dark" style="font-size: 0.88rem;">{{ $item->tanggal->format('d-m-Y') }}</span>
                    </td>
                    <td class="px-3 text-center" style="white-space: nowrap;">
                        @if($item->file)
                            <div class="d-inline-flex align-items-center gap-1">
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary py-1 px-2 d-inline-flex align-items-center gap-1" 
                                        style="font-size: 0.8rem;" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#lampiranModal"
                                        onclick="previewLampiran('{{ asset('storage/' . $item->file) }}', 'Lampiran {{ $item->pegawai->nama ?? 'Pegawai' }} - {{ ucfirst($item->keterangan) }} ({{ $item->tanggal->format('d/m/Y') }})', '{{ route('admin.ketidakhadiran.download', $item) }}', '{{ $item->download_filename }}')">
                                    <i class="bi bi-eye"></i> Lampiran
                                </button>
                                <a href="{{ route('admin.ketidakhadiran.download', $item) }}" 
                                   download="{{ $item->download_filename }}" 
                                   class="btn btn-sm btn-outline-secondary py-1 px-2 d-inline-flex align-items-center" 
                                   style="font-size: 0.8rem;" 
                                   title="Unduh {{ $item->download_filename }}">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="px-3 text-center" style="white-space: nowrap;">
                        @if($item->status_pengajuan == 'approved')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size: 0.78rem; font-weight: 600;">
                                <i class="bi bi-check-circle-fill me-1"></i> Disetujui
                            </span>
                        @elseif($item->status_pengajuan == 'rejected')
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill" style="font-size: 0.78rem; font-weight: 600;">
                                <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-1 rounded-pill" style="font-size: 0.78rem; font-weight: 600;">
                                <i class="bi bi-clock-fill me-1"></i> Menunggu
                            </span>
                        @endif
                    </td>
                    <td class="px-3 text-center" style="white-space: nowrap;">
                        @if ($item->status_pengajuan == 'pending')
                            <div class="d-flex justify-content-center gap-1">
                                <form action="{{ route('admin.ketidakhadiran.approve', $item) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="button" class="btn btn-sm btn-success px-2 py-1" style="font-size: 0.8rem;" onclick="confirmAction(event, this, { title: 'Setujui Pengajuan?', text: 'Pengajuan ketidakhadiran atas nama {{ addslashes($item->pegawai->nama ?? 'Pegawai') }} akan disetujui.', icon: 'question', confirmText: 'Ya, Setujui', confirmColor: '#28a745' })">
                                        <i class="bi bi-check-lg me-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.ketidakhadiran.reject', $item) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="button" class="btn btn-sm btn-danger px-2 py-1" style="font-size: 0.8rem;" onclick="confirmAction(event, this, { title: 'Tolak Pengajuan?', text: 'Pengajuan ketidakhadiran atas nama {{ addslashes($item->pegawai->nama ?? 'Pegawai') }} akan ditolak.', icon: 'warning', confirmText: 'Ya, Tolak', confirmColor: '#d33' })">
                                        <i class="bi bi-x-lg me-1"></i> Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span class="text-muted small">Selesai</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem; color: #cbd5e1;"></i>
                        <div class="mt-2 fw-medium">Tidak ada data pengajuan ketidakhadiran yang sesuai.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $pengajuan->links() }}
</div>

<!-- Modal Preview Lampiran (Gambar & PDF) -->
<div class="modal fade" id="lampiranModal" tabindex="-1" aria-labelledby="lampiranModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-bottom py-3 px-4 bg-light">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-file-earmark-text text-primary fs-5"></i>
                    <h6 class="modal-title fw-bold text-dark m-0" id="lampiranModalLabel">Preview Lampiran</h6>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3 text-center bg-light-subtle" style="min-height: 350px; display: flex; align-items: center; justify-content: center;">
                <!-- Image Preview Container -->
                <div id="previewImageContainer" style="display: none; width: 100%;">
                    <img id="previewImage" src="" alt="Lampiran" loading="lazy" decoding="async" class="img-fluid rounded border shadow-sm" style="max-height: 70vh; max-width: 100%; object-fit: contain; background: #fff;">
                </div>
                <!-- PDF Preview Container -->
                <div id="previewPdfContainer" style="display: none; width: 100%;">
                    <iframe id="previewPdf" src="" style="width: 100%; height: 70vh; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;"></iframe>
                </div>
                <!-- Fallback Container -->
                <div id="previewUnsupported" style="display: none;" class="py-4">
                    <i class="bi bi-file-earmark-arrow-down fs-1 text-muted d-block mb-2"></i>
                    <p class="text-muted m-0">Tipe file tidak mendukung preview langsung.</p>
                </div>
            </div>
            <div class="modal-footer border-top py-2 px-4 bg-white justify-content-between">
                <div class="d-flex gap-2">
                    <a id="previewOpenTab" href="" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-up-right"></i> Buka Tab Baru
                    </a>
                    <a id="previewDownload" href="" download class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                        <i class="bi bi-download"></i> Unduh
                    </a>
                </div>
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function previewLampiran(url, title, downloadUrl, filename) {
        document.getElementById('lampiranModalLabel').innerText = title || 'Preview Lampiran';
        document.getElementById('previewOpenTab').href = url;
        
        const downloadBtn = document.getElementById('previewDownload');
        downloadBtn.href = downloadUrl || url;
        if (filename) {
            downloadBtn.setAttribute('download', filename);
        } else {
            downloadBtn.removeAttribute('download');
        }

        const imgContainer = document.getElementById('previewImageContainer');
        const pdfContainer = document.getElementById('previewPdfContainer');
        const unsupportedContainer = document.getElementById('previewUnsupported');
        const imgEl = document.getElementById('previewImage');
        const pdfEl = document.getElementById('previewPdf');

        const cleanUrl = url.split('?')[0].split('#')[0];
        const ext = cleanUrl.split('.').pop().toLowerCase();

        if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) {
            imgEl.src = url;
            pdfEl.src = '';
            imgContainer.style.display = 'block';
            pdfContainer.style.display = 'none';
            unsupportedContainer.style.display = 'none';
        } else if (ext === 'pdf') {
            pdfEl.src = url;
            imgEl.src = '';
            imgContainer.style.display = 'none';
            pdfContainer.style.display = 'block';
            unsupportedContainer.style.display = 'none';
        } else {
            imgEl.src = '';
            pdfEl.src = '';
            imgContainer.style.display = 'none';
            pdfContainer.style.display = 'none';
            unsupportedContainer.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('lampiranModal');
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function() {
                const pdfEl = document.getElementById('previewPdf');
                const imgEl = document.getElementById('previewImage');
                if (pdfEl) pdfEl.src = '';
                if (imgEl) imgEl.src = '';
            });
        }
    });
</script>
@endsection
