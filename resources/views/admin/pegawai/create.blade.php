@extends('layouts.admin')

@section('admin-content')
<h4 class="mb-3">Tambah Pegawai</h4>
<form action="{{ route('admin.pegawai.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-3 rounded">
    @csrf
    @include('admin.pegawai._form')
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
