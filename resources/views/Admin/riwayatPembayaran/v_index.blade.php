@extends('layouts.main')
@section('css')
    <style>
        .uniqueClassName {
            text-align: right;
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
                    <li class="breadcrumb-item"><a href="/piutang"><i class="fadeIn animated bx bx-archive"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="">Invoice
                            Pelanggan</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Pembayaran</li>
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
                <table class="table table-striped table-bordered" style="width:100%" id="history-dt">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor Invoice</th>
                            <th>Nominal</th>
                            <th>Tertanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th></th>
                            <th class="text-end">Total</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        let list_riwayat_pembayaran = [];

        const table = $("#history-dt").DataTable({
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
                url: "{{ url('') }}/list_detail_riwayat_pembayaran/{{ base64_encode($id_invoice) }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "id_riwayat_pembayaran",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                    list_riwayat_pembayaran[row.id_riwayat_pembayaran] = row;
                }
            }, {
                "targets": 1,
                "data": "no_invoice",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 2,
                "data": "pembayaran",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            }, {
                "targets": 3,
                "data": "created_at",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var tanggal = new Date(data)
                    m = tanggal.getMonth();
                    m += 1
                    return tanggal.getDate() + `/` + m + `/` + tanggal.getFullYear() + ` ` + tanggal
                        .getHours() + `:` + tanggal.getMinutes() + `:` + tanggal.getSeconds();
                }
            }],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;

                var intVal = function(i) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                var nominal = api
                    .column(2)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                $(api.column(0).footer()).html('');
                $(api.column(1).footer()).html('Total');
                $(api.column(2).footer()).html(formatRupiah(nominal));
                $(api.column(3).footer()).html('');
            },
        });
    </script>
@stop
