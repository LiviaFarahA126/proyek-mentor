@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-lg font-semibold mb-4 text-gray-700">
        Edit Mahasiswa
    </h2>

    <form method="POST"
          action="/dataindividu/{{ $mhs->nomerindukmahasiswa }}"
          class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                NIM
            </label>
            <input type="text"
                   value="{{ $mhs->nomerindukmahasiswa }}"
                   disabled
                   class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Nama
            </label>
            <input type="text"
                   name="nama"
                   value="{{ $mhs->namamahasiswa }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">
                Alamat
            </label>
            <input type="text"
                   name="alamat"
                   value="{{ $mhs->alamatmahasiswa }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-end gap-2">
            <a href="/dataindividu"
               class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">
                Batal
            </a>

            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@endsection
