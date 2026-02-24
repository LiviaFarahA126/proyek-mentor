<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Sistem Absensi</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-sm mx-4">
  {{-- Logo --}}
  <div class="text-center mb-8">
    <div class="text-5xl mb-3">📚</div>
    <h1 class="text-2xl font-bold text-gray-800">Sistem Absensi</h1>
    <p class="text-gray-500 text-sm mt-1">Silakan login untuk melanjutkan</p>
  </div>

  {{-- Tab pilih role --}}
  <div class="flex rounded-xl bg-gray-200 p-1 mb-6">
    <button id="tabDosen" onclick="switchRole('dosen')"
      class="flex-1 py-2 rounded-lg text-sm font-medium transition-all bg-white shadow text-blue-600">
      👨‍🏫 Dosen
    </button>
    <button id="tabMhs" onclick="switchRole('mahasiswa')"
      class="flex-1 py-2 rounded-lg text-sm font-medium transition-all text-gray-500">
      🎓 Mahasiswa
    </button>
  </div>

  {{-- Card form --}}
  <div class="bg-white rounded-2xl shadow-lg p-7">

    @if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
      {{ session('error') }}
    </div>
    @endif

    {{-- FORM DOSEN --}}
    <form id="formDosen" action="/login/dosen" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="text-sm font-medium text-gray-700 block mb-1">Email</label>
        <input type="email" name="email" placeholder="email@kampus.ac.id" required
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700 block mb-1">Password</label>
        <input type="password" name="password" placeholder="••••••••" required
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <button type="submit"
        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg text-sm font-medium mt-2">
        Login sebagai Dosen
      </button>
    </form>

    {{-- FORM MAHASISWA --}}
    <form id="formMahasiswa" action="/login/mahasiswa" method="POST" class="space-y-4 hidden">
      @csrf
      <div>
        <label class="text-sm font-medium text-gray-700 block mb-1">ID Mahasiswa</label>
        <input type="text" name="id_mahasiswa" placeholder="Contoh: MHS001" required
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700 block mb-1">NIM</label>
        <input type="text" name="nim" placeholder="Nomor Induk Mahasiswa" required
          class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
      </div>
      <button type="submit"
        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-lg text-sm font-medium mt-2">
        Login sebagai Mahasiswa
      </button>
    </form>
  </div>

  <p class="text-center text-xs text-gray-400 mt-6">Sistem Absensi Mahasiswa &copy; {{ date('Y') }}</p>
</div>

<script>
function switchRole(role) {
  const isDosen = role === 'dosen';
  document.getElementById('formDosen').classList.toggle('hidden', !isDosen);
  document.getElementById('formMahasiswa').classList.toggle('hidden', isDosen);

  const tabDosen = document.getElementById('tabDosen');
  const tabMhs   = document.getElementById('tabMhs');

  if (isDosen) {
    tabDosen.className = 'flex-1 py-2 rounded-lg text-sm font-medium transition-all bg-white shadow text-blue-600';
    tabMhs.className   = 'flex-1 py-2 rounded-lg text-sm font-medium transition-all text-gray-500';
  } else {
    tabMhs.className   = 'flex-1 py-2 rounded-lg text-sm font-medium transition-all bg-white shadow text-indigo-600';
    tabDosen.className = 'flex-1 py-2 rounded-lg text-sm font-medium transition-all text-gray-500';
  }
}
</script>
</body>
</html>