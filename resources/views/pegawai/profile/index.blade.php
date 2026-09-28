@extends('layouts.pegawai')

@section('title', 'Pengaturan Profil Pegawai')

@section('pegawai-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="m-0 fw-bold">Pengaturan Profil</h4>
</div>

<div class="card border-0 shadow-sm p-3 p-md-4" style="border-radius: 14px; border: 1px solid #e2e8f0; background: #ffffff;">
    <form action="{{ route('pegawai.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Bagian Foto Profil -->
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3 pb-3 border-bottom">
            <img id="avatar-preview" 
                 src="{{ ($pegawai && $pegawai->foto) ? asset('storage/'.$pegawai->foto) : 'https://ui-avatars.com/api/?name='.urlencode($pegawai->nama ?? $user->username).'&background=2563eb&color=fff' }}" 
                 alt="Foto Profil" 
                 class="rounded-circle border" 
                 style="width: 72px; height: 72px; object-fit: cover;">
            
            <div class="flex-grow-1">
                <label class="form-label fw-semibold mb-1" style="font-size: 0.9rem;">Foto Profil</label>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <input type="file" name="foto" class="form-control form-control-sm" style="max-width: 280px;" accept="image/jpeg,image/png,image/webp" onchange="previewAvatar(event)">
                    @if($pegawai && $pegawai->foto)
                    <div class="form-check m-0">
                        <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapus_foto">
                        <label class="form-check-label text-danger small" for="hapus_foto">
                            Hapus foto saat ini
                        </label>
                    </div>
                    @endif
                </div>
                <small class="text-muted" style="font-size: 0.78rem;">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
            </div>
        </div>

        <!-- Info Penugasan (Readonly / Keterangan Singkat) -->
        <div class="row g-2 py-3 bg-light rounded mt-3 px-2 border text-muted" style="font-size: 0.83rem;">
            <div class="col-6 col-md-3">
                <span class="d-block text-secondary">NRG:</span>
                <span class="fw-semibold text-dark">{{ $pegawai->nrg ?? '-' }}</span>
            </div>
            <div class="col-6 col-md-3">
                <span class="d-block text-secondary">Jabatan:</span>
                <span class="fw-semibold text-dark">{{ $pegawai->jabatan->jabatan ?? '-' }}</span>
            </div>
            <div class="col-6 col-md-3">
                <span class="d-block text-secondary">Kantor:</span>
                <span class="fw-semibold text-dark">{{ $pegawai->lokasiPresensi->nama_lokasi ?? '-' }}</span>
            </div>
            <div class="col-6 col-md-3">
                <span class="d-block text-secondary">Radius Presensi:</span>
                <span class="fw-semibold text-dark">{{ $pegawai->lokasiPresensi->radius ?? 0 }} m</span>
            </div>
        </div>

        <!-- Bagian Data Diri -->
        <h5 class="fw-semibold mt-4 mb-3" style="font-size: 1rem;">Data Diri & Akun</h5>
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control form-control-sm @error('nama') is-invalid @enderror" 
                       value="{{ old('nama', $pegawai->nama ?? $user->username) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control form-control-sm @error('username') is-invalid @enderror" 
                       value="{{ old('username', $user->username) }}" required>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select form-select-sm @error('jenis_kelamin') is-invalid @enderror">
                    <option value="Laki-laki" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? 'Laki-laki') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label small fw-semibold">No. HP / WhatsApp</label>
                <input type="text" name="no_handphone" class="form-control form-control-sm @error('no_handphone') is-invalid @enderror" 
                       value="{{ old('no_handphone', $pegawai->no_handphone ?? '') }}" placeholder="08xxxxxxxxxx">
                @error('no_handphone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Alamat Domisili</label>
                <textarea name="alamat" class="form-control form-control-sm @error('alamat') is-invalid @enderror" rows="2">{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <hr class="my-4">

        <!-- Bagian Ganti Password -->
        <h5 class="fw-semibold mb-1" style="font-size: 1rem;">Ubah Password</h5>
        <p class="text-muted small mb-3">Kosongkan jika Anda tidak ingin mengubah password.</p>
        
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold">Password Lama</label>
                <div class="input-group input-group-sm">
                    <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Password saat ini">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('current_password', 'icon-cur')">
                        <i class="bi bi-eye" id="icon-cur"></i>
                    </button>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold">Password Baru</label>
                <div class="input-group input-group-sm">
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password', 'icon-new')">
                        <i class="bi bi-eye" id="icon-new"></i>
                    </button>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label small fw-semibold">Ulangi Password Baru</label>
                <div class="input-group input-group-sm">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi password">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password_confirmation', 'icon-conf')">
                        <i class="bi bi-eye" id="icon-conf"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
            <a href="{{ route('pegawai.dashboard') }}" class="btn btn-secondary btn-sm px-3">Batal</a>
            <button type="submit" class="btn btn-primary btn-sm px-3">Simpan Perubahan</button>
        </div>
    </form>
</div>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    function togglePasswordVisibility(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endsection
