@extends('layouts.admin')

@section('admin-content')
<h4 class="mb-3">Edit Jabatan</h4>
<form action="{{ route('admin.jabatan.update', $jabatan) }}" method="POST" class="bg-white p-3 rounded">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="form-label">Nama Jabatan</label>
        <input type="text" name="jabatan" class="form-control" value="{{ $jabatan->jabatan }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.jabatan.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
