@php $isModal = request()->has('modal'); @endphp

@if(!$isModal)
@extends('layouts.app')
@section('content')
@endif

<div class="{{ $isModal ? 'p-4' : 'p-6' }}">

    @if(!$isModal)
    <div class="mb-4">
        <a href="/pertemuan/{{ $pertemuan->id_kelas }}" class="text-sm text-blue-500 hover:underline">
            &larr; Kembali ke Pertemuan
        </a>
        <h2 class="text-xl font-bold mt-1">
            Absensi — {{ $kelas->nama_kelas ?? $kelas->id_kelas }}
        </h2>
        <p class="text-sm text-gray-500">Pertemuan {{ $pertemuan->pertemuan_ke }}
            &middot; {{ \Carbon\Carbon::parse($pertemuan->tanggal)->format('d M Y') }}
        </p>
    </div>
    @endif

    @if($absensi->isEmpty())
        <div class="py-10 text-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="font-medium">Belum ada mahasiswa di kelas ini.</p>
            <p class="text-sm mt-1">Tambahkan mahasiswa ke kelas terlebih dahulu.</p>
        </div>
    @else

    <form action="/absensi/update-status" method="POST">
        @csrf
        <input type="hidden" name="id_pertemuan" value="{{ $pertemuan->id_pertemuan }}">

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-3 py-2 text-left">No</th>
                        <th class="px-3 py-2 text-left">NIM</th>
                        <th class="px-3 py-2 text-left">Nama Mahasiswa</th>
                        <th class="px-3 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($absensi as $i => $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-3 py-2 text-gray-500">{{ $a->mahasiswa->nim ?? '-' }}</td>
                        <td class="px-3 py-2 font-medium">{{ $a->mahasiswa->nama_mahasiswa }}</td>
                        <td class="px-3 py-2">
                            <select name="status[{{ $a->id_absensi }}]"
                                class="border border-gray-300 rounded-lg px-2 py-1 text-sm w-full focus:outline-none focus:ring-2 focus:ring-blue-400
                                    {{ $a->status == 'hadir' ? 'bg-green-50 text-green-700 border-green-300' :
                                      ($a->status == 'izin'  ? 'bg-blue-50 text-blue-700 border-blue-300' :
                                      ($a->status == 'sakit' ? 'bg-yellow-50 text-yellow-700 border-yellow-300' :
                                                               'bg-red-50 text-red-700 border-red-300')) }}"
                                onchange="colorSelect(this)">
                                <option value="hadir"  {{ $a->status=='hadir'  ? 'selected':'' }}>Hadir</option>
                                <option value="izin"   {{ $a->status=='izin'   ? 'selected':'' }}>Izin</option>
                                <option value="sakit"  {{ $a->status=='sakit'  ? 'selected':'' }}>Sakit</option>
                                <option value="alpha"  {{ $a->status=='alpha'  ? 'selected':'' }}>Alpha</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Tombol Hadir Semua shortcut --}}
        <div class="flex items-center gap-3 mt-4">
            <button type="button" onclick="hadirSemua()"
                class="text-xs border border-green-400 text-green-600 px-3 py-1 rounded hover:bg-green-50">
                ✔ Hadir Semua
            </button>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium ml-auto">
                Simpan Absensi
            </button>
        </div>
    </form>

    @endif
</div>

<script>
function colorSelect(el) {
    el.className = el.className.replace(/bg-\S+ text-\S+ border-\S+/g, '');
    const map = {
        hadir: 'bg-green-50 text-green-700 border-green-300',
        izin:  'bg-blue-50 text-blue-700 border-blue-300',
        sakit: 'bg-yellow-50 text-yellow-700 border-yellow-300',
        alpha: 'bg-red-50 text-red-700 border-red-300',
    };
    el.classList.add(...(map[el.value] || '').split(' '));
}

function hadirSemua() {
    document.querySelectorAll('select[name^="status"]').forEach(sel => {
        sel.value = 'hadir';
        colorSelect(sel);
    });
}
</script>

@if(!$isModal)
@endsection
@endif
