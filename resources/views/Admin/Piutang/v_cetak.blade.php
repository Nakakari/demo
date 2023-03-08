<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ strtoupper($invoice->pelanggan->nama_perusahaan) }}</title>
    <style>
        body {
            padding: 0cm;
            margin: 0.2cm 0.2cm;
        }

        @page {
            size: A4 portrait;
            margin: 0cm 0cm;
        }

        #watermark {
            position: fixed;

            /**
                    Set a position in the page for your image
                    This should center it vertically
                **/
            bottom: 1cm;
            left: 1cm;

            /** Change image dimensions**/
            width: 10cm;
            height: 1cm;

            /** Your watermark should be behind every content**/
            z-index: -1000;

            font-size: 11px;
        }
    </style>
</head>

<?php
use Carbon\Carbon;
setlocale(LC_ALL, 'id-ID', 'id_ID');
$diterbitkan = Carbon::createFromFormat('Y-m-d', $invoice['diterbitkan'])->format('d M Y');
?>

<body>
    @include('Surat.v_kop')
    <table style="width: 100%; border-collapse: collapse; border: 1px solid black; text-align: center; font-size: 12px;">
        <thead>

            <tr style="text-transform: uppercase;">
                <th style="border: 1px solid black;" height="50px">no.</th>
                <th style="border: 1px solid black;">no. resi
                </th>
                <th style="border: 1px solid black;">tanggal</th>
                <th style="border: 1px solid black;">
                    pengirim</th>
                <th style="border: 1px solid black;">penerima</th>
                <th style="border: 1px solid black;">kota tujuan</th>
                <th style="border: 1px solid black;">berat</th>
                <th style="border: 1px solid black;">koli</th>
                <th style="border: 1px solid black;">nominal</th>

            </tr>
        </thead>
        <tbody style="text-align: left;text-transform: uppercase;">
            <?php
            $no = 1;
            $berat = 0;
            $colly = 0;
            $cash = 0;
            $cod = 0;
            $tagihan = 0;
            ?>
            @foreach ($invoice->detailInvoice as $details)
                <?php
                $berat += $details->pengiriman->berat ?: $details->pengiriman->volume;
                $colly += $details->pengiriman->koli;
                $cash += $details->pengiriman->tipe_pembayaran == 3 ? $details->pengiriman->biaya : 0;
                $cod += $details->pengiriman->tipe_pembayaran == 2 ? $details->pengiriman->biaya : 0;
                $tagihan += $details->pengiriman->tipe_pembayaran == 1 ? $details->pengiriman->biaya : 0;
                $total_biaya = $cash + $cod + $tagihan;
                ?>
                <tr>
                    <td style="border: 1px solid black; text-align:center;">{{ $no++ }}</td>
                    <td style="border: 1px solid black; text-transform: uppercase;"> {{ $details->pengiriman->no_resi }}
                    </td>
                    <td style="border: 1px solid black;">
                        {{ Carbon::createFromFormat('Y-m-d', $details->pengiriman->tgl_masuk)->format('d M Y') }}</td>
                    <td style="border: 1px solid black;">
                        {{ strtoupper($details->pengiriman->nama_pengirim) }}</td>
                    <td style="border: 1px solid black;">{{ $details->pengiriman->nama_penerima }}</td>
                    <td style="border: 1px solid black;">{{ $details->pengiriman->kota_penerima }}</td>
                    <td style="border: 1px solid black; text-align: right;">
                        {{ number_format($details->pengiriman->berat, 0, ',', '.') ?: number_format($details->pengiriman->volume, 0, ',', '.') }}
                    </td>
                    <td style="border: 1px solid black;text-align: right;">
                        {{ number_format($details->pengiriman->koli, 0, ',', '.') }}</td>
                    <td style="border: 1px solid black; text-align: right;">
                        {{ number_format($details->pengiriman->biaya, 0, ',', '.') }}</td>

                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th style="border: 1px solid black; text-align: right;"colspan="6">TOTAL
                </th>
                <th style="border: 1px solid black; text-align: right;">{{ number_format($berat, 0, ',', '.') }}</th>
                <th style="border: 1px solid black; text-align: right;">{{ number_format($colly, 0, ',', '.') }}</th>
                <th style="border: 1px solid black; text-align: right;">{{ number_format($total_biaya, 0, ',', '.') }}
                </th>

            </tr>
            <tr>
                <th style="border: 1px solid black;text-align: right;"colspan="8">PPN (%)
                </th>
                <th style="border: 1px solid black;text-align: right;">{{ $invoice->ppn }}</th>
            </tr>
            <tr>
                <th style="border: 1px solid black;text-align: right;"colspan="8">BIAYA TOTAL
                </th>
                <?php
                $biaya_total = ($invoice->ppn / 100) * $total_biaya;
                $get_total = intval($biaya_total) + $total_biaya;
                ?>
                <th style="border: 1px solid black; text-align: right;">{{ number_format($get_total, 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>
    <table style="margin-top: 0.5cm; text-align: center; margin-bottom: 2 cm;">
        <tr>
            <td>
                Pembayaran Transfer Via {{ strtoupper($invoice->bank->nama_bank) }} No. {{ $invoice->bank->no_rek }}
                a/n {{ $invoice->bank->an }}
            </td>
        </tr>
    </table>

    <table style="padding:0;border-spacing:0px; width: 100%;">
        <tr>
            <td style="font-size: 15px; text-align: center; width: 25%; vertical-align: top;">
                <br>
                Mengetahui
                <br><br><br><br><br><br>
                Hardian Yuli H.
            </td>
            <td style="font-size: 15px; text-align: center; width: 25%; vertical-align: top;">
                <br>
                Menyetujui
                <br><br><br><br><br><br>
                Sunarto
            </td>
            <td style="font-size: 15px; text-align: center; width: 25%; vertical-align: top;">
                <br>
                Penerima
                <br>
                <br><br><br><br><br><br>
            </td>

        </tr>
        <tr>
            <td style="font-size: 8px; text-align: center; vertical-align: top;">
                Nama Lengkap, Stempel, dan Tanda Tangan
            </td>
            <td style="font-size: 8px; text-align: center; vertical-align: top;">
                Nama Lengkap dan Tanda Tangan
            </td>
            <td style="font-size: 8px; text-align: center; vertical-align: top;">
                Nama Lengkap dan Tanda Tangan
            </td>

        </tr>
    </table>
    <div id="watermark">
        <p>Invoice Dibuat Oleh: {{ ucwords(strtolower($invoice->pembuat)) }}</p>
    </div>
</body>


</html>
