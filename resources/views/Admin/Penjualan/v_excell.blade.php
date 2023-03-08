<table style="width:100%">
    <thead>
        <tr>
            <td width="50px"></td>
            <td width="140px"></td>
            <td width="140px"></td>
            <td width="140px"></td>
            <td width="200px"></td>
            <td width="200px"></td>
            <td width="140px"></td>
            <td width="90px"></td>
            <td width="90px"></td>
            <?php for ($b = 0; $b < 3; $b++) { ?>
            <td width="100px"></td>
            <?php } ?>
            <td width="130px"></td>
            <td width="600px"></td>
        </tr>
        <tr>
            <td colspan="14" style="text-align: center;">
                <b>
                    LAPORAN PENJUALAN
                </b>
            </td>
        </tr>
        <tr>
            <td colspan="14" style="text-align: center;">
                <b>{{ strtoupper(nama_instansi()) }}</b>
            </td>
        </tr>

        <tr>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>NO</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>TANGGAL</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>RESI</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>RESI (MANUAL)</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>PENGIRIM</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>PENERIMA</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>KOTA PENERIMA</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>KILO</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>KOLI</b>
            </td>
            <td colspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>PEMBAYARAN</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>SURAT KEMBALI</b>
            </td>
            <td rowspan="3"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>KETERANGAN</b>
            </td>
        </tr>
        <tr>
            <td rowspan="2"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>CASH</b>
            </td>
            <td rowspan="2"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>COD</b>
            </td>
            <td rowspan="2"
                style="text-align: center; vertical-align: middle; border: 1px solid black; border-collapse: collapse;">
                <b>TAGIHAN</b>
            </td>
        </tr>
        <tr>
        </tr>
    </thead>
    <tbody>
        <?php
        $kilo = 0;
        $koli = 0;
        $cash = 0;
        $cod = 0;
        $tagihan = 0;
        $no = 1;
        ?>
        @foreach ($data as $v)
            <tr>
                <td style="border: 1px solid black;">{{ $no++ }}</td>
                <td style="border: 1px solid black; text-transform: uppercase;">
                    {{ $v->tgl_masuk }}</td>
                <td style="border: 1px solid black; text-transform: uppercase;">
                    {{ $v->no_resi }}</td>
                <td style="border: 1px solid black; text-transform: uppercase;">
                    {{ $v->no_resi_manual }}
                </td>
                <td style="border: 1px solid black; text-transform: uppercase;">
                    {{ $v->nama_pengirim }}</td>
                <td style="border: 1px solid black; text-transform: uppercase;">
                    {{ $v->nama_penerima }}</td>
                <td style="border: 1px solid black; text-transform: uppercase;">
                    {{ $v->kota_penerima }}</td>
                <td style="border: 1px solid black; text-align: right;">
                    {{ number_format($v->kilo, 2, ',', '.') }}
                </td>
                <td style="border: 1px solid black; text-align: right;">{{ number_format($v->koli, 2, ',', '.') }}</td>
                <td style="border: 1px solid black; text-align: right;">
                    {{ number_format($v->cash, 2, ',', '.') ?: '' }}
                </td>
                <td style="border: 1px solid black; text-align: right;">
                    {{ number_format($v->cod, 2, ',', '.') ?: '' }}
                </td>
                <td style="border: 1px solid black; text-align: right;">
                    {{ number_format($v->tagihan, 2, ',', '.') ?: '' }}
                </td>
                <td style="border: 1px solid black;">
                    {{ $v->tgl ?: '' }}</td>
                <td style="border: 1px solid black;"> {{ $v->ket }}</td>
            </tr>
            <?php
            $kilo += $v->kilo;
            $koli += $v->koli;
            $cash += $v->cash;
            $cod += $v->cod;
            $tagihan += $v->tagihan;
            ?>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                <b>TOTAL</b>
            </td>
            <td width="90px" style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                <b>{{ number_format($kilo, 2, ',', '.') }}</b>
            </td>
            <td width="90px" style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                <b>{{ number_format($koli, 2, ',', '.') }}</b>
            </td>
            <td width="100px" style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                <b>{{ number_format($cash, 2, ',', '.') }}</b>
            </td>
            <td width="100px" style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                <b>{{ number_format($cod, 2, ',', '.') }}</b>
            </td>
            <td width="100px" style="text-align: right; border: 1px solid black; border-collapse: collapse;">
                <b>{{ number_format($tagihan, 2, ',', '.') }}</b>
            </td>
            <td colspan="2" style="text-align: left; border: 1px solid black; border-collapse: collapse;"></td>
        </tr>
        <?php $total_biaya = $cash + $cod + $tagihan; ?>
        <tr style="text-transfor">
            <th style="border: 1px solid black;" colspan="9">
            </th>
            <th style="border: 1px solid black; text-align:center" colspan="3">
                <b> {{ 'Rp' . number_format($total_biaya, 2, ',', '.') }} </b>
            </th>
            <th style="border: 1px solid black;" colspan="2"></th>
        </tr>
    </tfoot>
</table>
