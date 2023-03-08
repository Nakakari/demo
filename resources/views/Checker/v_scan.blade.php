@extends('layouts.main')

@section('isi')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Scan Identitas Resi</div>
                    <div class="card-body">
                        @if (session('pesan'))
                            <div class="alert border-0 border-start border-5 border-success alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-success"><i class='bx bxs-check-circle'></i>
                                    </div>
                                    <div class="ms-3">
                                        <strong>{{ session('pesan') }}</strong>.
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif (session('gagal'))
                            <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-danger"><i class='bx bxs-message-square-x'></i>
                                    </div>
                                    <div class="ms-3">
                                        <strong>{{ session('hapus') }}</strong>.
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif (count($errors) > 0)
                            <div class="alert border-0 border-start border-5 border-danger alert-dismissible fade show py-2">
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
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
        let urlnaik = '/cek/';
        let nama_identitas = "NGW-23010003-koli4";
        let href = urlnaik+nama_identitas;
        $( document ).ready(function() {
            console.log(href);
        });
        function onScanSuccess(decodedText, decodedResult) {
            // handle the scanned code as you like, for example:
            // console.log(`Code matched = ${decodedText}`, decodedResult);
            $("#result").val(decodedText);
            window.location.href = decodedText;
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
@stop
