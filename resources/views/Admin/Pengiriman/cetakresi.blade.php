<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CETAK RESI</title>
    <style>
        body {
            padding: 0cm;
            margin: 0cm 0.5cm;
            transform: scale(1.1);
            transform-origin: 0 0;
        }

        @page {
            size: A4 portrait;
            margin: 0cm;
        }
    </style>
</head>

<body onload="window.print()">
    {{-- Color --}}
    <div style="height: 14cm; width: 19.5cm; margin-bottom: 0.2cm; border-bottom: 1px solid black;">
        <div style="display: flex; height: 17%">
            <img src="{{ url('') }}{{ logo_instansi() }}" alt="" width="25%"
                style="object-fit: contain">
            <div style="text-align: center; width: 75%;">
                <h1 style="text-transform: uppercase; color:#363f93; font-size: 35px; margin: 0px">{{ nama_instansi() }}
                </h1>
                <p style="font-size: 18px; font-style: bold; margin-top: 0px;">
                    {{ alamat_instansi() }} <br> {{ kota_instansi() }}
                    Tlp: {{ telp_instansi() }} Website: <span style="color:#363f93">{{ website_instansi() }}</span>
                </p>
            </div>
        </div>

        <div style="display: flex; padding: 0px;">
            <div style="font-size: 15px; font-weight: 400; width: 50%; text-transform: uppercase;">
                <div
                    style="width: fit-content; padding: 0px 10px; border: 3px solid red; border-radius: 40px; font-weight: 600; font-size: 25px">
                    SURAT MUATAN
                </div>
                <div style="margin: 10px 0px;">
                    <div style="font-weight: 600">
                        Pengirim : {{ $pengiriman->nama_pengirim }}
                    </div>
                    <div>
                        {{ $pengiriman->alamat_pengirim }}
                    </div>
                    <div>
                        {{ $pengiriman->kota_pengirim }}
                    </div>
                    <div>
                        {{ $pengiriman->tlp_pengirim }}
                    </div>
                </div>
                <div style="margin: 10px 0px;">
                    <div style="font-weight: 600">
                        Penerima : {{ $pengiriman->nama_penerima }}
                    </div>
                    <div>
                        {{ $pengiriman->alamat_penerima }}
                    </div>
                    <div>
                        {{ $pengiriman->kota_penerima }}
                    </div>
                    <div>
                        {{ $pengiriman->no_penerima }}
                    </div>
                </div>
            </div>
            <div style="width: 50%;">
                <div style="display: flex;  margin-bottom: 2px">
                    <div style="width: 50%">
                        <div style="color: #363f93; font-weight: 500; font-size: 22px">
                            {{ $pengiriman->no_resi }}
                        </div>
                        <div
                            style="color: #363f93; font-weight: 500; font-size: 23px; width: fit-content; border: 4px solid red; border-radius: 10px; margin-bottom: 30px; padding: 2px">
                            ASLI
                        </div>
                    </div>
                    <div
                        style="width: 50%; display: flex; justify-content: end; padding-top: 5px; padding-right: 50px; margin-bottom: 2px;">
                        {!! QrCode::size(100)->generate(route('scan-tracking') . '?search=' . $pengiriman->no_resi) !!}
                    </div>
                </div>
                <table style="color: white; border: 2px solid black; border-collapse: collapse; font-size: 15px;">
                    <tr>
                        <th colspan="3" style="background-color: red; border: 2px solid black; font-size: 17px">
                            Pembayaran
                        </th>
                    </tr>
                    <tr>
                        <td style="width: 30%; background-color: #363f93; border: 2px solid black">Bea Kirim</td>
                        <td style="width: 30%; border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->bea, 0, ',', '.') }}</td>
                        <td style="width: 30%; background-color: #363f93; border: 2px solid black">Total Biaya</td>
                    </tr>
                    <tr>
                        <td style="background-color: #363f93; border: 2px solid black;">Lainnya</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->bea_penerus, 0, ',', '.') }}</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->biaya, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #363f93; border: 2px solid black;">Bea Packging</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->bea_packing, 0, ',', '.') }}</td>
                        <td style="background-color: #363f93; border: 2px solid black;">Mode Pembayaran</td>
                    </tr>
                    <tr>
                        <td style="background-color: #363f93; border: 2px solid black;">Asuransi</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->asuransi, 0, ',', '.') }}</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ $pengiriman->nama_tipe_pemb }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <table
            style="width: 100%; border-collapse: collapse; border: 2px solid black; font-size: 16px; text-align: center; ">
            <tr style="background-color: red; color: white;  text-transform: uppercase;">
                <th style="width: 10%; border: 2px solid black;">koli</th>
                <th style="width: 30%; border: 2px solid black;">isi barang</th>
                <th style="width: 20%; border: 2px solid black;" colspan="2">berat</th>
                <th style="width: 40%; border: 2px solid black; background-color: yellow; color: black;">keterangan</th>
            </tr>
            <tr>
                <td rowspan="2" style="border: 2px solid black;">{{ number_format($pengiriman->koli, 0, ',', '.') }}
                </td>
                <td rowspan="2" style="border: 2px solid black;text-transform: capitalize;">
                    {{ $pengiriman->isi_barang }}</td>
                <td style="border: 2px solid black; background-color: #363f93; color: white">KG</td>
                <td style="border: 2px solid black; background-color: white; color: black">
                    {{ number_format($pengiriman->berat, 0, ',', '.') }}
                </td>
                <td style="width: 75px; border: 2px solid black;text-transform: capitalize;" rowspan="2">
                    {{ $pengiriman->keterangan }}</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; background-color: #363f93; color: white">M2</td>
                <td style="width: 75px; border: 2px solid black; background-color: white; color: black">
                    {{ number_format($pengiriman->volume, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; background-color: red; color: white; text-transform: uppercase;">
                    layanan</td>
                <td style="border: 2px solid black;" colspan="3">{{ $pengiriman->nama_pelayanan }}</td>
                <td
                    style="border: 2px solid black; text-transform: uppercase; background-color: yellow; color: black; text-align: center">
                    isi tidak diperiksa</td>
            </tr>
        </table>
        <div style="display: flex;">
            <div style="width: 60%; padding-top: 15px">
                <div style="text-transform: uppercase; font-size: 12px; padding-left: 7px">informasi :</div>
                <ol style="font-size: 13px; padding-left: 17px; margin: 0px; height: auto;">
                    {{-- @foreach (informasi_instansi() as $ii)
                        <li>{{$ii}}</li>
                    @endforeach --}}
                    <li>Dengan diterbitkannya resi ini, maka pelanggan telah menyetujui syarat dan ketentuan yang
                        berlaku di {{ nama_instansi() }}</li>
                    <li>CUSTOMER SERVICE : wa (0813-5741-6088), Telp ({{ telp_instansi() }})</li>
                    <li>Informasi Syarat dan Ketentuan, Lacak resi pengiriman barang dapat dilihat melalui website :
                        www.{{ website_instansi() }}</li>
                </ol>
            </div>
            <div
                style="width: 40%; display: flex; justify-content: space-between; font-size: 16px; padding: 10px 10px 0px 0px">
                <div
                    style="width: 50%;display: flex; flex-direction: column; justify-content: space-between; align-items: center">
                    <div>Penerima</div>
                    <div>(.......................)</div>
                </div>
                <div
                    style="width: 50%;display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-transform: capitalize;">
                    <div>({{ $pengiriman->kota_pengirim }}, {{ $pengiriman->tgl_masuk }})</div>
                    <div>{{ $pengiriman->nama_member }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Black White --}}
    <div
        style="filter: grayscale(100%); height: 14cm; width: 19.5cm; margin-bottom: 0.4cm;
        border-bottom: 1px solid black;">
        <div style="display: flex; height: 17%">
            <img src="{{ url('') }}{{ logo_instansi() }}" alt="" width="25%"
                style="object-fit: cover">
            <div style="text-align: center; width: 75%;">
                <h1 style="text-transform: uppercase; color:#363f93; font-size: 35px; margin: 0px">
                    {{ nama_instansi() }}</h1>
                <p style="font-size: 18px; font-style: bold; margin-top: 0px;">
                    {{ alamat_instansi() }} <br> {{ kota_instansi() }}
                    Tlp: {{ telp_instansi() }} Website: <span style="color:#363f93">{{ website_instansi() }}</span>
                </p>
            </div>
        </div>
        <div style="display: flex;  margin-bottom: 2px">
            <div style="font-size: 15px; font-weight: 400; width: 50%; text-transform: uppercase;">
                <div
                    style="width: fit-content; padding: 0px 10px; border: 3px solid red; border-radius: 40px; font-weight: 600; font-size: 25px">
                    SURAT MUATAN
                </div>
                <div style="margin: 10px 0px; text-transform: uppercase;">
                    <div style="font-weight: 600">
                        Pengirim : {{ $pengiriman->nama_pengirim }}
                    </div>
                    <div>
                        {{ $pengiriman->alamat_pengirim }}
                    </div>
                    <div>
                        {{ $pengiriman->kota_pengirim }}
                    </div>
                    <div>
                        {{ $pengiriman->tlp_pengirim }}
                    </div>
                </div>
                <div style="margin: 10px 0px; text-transform: uppercase;">
                    <div style="font-weight: 600">
                        Penerima : {{ $pengiriman->nama_penerima }}
                    </div>
                    <div>
                        {{ $pengiriman->alamat_penerima }}
                    </div>
                    <div>
                        {{ $pengiriman->kota_penerima }}
                    </div>
                    <div>
                        {{ $pengiriman->no_penerima }}
                    </div>
                </div>
            </div>
            <div style="width: 50%;">
                <div style="display: flex;">
                    <div style="width: 50%">
                        <div style="color: #363f93; font-weight: 500; font-size: 22px">
                            {{ $pengiriman->no_resi }}
                        </div>
                        <div
                            style="color: #363f93; font-weight: 500; font-size: 23px; width: fit-content; border: 4px solid red; border-radius: 10px; margin-bottom: 30px; padding: 2px">
                            COPY
                        </div>
                    </div>
                    <div
                        style="width: 50%; display: flex; justify-content: end; padding-top: 5px; padding-right: 50px; margin-bottom: 2px;">
                        {!! QrCode::size(100)->generate(route('scan-tracking') . '?search=' . $pengiriman->no_resi) !!}
                    </div>
                </div>
                <table style="color: white; border: 2px solid black; border-collapse: collapse; font-size: 15px;">
                    <tr>
                        <th colspan="3" style="background-color: red; border: 2px solid black; font-size: 17px">
                            Pembayaran
                        </th>
                    </tr>
                    <tr>
                        <td style="width: 30%; background-color: #363f93; border: 2px solid black">Bea Kirim</td>
                        <td style="width: 30%; border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->bea, 0, ',', '.') }}</td>
                        <td style="width: 30%; background-color: #363f93; border: 2px solid black">Total Biaya</td>
                    </tr>
                    <tr>
                        <td style="background-color: #363f93; border: 2px solid black;">Lainnya</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->bea_penerus, 0, ',', '.') }}</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->biaya, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td style="background-color: #363f93; border: 2px solid black;">Bea Packging</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->bea_packing, 0, ',', '.') }}</td>
                        <td style="background-color: #363f93; border: 2px solid black;">Mode Pembayaran</td>
                    </tr>
                    <tr>
                        <td style="background-color: #363f93; border: 2px solid black;">Asuransi</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ number_format($pengiriman->asuransi, 0, ',', '.') }}</td>
                        <td style="border: 2px solid black; background-color: white; color: black">
                            {{ $pengiriman->nama_tipe_pemb }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <table
            style="width: 100%; border-collapse: collapse; border: 2px solid black; font-size: 16px; text-align: center; margin-top: 2px;">
            <tr style="text-transform: uppercase; background-color: red; color: white;">
                <th style="width: 10%; border: 2px solid black;">koli</th>
                <th style="width: 30%; border: 2px solid black;">isi barang</th>
                <th style="width: 20%; border: 2px solid black;" colspan="2">berat</th>
                <th style="width: 40%; border: 2px solid black; background-color: yellow; color: black;">keterangan
                </th>
            </tr>
            <tr>
                <td rowspan="2" style="border: 2px solid black;">
                    {{ number_format($pengiriman->koli, 0, ',', '.') }}</td>
                <td rowspan="2" style="border: 2px solid black;text-transform: capitalize;">
                    {{ $pengiriman->isi_barang }}</td>
                <td style="border: 2px solid black; background-color: #363f93; color: white">KG</td>
                <td style="border: 2px solid black;">{{ number_format($pengiriman->berat, 0, ',', '.') }}</td>
                <td style="width: 75px; border: 2px solid black;text-transform: capitalize;" rowspan="2">
                    {{ $pengiriman->keterangan }}</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; background-color: #363f93; color: white">M2</td>
                <td style="width: 75px; border: 2px solid black; background-color: white; color: black;">
                    {{ number_format($pengiriman->volume, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="border: 2px solid black; background-color: red; color: white; text-transform: uppercase; ">
                    layanan</td>
                <td style="border: 2px solid black; background-color: white; color: black;" colspan="3">
                    {{ $pengiriman->nama_pelayanan }}</td>
                <td
                    style="border: 2px solid black; text-transform: uppercase; background-color: yellow; color: black; text-align: center">
                    isi tidak diperiksa</td>
            </tr>
        </table>
        <div style="display: flex;">
            <div style="width: 60%; padding-top: 15px">
                <div style="text-transform: uppercase; font-size: 12px; padding-left: 7px">informasi :</div>
                <ol style="font-size: 13px; padding-left: 17px; margin: 0px; height: auto;">
                    {{-- @foreach (informasi_instansi() as $ii)
                        <li>{{ $ii }}</li>
                    @endforeach --}}
                    <li>Dengan diterbitkannya resi ini, maka pelanggan telah menyetujui syarat dan ketentuan yang
                        berlaku di {{ nama_instansi() }}</li>
                    <li>CUSTOMER SERVICE : wa (0813-5741-6088), Telp ({{ telp_instansi() }})</li>
                    <li>Informasi Syarat dan Ketentuan, Lacak resi pengiriman barang dapat dilihat melalui website :
                        www.{{ website_instansi() }}</li>
                </ol>
            </div>
            <div
                style="width: 40%; display: flex; justify-content: space-between; font-size: 16px; padding: 10px 10px 0px 0px">
                <div
                    style="width: 50%; display: flex; flex-direction: column; justify-content: space-between; align-items: center">
                    <div>Penerima</div>
                    <div>(.......................)</div>
                </div>
                <div
                    style="width: 50%; display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-transform: capitalize;">
                    <div>({{ $pengiriman->kota_pengirim }}, {{ $pengiriman->tgl_masuk }})</div>
                    <div>{{ $pengiriman->nama_member }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-divider" style="margin-bottom: 3cm"></div>

    {{-- Black White at different page --}}
    @for ($i = 0; $i < 2; $i++)
        <div
            style="filter: grayscale(100%); height: 14cm; width: 19.5cm; margin-bottom: 0.4cm;
        border-bottom: 1px solid black;">
            <div style="display: flex; height: 17%">
                <img src="{{ url('') }}{{ logo_instansi() }}" alt="" width="25%"
                    style="object-fit: cover">
                <div style="text-align: center; width: 75%;">
                    <h1 style="text-transform: uppercase; color:#363f93; font-size: 35px; margin: 0px">
                        {{ nama_instansi() }}</h1>
                    <p style="font-size: 18px; font-style: bold; margin-top: 0px;">
                        {{ alamat_instansi() }} <br> {{ kota_instansi() }}
                        Tlp: {{ telp_instansi() }} Website: <span
                            style="color:#363f93">{{ website_instansi() }}</span>
                    </p>
                </div>
            </div>
            <div style="display: flex; padding: 0px;">
                <div style="font-size: 15px; font-weight: 400; width: 50%; text-transform: uppercase;">
                    <div
                        style="width: fit-content; padding: 0px 10px; border: 3px solid red; border-radius: 40px; font-weight: 600; font-size: 25px">
                        SURAT MUATAN
                    </div>
                    <div style="margin: 10px 0px;">
                        <div style="font-weight: 600">
                            Pengirim : {{ $pengiriman->nama_pengirim }}
                        </div>
                        <div>
                            {{ $pengiriman->alamat_pengirim }}
                        </div>
                        <div>
                            {{ $pengiriman->kota_pengirim }}
                        </div>
                        <div>
                            {{ $pengiriman->tlp_pengirim }}
                        </div>
                    </div>
                    <div style="margin: 10px 0px;">
                        <div style="font-weight: 600">
                            Penerima : {{ $pengiriman->nama_penerima }}
                        </div>
                        <div>
                            {{ $pengiriman->alamat_penerima }}
                        </div>
                        <div>
                            {{ $pengiriman->kota_penerima }}
                        </div>
                        <div>
                            {{ $pengiriman->no_penerima }}
                        </div>
                    </div>
                </div>
                <div style="width: 50%;">
                    <div style="display: flex;  margin-bottom: 2px">
                        <div style="width: 50%">
                            <div style="color: #363f93; font-weight: 500; font-size: 22px">
                                {{ $pengiriman->no_resi }}
                            </div>
                            <div
                                style="color: #363f93; font-weight: 500; font-size: 23px; width: fit-content; border: 4px solid red; border-radius: 10px; margin-bottom: 30px; padding: 2px">
                                COPY
                            </div>
                        </div>
                        <div
                            style="width: 50%; display: flex; justify-content: end; padding-top: 5px; padding-right: 50px; margin-bottom: 2px;">
                            {!! QrCode::size(100)->generate(route('scan-tracking') . '?search=' . $pengiriman->no_resi) !!}
                        </div>
                    </div>
                    <table style="color: white; border: 2px solid black; border-collapse: collapse; font-size: 15px">
                        <tr>
                            <th colspan="3"
                                style="background-color: red; border: 2px solid black; font-size: 17px">
                                Pembayaran
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 30%; background-color: #363f93; border: 2px solid black">Bea Kirim</td>
                            <td style="width: 30%; border: 2px solid black; background-color: white; color: black">
                                {{ number_format($pengiriman->bea, 0, ',', '.') }}</td>
                            <td style="width: 30%; background-color: #363f93; border: 2px solid black">Total Biaya</td>
                        </tr>
                        <tr>
                            <td style="background-color: #363f93; border: 2px solid black;">Lainnya</td>
                            <td style="border: 2px solid black; background-color: white; color: black">
                                {{ number_format($pengiriman->bea_penerus, 0, ',', '.') }}</td>
                            <td style="border: 2px solid black; background-color: white; color: black">
                                {{ number_format($pengiriman->biaya, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="background-color: #363f93; border: 2px solid black;">Bea Packging</td>
                            <td style="border: 2px solid black; background-color: white; color: black">
                                {{ number_format($pengiriman->bea_packing, 0, ',', '.') }}</td>
                            <td style="background-color: #363f93; border: 2px solid black;">Mode Pembayaran</td>
                        </tr>
                        <tr>
                            <td style="background-color: #363f93; border: 2px solid black;">Asuransi</td>
                            <td style="border: 2px solid black; background-color: white; color: black">
                                {{ number_format($pengiriman->asuransi, 0, ',', '.') }}</td>
                            <td style="border: 2px solid black; background-color: white; color: black">
                                {{ $pengiriman->nama_tipe_pemb }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <table
                style="width: 100%; border-collapse: collapse; border: 2px solid black; font-size: 16px; text-align: center; margin-top: 2px">
                <tr style="text-transform: uppercase; background-color: red; color: white;">
                    <th style="width: 10%; border: 2px solid black;">koli</th>
                    <th style="width: 30%; border: 2px solid black;">isi barang</th>
                    <th style="width: 20%; border: 2px solid black;" colspan="2">berat</th>
                    <th style="width: 40%; border: 2px solid black; background-color: yellow; color: black;">keterangan
                    </th>
                </tr>
                <tr>
                    <td rowspan="2" style="border: 2px solid black;">
                        {{ number_format($pengiriman->koli, 0, ',', '.') }}</td>
                    <td rowspan="2" style="border: 2px solid black;text-transform: capitalize;">
                        {{ $pengiriman->isi_barang }}</td>
                    <td style="border: 2px solid black; background-color: #363f93; color: white">KG</td>
                    <td style="border: 2px solid black;">{{ number_format($pengiriman->berat, 0, ',', '.') }}</td>
                    <td style="width: 75px; border: 2px solid black;text-transform: capitalize;" rowspan="2">
                        {{ $pengiriman->keterangan }}
                    </td>
                </tr>
                <tr>
                    <td style="border: 2px solid black; background-color: #363f93; color: white">M2</td>
                    <td style="width: 75px; border: 2px solid black; background-color: white; color: black;">
                        {{ number_format($pengiriman->volume, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td
                        style="border: 2px solid black; background-color: red; color: white; text-transform: uppercase; ">
                        layanan</td>
                    <td style="border: 2px solid black; background-color: white; color: black;" colspan="3">
                        {{ $pengiriman->nama_pelayanan }}</td>
                    <td
                        style="border: 2px solid black; text-transform: uppercase; background-color: yellow; color: black; text-align: center">
                        isi tidak diperiksa</td>
                </tr>
            </table>
            <div style="display: flex;">
                <div style="width: 60%; padding-top: 15px">
                    <div style="text-transform: uppercase; font-size: 12px; padding-left: 7px">informasi :</div>
                    <ol style="font-size: 13px; padding-left: 17px; margin: 0px; height: auto;">
                        {{-- @foreach (informasi_instansi() as $ii)
                            <li>{{ $ii }}</li>
                        @endforeach --}}
                        <li>Dengan diterbitkannya resi ini, maka pelanggan telah menyetujui syarat dan ketentuan yang
                            berlaku di PT. AISY SAE BERSAUDARA</li>
                        <li>CUSTOMER SERVICE : wa (0813-5741-6088), Telp ({{ telp_instansi() }})</li>
                        <li>Informasi Syarat dan Ketentuan, Lacak resi pengiriman barang dapat dilihat melalui website :
                            www.{{ website_instansi() }}</li>
                    </ol>
                </div>
                <div
                    style="width: 40%; display: flex; justify-content: space-between; font-size: 16px; padding: 10px 10px 0px 0px">
                    <div
                        style="width: 50%; display: flex; flex-direction: column; justify-content: space-between; align-items: center">
                        <div>Penerima</div>
                        <div>(.......................)</div>
                    </div>
                    <div
                        style=" width: 50%; display: flex; flex-direction: column; justify-content: space-between; align-items: center; text-transform: capitalize;">
                        <div>({{ $pengiriman->kota_pengirim }}, {{ $pengiriman->tgl_masuk }})</div>
                        <div>{{ $pengiriman->nama_member }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endfor

</body>

</html>
