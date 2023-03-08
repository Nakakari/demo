@extends('layouts.main')
@section('css')
    <style type="text/css">
        hr {
            margin-top: 1rem;
            margin-bottom: 1rem;
            border: 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .hidden {
            display: none;
        }

        .simpan {
            display: none;
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
                        <li class="breadcrumb-item active" aria-current="page">Daftar Tugas</li>
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
                                <p class="mb-0 text-secondary">Total Omset</p>
                                {{-- <h4 class="my-1">Rp{{ number_format($totalOmset->jumlah, 2, ',', '.') }}</h4> --}}
                                <h4 id="omsetheading" class="my-1 omsetheading">
                                    Rp{{ number_format($totalOmset->jumlah, 0, ',', '.') }}</h4>
                                <h4 id="omsetheading2" class="my-1 omsetheading2">
                                </h4>
                                <p id="p_omset" class="mb-0 font-13 text-success p_omset">
                                    <strong>{{ $tgl }}</strong>
                                </p>
                                <p id="p_omset2" class="mb-0 font-13 text-success p_omset2"><strong></strong></p>
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
                                <p class="mb-0 text-secondary">Transaksi</p>
                                <h4 id="transaksi" class="my-1">{{ number_format($totalTransaksi, 0, ',', '.') }}</h4>
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
                                <p class="mb-0 text-secondary">Tonase</p>
                                <h4 id="tonase" class="my-1 tonase">{{ number_format($tonase->kg, 0, ',', '.') }}</h4>
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
                    <button id="manifest" type="button" class="btn btn-success" onclick="manifestTerpilih()" disabled>
                        Buat Manifest</button>
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
                        <h6 class="mb-2">Daftar Tugas</h6>
                        <div class="col-12">
                            @foreach ($cab as $ca)
                                @if (Auth::user()->id_cabang == $ca->id_cabang)
                                    <p>Daftar tugas untuk cabang <b>{{ $ca->nama_kota }}
                                            ({{ $ca->kode_area }})
                                        </b>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%" id="tugas-dt">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="head-cb"></th>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Resi</th>
                                <th>Pengirim</th>
                                <th>Penerima</th>
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
    @else
        Whops!
    @endif
@stop
@section('modal')
    {{-- Modal Manifest --}}
    <div class="modal fade" id="modalmanifest" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Manifest</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reset()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Buat Manifest/Daftar Muat</h5>
                            </div>
                            <hr>
                            <h6 class="mb-2">Nomor
                                <span id="no_manifest"></span>
                            </h6>
                            <div class="col-12">
                                <p id="count-resi" class="mb-0">
                                </p>
                                @foreach ($cab as $ca)
                                    @if (Auth::user()->id_cabang == $ca->id_cabang)
                                        <p>Dari cabang <b>{{ $ca->nama_kota }}
                                                ({{ $ca->kode_area }})
                                            </b>
                                        </p>
                                    @endif
                                @endforeach
                            </div>

                            <form id="form-manifest" class="row g-3" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div id="isian">
                                    <input id="no_man" type="hidden">
                                    <input id="id_cab" type="hidden" value="{{ $id_cabang }}">
                                    <div id="drvnotlp" class="row g-2">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Driver</label>
                                            <input id="driver" type="text"
                                                class="form-control @error('driver') is-invalid @enderror" name="driver"
                                                required>
                                            <input id="jml_tujuan" type="hidden">
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Nomor Telephone</label>
                                            <input id="no_tlp" type="text"
                                                class="form-control @error('no_tlp') is-invalid @enderror" name="no_tlp"
                                                required>
                                        </div>
                                    </div>
                                    <div id="nopjken" class="row g-2">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Nopol</label>
                                            <input id="nopol" type="text"
                                                class="form-control @error('nopol') is-invalid @enderror" name="nopol"
                                                required>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Jenis Kendaraan</label>
                                            <input id="jken" type="text"
                                                class="form-control @error('jken') is-invalid @enderror" name="jken"
                                                required>
                                        </div>
                                    </div>
                                    <div id="form_sample" class="row g-2">
                                        <div id="isi_form"></div>
                                        <div id="isi_form2"></div>
                                    </div>
                                    <div id="tombolnya" class="col-12">
                                        <button type="submit" id="tsubmit" class="btn btn-primary ">Simpan</button>
                                        <button type="reset" id="treset" class="btn btn-danger"
                                            data-bs-dismiss="modal">Reset</button>
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
        let list_tugas = [];
        let hitung_checked = 0;

        const table = $("#tugas-dt").DataTable({
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
                [1, "desc"]
            ],
            "ajax": {
                url: "{{ url('') }}/daftar_tugas/{{ $id_cabang }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}",
                        d.dari = $("#dari_tanggal").val(),
                        d.sampai = $("#sampai_tanggal").val()
                }
            },
            "columnDefs": [{
                    "targets": 0,
                    "data": "id_pengiriman",
                    "sortable": false,
                    'checkboxes': {
                        'selectRow': true
                    },
                }, {
                    "targets": 1,
                    "data": "no_resi",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                        list_tugas[row.id_pengiriman] = row;
                    }
                }, {
                    "targets": 2,
                    "data": "tgl_masuk",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 3,
                    "data": "no_resi",
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
                },
                {
                    "targets": 5,
                    "sortable": false,
                    "data": "nama_penerima",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 6,
                    "sortable": false,
                    "data": "kota_penerima",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 7,
                    "data": "status_sent",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return `<div ` + row.class + `>` +
                            row.nama_status + `</div>`;
                    }
                }, {
                    "targets": 8,
                    "data": "no_resi",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        const url_print1 = "/pengiriman/print/" + btoa(row.id_pengiriman)
                        return `<div class="d-flex order-actions">
                            <a href="` + url_print1 + `" id="btnprn" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a>
                            <a class="ms-3" data-bs-toggle="modal" data-bs-target="#modalDetail"><i class='lni lni-eye'></i></a>
                        </div>`;
                    }
                },
            ],
            'select': {
                'style': 'multi'
            },
        });

        table.on('click', 'td:first-child', e => {
            let all_checkbox = $('#tugas-dt tbody .dt-checkboxes:checked')
            let manifest_status = (all_checkbox.length > 0)
            $("#manifest").prop('disabled', !manifest_status)
        });

        $('.dt-checkboxes-select-all').on('click', function() {
            var isChecked = $("#tugas-dt thead input[type='checkbox']").prop('checked')
            $("#manifest").prop('disabled', !isChecked)
        });

        function addManifest() {
            const url = "{{ url('') }}/add_manifest/{{ $id_cabang }}"
            $.ajax({
                url,
                method: "GET",
                success: function(response) {
                    $('#no_manifest').html(response);
                    document.getElementById("no_man").setAttribute("value", response);
                },
                error: function(jqXHR, textStatus, errorThrown) {}
            })
        }

        function manifestTerpilih() {
            let all_id_pengiriman = []
            var rows_selected = table.column(0).checkboxes.selected();
            $.each(rows_selected, function(index, rowId) {
                all_id_pengiriman.push(rowId)
            })

            const url = "{{ url('showmanifest') }}";
            $.ajax({
                url,
                method: "POST",
                data: {
                    id: all_id_pengiriman,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // console.log(response)
                    $('#modalmanifest').modal('show')
                    $("#form-manifest #id_terpilih").val(all_id_pengiriman)
                    $('#count-resi').text(all_id_pengiriman.length + " Resi");
                    addManifest();

                    var x = document.getElementById("form_sample");
                    var y = document.getElementById("isi_form");
                    y.setAttribute("class", 'col-md');
                    x.setAttribute("class", 'row g-2');
                    x.appendChild(y);

                    var z = document.getElementById("isi_form2");
                    z.setAttribute("class", 'col-md-6');
                    x.appendChild(z);
                    const unique = Array.from(new Set(response));
                    let result = 0;
                    for (let i = 0; i < unique.length; i++) {
                        const a = unique[i];
                        // console.log(a)
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


                        var messagelabel2 = document.createElement('label');
                        messagelabel2.setAttribute("class", "form-label");
                        messagelabel2.innerHTML = "Estimasi";
                        z.appendChild(messagelabel2)

                        var inputdate = document.createElement('input'); // Create input field for name
                        inputdate.setAttribute("type", "date");
                        inputdate.setAttribute("name", "estimasi" + [i]);
                        inputdate.setAttribute("id", "estimasi" + [i]);
                        inputdate.setAttribute("class", "form-control");
                        z.appendChild(inputdate);
                        y.appendChild(messagebreak);
                        z.appendChild(messagebreak2);

                        $("#form-manifest #jml_tujuan").val(unique.length)
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // alert(jqXHR.responseJSON.errors.id_penelitian)
                }
            })
            $("#manifest").prop('disabled', true)
            //

        }

        $('#form-manifest').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            submitFormWaktu()
        });

        $('#form-manifest').on('reset', function(event) {
            event.preventDefault() //jangan disubmit
            reset()
        });

        function submitFormWaktu() {
            let form = $('#form-manifest');
            let all_tujuan = []
            let all_estimasi = []
            let all_id_pengiriman = []
            var rows_selected = table.column(0).checkboxes.selected();
            $.each(rows_selected, function(index, rowId) {
                all_id_pengiriman.push(rowId)
            })
            let no_manifest = document.getElementById("no_man").value;
            let id_cab = document.getElementById("id_cab").value;
            let driver = document.getElementById("driver").value;
            let no_tlp = document.getElementById("no_tlp").value;
            let nopol = document.getElementById("nopol").value;
            let jken = document.getElementById("jken").value;
            let jml_tujuan = document.getElementById("jml_tujuan").value;

            for (let i = 0; i < jml_tujuan; i++) {
                let tujuan = document.getElementById("tujuan" + [i]).value;
                all_tujuan.push(tujuan)
                let estimasi = document.getElementById("estimasi" + [i]).value;
                all_estimasi.push(estimasi)
            }

            const url = "{{ url('insertmanifest') }}";
            $.ajax({
                url,
                method: "POST",
                data: {
                    no_manifest: no_manifest,
                    id_cab: id_cab,
                    driver: driver,
                    no_tlp: no_tlp,
                    nopol: nopol,
                    jken: jken,
                    id: all_id_pengiriman,
                    tujuan: all_tujuan,
                    estimasi: all_estimasi,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response == true) {
                        window.location.href = "{{ url('') }}/daftarmuat/" + btoa({{ $id_cabang }});
                    } else if (response == false) {
                        alert("No Manifest already exist!!!");
                    }
                },
                error: function(e) {
                    alert("Whops!")
                }
            })
        }

        function reset() {
            var isChecked = false
            $('#head-cb').prop('checked', isChecked)
            $('.cb-child').prop('checked', isChecked)
            $("#manifest").prop('disabled', !isChecked)

            $('#no_man').remove();
            $('#id_cab').remove();
            $('#drvnotlp').remove();
            $('#nopjken').remove();
            $('#form_sample').remove();
            $('#tombolnya').remove();

            $('#isian').append('<input id="no_man" type="hidden">' +
                '<input id="id_cab" type="hidden" value="{{ $id_cabang }}">' +
                '<div id="drvnotlp" class="row g-2">' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Driver</label>' +
                '<input id="driver" type="text" class="form-control @error('driver') is-invalid @enderror" name="driver" required>' +
                '<input id="jml_tujuan" type="hidden"></div>' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Nomor Telephone</label>' +
                '<input id="no_tlp" type="text" class="form-control @error('no_tlp') is-invalid @enderror" name="no_tlp" required></div></div>' +
                '<div id="nopjken" class="row g-2">' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Nopol</label>' +
                '<input id="nopol" type="text" class="form-control @error('nopol') is-invalid @enderror" name="nopol" required></div>' +
                '<div class="col-md">' +
                '<label for="inputAddress" class="form-label">Jenis Kendaraan</label>' +
                '<input id="jken" type="text" class="form-control @error('jken') is-invalid @enderror" name="jken" required>' +
                '</div></div>' +
                '<div id="form_sample">' +
                '<div id="isi_form"></div>' +
                '<div id="isi_form2"></div></div>' +
                '<div id="tombolnya" class="col-12">' +
                '<button type="submit" id="tsubmit" class="btn btn-primary ">Simpan</button>' +
                '<button type="reset" id="treset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button></div>'
            );
        }

        function filter() {
            table.ajax.reload(null, false)
            // console.log(recordsTotal)
            var dari_tanggal = $('#dari_tanggal').val();
            var sampai_tanggal = $('#sampai_tanggal').val();

            fill_show_all(dari_tanggal, sampai_tanggal);

        }

        function fill_show(dari_tanggal, sampai_tanggal) {
            // console.log(dari_tanggal, sampai_tanggal, filter_kondisi)
            $.ajax({
                url: "{{ url('') }}/tampil_filter/{{ $id_cabang }}",
                method: 'POST',
                data: {
                    tgl_dari: dari_tanggal,
                    tgl_sampai: sampai_tanggal,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    console.log(res)
                    // let num = res[1].jumlah;
                    if (res[1].jumlah != null || res[2].kg != null) {
                        $('#transaksi').text(formatRupiah(res[0]));
                        //Tonase
                        $('#tonase').addClass('tonase2').text(formatRupiah(res[2].kg));
                        $(this).addClass('tonase2').removeClass('tonase');
                        //Total Omset
                        var n = res[1].jumlah;
                        $('#omsetheading').addClass('omsetheading2').text('Rp' + formatRupiah(n));
                        $(this).addClass('omsetheading2').removeClass('omsetheading');
                        $('#p_omset').addClass('p_omset2').text(dari_tanggal + ' s.d. ' + sampai_tanggal);
                        $(this).addClass('p_omset2').removeClass('p_omset');
                    } else {
                        $('#transaksi').text(formatRupiah(res[0]));
                        //Tonase
                        $('#tonase').addClass('tonase2').text('0');
                        $(this).addClass('tonase2').removeClass('tonase');
                        //Total Omset
                        $('#omsetheading').addClass('omsetheading2').text('Rp0');
                        $(this).addClass('omsetheading2').removeClass('omsetheading');
                        $('#p_omset').addClass('p_omset2').text(dari_tanggal + ' s.d. ' + sampai_tanggal);
                        $(this).addClass('p_omset2').removeClass('p_omset');
                    }
                    // console.log(res[2])

                },
                error: function(e) {
                    console.log(e)

                }
            })
        }

        function fill_show_all(dari_tanggal, sampai_tanggal) {
            // console.log(dari_tanggal, sampai_tanggal, filter_kondisi)
            $.ajax({
                url: "{{ url('') }}/show_fill_all2/{{ $id_cabang }}",
                method: 'POST',
                data: {
                    tgl_dari: dari_tanggal,
                    tgl_sampai: sampai_tanggal,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    // let num = res[1].jumlah;
                    console.log(res)
                    if (res[1].jumlah != null || res[2].kg != null) {
                        $('#transaksi').text(formatRupiah(res[0]));
                        //Tonase
                        $('#tonase').addClass('tonase2').text(formatRupiah(res[2].kg));
                        $(this).addClass('tonase2').removeClass('tonase');
                        //Total Omset
                        var n = res[1].jumlah;
                        $('#omsetheading').addClass('omsetheading2').text('Rp' + formatRupiah(n));
                        $(this).addClass('omsetheading2').removeClass('omsetheading');
                        $('#p_omset').addClass('p_omset2').text(dari_tanggal + ' s.d. ' + sampai_tanggal);
                        $(this).addClass('p_omset2').removeClass('p_omset');
                    } else {
                        $('#transaksi').text(formatRupiah(res[0]));
                        //Tonase
                        $('#tonase').addClass('tonase2').text('0');
                        $(this).addClass('tonase2').removeClass('tonase');
                        //Total Omset
                        $('#omsetheading').addClass('omsetheading2').text('Rp0');
                        $(this).addClass('omsetheading2').removeClass('omsetheading');
                        $('#p_omset').addClass('p_omset2').text(dari_tanggal + ' s.d. ' + sampai_tanggal);
                        $(this).addClass('p_omset2').removeClass('p_omset');
                    }
                    // console.log(res[2])

                },
                error: function(e) {
                    console.log(e)

                }
            })
        }

        function fill_tanpa_filter() {
            $.ajax({
                url: "{{ url('') }}/show_fill_tanpa_filter/{{ $id_cabang }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    var n = res[0].jumlah;
                    $('#transaksi').text(formatRupiah(res[1]));
                    //Total Omset
                    $('#omsetheading').addClass('omsetheading2').text('Rp' + formatRupiah(n));
                    $(this).addClass('omsetheading2').removeClass('omsetheading');
                    $('#p_omset').addClass('p_omset2').text(res[3].tgl_masuk + ' s.d. ' + res[4].tgl_masuk);
                    $(this).addClass('p_omset2').removeClass('p_omset');
                    $('#tonase').addClass('tonase2').text(formatRupiah(res[2].kg));
                    $(this).addClass('tonase2').removeClass('tonase');

                }
            })
        }
    </script>
@endsection
