<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Manifest Daftar Muat</title>
    <style>
        body {
            padding: 0cm;
            margin: 1cm 1cm;
        }

        @page {
            size: A4 landscape;
            margin: 0cm;
        }
    </style>
</head>
@foreach ($pengiriman as $data)
    <?php setlocale(LC_ALL, 'id-ID', 'id_ID'); ?>

    <body>
        <h3>MANIFEST DAFTAR MUAT</h3>
        <table width="100%" style="margin-bottom: 0.2cm;">
            <tr style="font-size: 17px;">
                <td>TANGGAL : {{ strftime('%A, %d %B %Y', strtotime($data->tgl_buat)) }}</td>
                <td style="text-transform: uppercase;">NOPOL : {{ $data->nopol }}</td>
                <td style="text-transform: uppercase;">SUPIR & KERNET : {{ $data->driver }}</td>
            </tr>
        </table>

        <table
            style="width: 100%; border-collapse: collapse; border: 1px solid black; text-align: center; font-size: 15px; margin-right: 2cm">
            <thead>
                <tr style="text-transform: uppercase;">
                    <th style="border: 1px solid black;" rowspan="2">no. resi
                    </th>
                    <th style="border: 1px solid black;" rowspan="2">
                        pengirim</th>
                    <th style="border: 1px solid black;" rowspan="2">penerima</th>
                    <th style="border: 1px solid black;" rowspan="2">kota tujuan</th>
                    <th style="border: 1px solid black;" rowspan="2">isi barang</th>
                    <th style="border: 1px solid black;" rowspan="2">berat</th>
                    <th style="border: 1px solid black;" rowspan="2">colly</th>
                    <th style="border: 1px solid black;" colspan="3">Pembayaran</th>
                    <th style="border: 1px solid black;" rowspan="2">estimasi tiba</th>
                    <th style="border: 1px solid black;" rowspan="2">keterangan</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;">CASH</th>
                    <th style="border: 1px solid black;">COD</th>
                    <th style="border: 1px solid black;">TAGIHAN</th>
                </tr>
            </thead>
            <?php
            $berat = 0;
            $colly = 0;
            $cash = 0;
            $cod = 0;
            $tagihan = 0;
            ?>
            @foreach ($data->detailmanifest as $data2)
                <tbody>
                    <tr>
                        <td style="border: 1px solid black;">{{ $data2->pengiriman->no_resi }}</td>
                        <td style="border: 1px solid black; text-transform: uppercase;">
                            {{ $data2->pengiriman->nama_pengirim }}</td>
                        <td style="border: 1px solid black; text-transform: uppercase;">
                            {{ $data2->pengiriman->nama_penerima }}</td>
                        <td style="border: 1px solid black; text-transform: uppercase;">
                            {{ $data2->pengiriman->kota_penerima }}
                        </td>
                        <td style="border: 1px solid black; text-transform: uppercase;">
                            {{ $data2->pengiriman->isi_barang }}</td>
                        <td style="border: 1px solid black;">
                            {{ number_format($data2->pengiriman->berat, 0, ',', '.') ?: number_format($data2->pengiriman->volume, 0, ',', '.') }}
                        </td>
                        <td style="border: 1px solid black;">{{ number_format($data2->pengiriman->koli, 0, ',', '.') }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $data2->pengiriman->tipe_pembayaran == 3 ? 'Rp' . number_format($data2->pengiriman->biaya, 0, ',', '.') : '' }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $data2->pengiriman->tipe_pembayaran == 2 ? 'Rp' . number_format($data2->pengiriman->biaya, 0, ',', '.') : '' }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ $data2->pengiriman->tipe_pembayaran == 1 ? 'Rp' . number_format($data2->pengiriman->biaya, 0, ',', '.') : '' }}
                        </td>
                        <td style="border: 1px solid black;">
                            {{ strftime('%d %B %Y', strtotime($data2->estimasi)) }}</td>
                        <td style="border: 1px solid black;"></td>
                    </tr>
                </tbody>
                <?php
                $berat += $data2->pengiriman->berat ?: $data2->pengiriman->volume;
                $colly += $data2->pengiriman->koli;
                $cash += $data2->pengiriman->tipe_pembayaran == 3 ? $data2->pengiriman->biaya : 0;
                $cod += $data2->pengiriman->tipe_pembayaran == 2 ? $data2->pengiriman->biaya : 0;
                $tagihan += $data2->pengiriman->tipe_pembayaran == 1 ? $data2->pengiriman->biaya : 0;
                ?>
            @endforeach
            <tfoot>
                <tr>
                    <th style="border: 1px solid black;"colspan="4">
                    </th>
                    <th style="border: 1px solid black;">TOTAL</th>
                    <th style="border: 1px solid black;">{{ number_format($berat, 0, ',', '.') }}</th>
                    <th style="border: 1px solid black;">{{ number_format($colly, 0, ',', '.') }}</th>
                    <th style="border: 1px solid black;">
                        {{ $cash == 0 ? '' : 'Rp' . number_format($cash, 0, ',', '.') }}</th>
                    <th style="border: 1px solid black;">{{ $cod == 0 ? '' : 'Rp' . number_format($cod, 0, ',', '.') }}
                    </th>
                    <th style="border: 1px solid black;">
                        {{ $tagihan == 0 ? '' : 'Rp' . number_format($tagihan, 0, ',', '.') }}</th>
                    <th style="border: 1px solid black;"></th>
                    <th style="border: 1px solid black;"></th>
                </tr>
                <?php $total_biaya = $cash + $cod + $tagihan; ?>
                <tr style="text-transfor">
                    <th style="border: 1px solid black;"colspan="4">
                    </th>
                    <th style="border: 1px solid black;" colspan="3">TOTAL PENDAPATAN MANIFEST</th>
                    <th style="border: 1px solid black;" colspan="3">
                        {{ 'Rp' . number_format($total_biaya, 0, ',', '.') }}</th>
                    <th style="border: 1px solid black;"></th>
                    <th style="border: 1px solid black;"></th>

                </tr>
            </tfoot>
        </table>
        {{-- <p>{{ strftime('%A, %d %B %Y', strtotime($data2->estimasi)) }}</p> --}}


    </body>
@endforeach

</html>
