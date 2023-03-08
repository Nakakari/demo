@extends('layouts.main')
@section('css')
    <style>
        .table-header {
            text-align: center;
            font-style: bold;
            padding-top: 4px;
            padding-bottom: 4px;
        }
    </style>
@stop
@section('isi')
    @if ($id_cabang == Auth::user()->id_cabang)
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Checker</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="/checker/home"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Cek Koli Naik
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="card radius-10">
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

                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-2">Data cek koli naik</h6>
                            <div class="col-12">
                                @foreach ($cab as $ca)
                                    @if (Auth::user()->id_cabang == $ca->id_cabang)
                                        <p>Daftar data koli naik dari cabang <b>{{ $ca->nama_kota }}
                                                ({{ $ca->kode_area }})
                                            </b>
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="dropdown ms-auto mb-2">
                            <button id="tambah" type="button" class="btn btn-success" onclick="tambah()">
                                Buat Data Baru</button>
                        </div>
                    </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center" style="width:100%" id="resi-dt">
                        <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Tanggal Buat</th>
                            <th>Driver</th>
                            <th>Nopol</th>
                            <th>Jumlah Resi</th>
                            <th>Jumlah Koli</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <p>Whoops!</p>
    @endif
@stop
@section('modal')
    {{-- Modal Manifest --}}
    <div class="modal fade" id="modaltambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reset()"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Buat Data Baru</h5>
                            </div>
                            <hr>
                            <div class="col-12">
                                <p id="count-resi" class="mb-0">
                                </p>
                                @foreach ($cab as $ca)
                                    @if (Auth::user()->id_cabang == $ca->id_cabang)
                                        <p>Dari cabang <b>{{ $ca->nama_kota }}
                                                ({{ $ca->kode_area }})
                                            </b>
                                        </p>
                                    @endif
                                @endforeach
                            </div>

                            <form id="form-tambah" class="row g-3" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div id="isian">
                                    <div id="drvnotlp" class="row g-2 mb-2">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Driver</label>
                                            <input id="driver" type="text"
                                                   class="form-control @error('driver') is-invalid @enderror" name="driver"
                                                   required>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Nopol</label>
                                            <input id="nopol" type="text"
                                                   class="form-control @error('nopol') is-invalid @enderror" name="nopol"
                                                   required>
                                        </div>
                                    </div>
                                    <div id="tombolnya" class="col-12">
                                        <button type="submit" id="tsubmit" class="btn btn-primary ">Simpan</button>
                                        <button type="reset" id="treset" class="btn btn-danger"
                                                data-bs-dismiss="modal">Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        let list_resi = [];

        const table = $("#resi-dt").DataTable({
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "bLengthChange": true,
            "bFilter": true,

            "bInfo": true,
            "processing": true,
            "bServerSide": true,
            // "order": [
            //     [1, "asc"]
            // ],
            "scrollX": true,
            "ajax": {
                url: "{{ url('/list_naik/' . base64_encode($id_cabang)) }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "id_identitas",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },{
                "targets": 1,
                "sortable": false,
                "data": "tgl_buat",
                "render": function(data, type, row, meta) {
                    return data;
                    // console.log(recordsTotal)
                }
            }, {
                "targets": 2,
                "sortable": false,
                "data": "driver",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 3,
                "sortable": false,
                "data": "nopol",
                "render": function(data, type, row, meta) {
                    return data
                }
            }, {
                "targets": 4,
                "sortable": false,
                "data": "jumlah_resi",
                "render": function(data, type, row, meta) {
                    if(data === null){
                        return 0;
                    }else{
                        return data;
                    }
                }
            }, {
                "targets": 5,
                "sortable": false,
                "data": "jumlah_koli",
                "render": function(data, type, row, meta) {
                    if(data === null){
                        return 0;
                    }else{
                        return data;
                    }
                }
            }, {
                "targets": 6,
                "sortable": false,
                "data": "id_kolicek",
                "render": function(data, type, row, meta) {
                    const url_print1 = "/scan_identitas/" + btoa(data)
                    const url_print2 = "/resi_naik/" + btoa(data)
                    return `<div class="d-flex order-actions justify-content-center">
                            <a href="` + url_print1 + `" id="btnprn" class="ms-3"><i class="lni lni-camera"></i></a>
                            <a href="` + url_print2 + `" id="btnprn" class="ms-3"><i class="lni lni-eye"></i></a>
                        </div>`;
                }
            },
            ]
        });

        function tambah(){
            $('#modaltambah').modal('show')
        }

        $('#form-tambah').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            submitFormWaktu()
        });

        $('#form-tambah').on('reset', function(event) {
            event.preventDefault() //jangan disubmit
            reset()
        });

        function submitFormWaktu() {
            let form = $('#form-manifest');

            let driver = document.getElementById("driver").value;
            let nopol = document.getElementById("nopol").value;

            const url = "{{ url('insertnaik') }}";
            $.ajax({
                url,
                method: "POST",
                data: {
                    driver: driver,
                    nopol: nopol,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // table.ajax.reload(null, false)
                    console.log(response)
                    window.location.href = "{{ url('') }}/checker_naik/" + btoa({{ $id_cabang }});
                },
                error: function(e) {
                    // alert(jqXHR.responseJSON.errors.id_penelitian)
                    // console.log(e)
                    alert("Whops!")
                }
            })
        }

        function reset() {
            $('#drvnotlp').remove();
            $('#tombolnya').remove();

            $('#isian').append(
                `<div id="drvnotlp" class="row g-2 mb-2">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Driver</label>
                                            <input id="driver" type="text"
                                                   class="form-control @error('driver') is-invalid @enderror" name="driver"
                                                   required>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Nopol</label>
                                            <input id="nopol" type="text"
                                                   class="form-control @error('nopol') is-invalid @enderror" name="nopol"
                                                   required>
                                        </div>
                                    </div>
                                    <div id="tombolnya" class="col-12">
                                        <button type="submit" id="tsubmit" class="btn btn-primary ">Simpan</button>
                                        <button type="reset" id="treset" class="btn btn-danger"
                                                data-bs-dismiss="modal">Reset</button>
                                    </div>`
            );
        }
    </script>
@stop
