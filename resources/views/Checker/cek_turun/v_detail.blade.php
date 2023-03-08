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
                        <li class="breadcrumb-item"><a href="/checker_turun/{{ base64_encode(Auth::user()->id_cabang) }}"><i class="bx bx-down-arrow"></i></a></li>
                        <li class="breadcrumb-item"><a href="/resi_turun/{{ base64_encode($id_cek) }}">Detail Resi Naik dari Driver {{$cek->driver}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Detail Koli Naik dari Resi {{$cek2->no_resi}}
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
                        <h6 class="mb-2">Data detail koli Turun</h6>
                        <div class="col-12">
                            <p>Daftar data detail koli Turun untuk Driver
                                <b>{{$cek->driver}}</b>, Nopol
                                <b>{{$cek->nopol}}</b>, Resi
                                <b>{{$cek2->no_resi}}</b>
                            </p>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center" style="width:100%" id="resi-dt">
                        <thead class="table-light">
                        <tr>
                            <th>No.</th>
                            <th>Urutan</th>
{{--                            <th>Nopol</th>--}}
{{--                            <th>Jumlah Resi</th>--}}
{{--                            <th>Jumlah Koli</th>--}}
{{--                            <th>Aksi</th>--}}
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
                url: "{{ url('/list_detail2/' . base64_encode($id_cek) .'/'. base64_encode($id_pengiriman)) }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "id_detail_koli",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },{
                "targets": 1,
                "sortable": false,
                "data": "nama_identitas",
                "render": function(data, type, row, meta) {
                    let position = data.search("-koli")+5;
                    let length = data.length;
                    let result = data.slice(position, length);
                    return result;
                }
            }
            ]
        });

    </script>
@stop
