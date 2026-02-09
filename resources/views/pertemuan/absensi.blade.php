<form method="POST" action="{{ route('absensi.store') }}">
@csrf

<input type="hidden" name="id_pertemuan" value="{{ $pertemuan_id }}">

<table>
<tr>
<th>Nama</th>
<th>Status</th>
</tr>

@foreach($data as $m)
<tr>
<td>{{ $m->nama_mahasiswa }}</td>

<td>
<select name="status[{{$m->id_mahasiswa}}]">

<option value="hadir" {{ $m->status=='hadir'?'selected':'' }}>Hadir</option>

<option value="izin" {{ $m->status=='izin'?'selected':'' }}>Izin</option>

<option value="sakit" {{ $m->status=='sakit'?'selected':'' }}>Sakit</option>

<option value="alpha" {{ $m->status=='alpha'?'selected':'' }}>Alpha</option>

</select>
</td>
</tr>
@endforeach

</table>

<button type="submit">Simpan</button>

</form>
