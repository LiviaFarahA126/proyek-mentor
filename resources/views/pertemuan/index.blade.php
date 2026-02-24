@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
  ✅ {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
  <div>
    <a href="/pertemuan" class="text-sm text-blue-500 hover:underline">&larr; Kembali ke Daftar Kelas</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-1">
      {{ $kelas->matakuliah->nama_mk ?? $kelas->id_kelas }}
    </h2>
    <p class="text-sm text-gray-400">
      {{ $kelas->id_kelas }}
      @if($kelas->dosen) &middot; {{ $kelas->dosen->nama_dosen }} @endif
      @if($kelas->tahun_ajaran) &middot; TA {{ $kelas->tahun_ajaran }} @endif
    </p>
  </div>
  <button onclick="openCreate()"
    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
    + Tambah Pertemuan
  </button>
</div>

{{-- Tabel --}}
<div class="overflow-x-auto rounded-lg border border-gray-100">
  <table class="w-full text-sm">
    <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
      <tr>
        <th class="px-4 py-3 text-center w-16">Ke-</th>
        <th class="px-4 py-3 text-left">Tanggal</th>
        <th class="px-4 py-3 text-left">Jam Mulai</th>
        <th class="px-4 py-3 text-left">Jam Selesai</th>
        <th class="px-4 py-3 text-center">Status Absen</th>
        <th class="px-4 py-3 text-center">Aksi</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
      @forelse($pertemuan as $p)
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-3 text-center font-bold text-gray-700">{{ $p->pertemuan_ke }}</td>
        <td class="px-4 py-3 text-gray-700">
          {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}
        </td>
        <td class="px-4 py-3 text-gray-500">{{ $p->jam_mulai ?? '-' }}</td>
        <td class="px-4 py-3 text-gray-500">{{ $p->jam_selesai ?? '-' }}</td>
        <td class="px-4 py-3 text-center">
          @if($p->absensi_exists)
            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">✅ Sudah</span>
          @else
            <span class="bg-gray-100 text-gray-400 text-xs px-2 py-1 rounded-full">Belum</span>
          @endif
        </td>
        <td class="px-4 py-3 text-center">
          <div class="flex items-center justify-center gap-1 flex-wrap">
            <button onclick="openAbsensi('{{ $p->id_pertemuan }}', {{ $p->pertemuan_ke }})"
              class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">Isi Absen</button>
            <button onclick="openEdit(
                '{{ $p->id_pertemuan }}',
                {{ $p->pertemuan_ke }},
                '{{ \Carbon\Carbon::parse($p->tanggal)->format('Y-m-d') }}',
                '{{ $p->jam_mulai ?? '' }}',
                '{{ $p->jam_selesai ?? '' }}'
              )"
              class="bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded text-xs">Edit</button>
            <button onclick="confirmHapus('{{ $p->id_pertemuan }}', {{ $p->pertemuan_ke }})"
              class="bg-red-400 hover:bg-red-500 text-white px-3 py-1 rounded text-xs">Hapus</button>
          </div>
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="6" class="px-4 py-10 text-center text-gray-400">
          Belum ada pertemuan. Klik "+ Tambah Pertemuan".
        </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>


{{-- ===== MODAL TAMBAH/EDIT PERTEMUAN ===== --}}
<div id="modalPertemuan" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.5)">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4" onclick="event.stopPropagation()">
    <div class="flex items-center justify-between px-6 py-4 border-b">
      <h3 id="modalTitle" class="font-bold text-lg text-gray-800"></h3>
      <button onclick="closeModalPertemuan()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
    </div>
    <form id="formPertemuan" method="POST" class="px-6 py-4 space-y-4">
      @csrf
      <input type="hidden" name="_method" id="methodField" value="POST">
      <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">

      <div>
        <label class="text-sm font-medium text-gray-700">Kelas</label>
        <input type="text" value="{{ $kelas->matakuliah->nama_mk ?? $kelas->id_kelas }} ({{ $kelas->id_kelas }})"
          class="mt-1 w-full border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-400 text-sm cursor-not-allowed" readonly>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700">Pertemuan Ke <span class="text-red-400">*</span></label>
        <input type="number" name="pertemuan_ke" id="inp_ke" min="1"
          class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
      </div>

      <div>
        <label class="text-sm font-medium text-gray-700">Tanggal <span class="text-red-400">*</span></label>
        <input type="date" name="tanggal" id="inp_tanggal"
          class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" required>
      </div>

      <div class="flex gap-3">
        <div class="flex-1">
          <label class="text-sm font-medium text-gray-700">Jam Mulai</label>
          <input type="time" name="jam_mulai" id="inp_jam_mulai"
            class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="flex-1">
          <label class="text-sm font-medium text-gray-700">Jam Selesai</label>
          <input type="time" name="jam_selesai" id="inp_jam_selesai"
            class="mt-1 w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
      </div>

      <div class="flex justify-end gap-2 pt-2">
        <button type="button" onclick="closeModalPertemuan()"
          class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Batal</button>
        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Simpan</button>
      </div>
    </form>
  </div>
</div>

{{-- ===== MODAL HAPUS ===== --}}
<div id="modalHapus" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.5)">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm mx-4 p-6" onclick="event.stopPropagation()">
    <h3 class="font-bold text-lg text-gray-800 mb-2">Hapus Pertemuan?</h3>
    <p class="text-sm text-gray-500 mb-1">Pertemuan ke-<span id="hapusKe" class="font-semibold text-gray-800"></span> akan dihapus.</p>
    <p class="text-xs text-red-500 mb-5">⚠️ Data absensi yang terkait juga akan terhapus.</p>
    <form id="formHapus" method="POST">
      @csrf
      @method('DELETE')
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeHapus()"
          class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Batal</button>
        <button type="submit"
          class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg text-sm font-medium">Ya, Hapus</button>
      </div>
    </form>
  </div>
</div>

{{-- ===== MODAL ABSENSI (AJAX) ===== --}}
<div id="modalAbsensi" class="hidden fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,0.5)">
  <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 flex flex-col" style="max-height:88vh" onclick="event.stopPropagation()">
    <div class="flex items-center justify-between px-6 py-4 border-b flex-shrink-0">
      <div>
        <h3 class="font-bold text-lg text-gray-800">Isi Absensi</h3>
        <p id="absensiSubtitle" class="text-xs text-gray-400 mt-0.5"></p>
      </div>
      <button onclick="closeAbsensi()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
    </div>
    <div id="absensiBody" class="overflow-y-auto flex-1 px-6 py-4">
      <div class="py-10 text-center text-gray-400 text-sm">Memuat data mahasiswa...</div>
    </div>
    <div id="absensiFooter" class="hidden px-6 py-4 border-t flex-shrink-0 flex items-center justify-between">
      <button type="button" onclick="hadirSemua()"
        class="text-xs border border-green-400 text-green-600 px-3 py-1.5 rounded hover:bg-green-50">✔ Hadir Semua</button>
      <div class="flex gap-2">
        <button type="button" onclick="closeAbsensi()"
          class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm">Batal</button>
        <button id="btnSimpan" type="button" onclick="submitAbsensi()"
          class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium">Simpan Absensi</button>
      </div>
    </div>
  </div>
</div>

<script>
// ---- MODAL PERTEMUAN ----
const modalPertemuan = document.getElementById('modalPertemuan');

function openCreate() {
  document.getElementById('modalTitle').innerText = 'Tambah Pertemuan';
  document.getElementById('formPertemuan').action = '/pertemuan/store';
  document.getElementById('methodField').value = 'POST';
  document.getElementById('inp_ke').value = '';
  document.getElementById('inp_tanggal').value = new Date().toISOString().slice(0,10);
  document.getElementById('inp_jam_mulai').value = '';
  document.getElementById('inp_jam_selesai').value = '';
  modalPertemuan.classList.remove('hidden');
}

function openEdit(id, ke, tanggal, jam_mulai, jam_selesai) {
  document.getElementById('modalTitle').innerText = 'Edit Pertemuan';
  document.getElementById('formPertemuan').action = '/pertemuan/' + id;
  document.getElementById('methodField').value = 'PUT';
  document.getElementById('inp_ke').value = ke;
  document.getElementById('inp_tanggal').value = tanggal;
  document.getElementById('inp_jam_mulai').value = jam_mulai;
  document.getElementById('inp_jam_selesai').value = jam_selesai;
  modalPertemuan.classList.remove('hidden');
}

function closeModalPertemuan() { modalPertemuan.classList.add('hidden'); }
modalPertemuan.addEventListener('click', closeModalPertemuan);

// ---- MODAL HAPUS ----
const modalHapus = document.getElementById('modalHapus');

function confirmHapus(id, ke) {
  document.getElementById('hapusKe').innerText = ke;
  document.getElementById('formHapus').action = '/pertemuan/' + id;
  modalHapus.classList.remove('hidden');
}
function closeHapus() { modalHapus.classList.add('hidden'); }
modalHapus.addEventListener('click', closeHapus);

// ---- MODAL ABSENSI ----
const modalAbsensi = document.getElementById('modalAbsensi');
let currentIdPertemuan = null;

function openAbsensi(id_pertemuan, ke) {
  currentIdPertemuan = id_pertemuan;
  document.getElementById('absensiSubtitle').innerText =
    '{{ $kelas->matakuliah->nama_mk ?? $kelas->id_kelas }} — Pertemuan ' + ke;
  document.getElementById('absensiBody').innerHTML =
    '<div class="py-10 text-center text-gray-400 text-sm">Memuat data mahasiswa...</div>';
  document.getElementById('absensiFooter').classList.add('hidden');
  modalAbsensi.classList.remove('hidden');

  fetch('/absensi/' + id_pertemuan + '/data')
    .then(r => r.json())
    .then(data => renderAbsensi(data))
    .catch(() => {
      document.getElementById('absensiBody').innerHTML =
        '<div class="py-10 text-center text-red-400 text-sm">Gagal memuat data.</div>';
    });
}

function renderAbsensi(data) {
  const body = document.getElementById('absensiBody');
  if (!data || data.length === 0) {
    body.innerHTML = `
      <div class="py-10 text-center">
        <div class="text-5xl mb-3">👥</div>
        <p class="font-medium text-gray-600">Belum ada mahasiswa di kelas ini.</p>
        <p class="text-xs text-gray-400 mt-1">Mahasiswa perlu mendaftar KRS untuk kelas ini terlebih dahulu.</p>
      </div>`;
    return;
  }

  const colorMap = {
    hadir:'bg-green-50 text-green-700 border-green-300',
    izin: 'bg-blue-50 text-blue-700 border-blue-300',
    sakit:'bg-yellow-50 text-yellow-700 border-yellow-300',
    alpha:'bg-red-50 text-red-700 border-red-300',
  };

  const rows = data.map((a, i) => {
    const opts = ['hadir','izin','sakit','alpha'].map(s =>
      `<option value="${s}"${a.status===s?' selected':''}>${s.charAt(0).toUpperCase()+s.slice(1)}</option>`
    ).join('');
    return `
      <tr class="border-b border-gray-100 hover:bg-gray-50">
        <td class="py-2.5 pr-2 text-gray-400 text-xs text-center">${i+1}</td>
        <td class="py-2.5 pr-3 text-gray-500 text-xs">${a.nim ?? '-'}</td>
        <td class="py-2.5 pr-4 font-medium text-gray-800 text-sm">${a.nama_mahasiswa}</td>
        <td class="py-2.5">
          <select data-id="${a.id_absensi}"
            class="absensi-select border rounded-lg px-2 py-1 text-xs w-full ${colorMap[a.status]??colorMap.hadir}"
            onchange="colorSelect(this)">${opts}</select>
        </td>
      </tr>`;
  }).join('');

  body.innerHTML = `
    <table class="w-full">
      <thead>
        <tr class="text-xs text-gray-400 uppercase border-b">
          <th class="pb-2 text-center w-8">No</th>
          <th class="pb-2 text-left">NIM</th>
          <th class="pb-2 text-left">Nama</th>
          <th class="pb-2 text-left">Status</th>
        </tr>
      </thead>
      <tbody>${rows}</tbody>
    </table>`;
  document.getElementById('absensiFooter').classList.remove('hidden');
}

function colorSelect(el) {
  const all = ['bg-green-50','text-green-700','border-green-300','bg-blue-50','text-blue-700',
    'border-blue-300','bg-yellow-50','text-yellow-700','border-yellow-300','bg-red-50','text-red-700','border-red-300'];
  const map = {
    hadir:'bg-green-50 text-green-700 border-green-300',
    izin:'bg-blue-50 text-blue-700 border-blue-300',
    sakit:'bg-yellow-50 text-yellow-700 border-yellow-300',
    alpha:'bg-red-50 text-red-700 border-red-300'
  };
  all.forEach(c => el.classList.remove(c));
  (map[el.value]||'').split(' ').forEach(c => c && el.classList.add(c));
}

function hadirSemua() {
  document.querySelectorAll('.absensi-select').forEach(sel => { sel.value='hadir'; colorSelect(sel); });
}

function submitAbsensi() {
  const selects = document.querySelectorAll('.absensi-select');
  if (!selects.length) { closeAbsensi(); return; }
  const statusData = {};
  selects.forEach(sel => { statusData[sel.dataset.id] = sel.value; });
  const btn = document.getElementById('btnSimpan');
  btn.innerText = 'Menyimpan...'; btn.disabled = true;

  fetch('/absensi/update-status', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({ id_pertemuan: currentIdPertemuan, status: statusData })
  })
  .then(r => r.json())
  .then(res => {
    if (res.success) { closeAbsensi(); location.reload(); }
    else { alert('Gagal: ' + (res.message??'coba lagi')); btn.innerText='Simpan Absensi'; btn.disabled=false; }
  })
  .catch(() => { alert('Error jaringan.'); btn.innerText='Simpan Absensi'; btn.disabled=false; });
}

function closeAbsensi() { modalAbsensi.classList.add('hidden'); currentIdPertemuan=null; }
modalAbsensi.addEventListener('click', closeAbsensi);
</script>
@endsection