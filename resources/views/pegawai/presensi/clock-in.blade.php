@extends('layouts.pegawai')

@section('pegawai-content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="swiss-card">
            <div class="text-center mb-4">
                <h4 class="font-mono text-accent">PRESENSI MASUK</h4>
                <p class="text-dim" style="font-size: 0.9rem;">Pastikan wajah terlihat jelas dan lokasi aktif</p>
            </div>

            <x-alert />

            <form action="{{ route('pegawai.presensi.clock-in') }}" method="POST" id="clockInForm">
                @csrf
                <x-webcam-capture :lokasi="Auth::user()->pegawai->lokasiPresensi" />
                
                <div class="d-grid mt-4">
                    <button type="submit" class="btn-swiss py-3" style="font-size: 1.1rem; border-radius: 8px;">
                        <i class="bi bi-box-arrow-in-right me-2"></i> KIRIM PRESENSI MASUK
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
