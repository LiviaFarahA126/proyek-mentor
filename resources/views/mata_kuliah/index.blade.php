@extends('layouts.app')

@section('content')

<!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<div class="p-6">

<h2 class="text-xl font-bold mb-4">Data Mata Kuliah</h2>

<button onclick="openCreate()" 
class="bg-blue-500 text-white px-4 py-2 rounded mb-4">
Tambah Mata Kuliah
</button>

<table class="w-full border">

<tr class="bg-gray-200">
<th>ID</th>
<th>Nama MK</th>
<th>SKS</th>
<th>Aksi</th>
</tr>

@foreach($mk as $m)

<tr class="border">

<td>{{ $m->id_mk }}</td>
<td>{{ $m->nama_mk }}</td>
<td>{{ $m->sks }}</td>

<td>

<button onclick="openEdit(
'{{ $m->id_mk }}',
'{{ $m->nama_mk }}',
'{{ $m->sks }}'
)" class="text-blue-500">Edit</button>

<form action="/mata-kuliah/{{ $m->id_mk }}" method="POST" class="inline">

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

<div class="bg-white p-6 rounded">

<h3 id="title"></h3>

<form id="formMK" method="POST">

@csrf

<span id="method"></span>

Nama MK:
<input type="text" name="nama_mk" id="nama" class="border block mb-2">

SKS:
<input type="number" name="sks" id="sks" class="border block mb-2">

<button class="bg-green-500 text-white px-4 py-2 rounded">
Save
</button>

<button type="button" onclick="closeModal()">Close</button>

</form>

</div>

</div>


<script>

function openCreate(){

modal.classList.remove('hidden');

title.innerText='Tambah Mata Kuliah';

formMK.action='/mata-kuliah';

method.innerHTML='';

nama.value='';
sks.value='';

}

function openEdit(id,namaMK,sksMK){

modal.classList.remove('hidden');

title.innerText='Edit Mata Kuliah';

formMK.action='/mata-kuliah/'+id;

method.innerHTML='@method("PUT")';

nama.value=namaMK;
sks.value=sksMK;

}

function closeModal(){

modal.classList.add('hidden');

}

</script>

@endsection
