@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-lg font-semibold mb-4 text-gray-700">
        Tambah Mahasiswa
    </h2>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/dataindividu" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                NIM
            </label>
            <input type="text" name="nim"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Nama
            </label>
            <input type="text" name="nama"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Alamat
            </label>
            <input type="text" name="alamat"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-end gap-2">
            <a href="/dataindividu"
               class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">
                Batal
            </a>

            <button type="submit"
                class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
                Simpan
            </button>
        </div>
    </form>

</div>

@endsection
