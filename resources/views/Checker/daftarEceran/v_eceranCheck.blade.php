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
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
          rel="stylesheet" />
@stop
@section('isi')
    @if ($id_cabang == Auth::user()->id_cabang)
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Sales / Counter</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="/sales/home"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Eceran</li>
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
                        <h6 class="mb-2">Daftar Eceran</h6>
                        <div class="col-12">
                            @foreach ($cab as $ca)
                                @if (Auth::user()->id_cabang == $ca->id_cabang)
                                    <p>Daftar eceran untuk cabang <b>{{ $ca->nama_kota }}
                                            ({{ $ca->kode_area }})
                                        </b>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%" id="daftar_ecer-dt">
                        <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Tiba</th>
                            <th>Resi</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            {{--                                <th>Aksi</th>--}}
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
@section('js')
    <script type="text/javascript"
            src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script type="text/javascript">
        let list_ecer = [];

        const table = $("#daftar_ecer-dt").DataTable({
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
            "order": [
                [1, "asc"]
            ],
            "scrollX": true,
            "ajax": {
                url: "{{ url('') }}/list_ecer_checker/{{ $id_cabang }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "id_pengiriman",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                    list_ecer[row.id_pengiriman] = row;
                }
            }, {
                "targets": 1,
                "data": "tgl_tiba",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var show = ``
                    var today = new Date();
                    var jatuh_tempo = new Date(data)

                    var Difference_In_Time = jatuh_tempo.getTime() - today.getTime();
                    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

                    if (Difference_In_Days < 0 && row.status_sent == 4) {
                        show = `<p class="text-danger text-strong">` + data + `</p>`
                    } else {
                        show = data
                    }
                    return show;
                }
            }, {
                "targets": 2,
                "data": "no_resi",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 3,
                "sortable": false,
                "data": "nama_pengirim",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 4,
                "sortable": false,
                "data": "nama_penerima",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 5,
                "sortable": false,
                "data": "kota_penerima",
                "render": function(data, type, row, meta) {
                    return data;
                }
            },
                {
                    "targets": 6,
                    "data": "status_sent",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return `<div ` + row.class + `>` +
                            row.nama_status + `</div>`;
                    }
                },
                // {
                //     "targets": 8,
                //     "sortable": false,
                //     "data": "id_pengiriman",
                //     "render": function(data, type, row, meta) {
                //         const url_print1 = "/pengiriman/print/" + btoa(row.id_pengiriman)
                //         return `<div class="d-flex order-actions">
                //             <a id="btnprn" href="` + url_print1 + `" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a>
                //             <a class="ms-3" data-bs-toggle="modal" href="" data-bs-target="#modaledit" id="detailman" onclick="idTerpilih(${data})"><i class='lni lni-eye'></i></a>
                //         </div>`;
                //     }
                // },
            ],
            'select': {
                'style': 'multi'
            },
        });
    </script>
@stop
