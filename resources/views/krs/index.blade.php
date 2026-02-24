<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>KRS Mahasiswa</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

{{-- Navbar --}}
<nav class="bg-white border-b px-6 py-3 flex items-center justify-between shadow-sm">
  <div class="flex items-center gap-3">
    <span class="text-xl">📚</span>
    <span class="font-semibold text-gray-800">Sistem Absensi</span>
    <span class="text-gray-300">|</span>
    <span class="text-sm text-gray-500">Kartu Rencana Studi</span>
  </div>
  <div class="flex items-center gap-4">
    <span class="text-sm text-gray-600">
      🎓 <span class="font-medium">{{ $mhs['nama_mahasiswa'] }}</span>
      <span class="text-gray-400 ml-1">{{ $mhs['nim'] }}</span>
    </span>
    <a href="/logout"
      class="text-sm text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400 px-3 py-1 rounded-lg transition">
      Logout
    </a>
  </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-8">

  {{-- Flash --}}
  @if(session('success'))
  <div class="mb-5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
    ✅ {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div class="mb-5 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
    ⚠️ {{ session('error') }}
  </div>
  @endif

  {{-- ===== KELAS YANG SUDAH DIAMBIL ===== --}}
  <div class="mb-8">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-bold text-gray-800">KRS Aktif</h2>
        <p class="text-sm text-gray-400">Kelas yang sudah kamu ambil semester ini</p>
      </div>
      <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full font-medium">
        {{ $krsAktif->count() }} kelas
      </span>
    </div>

    @if($krsAktif->isEmpty())
    <div class="bg-white rounded-xl border border-dashed border-gray-300 py-10 text-center text-gray-400">
      <div class="text-4xl mb-2">📋</div>
      <p>Belum ada kelas yang diambil.</p>
      <p class="text-sm mt-1">Browse kelas di bawah dan klik "Tambah ke KRS".</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      @foreach($krsAktif as $k)
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex flex-col gap-2">
        <div class="flex items-start justify-between">
          <div>
            <h3 class="font-semibold text-gray-800">{{ $k->nama_mk }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $k->id_kelas }} &middot; {{ $k->sks }} SKS</p>
          </div>
          <span class="text-xs px-2 py-1 rounded-full font-medium
            {{ $k->tipe_kelas === 'online' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
            {{ ucfirst($k->tipe_kelas ?? 'offline') }}
          </span>
        </div>

        <div class="text-xs text-gray-500 space-y-1">
          @if($k->nama_dosen)
          <div>👨‍🏫 {{ $k->nama_dosen }}</div>
          @endif
          @if($k->nama_ruangan)
          <div>📍 {{ $k->nama_ruangan }} ({{ $k->kode_ruangan }})
            @if($k->gedung) &middot; Gedung {{ $k->gedung }} @endif
            @if($k->lantai) Lt. {{ $k->lantai }} @endif
          </div>
          @endif
          @if($k->tahun_ajaran)
          <div>📅 TA {{ $k->tahun_ajaran }}</div>
          @endif
        </div>

        <form action="/krs/{{ $k->id_krs }}" method="POST" class="mt-1"
          onsubmit="return confirm('Hapus kelas ini dari KRS?')">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="text-xs text-red-400 hover:text-red-600 border border-red-200 hover:border-red-400 px-3 py-1 rounded-lg transition">
            ✕ Hapus dari KRS
          </button>
        </form>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  {{-- ===== BROWSE KELAS TERSEDIA ===== --}}
  <div>
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-bold text-gray-800">Kelas Tersedia</h2>
        <p class="text-sm text-gray-400">Browse kelas dan tambahkan ke KRS kamu</p>
      </div>
    </div>

    {{-- Search --}}
    <div class="mb-4">
      <input type="text" id="searchKelas" placeholder="Cari nama mata kuliah, dosen, atau ruangan..."
        onkeyup="filterKelas()"
        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    @if($kelasTersedia->isEmpty())
    <div class="bg-white rounded-xl border border-dashed border-gray-300 py-10 text-center text-gray-400">
      <div class="text-4xl mb-2">🎉</div>
      <p>Semua kelas yang tersedia sudah kamu ambil!</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="kelasGrid">
      @foreach($kelasTersedia as $k)
      <div class="kelas-card bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex flex-col gap-2 hover:border-blue-300 transition"
        data-search="{{ strtolower($k->nama_mk . ' ' . $k->nama_dosen . ' ' . $k->nama_ruangan . ' ' . $k->kode_ruangan) }}">

        <div class="flex items-start justify-between">
          <div>
            <h3 class="font-semibold text-gray-800">{{ $k->nama_mk }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $k->id_kelas }} &middot; {{ $k->sks }} SKS</p>
          </div>
          <span class="text-xs px-2 py-1 rounded-full font-medium flex-shrink-0
            {{ $k->tipe_kelas === 'online' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
            {{ ucfirst($k->tipe_kelas ?? 'offline') }}
          </span>
        </div>

        <div class="text-xs text-gray-500 space-y-1">
          @if($k->nama_dosen)
          <div>👨‍🏫 {{ $k->nama_dosen }}</div>
          @endif
          @if($k->nama_ruangan)
          <div class="flex items-start gap-1">
            <span>📍</span>
            <span>
              {{ $k->nama_ruangan }}
              @if($k->kode_ruangan) ({{ $k->kode_ruangan }}) @endif
              @if($k->gedung) &middot; Gedung {{ $k->gedung }} @endif
              @if($k->lantai) Lantai {{ $k->lantai }} @endif
              @if($k->kapasitas)
              <span class="ml-1 text-gray-400">· Kapasitas {{ $k->kapasitas }}</span>
              @endif
            </span>
          </div>
          @endif
          @if($k->tahun_ajaran)
          <div>📅 TA {{ $k->tahun_ajaran }}</div>
          @endif
        </div>

        <form action="/krs/tambah" method="POST" class="mt-1">
          @csrf
          <input type="hidden" name="id_kelas" value="{{ $k->id_kelas }}">
          <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs py-2 rounded-lg font-medium transition">
            + Tambah ke KRS
          </button>
        </form>
      </div>
      @endforeach
    </div>
    <p id="emptySearch" class="hidden text-center text-gray-400 py-8">Kelas tidak ditemukan.</p>
    @endif
  </div>

</div>

<script>
function filterKelas() {
  const keyword = document.getElementById('searchKelas').value.toLowerCase();
  const cards   = document.querySelectorAll('.kelas-card');
  let found = 0;
  cards.forEach(card => {
    const match = card.dataset.search.includes(keyword);
    card.style.display = match ? '' : 'none';
    if (match) found++;
  });
  const empty = document.getElementById('emptySearch');
  if (empty) empty.classList.toggle('hidden', found > 0);
}
</script>

</body>
</html>