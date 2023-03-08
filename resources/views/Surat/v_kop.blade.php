<table style="width: 100%; margin: 0px; height: 10px">
    <tr>
        <td style="width: 20%; text-align: right; margin: 0px">
            <img src="{{ path() }}" style="object-fit: contain" width="150" height="110">
        </td>
        <td style="text-align: center;">
            <h1 style="text-transform: uppercase; color:#d17709; font-size: 35px; margin: 0px">{{ nama_instansi() }}
            </h1>
            <p style="font-size: 14px; font-style: bold; margin-top: 0px;">
                {{ alamat_instansi() }} <br> {{ kota_instansi() }}
                Tlp: {{ telp_instansi() }} Website: <span style="color:#3670dc">{{ website_instansi() }}</span>
            </p>
        </td>
    </tr>
</table>
<hr style="height: 2px;">
<table width="100%" style="margin-bottom: 0.2cm;">
    <tr style="font-size: 17px;">
        <td width="50%">Kepada Yth. <br> {{ ucwords($invoice->pelanggan->nama_perusahaan) }} <br> di
            {{ ucwords(strtolower($invoice->pelanggan->kota)) }}
        </td>
        <td style="text-align: right;" width="50%">{{ kota_instansi() }},
            {{ $diterbitkan }}<br>
            Nomor: {{ strtoupper($invoice->no_invoice) }}
        </td>
    </tr>
</table>
