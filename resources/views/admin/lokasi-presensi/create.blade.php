@extends('layouts.admin')

@section('admin-content')
<h4 class="mb-3">Tambah Lokasi Presensi</h4>
<form action="{{ route('admin.lokasi-presensi.store') }}" method="POST" class="bg-white p-3 rounded">
    @csrf
    @include('admin.lokasi-presensi._form')
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('admin.lokasi-presensi.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
