@extends('layouts.main')

@section('isi')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Checker</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="/checker/home"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="/checker_naik/{{ base64_encode(Auth::user()->id_cabang) }}"><i
                                class="bx bx-up-arrow"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Scan Naik ke Nopol {{ $cek->nopol }}
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Scan Identitas Resi</div>
                    <div class="card-body">
                        @if (session('pesan'))
                            <div
                                class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-success"><i class='bx bxs-check-circle'></i>
                                    </div>
                                    <div class="ms-3">
                                        <strong>{{ session('pesan') }}</strong>.
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @elseif (session('gagal'))
                            <div
                                class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-danger"><i class='bx bxs-message-square-x'></i>
                                    </div>
                                    <div class="ms-3">
                                        <strong>{{ session('gagal') }}</strong>.
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @elseif (count($errors) > 0)
                            <div
                                class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-danger"><i class='bx bxs-message-square-x'></i>
                                    </div>
                                    <div class="ms-3">
                                        @foreach ($errors->all() as $error)
                                            <h6 class="mb-0 text-danger">Wahh</h6>
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @foreach ($cab as $ca)
                            @if (Auth::user()->id_cabang == $ca->id_cabang)
                                <p>Anda login sebagai checker <b>{{ $ca->nama_kota }}
                                        ({{ $ca->kode_area }})
                                    </b>
                                </p>
                            @endif
                        @endforeach
                        <p>Scan Identitas Resi Naik Untuk Driver
                            <b>{{ $cek->driver }}</b>, Nopol
                            <b>{{ $cek->nopol }}</b>
                        </p>
                        <div id="reader" class="center col-md-8" style="margin: auto"></div>
                        <div class="center col-md-8" style="margin: auto">
                            <input type="text" id="result">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script type="text/javascript">
        function onScanSuccess(decodedText, decodedResult) {
            // handle the scanned code as you like, for example:
            // console.log(`Code matched = ${decodedText}`, decodedResult);
            let id_cek = {{ $cek->id_kolicek }};
            let urlnaik = '/cek/';
            let result = decodedText.includes("search");
            let hasil = "";
            if (result === true) {
                let position = decodedText.search("search") + 7;
                let length = decodedText.length;
                let result2 = decodedText.slice(position, length);
                hasil = urlnaik + id_cek + '/' + result2;
            } else {
                hasil = urlnaik + id_cek + '/' + decodedText;
            }
            // $("#result").val(hasil);
            window.location.href = hasil;
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            // console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            /* verbose= */
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
@stop
