@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center px-3" style="min-height: 100vh;">
    <div class="swiss-card shadow-sm" style="width: 100%; max-width: 340px; padding: 1.5rem; border-radius: 14px;">
        <div class="text-center mb-3">
            <img src="{{ asset('favicon.png') }}" alt="Logo" width="48" height="48" class="mb-2 rounded-circle shadow-sm" style="width: 48px; height: 48px; object-fit: contain;">
            <h5 class="font-mono text-accent mb-1 fw-bold" style="font-size: 1.25rem;">Presensi App</h5>
            <p class="text-dim mb-0" style="font-size: 0.8rem;">Silakan login untuk melanjutkan</p>
        </div>

        <x-alert />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label-swiss" style="font-size: 0.8rem; margin-bottom: 0.35rem;">Username / NRG</label>
                <input type="text" name="username" class="form-control-swiss" style="font-size: 0.88rem; padding: 0.5rem 0.75rem;" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label-swiss" style="font-size: 0.8rem; margin-bottom: 0.35rem;">Password</label>
                <x-password-toggle />
            </div>
            <div class="mt-3 pt-1">
                <button type="submit" class="btn-swiss w-100" style="font-size: 0.9rem; padding: 0.55rem 1rem;">Login</button>
            </div>
        </form>
    </div>
</div>
@endsection
