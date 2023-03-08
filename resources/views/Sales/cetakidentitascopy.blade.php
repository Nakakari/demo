<div>No Resi: {{$resi}}</div>
<table width="100%" border="1" style="border-collapse:collapse; margin-bottom: 10px; text-align: center">
    <thead>
    <tr>
        <th>No</th>
        <th>Nama Identitas</th>
        <th>Barcode</th>
        <th>Dari Cabang</th>
        <th>Cek Terakhir</th>
    </tr>
    </thead>
    <tbody>
        @foreach($identitas as $key => $d)
        <tr style="margin-bottom: 10px;">
            <td>{{$key}}</td>
            <td>{{$d->nama_identitas}}</td>
            <td>{!! QrCode::size(90)->generate(route('cekidentitas', $d->nama_identitas)); !!}</td>
            <td>{{$d->kota_pengirim}}</td>
            <td>{{$d->nama_kota}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
