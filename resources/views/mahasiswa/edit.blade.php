@extends('layouts.app')

@section('content')

<h2>Edit Mahasiswa</h2>

<form action="/mahasiswa/{{ $mhs->id_mahasiswa }}" method="POST">

@csrf
@method('PUT')

NIM:
<input type="text" name="nim" value="{{ $mhs->nim }}">

Nama:
<input type="text" name="nama_mahasiswa" value="{{ $mhs->nama_mahasiswa }}">

Alamat:
<input type="text" name="alamat" value="{{ $mhs->alamat }}">

<button type="submit">Update</button>

</form>

@endsection
