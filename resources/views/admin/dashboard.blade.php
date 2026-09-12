@extends('layouts.admin')

@section('admin-content')
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-1 mb-4">
    <h3 class="font-mono m-0 fw-bold" style="font-size: clamp(1.25rem, 4vw, 1.6rem);">Dashboard Overview</h3>
    <div class="text-dim" style="font-size: 0.88rem;">Real-time Metrics</div>
</div>

<div class="row g-3 g-md-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="swiss-card">
            <div class="text-dim mb-2 font-mono" style="font-size: 0.88rem;">Total Pegawai</div>
            <h1 class="m-0 text-accent font-mono fw-bold" style="font-size: clamp(2.2rem, 5vw, 3rem);">{{ $totalPegawai }}</h1>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="swiss-card">
            <div class="text-dim mb-2 font-mono" style="font-size: 0.88rem;">Hadir Hari Ini</div>
            <h1 class="m-0 font-mono fw-bold" style="font-size: clamp(2.2rem, 5vw, 3rem); color: var(--app-success);">{{ $hadirHariIni }}</h1>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <a href="{{ route('admin.ketidakhadiran.index') }}" class="text-decoration-none">
            <div class="swiss-card">
                <div class="text-dim mb-2 font-mono" style="font-size: 0.88rem;">Pengajuan Pending</div>
                <h1 class="m-0 font-mono text-danger fw-bold" style="font-size: clamp(2.2rem, 5vw, 3rem); color: var(--app-danger) !important;">{{ $pengajuanPending }}</h1>
            </div>
        </a>
    </div>
</div>
@endsection
