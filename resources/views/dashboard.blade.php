@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('page-subtitle', \Carbon\Carbon::now()->translatedFormat('l, d F Y'))

@section('content')

{{-- Libur hari ini banner --}}
@if($liburHariIni)
<div class="mb-5 bg-[#9B1C31] text-white px-5 py-3 rounded-xl flex items-center gap-3">
  <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
  </svg>
  <span class="font-semibold text-sm">Hari ini adalah Hari Libur Nasional: {{ $liburHariIni['nama'] }}</span>
</div>
@endif

{{-- ── STAT CARDS ─────────────────────────────────── --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

  {{-- Jam realtime --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 col-span-2 lg:col-span-1">
    <div class="flex items-start justify-between mb-3">
      <div class="w-10 h-10 bg-[#FDF2F4] rounded-xl flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9B1C31]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
    </div>
    <p id="clock" class="text-2xl font-bold text-gray-800 tabular-nums tracking-tight"></p>
    <p class="text-xs text-gray-400 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    <p class="text-xs text-[#9B1C31] font-medium mt-0.5">Realtime</p>
  </div>

  {{-- Total Mahasiswa --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="w-10 h-10 bg-[#FDF2F4] rounded-xl flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9B1C31]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
    <p class="text-xs text-gray-500 mt-1 font-medium">Total Mahasiswa</p>
  </div>

  {{-- Total Mata Kuliah --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="w-10 h-10 bg-[#FDF2F4] rounded-xl flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9B1C31]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold text-gray-800">{{ $totalMataKuliah }}</p>
    <p class="text-xs text-gray-500 mt-1 font-medium">Total Mata Kuliah</p>
  </div>

  {{-- Total Kelas --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <div class="flex items-start justify-between mb-4">
      <div class="w-10 h-10 bg-[#FDF2F4] rounded-xl flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9B1C31]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold text-gray-800">{{ $totalKelas }}</p>
    <p class="text-xs text-gray-500 mt-1 font-medium">Total Kelas</p>
  </div>

  {{-- Pertemuan Aktif Hari Ini --}}
  <div class="bg-[#9B1C31] rounded-2xl shadow-sm p-5 col-span-2 lg:col-span-1">
    <div class="flex items-start justify-between mb-4">
      <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
      </div>
    </div>
    <p class="text-3xl font-bold text-white">{{ $pertemuanAktif }}</p>
    <p class="text-xs text-red-200 mt-1 font-medium">Pertemuan Aktif Hari Ini</p>
  </div>

</div>

{{-- ── ROW 2: Notifikasi + Kalender ──────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-6">

  {{-- Notifikasi Libur --}}
  <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-gray-800">Pengingat Libur Nasional</h2>
      <span class="text-xs text-gray-400">30 hari ke depan</span>
    </div>

    @if($notifikasi->isEmpty())
    <div class="py-6 text-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
      </svg>
      <p class="text-sm text-gray-400">Tidak ada libur nasional dalam 30 hari ke depan.</p>
    </div>
    @else
    <div class="space-y-2">
      @foreach($notifikasi as $n)
      <div class="flex items-center gap-3 bg-[#FDF2F4] rounded-xl px-4 py-3">
        <div class="w-8 h-8 bg-[#9B1C31] rounded-lg flex items-center justify-center flex-shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-semibold text-gray-800 truncate">{{ $n['nama'] }}</p>
          <p class="text-xs text-gray-500">{{ $n['label_tgl'] }}</p>
        </div>
        <span class="text-xs font-semibold text-[#9B1C31] bg-white px-2 py-1 rounded-lg flex-shrink-0">
          @if($n['hari_lagi'] == 0) Hari ini
          @elseif($n['hari_lagi'] == 1) Besok
          @else {{ $n['hari_lagi'] }} hari lagi
          @endif
        </span>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  {{-- Mini Kalender --}}
  <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
      <button id="prevMonth" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </button>
      <h3 id="calTitle" class="text-sm font-bold text-gray-800"></h3>
      <button id="nextMonth" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </button>
    </div>

    {{-- Day headers --}}
    <div class="grid grid-cols-7 mb-2">
      @foreach(['Min','Sen','Sel','Rab','Kam','Jum','Sab'] as $d)
      <div class="text-center text-xs text-gray-400 font-medium py-1">{{ $d }}</div>
      @endforeach
    </div>

    {{-- Calendar grid --}}
    <div id="calGrid" class="grid grid-cols-7 gap-y-1"></div>
  </div>

</div>

{{-- ── ROW 3: Pertemuan Hari Ini ─────────────────────── --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
  <div class="flex items-center justify-between mb-4">
    <h2 class="font-bold text-gray-800">Pertemuan Hari Ini</h2>
    <a href="/pertemuan" class="text-xs text-[#9B1C31] hover:underline font-medium">Lihat semua</a>
  </div>

  @if($pertemuanHariIni->isEmpty())
  <div class="py-8 text-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-200 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    <p class="text-sm text-gray-400">Tidak ada pertemuan hari ini.</p>
  </div>
  @else
  <div class="space-y-2">
    @foreach($pertemuanHariIni as $p)
    <div class="flex items-center gap-4 border border-gray-100 rounded-xl px-4 py-3 hover:bg-gray-50 transition">
      <div class="w-10 h-10 bg-[#FDF2F4] rounded-xl flex items-center justify-center flex-shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9B1C31]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>
      <div class="flex-1 min-w-0">
        <p class="font-semibold text-sm text-gray-800 truncate">{{ $p->nama_mk }}</p>
        <p class="text-xs text-gray-400">
          {{ $p->id_kelas }} &middot; Pertemuan {{ $p->pertemuan_ke }}
          @if($p->nama_dosen) &middot; {{ $p->nama_dosen }} @endif
        </p>
      </div>
      @if($p->jam_mulai)
      <span class="text-xs font-medium text-gray-500 flex-shrink-0">
        {{ $p->jam_mulai }}{{ $p->jam_selesai ? ' – '.$p->jam_selesai : '' }}
      </span>
      @endif
      <a href="/pertemuan/{{ $p->id_kelas }}"
        class="flex-shrink-0 text-xs border border-[#9B1C31] text-[#9B1C31] hover:bg-[#9B1C31] hover:text-white px-3 py-1.5 rounded-lg transition font-medium">
        Buka
      </a>
    </div>
    @endforeach
  </div>
  @endif
</div>

<script>
// ── Clock ─────────────────────────────────────────────
function updateClock() {
  const now  = new Date();
  const time = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit' });
  const el   = document.getElementById('clock');
  if (el) el.textContent = time;
}
updateClock();
setInterval(updateClock, 1000);

// ── Calendar ──────────────────────────────────────────
const MONTHS = ['Januari','Februari','Maret','April','Mei','Juni',
                'Juli','Agustus','September','Oktober','November','Desember'];

// Libur yang di-highlight (dari PHP)
const LIBUR = @json(\App\Http\Controllers\DashboardController::getLiburDates());

let curYear  = {{ now()->year }};
let curMonth = {{ now()->month - 1 }}; // 0-indexed
const todayDate = {{ now()->day }};
const todayMonth = {{ now()->month - 1 }};
const todayYear  = {{ now()->year }};

function renderCalendar(year, month) {
  document.getElementById('calTitle').textContent = MONTHS[month] + ' ' + year;
  const grid = document.getElementById('calGrid');
  grid.innerHTML = '';

  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();

  // Padding awal
  for (let i = 0; i < firstDay; i++) {
    grid.innerHTML += '<div></div>';
  }

  for (let d = 1; d <= daysInMonth; d++) {
    const dateStr = year + '-' + String(month+1).padStart(2,'0') + '-' + String(d).padStart(2,'0');
    const isToday = (d === todayDate && month === todayMonth && year === todayYear);
    const isLibur = LIBUR.includes(dateStr);
    const isSunday = new Date(year, month, d).getDay() === 0;

    let cls = 'text-center text-xs py-1.5 rounded-lg font-medium cursor-default ';
    if (isToday)      cls += 'bg-[#9B1C31] text-white ';
    else if (isLibur) cls += 'bg-[#FDF2F4] text-[#9B1C31] font-bold ';
    else if (isSunday) cls += 'text-red-400 ';
    else               cls += 'text-gray-700 hover:bg-gray-50 ';

    grid.innerHTML += `<div class="${cls}" title="${isLibur && !isToday ? 'Hari Libur' : ''}">${d}</div>`;
  }
}

renderCalendar(curYear, curMonth);

document.getElementById('prevMonth').addEventListener('click', () => {
  curMonth--; if (curMonth < 0) { curMonth = 11; curYear--; }
  renderCalendar(curYear, curMonth);
});
document.getElementById('nextMonth').addEventListener('click', () => {
  curMonth++; if (curMonth > 11) { curMonth = 0; curYear++; }
  renderCalendar(curYear, curMonth);
});
</script>

@endsection