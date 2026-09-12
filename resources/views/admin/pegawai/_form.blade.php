<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">NRG</label>
        <input type="text" name="nrg" class="form-control" value="{{ old('nrg', $pegawai->nrg ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $pegawai->nama ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-select" required>
            <option value="Laki-laki" @selected(old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Laki-laki')>Laki-laki</option>
            <option value="Perempuan" @selected(old('jenis_kelamin', $pegawai->jenis_kelamin ?? '') == 'Perempuan')>Perempuan</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">No. HP</label>
        <input type="text" name="no_handphone" class="form-control" value="{{ old('no_handphone', $pegawai->no_handphone ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Alamat</label>
        <textarea name="alamat" class="form-control">{{ old('alamat', $pegawai->alamat ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Jabatan</label>
        <select name="jabatan_id" class="form-select">
            <option value="">-- Pilih Jabatan --</option>
            @foreach ($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}" @selected(old('jabatan_id', $pegawai->jabatan_id ?? null) == $jabatan->id)>
                    {{ $jabatan->jabatan }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Lokasi Presensi</label>
        <select name="lokasi_presensi_id" class="form-select">
            <option value="">-- Pilih Lokasi Presensi --</option>
            @foreach ($lokasis as $lokasi)
                <option value="{{ $lokasi->id }}" @selected(old('lokasi_presensi_id', $pegawai->lokasi_presensi_id ?? null) == $lokasi->id)>
                    {{ $lokasi->nama_lokasi }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Foto</label>
        <input type="file" name="foto" class="form-control">
    </div>
</div>

<hr class="my-4">
<h5 class="mb-3">Informasi Akun Login</h5>
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" value="{{ old('username', $pegawai->user->username ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" {{ isset($pegawai) ? '' : 'required' }}>
        @if(isset($pegawai))
            <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
        @endif
    </div>
</div>
<hr>
