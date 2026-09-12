@extends('layouts.pegawai')

@section('pegawai-content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="swiss-card">
            <div class="text-center mb-4">
                <h4 class="font-mono text-accent" style="color: #f59e0b !important;">PRESENSI PULANG</h4>
                <p class="text-dim" style="font-size: 0.9rem;">Pastikan wajah terlihat jelas dan lokasi aktif</p>
            </div>

            <x-alert />

            <form action="{{ route('pegawai.presensi.clock-out') }}" method="POST" id="clockOutForm">
                @csrf
                <x-webcam-capture :lokasi="Auth::user()->pegawai->lokasiPresensi" />
                
                <div class="d-grid mt-4">
                    <button type="submit" class="btn-swiss py-3" style="background: #f59e0b; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.2); font-size: 1.1rem; border-radius: 8px;">
                        <i class="bi bi-box-arrow-left me-2"></i> KIRIM PRESENSI PULANG
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
