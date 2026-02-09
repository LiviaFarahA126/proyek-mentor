@extends('layouts.app')

@section('content')

<h2>Tambah Mahasiswa</h2>

<form action="/mahasiswa" method="POST">
@csrf

NIM:
<input type="text" name="nim">

Nama:
<input type="text" name="nama_mahasiswa">

Alamat:
<input type="text" name="alamat">

<button type="submit">Simpan</button>

</form>

@endsection
