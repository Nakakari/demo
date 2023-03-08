@extends('layouts.main')
@section('css')
    <style>
        .dataTables_length {
            padding-bottom: 15px;
        }

        .header-print {
            display: table-header-group;
        }

        .hidden {
            display: none;
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
                    <li class="breadcrumb-item active" aria-current="page">Invoice Pelanggan</li>
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
                            <p class="mb-0 text-secondary">Invoice Belum Lunas</p>
                            <h4 id="inv_blm_lunas" class="my-1 srt_blm_kmbli">
                                444</h4>
                            <p id="p_omset" class="mb-0 font-13 text-success p_omset"><strong>
                                    Invoice</strong></p>
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
                            <p class="mb-0 text-secondary">Invoice Melebihi Jatuh Tempo</p>
                            <h4 id="inv_melebihi_jatuh_tempo" class="my-1">555</h4>
                            <p class="mb-0 font-13 text-danger"><strong>Invoice</strong></p>
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
                            <p class="mb-0 text-secondary">Belum Tertagih</p>
                            <h4 id="belum_tertagih" class="my-1 tonase">555</h4>
                            <h4 class="my-1 tonase2"></h4>
                            <p class="mb-0 font-13 text-danger">
                                {{-- <strong>{{ $getPerusahaan->nama_perusahaan or 'Default' }}</strong> --}}
                                <strong>
                                    @isset($getPerusahaan->nama_perusahaan)
                                        {{ $getPerusahaan->nama_perusahaan }}
                                    @else
                                        <?= '' ?>
                                    @endisset
                                </strong>
                            </p>
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
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">

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
                            <option value="1">LUNAS</option>
                            <option value="0">BELUM LUNAS</option>
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
                <div>
                    {{-- <h6 class="mb-2">Halaman Piutang ({{ $getPerusahaan->nama_perusahaan or 'Default' }})</h6> --}}
                    <h6 class="mb-2">Halaman Piutang @isset($getPerusahaan->nama_perusahaan)
                            ({{ $getPerusahaan->nama_perusahaan }})
                        @else
                            <?= '' ?>
                        @endisset
                    </h6>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center mb-0" style="width:100%" id="penjualan-dt">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" class="form-check-input" id="head-cb"></th>
                            <th>No</th>
                            <th>Nomor Invoice</th>
                            <th>Nama Perusahaan</th>
                            <th>Jatuh Tempo</th>
                            <th>Nominal Piutang</th>
                            <th>Sisa Bayar</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop
@section('modal')
    {{-- Modal Pelunasan --}}
    <div class="modal fade" id="modalPelunasan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Pelunasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reset()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Pelunasan Invoice</h5>
                            </div>
                            <hr>
                            <form id="form-pelunasan" class="row g-3" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-6">
                                    <label for="no_invoice" class="form-label">Nomor Invoice</label>
                                    <input id="no_invoice" type="text" class="form-control" name="no_invoice"
                                        required readonly>
                                    <input type="hidden" id="id_invoice" name="id_invoice">
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_bank" class="form-label">Bank</label>
                                    <input id="nama_bank" type="text" class="form-control" name="nama_bank" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="no_rek" class="form-label">Nomor Rekening</label>
                                    <input id="no_rek" type="text" class="form-control" name="no_rek" readonly>

                                </div>
                                <div class="col-md-6">
                                    <label for="pelunasan" class="form-label">Bayar</label>
                                    <input id="pelunasan" type="input" class="form-control" name="nominal_pelunasan"
                                        onkeyup="this.value = formatCurrency(this.value);" required />
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary button-prevent">Simpan</button>
                                    <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Modal Update Pelunasan --}}
    <div class="modal fade" id="modalUpdate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Pelunasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reset()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Update Pelunasan Invoice</h5>
                            </div>
                            <hr>
                            <form id="form-update" class="row g-3" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-6">
                                    <label for="no_invoice" class="form-label">Nomor Invoice</label>
                                    <input id="no_invoice" type="text" class="form-control" name="no_invoice"
                                        required readonly>
                                    <input type="hidden" id="id_invoice" name="id_invoice">
                                </div>
                                <div class="col-md-6">
                                    <label for="nama_bank" class="form-label">Bank</label>
                                    <input id="nama_bank" type="text" class="form-control" name="nama_bank" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="no_rek" class="form-label">Nomor Rekening</label>
                                    <input id="no_rek" type="text" class="form-control" name="no_rek" readonly>

                                </div>
                                <div class="col-md-6">
                                    <label for="pelunasan" class="form-label">Bayar</label>
                                    <input id="pelunasan" type="input" class="form-control"
                                        name="update_nominal_pelunasan" onkeyup="this.value = formatCurrency(this.value);"
                                        required />
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary button-prevent">Update</button>
                                    <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
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
        let list_invoice = [];

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
            "order": [
                [1, "asc"]
            ],
            "scrollX": true,
            "ajax": {
                url: "{{ url('') }}/list_detail_piutang/{{ base64_encode($id_pelanggan) }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}",
                        d.dari = $("#dari_tanggal").val(),
                        d.sampai = $("#sampai_tanggal").val(),
                        d.kondisi = $("#filter-kondisi").val()
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "id_invoice",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return `<input type="checkbox" class="cb-child form-check-input" value="${row.id_invoice}">`;
                }
            }, {
                "targets": 1,
                "data": "id_invoice",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                "targets": 2,
                "data": "no_invoice",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    list_invoice[row.id_invoice] = row;
                    return data;
                }
            }, {
                "targets": 3,
                "data": "nama_perusahaan",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 4,
                "data": "jatuh_tempo",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 5,
                "data": "ttl_biaya_invoice",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    // let ppn = row.ppn;
                    // let get_total = parseInt(data) * parseFloat(ppn);
                    // let total = parseInt(data) + parseInt(get_total);
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(parseInt(
                        data));
                    if (nilai == 0) {
                        return '';
                    } else {
                        return nilai;
                    }
                }
            }, {
                "targets": 6,
                "data": "nominal_pelunasan",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    let sisa_bayar = parseInt(row.ttl_biaya_invoice) - parseInt(data)
                    var nilai = new Intl.NumberFormat(['ban', 'id']).format(parseInt(
                        sisa_bayar));
                    if (data == null) {
                        return '';
                    } else {
                        return nilai;
                    }

                }
            }, {
                "targets": 7,
                "data": "is_lunas",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    if (data == 1) {
                        return `Lunas`
                    } else if (data == 0) {
                        return `Belum Lunas`
                    }
                }
            }, {
                "targets": 8,
                "sortable": false,
                "data": "id_invoice",
                "render": function(data, type, row, meta) {
                    let tampilan = ``
                    var display = `style="display:none;"`
                    if (row.is_lunas == 1 && row.nominal_pelunasan != null && row.ttl_biaya_invoice ==
                        row.nominal_pelunasan) {
                        tampilan += `
                            <a href="#"  class="ms-3"><i class='fadeIn animated bx bx-dollar-circle'></i></a>
                        `;
                    } else if (row.is_lunas == 0 && row.nominal_pelunasan != null && row
                        .ttl_biaya_invoice !=
                        row.nominal_pelunasan) {
                        tampilan += `
                            <a onclick="updatePelunasan(${row.id_invoice})"  class="ms-3"><i class='fadeIn animated bx bx-dollar-circle'></i></a>
                        `;
                        display = ``
                    } else if (row.is_lunas == 0 && row.nominal_pelunasan == null) {
                        tampilan += `
                            <a onclick="pelunasan(${row.id_invoice})"  class="ms-3"><i class='fadeIn animated bx bx-dollar-circle'></i></a>
                        `;
                        display = ``
                    }
                    var editinvoice = "/edit_invoice/" + btoa(row.id_invoice)
                    var history = "/history/pembayaran/" + btoa(row.id_invoice)
                    var cetak = "/cetak_invoice/" + btoa(row.id_invoice)
                    return `<div class="d-flex order-actions">
                        <a href="` + editinvoice +
                        `" ` + display + `><i class="fadeIn animated bx bx-edit-alt ms-3" ></i></a>
                        <a href="` + history + `" class="ms-3"><i class="fadeIn animated bx bx-history"></i></a>
                        <a href="${cetak}" class="ms-3"><i class="fadeIn animated bx bx-file"></i></a>` + tampilan + `
                        </div>`;
                    //  <a href="#" id="btnprn" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a>
                }
            }],
            "createdRow": function(row, data, dataIndex) {
                if (data["is_lunas"] == 1) {
                    $('td', row).eq(7).css("background-color", "#29FF4C");
                } else {
                    $('td', row).eq(7).css("background-color", "#F47174");
                }
            },
            "footerCallback": function(row, data, start, end, display) {
                var api = this.api(),
                    data;
                let jumlah = 0
                let inv_blm_lunas = []
                let melebihi_jatuh_tempo = []
                let belum_tertagih = 0

                for (let i = 0; i < data.length; i++) {
                    let ppn = data[i]['ppn']
                    let ppn2 = ppn / 100
                    let biaya_invoice = data[i]['ttl_biaya_invoice']
                    let get_count = parseInt(biaya_invoice) * parseFloat(ppn2)
                    let total_nilai = parseInt(get_count) + parseInt(biaya_invoice)
                    jumlah += biaya_invoice

                    let get_belum_tertagih = biaya_invoice - data[i]['nominal_pelunasan']
                    belum_tertagih += get_belum_tertagih

                    if (data[i]['is_lunas'] == 0) {
                        inv_blm_lunas.push(data[i]['is_lunas'])
                    }

                    //Invoice Melebihi Jatuh Tempo
                    var today = new Date();
                    var jatuh_tempo = new Date(data[i]['tgl_jatuh_tempo'])

                    var Difference_In_Time = jatuh_tempo.getTime() - today.getTime();
                    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

                    if (Difference_In_Days < 0) {
                        melebihi_jatuh_tempo.push(Difference_In_Days)
                    }
                }

                var intVal = function(i) {
                    return typeof i == 'string' ?
                        i.replace(/[\$,]/g, '') * 1 :
                        typeof i == 'number' ?
                        i : 0;
                };

                var pelunasan = api
                    .column(6)
                    .data()
                    .reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                var total_piutang = String(jumlah).replace(/(.)(?=(\d{3})+$)/g, '$1.');
                var nominal_belum_tertagih = String(belum_tertagih).replace(/(.)(?=(\d{3})+$)/g, '$1.');

                $(api.column(0).footer()).html('');
                $(api.column(1).footer()).html('');
                $(api.column(2).footer()).html('');
                $(api.column(3).footer()).html('');
                $(api.column(4).footer()).html('Total');
                $(api.column(5).footer()).html(total_piutang);
                $(api.column(6).footer()).html(nominal_belum_tertagih);
                $(api.column(7).footer()).html('');
                $(api.column(8).footer()).html();

                $('#inv_blm_lunas').html(inv_blm_lunas.length);
                $('#inv_melebihi_jatuh_tempo').html(melebihi_jatuh_tempo.length);
                $('#belum_tertagih').html(nominal_belum_tertagih);
            },
        });

        function filter() {
            table.ajax.reload(null, false)
        }

        $('#head-cb').on('click', function() {
            var isChecked = $('#head-cb').prop('checked')
            $('.cb-child').prop('checked', isChecked)
            $("#proses-data").prop('disabled', !isChecked)
        });

        $('#penjualan-dt tbody').on('click', '.cb-child', function() {
            if ($(this).prop('checked') != true) {
                $('#head-cb').prop('checked', false)
            }
            let all_checkbox = $('#penjualan-dt tbody .cb-child:checked')
            let manifest_status = (all_checkbox.length > 0)
            $("#proses-data").prop('disabled', !manifest_status)
        });

        function pelunasan(id) {
            $('#modalPelunasan').modal('show')
            const invoice = list_invoice[id]

            $("#form-pelunasan [name='no_invoice']").val(invoice.no_invoice)
            $("#form-pelunasan [name='id_invoice']").val(invoice.id_invoice)
            $("#form-pelunasan [name='nama_bank']").val(invoice.nama_bank)
            $("#form-pelunasan [name='no_rek']").val(invoice.no_rek)
        }

        $('#form-pelunasan').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            $('#form-pelunasan .button-prevent').attr('disabled', 'true');
            formpelunasan()
        });

        function formpelunasan() {
            let form = $('#form-pelunasan');
            const url = "{{ url('pelunasan_invoice') }}";
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    table.ajax.reload(null, false)
                    success_noti()
                    $('#modalPelunasan').modal('hide')
                    $("#form-pelunasan [name='nominal_pelunasan']").val()
                    $('#form-pelunasan .button-prevent').prop("disabled", false);
                },
                error: function(e) {
                    error_noti()
                    $('#form-pelunasan .button-prevent').prop("disabled", false);
                }
            })
        }

        function updatePelunasan(id) {
            $('#modalUpdate').modal('show')
            const invoice = list_invoice[id]

            $("#form-update [name='no_invoice']").val(invoice.no_invoice)
            $("#form-update [name='id_invoice']").val(invoice.id_invoice)
            $("#form-update [name='nama_bank']").val(invoice.nama_bank)
            $("#form-update [name='no_rek']").val(invoice.no_rek)
        }

        $('#form-update').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            $('#form-update .button-prevent').attr('disabled', 'true');
            updatepelunasan()
        });

        function updatepelunasan() {
            let form = $('#form-update');
            const url = "{{ url('update_pelunasan_invoice') }}";
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    table.ajax.reload(null, false)
                    success_noti()
                    $('#modalUpdate').modal('hide')
                    $("#form-update [name='nominal_pelunasan']").val()
                    $('#form-update .button-prevent').prop("disabled", false);
                },
                error: function(e) {
                    error_noti()
                    $('#form-update .button-prevent').prop("disabled", false);
                }
            })
        }
    </script>
@stop
