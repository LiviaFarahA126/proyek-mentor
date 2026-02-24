<form action="/absensi/store" method="POST">

@csrf

<input type="hidden" name="id_pertemuan" value="{{ $pertemuan->id_pertemuan }}">

<input type="hidden" name="id_kelas" value="{{ $pertemuan->id_kelas }}">

<table>

@foreach($mahasiswa as $m)

<tr>

<td>{{ $m->nama }}</td>

<td>

<select name="status[{{ $m->id_mahasiswa }}]">

<option value="hadir">Hadir</option>

<option value="izin">Izin</option>

<option value="sakit">Sakit</option>

<option value="alpha">Alpha</option>

</select>

</td>

</tr>

@endforeach

</table>

<button>Simpan Absensi</button>

</form>
