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
                    <li class="breadcrumb-item active" aria-current="page">Pengiriman</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary nama_cabang">Cabang/Agen</p>
                            {{-- <h4 class="my-1">Rp{{ number_format($totalOmset->jumlah, 2, ',', '.') }}</h4> --}}
                            <h4 id="omsetheading" class="my-1 omsetheading">
                            </h4>
                            </h4>
                            <p id="p_omset" class="mb-0 font-13 text-success p_omset">Total Omset<strong></strong>
                            </p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                class="bx bxs-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary nama_cabang">Cabang/Agen</p>
                            <h4 id="transaksi" class="my-1"></h4>
                            <p class="mb-0 font-13 text-info"><strong>Transaksi</strong></p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto"><i
                                class='bx bx-user-pin'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary nama_cabang">Cabang/Agen</p>
                            <h4 id="tonase" class="my-1 tonase"></h4>
                            <h4 class="my-1 tonase2"></h4>
                            <p class="mb-0 font-13 text-danger"><strong>Tonase</strong></p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto"><i
                                class='bx bx-cart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-3 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary nama_cabang">Cabang/Agen</p>
                            <h4 class="my-1 text-warning" id="koli"></h4>
                            <p class="mb-0 font-13 text-warning">Koli</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto"><i
                                class="lni lni-dropbox-original"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--end row-->
    {{-- Filter --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <label for="inputAddress" class="form-label">Cabang/Agen</label>
                    <div class="col-12 select2-sm">
                        <select class="single-select" id="filter-cabang" onchange="filter()">
                            <option value="">Pilih Cabang ..</option>
                            @foreach ($cab as $c)
                                <option value="{{ $c->id_cabang }}">{{ $c->nama_kota }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <label for="inputAddress" class="form-label">Dari Tanggal</label>
                    <div class="col-12">
                        <input id="dari_tanggal" type="date" class="form-control" onchange="filter()">
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <label for="inputAddress" class="form-label">Sampai Tanggal</label>
                    <div class="col-12">
                        <input id="sampai_tanggal" type="date" class="form-control" onchange="filter()">
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <label for="inputAddress" class="form-label">Kondisi</label>
                    <div class="col-12">
                        <select class="form-select" id="filter-kondisi" onchange="filter()">
                            <option value="">Pilih Status ..</option>
                            @foreach ($kondisi as $s)
                                <option value="{{ $s->id_kondisi_resi }}">{{ $s->nama_kondisi_resi }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                <table class="table table-striped table-bordered text-center" style="width:100%" id="pengiriman-dt">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Resi</th>
                            <th rowspan="2">Resi (Manual)</th>
                            <th rowspan="2">Pengirim</th>
                            <th rowspan="2">Penerima</th>
                            <th rowspan="2">Kota Penerima</th>
                            <th rowspan="2">Kilo</th>
                            <th rowspan="2">Koli</th>
                            <th colspan="3">Pembayaran</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>CASH</th>
                            <th>COD</th>
                            <th>TAGIHAN</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="sum"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
@stop
@section('js')

    <script type="text/javascript">
        let list_pengiriman = [];

        const table = $("#pengiriman-dt").DataTable({
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
                url: "{{ url('data_pengiriman') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}",
                        d.dari = $("#dari_tanggal").val(),
                        d.sampai = $("#sampai_tanggal").val(),
                        d.kondisi = $("#filter-kondisi").val(),
                        d.cabang = $("#filter-cabang").val()
                    // console.log(d.recordsFiltered)
                }
            },
            "columnDefs": [{
                "targets": 0,
                "sortable": false,
                "data": "id_pengiriman",
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                "targets": 1,
                "sortable": false,
                "data": "tgl_masuk",
                "render": function(data, type, row, meta) {
                    list_pengiriman[row.id_pengiriman] = row;
                    return data;
                }
            }, {
                "targets": 2,
                "sortable": false,
                "data": "no_resi",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 3,
                "sortable": false,
                "data": "no_resi_manual",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 4,
                "data": "nama_pengirim",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 5,
                "data": "nama_penerima",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 6,
                "data": "kota_penerima",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 7,
                "data": "kilo",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                    if (nilai == 0) {
                        return '';
                    } else {
                        return nilai;
                    }
                }
            }, {
                "targets": 8,
                "data": "koli",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                    if (nilai == 0) {
                        return '';
                    } else {
                        return nilai;
                    }
                }
            }, {
                "targets": 9,
                "data": "cash",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                    if (nilai == 0) {
                        return '';
                    } else {
                        return nilai;
                    }
                }
            }, {
                "targets": 10,
                "data": "cod",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                    if (nilai == 0) {
                        return '';
                    } else {
                        return nilai;
                    }
                }
            }, {
                "targets": 11,
                "data": "tagihan",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(data);
                    if (nilai == 0) {
                        return '';
                    } else {
                        return nilai;
                    }
                }
            }, {
                "targets": 12,
                "sortable": false,
                "data": "id_pengiriman",
                "render": function(data, type, row, meta) {
                    const url_edit = "/edit_resi/" + btoa(row.id_pengiriman)
                    const url_print = "/print2/" + btoa(row.id_pengiriman)
                    return `<div class="d-flex order-actions">
                        <a href="` + url_print + `" id="btnprn" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a>
                            <a href="` + url_edit + `" class="ms-3" ><i class='bx bxs-edit'></i></a>
                        </div>`;
                }
            }, ],
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                var get_cab = $("#filter-cabang").val();
                const unique = Array.from(new Set(data));

                for (let i = 0; i < unique.length; i++) {
                    if (get_cab !== null) {
                        if (get_cab == data[i]['id_cabang_tujuan']) {
                            $('.nama_cabang').html(unique[i]['nama_kota']);
                        }
                    } else {
                        $('.nama_cabang').html('Cabang/Agen');
                    }
                }

                var intVal = function(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i :
                        0;
                };

                // computing column Total of the complete result
                var resi = table.page.info().recordsTotal;

                var kilo = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var koli = api
                    .column(8)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var cash = api
                    .column(9)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var cod = api
                    .column(10)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var tagihan = api
                    .column(11)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var cumulatif = String((tagihan + cod + cash)).replace(/(.)(?=(\d{3})+$)/g, '$1.');
                var cash_total = String(cash).replace(/(.)(?=(\d{3})+$)/g, '$1.');
                var cod_total = String(cod).replace(/(.)(?=(\d{3})+$)/g, '$1.');
                var tagihan_total = String(tagihan).replace(/(.)(?=(\d{3})+$)/g, '$1.');
                var total_kilo = String(kilo).replace(/(.)(?=(\d{3})+$)/g, '$1.');
                var total_koli = String(koli).replace(/(.)(?=(\d{3})+$)/g, '$1.');

                $(api.column(0).footer()).html('');
                $(api.column(1).footer()).html('');
                $(api.column(2).footer()).html('');
                $(api.column(3).footer()).html('');
                $(api.column(4).footer()).html('');
                $(api.column(5).footer()).html('');
                $(api.column(6).footer()).html('Total');
                $(api.column(7).footer()).html(total_kilo);
                $(api.column(8).footer()).html(total_koli);
                $(api.column(9).footer()).html(cash_total);
                $(api.column(10).footer()).html(cod_total);
                $(api.column(11).footer()).html(tagihan_total);
                $(api.column(12).footer()).html('');
                $('.sum').html(cumulatif);
                $('#transaksi').html(resi);
                $('#tonase').html(total_kilo + ' Kg');
                $('#koli').html(total_koli + ' Qty');
                $('#omsetheading').html('Rp' + cumulatif);
                var b = $("#filter-cabang").val();
                // $('#cabang').html(b);
            },
        });

        function filter() {
            table.ajax.reload(null, false)
            var dari_tanggal = $('#dari_tanggal').val();
            var sampai_tanggal = $('#sampai_tanggal').val();
            var filter_kondisi = $('#filter-kondisi').val();
            var filter_cabang = $("#filter-cabang").val();
        }
    </script>
@stop
