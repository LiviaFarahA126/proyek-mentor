@extends('layouts.app')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>

<div class="p-6">

<h2 class="text-xl font-bold mb-4">Data Kelas</h2>

<button onclick="openCreate()" 
class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
Tambah Kelas
</button>

<table class="w-full border">

<tr class="bg-gray-200">
<th>Kode Kelas</th>
<th>Mata Kuliah</th>
<th>Dosen</th>
<th>Tahun</th>
<th>Aksi</th>
</tr>

@foreach($kelas as $k)

<tr class="border">

<td class="px-4 py-3 text-gray-400 text-xs font-mono">{{ $k->id_kelas }}</td>
<td>{{ $k->matakuliah->nama_mk ?? '-' }}</td>
<td>{{ $k->dosen->nama_dosen ?? '-' }}</td>
<td>{{ $k->tahun_ajaran }}</td>

<td>

<button onclick="openEdit(
'{{ $k->id_kelas }}',
'{{ $k->id_mk }}',
'{{ $k->id_dosen }}',
'{{ $k->tahun_ajaran }}'
)" class="text-blue-500">Edit</button>

<form action="/kelas/{{ $k->id_kelas }}" method="POST" class="inline">

@csrf
@method('DELETE')

<button class="text-red-500">Delete</button>

</form>

</td>

</tr>

@endforeach

</table>

</div>


<!-- MODAL -->

<div id="modal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center">

<div class="bg-white p-6 rounded w-96">

<h3 id="title" class="font-bold mb-3"></h3>

<form id="formKelas" method="POST">

@csrf
<span id="method"></span>

Mata Kuliah:
<select name="id_mk" id="id_mk" class="border block mb-2 w-full">
@foreach($matakuliah as $mk)
<option value="{{ $mk->id_mk }}">{{ $mk->nama_mk }}</option>
@endforeach
</select>

Dosen:
<select name="id_dosen" id="id_dosen" class="border block mb-2 w-full">
@foreach($dosen as $d)
<option value="{{ $d->id_dosen }}">{{ $d->nama_dosen }}</option>
@endforeach
</select>

Tahun Ajaran:
<input type="text" name="tahun_ajaran" id="tahun_ajaran" class="border block mb-2 w-full">

<button class="bg-green-500 text-white px-4 py-2 rounded">
Save
</button>

<button type="button" onclick="closeModal()">Close</button>

</form>

</div>

</div>


<script>

const modal = document.getElementById('modal');
const title = document.getElementById('title');
const formKelas = document.getElementById('formKelas');
const method = document.getElementById('method');

function openCreate(){

modal.classList.remove('hidden');

title.innerText='Tambah Kelas';

formKelas.action='/kelas';

method.innerHTML='';

document.getElementById('tahun_ajaran').value='';

}

function openEdit(id,id_mk,id_dosen,nama_kelas,tahun){

modal.classList.remove('hidden');

title.innerText='Edit Kelas';

formKelas.action='/kelas/'+id;

method.innerHTML='@method("PUT")';

document.getElementById('id_mk').value=id_mk;
document.getElementById('id_dosen').value=id_dosen;
document.getElementById('tahun_ajaran').value=tahun;

}

function closeModal(){

modal.classList.add('hidden');

}

</script>

@endsection
