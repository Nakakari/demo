@extends('layouts.main')
@section('isi')
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Admin</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="/admin/home"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Invoice</li>
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
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="pelanggan-dt">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Perusahaan</th>
                            <th>Invoice Belum Lunas</th>
                            <th>Nominal Piutang</th>
                            <th>Action</th>
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
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All Data']
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
                url: "{{ url('list_piutang') }}",
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
                "data": "inv_blm_lunas",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 3,
                "data": "ttl_biaya_invoice",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    if(row.ttl_pelunasan === null){
                        data = row.ttl_biaya;
                    }
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                    if (nilai == 0) {
                        return 0;
                    } else {
                        return nilai;
                    }
                }
            }, {
                "targets": 4,
                "data": "id_pelanggan",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var url = "/detail_piutang/" + btoa(data)
                    return `<div class="d-flex order-actions">
                            <a href=` + url + ` class="ms-3"><i class='bx bxs-edit'></i></a>
                        </div>`;
                }
            }]
        });
    </script>
@stop
