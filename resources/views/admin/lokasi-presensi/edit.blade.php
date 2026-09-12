@extends('layouts.admin')

@section('admin-content')
<h4 class="mb-3">Edit Lokasi Presensi</h4>
<form action="{{ route('admin.lokasi-presensi.update', $lokasi) }}" method="POST" class="bg-white p-3 rounded">
    @csrf @method('PUT')
    @include('admin.lokasi-presensi._form')
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.lokasi-presensi.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
