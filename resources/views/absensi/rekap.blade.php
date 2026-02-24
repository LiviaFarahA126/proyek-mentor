@extends('layouts.app')
@section('page-title', 'Rekap Absensi')
@section('page-subtitle', 'Lihat dan unduh data kehadiran mahasiswa')

@section('content')

{{-- Tab mode --}}
<div class="flex gap-2 mb-6">
  <a href="?mode=kelas{{ $id_kelas ? '&id_kelas='.$id_kelas : '' }}"
    class="px-4 py-2 rounded-lg text-sm font-medium border transition
      {{ $mode === 'kelas' ? 'bg-[#9B1C31] text-white border-[#9B1C31]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#9B1C31] hover:text-[#9B1C31]' }}">
    <div class="flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
      </svg>
      Per Kelas
    </div>
  </a>
  <a href="?mode=mahasiswa{{ $id_mhs ? '&id_mahasiswa='.$id_mhs : '' }}"
    class="px-4 py-2 rounded-lg text-sm font-medium border transition
      {{ $mode === 'mahasiswa' ? 'bg-[#9B1C31] text-white border-[#9B1C31]' : 'bg-white text-gray-600 border-gray-200 hover:border-[#9B1C31] hover:text-[#9B1C31]' }}">
    <div class="flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
      Per Mahasiswa
    </div>
  </a>
</div>

{{-- Filter form --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-5">
  <form method="GET" action="/absensi-rekap" class="flex flex-wrap items-end gap-3">
    <input type="hidden" name="mode" value="{{ $mode }}">

    @if($mode === 'kelas')
    <div class="flex-1 min-w-48">
      <label class="text-xs font-medium text-gray-600 block mb-1">Pilih Kelas</label>
      <select name="id_kelas"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#9B1C31]">
        <option value="">-- Pilih Kelas --</option>
        @foreach($kelasList as $k)
        <option value="{{ $k->id_kelas }}" {{ $id_kelas == $k->id_kelas ? 'selected' : '' }}>
          {{ $k->nama_mk }} ({{ $k->id_kelas }}){{ $k->tahun_ajaran ? ' · TA '.$k->tahun_ajaran : '' }}
        </option>
        @endforeach
      </select>
    </div>
    @else
    <div class="flex-1 min-w-48">
      <label class="text-xs font-medium text-gray-600 block mb-1">Pilih Mahasiswa</label>
      <select name="id_mahasiswa"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#9B1C31]">
        <option value="">-- Pilih Mahasiswa --</option>
        @foreach($mahasiswaList as $m)
        <option value="{{ $m->id_mahasiswa }}" {{ $id_mhs == $m->id_mahasiswa ? 'selected' : '' }}>
          {{ $m->nama_mahasiswa }} ({{ $m->nim }})
        </option>
        @endforeach
      </select>
    </div>
    @endif

    <button type="submit"
      class="bg-[#9B1C31] hover:bg-[#7f1728] text-white px-5 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
      </svg>
      Tampilkan
    </button>

    @if($rekap->isNotEmpty())
    <a href="/absensi-rekap/export?mode={{ $mode }}&{{ $mode === 'kelas' ? 'id_kelas='.$id_kelas : 'id_mahasiswa='.$id_mhs }}"
      class="border border-gray-300 text-gray-600 hover:border-[#9B1C31] hover:text-[#9B1C31] px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition bg-white">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
      </svg>
      Unduh CSV
    </a>
    @endif
  </form>
</div>

{{-- ── HASIL: Per Kelas ──────────────────────────────── --}}
@if($mode === 'kelas')

  @if(!$id_kelas)
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    <p class="font-medium">Pilih kelas untuk melihat rekap absensi.</p>
  </div>

  @elseif($rekap->isEmpty())
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
    <p>Belum ada data absensi untuk kelas ini, atau belum ada mahasiswa yang terdaftar di KRS.</p>
  </div>

  @else
  {{-- Info kelas --}}
  @if($selectedKelas)
  <div class="flex items-center gap-3 mb-3">
    <div>
      <h3 class="font-bold text-gray-800">{{ $selectedKelas->nama_mk }}</h3>
      <p class="text-xs text-gray-400">{{ $selectedKelas->id_kelas }}{{ $selectedKelas->nama_dosen ? ' · '.$selectedKelas->nama_dosen : '' }}</p>
    </div>
    <span class="ml-auto text-xs text-gray-500">{{ $rekap->count() }} mahasiswa · {{ $pertemuanList->count() }} pertemuan</span>
  </div>
  @endif

  {{-- Tabel rekap per kelas --}}
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-xs">
        <thead class="bg-gray-50 text-gray-500 uppercase">
          <tr>
            <th class="px-4 py-3 text-left sticky left-0 bg-gray-50 z-10">Mahasiswa</th>
            @foreach($pertemuanList as $p)
            <th class="px-2 py-3 text-center whitespace-nowrap">P{{ $p->pertemuan_ke }}</th>
            @endforeach
            <th class="px-3 py-3 text-center bg-green-50 text-green-700">H</th>
            <th class="px-3 py-3 text-center bg-blue-50 text-blue-700">I</th>
            <th class="px-3 py-3 text-center bg-yellow-50 text-yellow-700">S</th>
            <th class="px-3 py-3 text-center bg-red-50 text-red-700">A</th>
            <th class="px-3 py-3 text-center">%</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @foreach($rekap as $r)
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 sticky left-0 bg-white z-10">
              <p class="font-semibold text-gray-800">{{ $r->nama_mahasiswa }}</p>
              <p class="text-gray-400">{{ $r->nim }}</p>
            </td>
            @foreach($r->detail as $status)
            <td class="px-2 py-3 text-center">
              @if($status === 'hadir')
                <span class="inline-block w-6 h-6 bg-green-100 text-green-700 rounded font-bold text-xs leading-6">H</span>
              @elseif($status === 'izin')
                <span class="inline-block w-6 h-6 bg-blue-100 text-blue-700 rounded font-bold text-xs leading-6">I</span>
              @elseif($status === 'sakit')
                <span class="inline-block w-6 h-6 bg-yellow-100 text-yellow-700 rounded font-bold text-xs leading-6">S</span>
              @elseif($status === 'alpha')
                <span class="inline-block w-6 h-6 bg-red-100 text-red-700 rounded font-bold text-xs leading-6">A</span>
              @else
                <span class="text-gray-300">—</span>
              @endif
            </td>
            @endforeach
            <td class="px-3 py-3 text-center font-semibold text-green-700">{{ $r->hadir }}</td>
            <td class="px-3 py-3 text-center font-semibold text-blue-700">{{ $r->izin }}</td>
            <td class="px-3 py-3 text-center font-semibold text-yellow-700">{{ $r->sakit }}</td>
            <td class="px-3 py-3 text-center font-semibold text-red-700">{{ $r->alpha }}</td>
            <td class="px-3 py-3 text-center">
              <span class="font-bold {{ $r->persen_hadir >= 75 ? 'text-green-600' : 'text-red-500' }}">
                {{ $r->persen_hadir }}%
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    {{-- Legenda --}}
    <div class="px-4 py-3 border-t border-gray-100 flex gap-4 text-xs text-gray-500 flex-wrap">
      <span class="flex items-center gap-1.5"><span class="w-4 h-4 bg-green-100 text-green-700 rounded text-xs font-bold text-center leading-4">H</span> Hadir</span>
      <span class="flex items-center gap-1.5"><span class="w-4 h-4 bg-blue-100 text-blue-700 rounded text-xs font-bold text-center leading-4">I</span> Izin</span>
      <span class="flex items-center gap-1.5"><span class="w-4 h-4 bg-yellow-100 text-yellow-700 rounded text-xs font-bold text-center leading-4">S</span> Sakit</span>
      <span class="flex items-center gap-1.5"><span class="w-4 h-4 bg-red-100 text-red-700 rounded text-xs font-bold text-center leading-4">A</span> Alpha</span>
      <span class="ml-auto text-gray-400">Merah = kehadiran &lt; 75%</span>
    </div>
  </div>
  @endif

{{-- ── HASIL: Per Mahasiswa ──────────────────────────── --}}
@elseif($mode === 'mahasiswa')

  @if(!$id_mhs)
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    <p class="font-medium">Pilih mahasiswa untuk melihat rekap kehadiran.</p>
  </div>

  @elseif($rekap->isEmpty())
  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10 text-center text-gray-400">
    <p>Belum ada data absensi untuk mahasiswa ini.</p>
  </div>

  @else
  @if($selectedMhs)
  <div class="flex items-center gap-3 mb-3">
    <div class="w-10 h-10 bg-[#FDF2F4] rounded-xl flex items-center justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#9B1C31]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
      </svg>
    </div>
    <div>
      <h3 class="font-bold text-gray-800">{{ $selectedMhs->nama_mahasiswa }}</h3>
      <p class="text-xs text-gray-400">NIM {{ $selectedMhs->nim }}</p>
    </div>
    <span class="ml-auto text-xs text-gray-500">{{ $rekap->count() }} pertemuan tercatat</span>
  </div>
  @endif

  <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    {{-- Summary per mata kuliah --}}
    @php
      $grouped = $rekap->groupBy('nama_mk');
    @endphp
    @foreach($grouped as $namaMk => $rows)
    <div class="border-b border-gray-100 last:border-0">
      <div class="px-5 py-3 bg-gray-50 flex items-center justify-between">
        <h4 class="text-sm font-semibold text-gray-800">{{ $namaMk }}</h4>
        @php
          $hadirCount = $rows->where('status','hadir')->count();
          $pct = round($hadirCount / $rows->count() * 100);
        @endphp
        <span class="text-xs font-bold {{ $pct >= 75 ? 'text-green-600' : 'text-red-500' }}">
          Kehadiran {{ $pct }}%
        </span>
      </div>
      <table class="w-full text-sm">
        <tbody class="divide-y divide-gray-50">
          @foreach($rows as $row)
          <tr class="hover:bg-gray-50">
            <td class="px-5 py-2.5 text-gray-500 text-xs w-32">
              {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
            </td>
            <td class="px-2 py-2.5 text-gray-500 text-xs">Pertemuan {{ $row->pertemuan_ke }}</td>
            <td class="px-2 py-2.5">
              @php
                $cls = [
                  'hadir' => 'bg-green-100 text-green-700',
                  'izin'  => 'bg-blue-100 text-blue-700',
                  'sakit' => 'bg-yellow-100 text-yellow-700',
                  'alpha' => 'bg-red-100 text-red-700',
                ][$row->status] ?? 'bg-gray-100 text-gray-500';
              @endphp
              <span class="text-xs px-2 py-1 rounded-full font-medium {{ $cls }}">
                {{ ucfirst($row->status) }}
              </span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endforeach
  </div>
  @endif

@endif

@endsection