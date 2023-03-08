@extends('layouts.main')
@section('css')
    <style>
        .dataTables_length {
            padding-bottom: 15px;
        }

        .header-print {
            display: table-header-group;
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
                    <li class="breadcrumb-item"><a href="/penjualan_pelanggan"><i
                                class="fadeIn animated bx bx-archive"></i></a>
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
                            <p class="mb-0 font-13 text-danger"><strong>Resi</strong></p>
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
                            <p class="mb-0 font-13 text-danger"><strong>Kg</strong></p>
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
                            <strong>{{ session('gagal') }}</strong>.
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
                    <h6 class="mb-2">Data Transaksi Pelanggan({{ $getPerusahaan->nama_perusahaan }})</h6>
                </div>
                <div class="dropdown ms-auto mb-2">
                    <a type="button" class="btn btn-success"
                        href="/add_transaksi_penjualan/{{ base64_encode($id_pelanggan) }}">Tambah
                        Data</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center mb-0" style="width:100%" id="penjualan-dt">
                    <thead class="table-light">
                        <tr>
                            <th colspan="14" style="text-align:Left" class="table-light">
                                Nama Perusahaan: {{ $getPerusahaan->nama_perusahaan }}</th>
                        </tr>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Resi</th>
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
                            <th></th>
                            <th></th>
                            <th></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="sum"></th>
                        </tr>
                        <tr>
                            <th colspan="14">
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop
@section('modal')
    <div class="modal fade" id="modal-batal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Data Transaksi Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="reset()"></button>
                </div>
                <form id="form-batal-trans" class="row g-3" enctype="multipart/form-data">
                    <div class="modal-body">Apakah Anda yakin untuk membatalkan salah satau transaksi
                        {{ $getPerusahaan->nama_perusahaan }}?</div>
                    <input type="hidden" name="id_pengiriman" id="id_pengiriman" />
                    <div class="modal-footer" id="tombolnya">
                        <button type="reset" id="treset" class="btn btn-danger"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
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
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "processing": true,
            "bServerSide": true,
            'dom': "lBfrtip",
            'buttons': [{
                    extend: 'copyHtml5',
                    footer: true
                },
                {
                    extend: 'excelHtml5',
                    footer: true
                },
                {
                    extend: 'csvHtml5',
                    footer: true
                },
                {
                    extend: 'pdfHtml5',
                    footer: true
                },
                {
                    extend: 'print',
                    footer: true
                }
            ],
            "scrollX": true,
            "order": [
                [1, "asc"]
            ],
            "ajax": {
                url: "{{ url('') }}/list_trans_pelanggan/{{ base64_encode($id_pelanggan) }}",
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
                "data": "nama_pengirim",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 4,
                "data": "nama_penerima",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 5,
                "data": "kota_penerima",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 6,
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
                "targets": 7,
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
                "targets": 8,
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
                "targets": 9,
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
                "targets": 10,
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
                "targets": 11,
                "data": "tgl",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 12,
                "data": "ket",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 13,
                "data": "id_pengiriman",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return `<div class="d-flex order-actions">
                            <a class="ms-3" onclick="toggleStatus('${row.id_pengiriman}')"><i class="fadeIn animated bx bx-reset"></i></a>	   
                        </div>`;
                }
            }, ],
            "createdRow": function(row, data, dataIndex) {
                if (data["tgl"] == null && data['ket'] == null) {
                    $('td', row).eq(12).css("background-color", "#F47174");
                    $('td', row).eq(11).css("background-color", "#F47174");
                } else if (data['tgl'] != null && data['ket'] == null) {
                    $('td', row).eq(12).css("background-color", "#F47174");
                } else if (data['tgl'] == null && data['ket'] != null) {
                    $('td', row).eq(11).css("background-color", "#F47174");
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
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i === 'number' ?
                        i : 0;
                };

                // computing column Total of the complete result 
                var resi = table.page.info().recordsTotal;

                var kilo = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var koli = api
                    .column(7)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var cash = api
                    .column(8)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var cod = api
                    .column(9)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var tagihan = api
                    .column(10)
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
                $(api.column(5).footer()).html('Total');
                $(api.column(6).footer()).html(total_kilo);
                $(api.column(7).footer()).html(total_koli);
                $(api.column(8).footer()).html(cash_total);
                $(api.column(9).footer()).html(cod_total);
                $(api.column(10).footer()).html(tagihan_total);
                $(api.column(11).footer()).html('');
                $(api.column(12).footer()).html('');
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

        function tambahData() {
            window.location.href = "/add_transaksi_penjualan";
        }

        function toggleStatus(id_pengiriman) {
            $('#modal-batal').modal('show')
            $("#form-batal-trans [name='id_pengiriman']").val(id_pengiriman)
        }

        $('#form-batal-trans').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            submitForm()
        });

        function submitForm() {
            let id_pelanggan = null;
            let id_pengiriman = document.getElementById("id_pengiriman").value;
            const url = "{{ url('ubahstatus_trans_pelanggan') }}";
            $.ajax({
                url,
                method: "POST",
                data: {
                    id_pengiriman: id_pengiriman,
                    id_pelanggan: id_pelanggan,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response === true) {
                        $('#modal-batal').modal('hide')
                        table.ajax.reload(null, false)
                    }
                },
                error: function(e) {
                    alert('Whops!')
                }
            })
        }
    </script>
@stop
