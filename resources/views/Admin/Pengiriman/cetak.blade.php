<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @page {
            margin: 0px;
            padding: 0px;
        }

        body {
            font-family: "Arial", sans-serif;
        }

        .table-header {
            text-align: center;
            font-style: bold;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        td.merah {
            background-color: #ee1d23;
        }

        .merah p {
            color: white;
        }

        @media print {
            td.merah {
                background-color: #ee1d23 !important;
                -webkit-print-color-adjust: exact;
            }

            td.kuning {
                background-color: #fff101;
                -webkit-print-color-adjust: exact;
            }
        }

        @media print {
            .merah p {
                color: white !important;
            }
        }
    </style>
</head>

<body onload="window.print();">

    <table width="100%">
        <tr>
            <td width="15%">
                <img src="{{ url('') }}/img_aplikasi/ase1.png" style="height: 200px;" alt="">
            </td>
            <td width="70%" style="vertical-align: center;">
                <div style="text-align: center">
                    <span style="font-size: 42px; color:#363f93; font-style: bold">PT. AISY SAE BERSAUDARA</span>
                    <p style="padding:0; margin:0; font-size: 14px; font-style: bold">
                        Kantor pusat JL. Semut Kali, Ruko Semut Indah Blok B No.14 Surabaya <br>
                        Tlp: 031-99220818 Email: <span style="color:#363f93"><u>aisysaeexpress1@gmail.com</u></span>
                        Website: <br>
                        aisysaeexpress.com
                    </p>
                </div>
            </td>
            {{-- <td width="20%">
                <p
                    style="font-size: 32px; color:#363f93; font-style: bold; padding: 4px 4px; border: 3px solid red; display: inline-block; border-radius: 8px">
                    DEFG
                </p>
            </td> --}}
        </tr>
    </table>

    <table width="100%" style="margin-top: -16px;">
        <tr>
            <td width="50%" style="text-align: center;">
                <div
                    style="color:#363f93; border: 3px solid #ee1d23; display: inline-block; font-size: 24px; font-style:bold; padding: 2px 18px; margin-right: 8px; border-radius: 25px; text-align: center">
                    SURAT MUATAN</div>
                <div style="padding: 2px 0px; font-size: 28px; font-style:bold; display: inline-block; color:#363f93;">

                </div>
            </td>
            <td width="50%">
                <div
                    style="padding: 2px 0px; font-size: 28px; font-style:bold; display: inline-block; color:#363f93; text-align: left">
                    (RESI)
                </div>
            </td>
        </tr>
    </table>

    <table width="100%" style="border-collapse:collapse; margin-bottom: 10px">
        <tr style="min-height: 80px; max-height: 80px;">
            <td width="35%"
                style="padding-left: 12px; padding-right: 2px; vertical-align: top; text-transform: capitalize; text-align: left">
                <b>PENGIRIM :</b> <br>

                <br>

            </td>
            {{-- <td width="35%" style="vertical-align: top; text-align: center">
                <b>PENERIMA :</b> <br>

                <br>

            </td>
            <td width="30%" style="vertical-align: top; text-align: center">
                <div class="visible-print text-center">

                </div>
            </td> --}}
        </tr>
    </table>
    <table width="100%" style="border-collapse:collapse; margin-bottom: 10px">
        <tr style="min-height: 80px; max-height: 80px;">
            <td width="35%" style=" vertical-align: top; text-transform: capitalize; text-align: center">
                <b>PENGIRIM :</b> <br>

                <br>

            </td>
            {{-- <td width="35%" style="vertical-align: top; text-align: center">
                <b>PENERIMA :</b> <br>

                <br>

            </td>
            <td width="30%" style="vertical-align: top; text-align: center">
                <div class="visible-print text-center">

                </div>
            </td> --}}
        </tr>
    </table>

    <table width="100%" border="1" style="border-collapse:collapse; margin-bottom: 10px">
        <tr>
            <td class="table-header" width="10%">COLLY</td>
            <td class="table-header" width="30%">ISI BARANG</td>
            <td class="table-header">BERAT</td>
            <td class="table-header" width="20%">BIAYA</td>
            <td class="table-header">MODE BAYAR</td>
        </tr>
        <tr style="height: 64px">
            <td style="text-align: center">

            </td>
            <td style="vertical-align: top; padding: 4px 6px">

            </td>
            <td style="text-align: center">


            </td>
            <td style="text-align: center">

            </td>
            <td style="text-align: center">

            </td>
        </tr>
        <tr>
            <td class="merah" colspan="3" style="text-align: center;">
                <p style="font-size: 18px; padding: 4px 0; margin: 0; font-style: bold">ISI TIDAK
                    DIPERIKSA</p>
            </td>
            <td class="kuning" colspan="2" style="background-color: #fff101; max-height: 200px">
                <p style="font-size: 18px;font-style: bold; padding: 0; margin: 0">
                    &nbsp;No.Ref :
                </p>
            </td>
        </tr>
    </table>

    <table width="100%">
        <tr>
            <td width="15%" style="vertical-align: top; text-align: center">
                <span>PENERIMA <br><br><br><br><br></span>
                <p>(_ _ _ _ _ _ _ _)</p>
            </td>
            <td style="vertical-align: top; font-size: 11.5px; line-height: 90%;" width="50%">
                <b>KETENTUAN :</b> <br>
                1. Barang kiriman diserahkan oleh pengangkut sesuai alamat tertera <br>
                2. Dalam hal force majure seperti; kecelakaan, keterlambatan, kebakaran, perampokan tidak menjadi
                tanggung jawab pengangkut <br>
                3. Kiriman-kiriman yang mudah pecah dan busuk tidak menjadi tanggung jawab kami <br>
                4. Kiriman yang hilang diganti max 10x bea angkut kecuali yang diasuransikan melalui kami <br>
                5. Claim dilayani selambat-lambatnya 24 jam setelah penyerahan berita acara <br>
                6. Kiriman yang tidak ditanyakan dalam tempo 3 bulan, pengangkut dibebaskan dari tanggung jawab <br>
                7. Apabila penerima menolak bea angkutan, maka pengirim diwajibkan untuk membayarnya <br>
                8. Untuk pembatalan kiriman dikenakan bea pembatalan sebesar 50% dari biaya pengiriman <br>
                <b>9. KRITIK DAN SARAN HUB. WA 081-216 286 462</b>
            </td>
            <td width="15%" style="vertical-align: top; text-align: center;">
                <span style="margin-bottom: 30px">
                    <span style="text-transform: capitalize; display: inline-block;">

                    </span>, <br>

                    <br><br><br><br><br><br>
                </span>
                <span>EKSPEDISI</span>
            </td>
        </tr>
    </table>

</body>

</html>
