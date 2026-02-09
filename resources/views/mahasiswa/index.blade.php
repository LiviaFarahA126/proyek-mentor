@extends('layouts.app')

@section('content')

<h2>Data Mahasiswa</h2>

<button onclick="openCreate()">Tambah Mahasiswa</button>

<table border="1">

<tr>
<th>ID</th>
<th>NIM</th>
<th>Nama</th>
<th>Alamat</th>
<th>Aksi</th>
</tr>

@foreach($mhs as $m)

<tr>
<td>{{ $m->id_mahasiswa }}</td>
<td>{{ $m->nim }}</td>
<td>{{ $m->nama_mahasiswa }}</td>
<td>{{ $m->alamat }}</td>

<td>

<button onclick="openEdit(
'{{ $m->id_mahasiswa }}',
'{{ $m->nim }}',
'{{ $m->nama_mahasiswa }}',
'{{ $m->alamat }}'
)">Edit</button>

<form action="/mahasiswa/{{ $m->id_mahasiswa }}" method="POST">

@csrf
@method('DELETE')

<button>Delete</button>

</form>

</td>

</tr>

@endforeach

</table>


<!-- MODAL -->

<div id="modal" style="display:none; position:fixed; top:20%; left:30%; background:white; padding:20px; border:1px solid black;">

<h3 id="modalTitle"></h3>

<form id="formMahasiswa" method="POST">

@csrf

<input type="hidden" id="methodField">

NIM:
<input type="text" name="nim" id="nim">

Nama:
<input type="text" name="nama_mahasiswa" id="nama">

Alamat:
<input type="text" name="alamat" id="alamat">

<button type="submit">Save</button>

<button type="button" onclick="closeModal()">Close</button>

</form>

</div>


<script>

function openCreate(){

document.getElementById('modal').style.display='block';
document.getElementById('modalTitle').innerText='Tambah Mahasiswa';

document.getElementById('formMahasiswa').action='/mahasiswa';

document.getElementById('methodField').innerHTML='';

document.getElementById('nim').value='';
document.getElementById('nama').value='';
document.getElementById('alamat').value='';

}

function openEdit(id,nim,nama,alamat){

document.getElementById('modal').style.display='block';
document.getElementById('modalTitle').innerText='Edit Mahasiswa';

document.getElementById('formMahasiswa').action='/mahasiswa/'+id;

document.getElementById('methodField').innerHTML='@method("PUT")';

document.getElementById('nim').value=nim;
document.getElementById('nama').value=nama;
document.getElementById('alamat').value=alamat;

}

function closeModal(){

document.getElementById('modal').style.display='none';

}

</script>

@endsection
