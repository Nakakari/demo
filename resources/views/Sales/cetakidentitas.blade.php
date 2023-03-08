<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print</title>
    <style>
        @media print {
            @page {
                size: 10.5cm 15cm;
                margin: 0cm;
            }
        }
    </style>
</head>

<body onload="window.print();">
    <?php
    $cari['koli'] = $cari['koli'] == 0 ? 1 : $cari['koli'];
    ?>
    @for ($i = 1; $i <= $cari['koli']; $i++)
        <div style="width: 10.5cm; height: 15cm; border: 2px solid black;">
            <div style="display: flex">
                <img src="{{ url('') }}{{ logo_instansi() }}" alt="" width="25%"
                    style="object-fit: cover">
                <h1 style="text-transform: uppercase; text-align: center; color:#363f93; font-size: 27px">
                    {{ nama_instansi() }}</h1>
            </div>
            <p style="font-size: 10px; font-style: bold; text-align: center; margin-top: 0px; margin-bottom: 8px">
                {{ alamat_instansi() }} <br>
                Tlp: {{ telp_instansi() }} Email: <span
                    style="color:#363f93"><u>{{ nama_instansi() }}@gmail.com</u></span>
                Website: <br>
                {{ website_instansi() }}
            </p>
            <div style="width: 100% ;display: flex; color:#363f93; font-size: 14px; text-transform: uppercase;">
                <div style="width: 50%; text-align: center">
                    {{ $cari['no_resi'] }}
                </div>
                <div style="width: 50%; text-align: center">
                    {{ $cari['kota_penerima'] }}
                </div>
            </div>
            <div style="padding: 0px 10px; display: flex; justify-content: space-between; text-transform: uppercase; ">
                <div style="font-size: 12px; font-weight: 400;">
                    <div style="margin: 8px 0px;">
                        <div>
                            Pengirim : {{ $cari['nama_pengirim'] }}
                        </div>
                        <div>
                            {{ $cari['alamat_pengirim'] }}
                        </div>
                        <div>
                            {{ $cari['kota_pengirim'] }}
                        </div>
                        <div>
                            {{ $cari['tlp_pengirim'] }}
                        </div>
                    </div>
                    <div style="margin: 8px 0px;">
                        <div>
                            Penerima : {{ $cari['nama_penerima'] }}
                        </div>
                        <div>
                            {{ $cari['alamat_penerima'] }}
                        </div>
                        <div>
                            {{ $cari['kota_penerima'] }}
                        </div>
                        <div>
                            {{ $cari['no_penerima'] }}
                        </div>
                    </div>
                </div>
                <div style="font-size: 27px; text-align: center; width: 80%; text-align: center">
                    {!! QrCode::size(90)->generate($cari['no_resi'] . '-koli' . $i) !!}
                </div>
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 15px;">
                <tr style="background-color: yellow; color: #363f93">
                    <th style="border: 1px solid black;">Koli</th>
                    <th style="border: 1px solid black;">Isi Barang</th>
                    <th style="border: 1px solid black;">Berat</th>
                </tr>
                <tr style="height: 30px;">
                    <td style="border: 1px solid black; text-align: center">
                        {{ number_format($cari['koli'], 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: center; text-transform: capitalize">
                        {{ $cari['isi_barang'] }}</td>
                    <td style="border: 1px solid black; text-align: center">
                        {{ number_format($cari['berat'], 0, ',', '.') ?: number_format($cari['volume'], 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="background-color: yellow; color: #363f93">
                    <th style="border: 1px solid black;">Urutan</th>
                    <th style="border: 1px solid black;">Keterangan</th>
                    <th style="border: 1px solid black;">Layanan</th>
                </tr>
                <tr style="height: 30px;">
                    <td style="border: 1px solid black; text-align: center">{{ $i + 1 }}</td>
                    <td style="border: 1px solid black; text-align: center; text-transform: capitalize">
                        {{ $cari['keterangan'] }}</td>
                    <td style="border: 1px solid black; text-align: center">{{ $pelayanan->nama_pelayanan }}</td>
                </tr>
            </table>
            <div
                style="text-transform: uppercase; text-align: center; font-weight: 500; border: 1px solid black; background-color: red; color: yellow; margin-bottom: 10px">
                isi tidak diperiksa
            </div>
            <div style="text-transform: uppercase; font-size: 12px">informasi :</div>
            <ol style="font-size: 12px; padding-left: 20px; margin-top: 0px">
                {{-- @foreach (informasi_instansi() as $ii)
                    <li>{{$ii}}</li>
                @endforeach --}}
                <li>Dengan diterbitkannya resi ini, maka pelanggan telah menyetujui syarat dan ketentuan yang berlaku di
                    {{ strtoupper(nama_instansi()) }}</li>
                <li>CUSTOMER SERVICE : WA (0813-5741-6088), Telp (031-99220818)</li>
                <li>Informasi Syarat dan Ketentuan, Lacak resi pengiriman barang dapat dilihat melalui website :
                    www.aisysaeexpress.com</li>
            </ol>
        </div>
    @endfor
</body>

</html>
