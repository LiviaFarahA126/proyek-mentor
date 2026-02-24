@extends('layouts.app')

@section('content')

<div class="container">

<h3>Data Dosen</h3>

<button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">
Tambah Dosen
</button>

<table class="table table-bordered">

<thead>
<tr>
<th>ID Dosen</th>
<th>NIK</th>
<th>Nama</th>
<th>Email</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

@foreach($dosen as $d)
<tr>
<td>{{ $d->id_dosen }}</td>
<td>{{ $d->nik }}</td>
<td>{{ $d->nama_dosen }}</td>
<td>{{ $d->email }}</td>

<td>

<button class="btn btn-warning btn-sm"
data-bs-toggle="modal"
data-bs-target="#edit{{ $d->id_dosen }}">
Edit
</button>

<form action="{{ route('dosen.delete',$d->id_dosen) }}" method="POST" style="display:inline;">
@csrf
@method('DELETE')
<button class="btn btn-danger btn-sm">Delete</button>
</form>

</td>
</tr>

{{-- MODAL EDIT --}}
<div class="modal fade" id="edit{{ $d->id_dosen }}">

<div class="modal-dialog">

<form action="{{ route('dosen.update',$d->id_dosen) }}" method="POST">

@csrf
@method('PUT')

<div class="modal-content">

<div class="modal-header">
<h5>Edit Dosen</h5>
</div>

<div class="modal-body">

<input name="nik" value="{{ $d->nik }}" class="form-control mb-2">

<input name="nama_dosen" value="{{ $d->nama_dosen }}" class="form-control mb-2">

<input name="email" value="{{ $d->email }}" class="form-control mb-2">

<input name="password" type="password" placeholder="Password baru (opsional)" class="form-control mb-2">

</div>

<div class="modal-footer">
<button class="btn btn-success">Update</button>
</div>

</div>

</form>

</div>

</div>

@endforeach

</tbody>

</table>

</div>

{{-- MODAL TAMBAH --}}

<div class="modal fade" id="modalTambah">

<div class="modal-dialog">

<form action="{{ route('dosen.store') }}" method="POST">

@csrf

<div class="modal-content">

<div class="modal-header">
<h5>Tambah Dosen</h5>
</div>

<div class="modal-body">

<input name="id_dosen" placeholder="ID Dosen" class="form-control mb-2">

<input name="nik" placeholder="NIK" class="form-control mb-2">

<input name="nama_dosen" placeholder="Nama Dosen" class="form-control mb-2">

<input name="email" placeholder="Email" class="form-control mb-2">

<input name="password" type="password" placeholder="Password" class="form-control mb-2">

</div>

<div class="modal-footer">
<button class="btn btn-primary">Simpan</button>
</div>

</div>

</form>

</div>

</div>

@endsection
