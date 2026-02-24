@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
  ✅ {{ session('success') }}
</div>
@endif

<div class="flex items-center justify-between mb-5">
  <h2 class="text-xl font-bold text-gray-800">Data Mahasiswa</h2>
  <button onclick="openCreate()"
    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
    + Tambah Mahasiswa
  </button>
</div>

<div class="overflow-x-auto rounded-lg border border-gray-100">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
      <tr>
        <th class="px-4 py-3 text-left">ID</th>
        <th class="px-4 py-3 text-left">NIM</th>
        <th class="px-4 py-3 text-left">Nama</th>
        <th class="px-4 py-3 text-left">Alamat</th>
        <th class="px-4 py-3 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      @forelse($mhs as $m)
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3 text-gray-400 text-xs font-mono">{{ $m->id_mahasiswa }}</td>
        <td class="px-4 py-3 text-gray-600">{{ $m->nim }}</td>
        <td class="px-4 py-3 font-medium text-gray-800">{{ $m->nama_mahasiswa }}</td>
        <td class="px-4 py-3 text-gray-500">{{ $m->alamat }}</td>
        <td class="px-4 py-3 text-center">
          {{-- FIX: parameter pakai nama berbeda dari id elemen DOM --}}
          <button onclick="openEdit(
              '{{ $m->id_mahasiswa }}',
              '{{ addslashes($m->nim) }}',
              '{{ addslashes($m->nama_mahasiswa) }}',
              '{{ addslashes($m->alamat) }}'
            )"
            class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</button>
          <form action="/mahasiswa/{{ $m->id_mahasiswa }}" method="POST" class="inline"
            onsubmit="return confirm('Hapus mahasiswa ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-400 hover:bg-red-500 text-white px-3 py-1 rounded text-xs">Hapus</button>
          </form>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada data mahasiswa.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- ===== MODAL TAMBAH / EDIT MAHASISWA ===== --}}
<div id="modalMahasiswa" class="hidden fixed inset-0 z-50 flex items-center justify-center"
  style="background:rgba(0,0,0,0.5)">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4" onclick="event.stopPropagation()">

    <div class="flex items-center justify-between px-6 py-4 border-b">
      <h3 id="modalMhsTitle" class="font-bold text-lg text-gray-800"></h3>
      <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
    </div>

    <form id="formMahasiswa" method="POST" class="px-6 py-4 space-y-4">
      @csrf
      <input type="hidden" name="_method" id="mhsMethod" value="POST">

      <div>
        <label class="text-sm font-medium text-gray-700">NIM</label>
        {{-- FIX: id elemen pakai prefix "fld_" supaya tidak bentrok dengan nama variabel JS --}}
        <input type="text" name="nim" id="fld_nim"
          class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          placeholder="Nomor Induk Mahasiswa">
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700">Nama Mahasiswa <span class="text-red-400">*</span></label>
        <input type="text" name="nama_mahasiswa" id="fld_nama"
          class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          placeholder="Nama lengkap" required>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700">Alamat</label>
        <input type="text" name="alamat" id="fld_alamat"
          class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          placeholder="Alamat mahasiswa">
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeModal()"
          class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Batal</button>
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
const modalMahasiswa = document.getElementById('modalMahasiswa');

function openCreate() {
  document.getElementById('modalMhsTitle').innerText = 'Tambah Mahasiswa';
  document.getElementById('formMahasiswa').action = '/mahasiswa';
  document.getElementById('mhsMethod').value = 'POST';
  // FIX: pakai id elemen dengan prefix fld_ — tidak akan bentrok dengan parameter fungsi
  document.getElementById('fld_nim').value    = '';
  document.getElementById('fld_nama').value   = '';
  document.getElementById('fld_alamat').value = '';
  modalMahasiswa.classList.remove('hidden');
}

// FIX: parameter fungsi pakai nama val_nim, val_nama, val_alamat
// sehingga tidak bertabrakan dengan document.getElementById('nim') dll
function openEdit(id, val_nim, val_nama, val_alamat) {
  document.getElementById('modalMhsTitle').innerText = 'Edit Mahasiswa';
  document.getElementById('formMahasiswa').action = '/mahasiswa/' + id;
  document.getElementById('mhsMethod').value = 'PUT';
  document.getElementById('fld_nim').value    = val_nim;
  document.getElementById('fld_nama').value   = val_nama;
  document.getElementById('fld_alamat').value = val_alamat;
  modalMahasiswa.classList.remove('hidden');
}

function closeModal() { modalMahasiswa.classList.add('hidden'); }
modalMahasiswa.addEventListener('click', closeModal);
</script>

@endsection