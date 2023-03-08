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
                        <li class="breadcrumb-item active" aria-current="page">Muat</li>
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
                        <h6 class="mb-2">Daftar Muat</h6>
                        <div class="col-12">
                            @foreach ($cab as $ca)
                                @if (Auth::user()->id_cabang == $ca->id_cabang)
                                    <p>Daftar muat dari cabang <b>{{ $ca->nama_kota }}
                                            ({{ $ca->kode_area }})
                                        </b>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="dropdown ms-auto mb-2">
                        <button class="btn btn-outline-light text-dark" id="cetak-pdf" onclick="cetakTerpilih()" disabled><i
                                class='bx bxs-file-pdf me-2 font-24 text-danger'></i>Cetak Pdf</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%" id="muat-dt">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="head-cb"></th>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Supir</th>
                                <th>Nomer Manifest</th>
                                <th>Estimasi Tiba</th>
                                <th>Tujuan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <form action="{{ url('') }}/pdf_daftar_muat" method="post" id="form-pdf" class="hidden">
            {{ csrf_field() }}
            <input type="hidden" name="id_muat" />
            <button class="hidden" style="display: none;" type="submit">S</button>
        </form>
    @else
        <p>Whoops!</p>
    @endif
    {{-- Show PDF --}}
    {{-- <div id="dvContainer">
        This content needs to be printed.
    </div> --}}
@stop
@section('modal')
    {{-- Modal Manifest --}}
    <div class="modal fade" id="modaledit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Muat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reset()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Update Status Muatan</h5>
                            </div>
                            <hr>
                            <div class="col-12">
                                @foreach ($cab as $ca)
                                    @if (Auth::user()->id_cabang == $ca->id_cabang)
                                        <h6 class="mb-2">Anda Login Sebagai Akun Dari Cabang {{ $ca->nama_kota }}</h6>
                                        <p>Pilih Sesuai Dengan Barang Yang Turun Pada Cabang {{ $ca->nama_kota }}</p>
                                    @endif
                                @endforeach
                            </div>

                            <form id="form-edit" class="row g-3" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div id="isian">
                                    <input id="id_man" type="hidden">
                                    <div class="row g-2" id="drvnotlp">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Driver</label>
                                            <input id="driver" type="text"
                                                class="form-control @error('driver') is-invalid @enderror" name="driver"
                                                disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Nomor Telephone</label>
                                            <input id="no_tlp" type="text"
                                                class="form-control @error('no_tlp') is-invalid @enderror" name="no_tlp"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="nopjken">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Nopol</label>
                                            <input id="nopol" type="text"
                                                class="form-control @error('nopol') is-invalid @enderror" name="nopol"
                                                disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Jenis Kendaraan</label>
                                            <input id="jken" type="text"
                                                class="form-control @error('jken') is-invalid @enderror" name="jken"
                                                disabled>
                                        </div>
                                    </div>
                                    <input id="jml_tujuan" type="hidden">
                                    <div id="form_sample">
                                        <div id="isi_form"></div>
                                        <div id="isi_form2"></div>
                                        <div id="isi_form3"></div>
                                    </div>
                                    <div class="col-12" id="tombolnya">
                                        <button id="submit-edit" type="submit" class="btn btn-primary ">Simpan</button>
                                        <button id="reset-edit" type="reset" class="btn btn-danger"
                                            data-bs-dismiss="modal" onclick="reset()">Reset</button>
                                    </div>
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
    <script type="text/javascript"
        src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script type="text/javascript">
        let list_muat = [];

        const table = $("#muat-dt").DataTable({
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
            "ajax": {
                url: "{{ url('') }}/daftar_muat/{{ $id_cabang }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                    // console.log(d);
                }
            },
            "columnDefs": [{
                    "targets": 0,
                    "data": "id_manifest",
                    "sortable": false,
                    'checkboxes': {
                        'selectRow': true
                    },
                }, {
                    "targets": 1,
                    "data": "no_manifest",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                        list_muat[row.id_manifest] = row;
                    }
                }, {
                    "targets": 2,
                    "data": "tgl_buat",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return data;
                        // console.log(recordsTotal)
                    }
                }, {
                    "targets": 3,
                    "data": "driver",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 4,
                    "data": "no_manifest",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 5,
                    "data": "estimasi_tiba",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 6,
                    "data": "tujuan",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "targets": 7,
                    "data": "status",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        var tampilan = ``;
                        if (data == 0) {
                            tampilan +=
                                `<div class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3">` +
                                'Proses' + `</div>`
                        } else if (data == 1) {
                            tampilan +=
                                `<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">` +
                                'Selesai' + `</div>`
                        }
                        return tampilan;
                    }
                },
                {
                    "targets": 8,
                    "sortable": false,
                    "data": "no_manifest",
                    "render": function(data, type, row, meta) {
                        return `<div class="d-flex order-actions">
                            {{-- <a href="{{ url('') }}/pengiriman/print/${row.id_manifest}" id="btnprn" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a> --}}
                        <a class="ms-3" data-bs-toggle="modal" href="" data-bs-target="#modaledit" id="detailman" onclick="idTerpilih(${row.id_manifest})"><i class='lni lni-eye'></i></a>
                        </div>`;
                    }
                },
            ],
            'select': {
                'style': 'multi'
            },
        });

        function idTerpilih(id) {
            const url = "{{ url('/detailmanifest/') }}" + "/" + id;
            $.ajax({
                url,
                method: "GET",
                success: function(response) {
                    $('#modaledit').modal('show')
                    document.getElementById("id_man").setAttribute("value", id);
                    document.getElementById("driver").setAttribute("value", response['driver']);
                    document.getElementById("no_tlp").setAttribute("value", response['no_tlp_driver']);
                    document.getElementById("nopol").setAttribute("value", response['nopol']);
                    document.getElementById("jken").setAttribute("value", response['jeken']);

                    var x = document.getElementById("form_sample");
                    var y = document.getElementById("isi_form");
                    y.setAttribute("class", 'col-md');
                    x.setAttribute("class", 'row g-2');
                    x.appendChild(y);

                    var z = document.getElementById("isi_form2");
                    z.setAttribute("class", 'col-4');
                    x.appendChild(z);

                    var z2 = document.getElementById("isi_form3");
                    z2.setAttribute("class", 'col-4');
                    x.appendChild(z2);

                    const unique = Array.from(new Set(response['query']));
                    const unique2 = Array.from(new Set(response['tujuan_id']));

                    let jmltujuan = document.getElementById("jml_tujuan");
                    jmltujuan.setAttribute("value", unique.length)
                    let result = 0;
                    for (let i = 0; i < unique.length; i++) {
                        const a = unique[i];

                        var messagelabel = document.createElement('label');
                        messagelabel.setAttribute("class", "form-label");
                        messagelabel.innerHTML = "Tujuan";
                        y.appendChild(messagelabel)

                        var inputelement = document.createElement('input'); // Create input field for name
                        inputelement.setAttribute("type", "text");
                        inputelement.setAttribute("name", "tujuan" + [i]);
                        inputelement.setAttribute("id", "tujuan" + [i]);
                        inputelement.setAttribute("class", "form-control");
                        inputelement.setAttribute("value", a);
                        inputelement.disabled = true;
                        y.appendChild(inputelement);

                        var messagebreak = document.createElement('br');
                        var messagebreak2 = document.createElement('br');
                        var messagebreak3 = document.createElement('br');

                        var messagelabel2 = document.createElement('label');
                        messagelabel2.setAttribute("class", "form-label");
                        messagelabel2.innerHTML = "Aksi";
                        z.appendChild(messagelabel2)

                        var inputdate = document.createElement('input'); // Create input field for name
                        inputdate.setAttribute("type", "button");
                        inputdate.setAttribute("name", "transit" + [i]);
                        inputdate.setAttribute("id", "transit" + [i]);
                        inputdate.setAttribute("value", "transit");
                        inputdate.setAttribute("onClick", "transit(" + [i] + ")");
                        inputdate.setAttribute("class", "form-control");

                        var messagelabel3 = document.createElement('label');
                        messagelabel3.setAttribute("class", "form-label");
                        messagelabel3.innerHTML = "Aksi";
                        z2.appendChild(messagelabel3)

                        var inputdate2 = document.createElement('input'); // Create input field for name
                        inputdate2.setAttribute("type", "button");
                        inputdate2.setAttribute("name", "sampai" + [i]);
                        inputdate2.setAttribute("id", "sampai" + [i]);
                        inputdate2.setAttribute("value", "sampai");
                        inputdate2.setAttribute("onClick", "sampai(" + [i] + ")");
                        inputdate2.setAttribute("class", "form-control");

                        var tsumbit = document.getElementById("submit-edit");
                        var treset = document.getElementById("reset-edit");

                        if (response["id_cab"]!=unique2[i]) {
                            inputdate2.disabled = true;
                        }

                        if (response["status_sent"][i] == "2") {
                            inputdate.setAttribute("class", "form-control btn-success");
                            inputdate.disabled = true;
                            inputdate2.disabled = true;
                            tsumbit.disabled = true;
                            treset.disabled = true;
                        } else if (response["status_sent"][i] == "1") {
                            inputdate2.setAttribute("class", "form-control btn-success");
                            inputdate.disabled = true;
                            inputdate2.disabled = true;
                            tsumbit.disabled = true;
                            treset.disabled = true;
                        }

                        z.appendChild(inputdate);
                        z2.appendChild(inputdate2);
                        y.appendChild(messagebreak);
                        z.appendChild(messagebreak2);
                        z2.appendChild(messagebreak3);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {}
            })
            $("#manifest").prop('disabled', true)
        }

        let all_tujuan = [];
        let all_sampai = [];
        let all_transit = [];

        function sampai(x) {
            let sampaii = x;
            var sampaiid = document.getElementById("sampai" + x)
            var transitid = document.getElementById("transit" + x)
            sampaiid.disabled = true;
            transitid.disabled = true;
            sampaiid.setAttribute("class", "form-control btn-success");
            all_sampai.push(sampaii)
        }

        function transit(x) {
            let transitt = x;
            var sampaiid = document.getElementById("sampai" + x)
            var transitid = document.getElementById("transit" + x)
            sampaiid.disabled = true;
            transitid.disabled = true;
            transitid.setAttribute("class", "form-control btn-success");
            all_transit.push(transitt)
        }
        $('#form-edit').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            submitForm()
        });

        function submitForm() {
            let jml_tujuan = document.getElementById("jml_tujuan").value;
            let j_sampai = all_sampai.length
            let j_transit = all_transit.length
            for (let i = 0; i < jml_tujuan; i++) {
                let tujuan = document.getElementById("tujuan" + [i]).value;
                all_tujuan.push(tujuan)
            }
            let no_man = document.getElementById("id_man").value;

            console.log(j_sampai)
            const url = "{{ url('editdetailmanifest') }}";
            $.ajax({
                url,
                method: "POST",
                data: {
                    id_manifest: no_man,
                    all_tujuan: all_tujuan,
                    all_transit: all_transit,
                    all_sampai: all_sampai,
                    j_sampai: j_sampai,
                    j_transit: j_transit,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // table.ajax.reload(null, false)
                    window.location.href = "{{ url('') }}/daftarmuat/{{ base64_encode($id_cabang) }}";
                },
                error: function(e) {
                    console.log(e)
                }
            })
        }

        $('#form-edit').on('reset', function(event) {
            event.preventDefault() //jangan disubmit
            reset()
        });

        function reset() {
            $('#id_man').remove();
            $('#drvnotlp').remove();
            $('#nopjken').remove();
            $('#form_sample').remove();
            $('#tombolnya').remove();

            $('#isian').append('<input id="id_man" type="hidden">' +
                '<div class="row g-2" id="drvnotlp">' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Driver</label>' +
                '<input id="driver" type="text" class="form-control @error('driver') is-invalid @enderror" name="driver" disabled>' +
                '</div>' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Nomor Telephone</label>' +
                '<input id="no_tlp" type="text" class="form-control @error('no_tlp') is-invalid @enderror" name="no_tlp" disabled>' +
                '</div>' +
                '</div>' +
                '<div class="row g-2" id="nopjken">' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Nopol</label>' +
                '<input id="nopol" type="text" class="form-control @error('nopol') is-invalid @enderror" name="nopol" disabled>' +
                '</div>' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Jenis Kendaraan</label>' +
                '<input id="jken" type="text" class="form-control @error('jken') is-invalid @enderror" name="jken" disabled>' +
                '</div>' +
                '</div>' +
                '<input id="jml_tujuan" type="hidden">' +
                '<div id="form_sample">' +
                '<div id="isi_form"></div>' +
                '<div id="isi_form2"></div>' +
                '<div id="isi_form3"></div>' +
                '</div>' +
                '<div class="col-12" id="tombolnya">' +
                '<button id="submit-edit" type="submit" class="btn btn-primary" style="margin-right: 3px;">Simpan</button>' +
                '<button id="reset-edit" type="reset" class="btn btn-danger" data-bs-dismiss="modal" onclick="reset()">Reset</button>' +
                '</div>'
            );
        }

        $('.dt-checkboxes-select-all').on('click', function() {
            var isChecked = $("#muat-dt thead input[type='checkbox']").prop('checked')
            $("#cetak-pdf").prop('disabled', !isChecked)
        });

        table.on('click', 'td:first-child', e => {
            let all_checkbox = $('#muat-dt tbody .dt-checkboxes:checked')
            let status = (all_checkbox.length > 0)
            $("#cetak-pdf").prop('disabled', !status)
        });

        function cetakTerpilih() {
            let all_id_manifest = []
            var rows_selected = table.column(0).checkboxes.selected();
            $.each(rows_selected, function(index, rowId) {
                all_id_manifest.push(rowId)
            })

            let ids = all_id_manifest.join(',')
            $("#form-pdf [name='id_muat']").val(ids)
            $("#form-pdf").submit()
        }
    </script>
@stop
