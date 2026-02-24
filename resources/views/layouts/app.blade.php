<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Absensi</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  * { font-family: 'Plus Jakarta Sans', sans-serif; }
  :root { --crimson: #9B1C31; --crimson-light: #C5243F; --crimson-pale: #FDF2F4; }
  .sidebar-link { display:flex; align-items:center; gap:10px; padding:10px 14px; border-radius:10px; color:#6b7280; font-size:14px; font-weight:500; text-decoration:none; transition:all .18s; }
  .sidebar-link:hover { background:#FDF2F4; color:#9B1C31; }
  .sidebar-link.active { background:#9B1C31; color:white; }
  .sidebar-link.active svg { color:white; }
  .sidebar-link svg { width:18px; height:18px; flex-shrink:0; }
</style>
</head>
<body class="bg-gray-50 flex min-h-screen">

{{-- SIDEBAR --}}
<aside class="w-56 bg-white border-r border-gray-100 min-h-screen flex flex-col px-4 py-6 flex-shrink-0 shadow-sm">
  {{-- Logo --}}
  <div class="flex items-center gap-2 px-2 mb-8">
    <div class="w-9 h-9 bg-[#9B1C31] rounded-xl flex items-center justify-center flex-shrink-0">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
      </svg>
    </div>
    <div>
      <div class="text-sm font-bold text-gray-800 leading-tight">Sistem</div>
      <div class="text-xs text-[#9B1C31] font-semibold">Absensi</div>
    </div>
  </div>

  {{-- Nav --}}
  <nav class="flex flex-col gap-1 flex-1">
    <a href="/dashboard" class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
      Dashboard
    </a>
    <a href="/mata-kuliah" class="sidebar-link {{ request()->is('mata-kuliah*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
      Mata Kuliah
    </a>
    <a href="/kelas" class="sidebar-link {{ request()->is('kelas*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
      Kelas
    </a>
    <a href="/pertemuan" class="sidebar-link {{ request()->is('pertemuan*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
      Pertemuan
    </a>
    <a href="/absensi-rekap" class="sidebar-link {{ request()->is('absensi-rekap*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Rekap Absensi
    </a>
    <a href="/mahasiswa" class="sidebar-link {{ request()->is('mahasiswa*') ? 'active' : '' }}">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
      Mahasiswa
    </a>
  </nav>

  {{-- User info + Logout --}}
  <div class="border-t border-gray-100 pt-4 mt-2">
    @if(session('dosen'))
    <div class="px-2 mb-3">
      <p class="text-xs font-semibold text-gray-800 truncate">{{ session('dosen.nama_dosen') }}</p>
      <p class="text-xs text-gray-400 truncate">{{ session('dosen.email') }}</p>
    </div>
    @endif
    <a href="/logout" class="sidebar-link text-red-500 hover:bg-red-50 hover:text-red-600">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
      Logout
    </a>
  </div>
</aside>

{{-- MAIN --}}
<div class="flex-1 flex flex-col min-h-screen overflow-auto">
  {{-- Top bar --}}
  <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between flex-shrink-0">
    <div>
      <h1 class="text-lg font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
      <p class="text-xs text-gray-400 mt-0.5">@yield('page-subtitle', '')</p>
    </div>
    <div class="flex items-center gap-3">
      @if(session('dosen'))
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-[#9B1C31] rounded-full flex items-center justify-center">
          <span class="text-white text-xs font-bold">{{ strtoupper(substr(session('dosen.nama_dosen'), 0, 1)) }}</span>
        </div>
        <div class="text-right">
          <p class="text-xs font-semibold text-gray-800">{{ session('dosen.nama_dosen') }}</p>
          <p class="text-xs text-gray-400">Dosen</p>
        </div>
      </div>
      @endif
    </div>
  </header>

  {{-- Flash messages --}}
  @if(session('success'))
  <div class="mx-8 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
  </div>
  @endif
  @if(session('error'))
  <div class="mx-8 mt-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    {{ session('error') }}
  </div>
  @endif

  {{-- Content --}}
  <main class="flex-1 p-8">
    @yield('content')
  </main>
</div>

</body>
</html>