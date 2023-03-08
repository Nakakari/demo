@extends('layouts.main')
@section('css')
    <style>
        .dataTables_length {
            padding-bottom: 15px;
        }
    </style>
@stop
@section('isi')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="/admin/home"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Penjualan Pelanggan</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card radius-10">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="pelanggan-dt">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Perusahaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        let list_pelanggan = [];

        const table = $("#pelanggan-dt").DataTable({
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
                url: "{{ url('data_pelanggan') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "id_pelanggan",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                    list_pelanggan[row.id_pelanggan] = row;
                }
            }, {
                "targets": 1,
                "data": "nama_perusahaan",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 2,
                "data": "id_pelanggan",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var url = "/transaksi_pelanggan/" + btoa(row.id_pelanggan)
                    return `<div class="d-flex order-actions">
                            <a href="` + url + `" class="ms-3"><i class='bx bxs-edit'></i></a>
                        </div>`;
                }
            }]
        });
    </script>
@stop
