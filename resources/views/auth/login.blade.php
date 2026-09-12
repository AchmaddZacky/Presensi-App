@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="swiss-card" style="width: 100%; max-width: 420px;">
        <div class="text-center mb-4">
            <img src="{{ asset('favicon.png') }}" alt="Logo" width="72" height="72" class="mb-3 rounded-circle shadow-sm" style="width: 72px; height: 72px; object-fit: contain;">
            <h4 class="font-mono text-accent" style="font-size: 1.75rem;">Presensi App</h4>
            <p class="text-dim" style="font-size: 0.9rem;">Silakan login untuk melanjutkan</p>
        </div>

        <x-alert />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label class="form-label-swiss">Username / NRG</label>
                <input type="text" name="username" class="form-control-swiss" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label-swiss">Password</label>
                <x-password-toggle />
            </div>
            <div class="mt-4">
                <button type="submit" class="btn-swiss w-100" style="font-size: 1rem;">Login</button>
            </div>
        </form>
    </div>
</div>
@endsection
