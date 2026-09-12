@extends('layouts.admin')

@section('admin-content')
<h4 class="mb-3">Tambah Jabatan</h4>
<form action="{{ route('admin.jabatan.store') }}" method="POST" class="bg-white p-3 rounded">
    @csrf
    <div class="mb-3">
        <label class="form-label">Nama Jabatan</label>
        <input type="text" name="jabatan" class="form-control" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.jabatan.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
