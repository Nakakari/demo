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
            <div class="breadcrumb-title pe-3">Sales</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="/sales/home"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Cek Koli Turun
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
                            <h6 class="mb-2">Data cek koli Turun</h6>
                            <div class="col-12">
                                @foreach ($cab as $ca)
                                    @if (Auth::user()->id_cabang == $ca->id_cabang)
                                        <p>Daftar data koli turun dari cabang <b>{{ $ca->nama_kota }}
                                                ({{ $ca->kode_area }})
                                            </b>
                                        </p>
                                    @endif
                                @endforeach
                            </div>
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
                            <th>Dibuat Oleh</th>
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
                url: "{{ url('/sales_list_turun/' . base64_encode($id_cabang)) }}",
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
                "data": "jumlah_resi2",
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
                "data": "jumlah_koli2",
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
                "data": "name",
                "render": function(data, type, row, meta) {
                    let cabang = row.nama_kota;
                    let dcabang = data+' ('+cabang+')';
                    return dcabang
                }
            }, {
                "targets": 7,
                "sortable": false,
                "data": "id_kolicek",
                "render": function(data, type, row, meta) {
                    const url_print2 = "/sales_resi_turun/" + btoa(data)
                    return `<div class="d-flex order-actions justify-content-center">
                            <a href="` + url_print2 + `" id="btnprn" class="ms-3"><i class="lni lni-eye"></i></a>
                        </div>`;
                }
            },
            ]
        });
    </script>
@stop
