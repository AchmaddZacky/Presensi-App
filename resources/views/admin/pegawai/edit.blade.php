@extends('layouts.admin')

@section('admin-content')
<h4 class="mb-3">Edit Pegawai</h4>
<form action="{{ route('admin.pegawai.update', $pegawai) }}" method="POST" enctype="multipart/form-data" class="bg-white p-3 rounded">
    @csrf @method('PUT')
    @include('admin.pegawai._form')
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection
