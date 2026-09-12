<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Lokasi</label>
        <input type="text" name="nama_lokasi" class="form-control" value="{{ old('nama_lokasi', $lokasi->nama_lokasi ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Tipe Lokasi</label>
        <input type="text" name="tipe_lokasi" class="form-control" value="{{ old('tipe_lokasi', $lokasi->tipe_lokasi ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Alamat Lokasi</label>
        <textarea name="alamat_lokasi" class="form-control" required>{{ old('alamat_lokasi', $lokasi->alamat_lokasi ?? '') }}</textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label">Latitude</label>
        <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $lokasi->latitude ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Longitude</label>
        <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $lokasi->longitude ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Radius (meter)</label>
        <input type="number" name="radius" class="form-control" value="{{ old('radius', $lokasi->radius ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Zona Waktu</label>
        <select name="zona_waktu" class="form-select" required>
            <option value="WIB" {{ old('zona_waktu', $lokasi->zona_waktu ?? 'WIB') == 'WIB' ? 'selected' : '' }}>WIB (UTC+7)</option>
            <option value="WITA" {{ old('zona_waktu', $lokasi->zona_waktu ?? 'WIB') == 'WITA' ? 'selected' : '' }}>WITA (UTC+8)</option>
            <option value="WIT" {{ old('zona_waktu', $lokasi->zona_waktu ?? 'WIB') == 'WIT' ? 'selected' : '' }}>WIT (UTC+9)</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Jam Masuk</label>
        <input type="time" name="jam_masuk" class="form-control" value="{{ old('jam_masuk', isset($lokasi->jam_masuk) ? \Carbon\Carbon::parse($lokasi->jam_masuk)->format('H:i') : '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Jam Pulang</label>
        <input type="time" name="jam_pulang" class="form-control" value="{{ old('jam_pulang', isset($lokasi->jam_pulang) ? \Carbon\Carbon::parse($lokasi->jam_pulang)->format('H:i') : '') }}" required>
    </div>
</div>
<hr>
