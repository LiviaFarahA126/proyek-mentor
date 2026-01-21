@extends('layouts.app')

@section('content')

<div class="bg-white rounded-lg shadow p-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-700">
            Data Individu Mahasiswa
        </h2>

        <a href="/dataindividu/create"
           class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
            + Tambah Mahasiswa
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">NIM</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Alamat</th>
                    <th class="px-4 py-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mahasiswa as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border">{{ $m->nomerindukmahasiswa }}</td>
                    <td class="px-4 py-2 border">{{ $m->namamahasiswa }}</td>
                    <td class="px-4 py-2 border">{{ $m->alamatmahasiswa }}</td>
                    <td class="px-4 py-2 border text-center space-x-2">

                        <a href="/dataindividu/{{ $m->nomerindukmahasiswa }}/edit"
                           class="inline-block px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">
                            Edit
                        </a>

                        <form method="POST"
                              action="/dataindividu/{{ $m->nomerindukmahasiswa }}"
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Yakin hapus data ini?')"
                                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

@endsection
