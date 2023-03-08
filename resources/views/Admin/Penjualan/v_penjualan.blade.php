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
                    <li class="breadcrumb-item active" aria-current="page">Penjualan</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Surat Belum Kembali</p>
                            <h4 id="srt_blm_kmbli" class="my-1 srt_blm_kmbli">
                                444</h4>
                            <p id="p_omset" class="mb-0 font-13 text-success p_omset"><strong>Resi</strong></p>
                        </div>
                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bxs-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Tanpa Keterangan</p>
                            <h4 id="tanpa_ket" class="my-1">555</h4>
                            <p class="mb-0 font-13 text-info"><strong>Resi</strong></p>
                        </div>
                        <div class="widgets-icons bg-light-info text-info ms-auto"><i class='bx bx-user-pin'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Penjualan</p>
                            <h4 id="tonase" class="my-1 tonase">555</h4>
                            <h4 class="my-1 tonase2"></h4>
                            <p class="mb-0 font-13 text-danger"><strong>Penjualan</strong></p>
                        </div>
                        <div class="widgets-icons bg-light-danger text-danger ms-auto"><i class='bx bx-cart'></i>
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
            <div class="d-flex align-items-center">
                <div class="dropdown ms-auto mb-2">
                    <a type="button" class="btn btn-success" onclick="cetakExcell()"><i data-feather="printer"
                            class="me-2"></i>Export</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center" style="width:100%" id="penjualan-dt">
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
                            <th rowspan="2">Surat Kembali</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>CASH</th>
                            <th>COD</th>
                            <th>Tagihan</th>
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
                            <th colspan="2" rowspan="2" id="kumulatif"></th>
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
    <form action="{{ url('') }}/excell_penjualan" method="post" id="form-excell" class="hidden">
        {{ csrf_field() }}
        <input type="hidden" name="id_cabang" />
        <input type="hidden" name="dari_tanggal" />
        <input type="hidden" name="sampai_tanggal" />
        <input type="hidden" name="filter-kondisi" />
        <button class="hidden" style="display: none;" type="submit">S</button>
    </form>
@stop
@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="surat-kembali" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Monitoring Surat Kembali dan Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body p-4">

                        <form id="form-surat_kembali" class="row g-3" action="" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Pengirim</label>
                                <input id="nama_pengirim" type="text" class="form-control" readonly>
                                <input id="id_pengirim" type="hidden" class="form-control" name="id_pengiriman">
                                <input id="no_resi" type="hidden" class="form-control" name="no_resi">
                                @error('nama_pengirim')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Penerima</label>
                                <input id="nama_penerima" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Isi Barang</label>
                                <input id="isi_barang" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Koli</label>
                                <input id="koli" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Kilo</label>
                                <input id="berat" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Total Biaya</label>
                                <input id="biaya" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Surat Kembali</label>
                                <input id="tgl" type="date" class="form-control" name="tgl">
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success button-prevent">Simpan</button>
                                <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-surat-kembali" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-primary">Monitoring Surat Kembali dan Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body p-4">

                        <form id="form-editsurat_kembali" class="row g-3" action="" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Pengirim</label>
                                <input id="nama_pengirim" type="text" class="form-control" readonly>
                                <input id="id_pengirim" type="hidden" class="form-control" name="id_pengiriman">
                                <input id="no_resi" type="hidden" class="form-control" name="no_resi">
                                @error('nama_pengirim')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Penerima</label>
                                <input id="nama_penerima" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Isi Barang</label>
                                <input id="isi_barang" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Koli</label>
                                <input id="koli" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Kilo</label>
                                <input id="berat" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Total Biaya</label>
                                <input id="biaya" type="text" class="form-control" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Surat Kembali</label>
                                <input id="tgl" type="date" class="form-control" name="tgl">
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="2"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-success button-prevent">Simpan</button>
                                <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        let list_penjualan = [];

        const table = $("#penjualan-dt").DataTable({
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
            "scrollX": true,
            "order": [
                [1, "asc"]
            ],
            "ajax": {
                url: "{{ url('data_penjualan') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}",
                        d.dari = $("#dari_tanggal").val(),
                        d.sampai = $("#sampai_tanggal").val(),
                        d.kondisi = $("#filter-kondisi").val(),
                        d.cabang = $("#filter-cabang").val()
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "id_pengiriman",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                "targets": 1,
                "data": "tgl_masuk",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    list_penjualan[row.id_pengiriman] = row;
                    return data;
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
                "data": "no_resi_manual",
                "sortable": false,
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
                "data": "tgl",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 13,
                "data": "ket",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 14,
                "sortable": false,
                "data": "no_resi",
                "render": function(data, type, row, meta) {
                    var tampilan = ``;
                    if (row.ket != null && row.tgl != null && row.noResi != null) {
                        tampilan += `<div class="d-flex order-actions">
                            <a class="ms-3" onclick="updateSuratKembali('${row.id_pengiriman}')"><i class="bx bxs-edit"></i></a>	   
                        </div>`;
                    } else if (row.ket == null && row.tgl == null && row.noResi != null) {
                        tampilan += `<div class="d-flex order-actions">
                            <a class="ms-3" onclick="updateSuratKembali('${row.id_pengiriman}')"><i class="bx bxs-edit"></i></a>	   
                        </div>`;
                    } else if (row.ket == null && row.tgl != null && row.noResi != null) {
                        tampilan += `<div class="d-flex order-actions">
                            <a class="ms-3" onclick="updateSuratKembali('${row.id_pengiriman}')"><i class="bx bxs-edit"></i></a>	   
                        </div>`;
                    } else if (row.ket != null && row.tgl == null && row.noResi != null) {
                        tampilan += `<div class="d-flex order-actions">
                            <a class="ms-3" onclick="updateSuratKembali('${row.id_pengiriman}')"><i class="bx bxs-edit"></i></a>	   
                        </div>`;
                    } else {
                        tampilan += `<div class="d-flex order-actions">
                            <a onClick="buatSuratKembali(${row.id_pengiriman})" class="ms-3" ><i class='bx bxs-edit'></i></a>
                        </div>`
                    }
                    return tampilan;
                }
            }, ],
            "createdRow": function(row, data, dataIndex) {
                if (data["tgl"] == null && data['ket'] == null) {
                    $('td', row).eq(13).css("background-color", "#F47174");
                    $('td', row).eq(12).css("background-color", "#F47174");
                } else if (data['tgl'] != null && data['ket'] == null) {
                    $('td', row).eq(13).css("background-color", "#F47174");
                } else if (data['tgl'] == null && data['ket'] != null) {
                    $('td', row).eq(12).css("background-color", "#F47174");
                } else {

                }
            },
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                // console.log(data)
                let tanpa_ket = []
                let ary = []
                for (let i = 0; i < data.length; i++) {
                    if (data[i]['is_kondisi_resi'] == 2) {
                        tanpa_ket.push(data[i]);
                    }
                    if (data[i]['is_kondisi_resi'] == 1) {
                        ary.push(data[i]);
                    }
                }
                // console.log(arr)
                var intVal = function(i) {
                    return typeof i == 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i == 'number' ?
                        i : 0;
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
                $(api.column(13).footer()).html('');
                $(api.column(14).footer()).html('');
                $('.sum').html(cumulatif);
                $('#tanpa_ket').html(tanpa_ket.length);
                $('#tonase').html('Rp' + cumulatif);

                $('#koli').html(total_koli + ' Qty');
                $('#srt_blm_kmbli').html(ary.length);

            },
        });

        function filter() {
            table.ajax.reload(null, false)
        }

        function buatSuratKembali(id_pengiriman) {
            const penjualan = list_penjualan[id_pengiriman]
            // console.log(penjualan)
            $('#surat-kembali').modal('show')
            $("#form-surat_kembali #nama_pengirim").val(penjualan.nama_pengirim)
            $("#form-surat_kembali #nama_penerima").val(penjualan.nama_penerima)
            $("#form-surat_kembali #isi_barang").val(penjualan.isi_barang)
            $("#form-surat_kembali #koli").val(formatRupiah(penjualan.koli))
            $("#form-surat_kembali #berat").val(formatRupiah(penjualan.kilo))
            $("#form-surat_kembali #biaya").val(formatRupiah(penjualan.biaya))
            $("#form-surat_kembali #keterangan").val(penjualan.ket)
            $("#form-surat_kembali [name='tgl']").val(penjualan.tgl)
            $("#form-surat_kembali [name='no_resi']").val(penjualan.no_resi)
            $("#form-surat_kembali [name='id_pengiriman']").val(id_pengiriman)
        }

        $('#form-surat_kembali').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            $('#form-surat_kembali .button-prevent').attr('disabled', 'true');
            newSuratKembali()
        });

        function newSuratKembali() {
            let form = $('#form-surat_kembali');
            const url = "{{ url('add_surat_kembali') }}";
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response == true) {
                        success_noti()
                        $('#surat-kembali').modal('hide')
                        table.ajax.reload(null, false)
                        $('#form-surat_kembali .button-prevent').prop("disabled", false);
                    }
                },
                error: function(e) {
                    alert('Isi data secara lengkap!')
                }
            })
        }

        function updateSuratKembali(id_pengiriman) {
            const penjualan = list_penjualan[id_pengiriman]
            $('#edit-surat-kembali').modal('show')
            $("#form-editsurat_kembali #nama_pengirim").val(penjualan.nama_pengirim)
            $("#form-editsurat_kembali #nama_penerima").val(penjualan.nama_penerima)
            $("#form-editsurat_kembali #isi_barang").val(penjualan.isi_barang)
            $("#form-editsurat_kembali #koli").val(formatRupiah(penjualan.koli))
            $("#form-editsurat_kembali #berat").val(formatRupiah(penjualan.kilo))
            $("#form-editsurat_kembali #biaya").val(formatRupiah(penjualan.biaya))
            $("#form-editsurat_kembali #keterangan").val(penjualan.ket)
            $("#form-editsurat_kembali [name='tgl']").val(penjualan.tgl)
            $("#form-editsurat_kembali [name='no_resi']").val(penjualan.no_resi)
            $("#form-editsurat_kembali [name='id_pengiriman']").val(id_pengiriman)
        }

        $('#form-editsurat_kembali').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            $('#form-editsurat_kembali .button-prevent').attr('disabled', 'true');
            editSuratKembali()
        });

        function editSuratKembali() {
            let form = $('#form-editsurat_kembali');
            const url = "{{ url('edit_surat_kembali') }}";
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response == true) {
                        success_noti()
                        $('#edit-surat-kembali').modal('hide')
                        table.ajax.reload(null, false)
                        $('#form-editsurat_kembali .button-prevent').prop("disabled", false);
                    }
                },
                error: function(e) {
                    error_noti()
                }
            })
        }

        function cetakExcell() {
            $("#form-excell [name='id_cabang']").val($("#filter-cabang").val())
            $("#form-excell [name='dari_tanggal']").val($("#dari_tanggal").val())
            $("#form-excell [name='sampai_tanggal']").val($("#sampai_tanggal").val())
            $("#form-excell [name='filter-kondisi']").val($("#filter-kondisi").val())
            $("#form-excell").submit()
        }
    </script>
@stop
