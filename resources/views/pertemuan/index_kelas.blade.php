@extends('layouts.app')
@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Kelas</h2>
    </div>

    {{-- Search Box --}}
    <div class="mb-4">
        <input
            type="text"
            id="searchKelas"
            placeholder="Cari kelas..."
            class="border border-gray-300 rounded-lg px-4 py-2 w-full max-w-sm focus:outline-none focus:ring-2 focus:ring-red-400"
            onkeyup="filterKelas()"
        >
    </div>

    {{-- List Kelas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4" id="kelasGrid">
        @forelse($kelas as $k)
        <a href="/pertemuan/{{ $k->id_kelas }}"
           class="kelas-card block bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md hover:border-red-400 transition"
           data-nama="{{ strtolower($k->nama_kelas ?? $k->id_kelas) }}"
           data-id="{{ strtolower($k->id_kelas) }}">
            <div class="flex items-center gap-3">
                <div class="bg-red-100 text-red-700 rounded-lg p-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $k->nama_kelas ?? $k->id_kelas }}</p>
                    <p class="text-xs text-gray-500">{{ $k->id_kelas }}</p>
                    @if(isset($k->nama_matkul))
                    <p class="text-xs text-red-500 mt-1">{{ $k->nama_matkul }}</p>
                    @endif
                </div>
            </div>
        </a>
        @empty
        <p class="text-gray-500 col-span-3">Belum ada kelas.</p>
        @endforelse
    </div>

    {{-- Empty state saat search tidak ketemu --}}
    <p id="emptySearch" class="text-gray-400 hidden mt-4">Kelas tidak ditemukan.</p>
</div>

<script>
function filterKelas() {
    const keyword = document.getElementById('searchKelas').value.toLowerCase();
    const cards = document.querySelectorAll('.kelas-card');
    let found = 0;

    cards.forEach(card => {
        const nama = card.dataset.nama || '';
        const id = card.dataset.id || '';
        if (nama.includes(keyword) || id.includes(keyword)) {
            card.style.display = '';
            found++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('emptySearch').classList.toggle('hidden', found > 0);
}
</script>
@endsection
