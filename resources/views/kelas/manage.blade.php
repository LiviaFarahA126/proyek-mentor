@extends('layouts.app')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

<div class="p-6">

<h2 class="text-xl font-bold mb-4">
Kelas {{ $kelas->id_kelas }}
</h2>

<form action="/pertemuan" method="POST">

@csrf

<input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">

Pertemuan ke:
<input name="pertemuan_ke" type="number" class="border">

Tanggal:
<input name="tanggal" type="date" class="border">

Jam Mulai:
<input name="jam_mulai" type="time" class="border">

Jam Selesai:
<input name="jam_selesai" type="time" class="border">

<button class="bg-blue-500 text-white px-3 py-1">
Tambah Pertemuan
</button>

</form>

<hr class="my-4">

@foreach($pertemuan as $p)

<div class="border p-3 mb-2">

Pertemuan {{ $p->pertemuan_ke }}

<a href="/pertemuan/{{ $p->id_pertemuan }}/absen"
class="text-blue-500">

ABSEN

</a>

</div>

@endforeach

</div>

@endsection
