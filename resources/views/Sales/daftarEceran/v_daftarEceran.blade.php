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
                        <li class="breadcrumb-item active" aria-current="page">Eceran</li>
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
                        <h6 class="mb-2">Daftar Eceran</h6>
                        <div class="col-12">
                            @foreach ($cab as $ca)
                                @if (Auth::user()->id_cabang == $ca->id_cabang)
                                    <p>Daftar eceran untuk cabang <b>{{ $ca->nama_kota }}
                                            ({{ $ca->kode_area }})
                                        </b>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="dropdown ms-auto mb-2">
                        <button class="btn btn-outline-warning" id="ecer-terpilih" onclick="ecerTerpilih()" disabled><i
                                class="lni lni-cart-full"></i>Proses</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%" id="daftar_ecer-dt">
                        <thead class="table-light">
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="head-cb"></th>
                                <th>No</th>
                                <th>Tanggal Tiba</th>
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
        <p>Whoops!</p>
    @endif
@stop
@section('modal')
    {{-- Modal Eceran Masal --}}
    <div class="modal fade" id="oneByOneEceran" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eceran Masal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <form method="POST" enctype="multipart/form-data" id="form-masal">
                                {{ csrf_field() }}
                                <div class="card-title d-flex align-items-center">
                                    <h5 class="mb-0 text-primary">Eceran Masal</h5>
                                </div>
                                <hr>
                                <h5 id="get_total_resi"></h5>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="nama_driver" class="form-label">Nama Driver</label>
                                        <input name="driver" id="driver" class="form-control" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nomor-telephone" class="form-label">Nomor Telephone</label>
                                        <input name="no_tlp" id="no_tlp" class="form-control" required />
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="nopol" class="form-label">Nopol</label>
                                        <input name="nopol" id="nopol" class="form-control" required />
                                    </div>
                                    <div class="col-md-6">
                                        <label for="jenisKendaraan" class="form-label">Jenis Kendaraan</label>
                                        <input name="jenis_kendaraan" id="jenis_kendaraan" class="form-control"
                                            required />
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label for="nopol" class="form-label">Update Status</label>
                                        <select name="status_update" id="status_update" class="form-select">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success"
                                        onclick="submitEceranMasal()">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Eceran One By One --}}
    <div class="modal fade" id="modaledit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Daftar Eceran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reset()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Detail Data Eceran Pengiriman</h5>
                            </div>
                            <hr>
                            <div class="col-12">
                                <h6 class="mb-2">No. Resi
                                    <span id="no_resi"></span>
                                </h6>
                            </div>
                            <form id="form-edit" class="row g-3" action="/editdetailecer" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div id="isian">
                                    <input id="id_peng" name="id_peng" type="hidden">
                                    <div class="row g-2" id="nama">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Pengirim</label>
                                            <input id="n_pengirim" type="text"
                                                class="form-control @error('driver') is-invalid @enderror" name="driver"
                                                disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Penerima</label>
                                            <input id="n_penerima" type="text"
                                                class="form-control @error('no_tlp') is-invalid @enderror" name="no_tlp"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="alamat">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Alamat</label>
                                            <input id="alamat_kir" type="text"
                                                class="form-control @error('nopol') is-invalid @enderror" name="nopol"
                                                disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Alamat</label>
                                            <input id="alamat_ter" type="text"
                                                class="form-control @error('alamat_ter') is-invalid @enderror"
                                                name="nopol" disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="cab">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Cabang Pengirim</label>
                                            <input id="cab_kir" type="text"
                                                class="form-control @error('cab_kir') is-invalid @enderror" name="nopol"
                                                disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Cabang Penerima</label>
                                            <input id="cab_ter" type="text"
                                                class="form-control @error('cab_ter') is-invalid @enderror" name="nopol"
                                                disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="lain">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Update Status</label>
                                            <select name="stat" id="stat" class="form-select" required></select>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Keterangan</label>
                                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"></textarea>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="upload">
                                        <div class="col-6 mb-2">
                                            <label for="inputAddress" class="form-label">Upload File</label>
                                            <input id="upload" type="file"
                                                class="form-control @error('upload') is-invalid @enderror" name="upload"
                                                value="{{ old('upload') }}" required autocomplete="upload">
                                        </div>
                                    </div>
                                    <div class="col-12" id="tombolnya">
                                        <button id="submit-edit" type="submit" class="btn btn-primary ">Simpan</button>
                                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal"
                                            onclick="reset()">Reset</button>
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
        let list_ecer = [];

        const table = $("#daftar_ecer-dt").DataTable({
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
                url: "{{ url('') }}/list_ecer/{{ $id_cabang }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
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
                    "data": "id_pengiriman",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                        list_ecer[row.id_pengiriman] = row;
                    }
                }, {
                    "targets": 2,
                    "data": "tgl_tiba",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        var show = ``
                        var today = new Date();
                        var jatuh_tempo = new Date(data)

                        var Difference_In_Time = jatuh_tempo.getTime() - today.getTime();
                        var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

                        if (Difference_In_Days < 0 && row.status_sent == 4) {
                            show = `<p class="text-danger text-strong">` + data + `</p>`
                        } else {
                            show = data
                        }
                        return show;
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
                    "sortable": false,
                    "data": "nama_pengirim",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
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
                },
                {
                    "targets": 7,
                    "data": "status_sent",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return `<div ` + row.class + `>` +
                            row.nama_status + `</div>`;
                    }
                },
                {
                    "targets": 8,
                    "sortable": false,
                    "data": "id_pengiriman",
                    "render": function(data, type, row, meta) {
                        const url_print1 = "/pengiriman/print/" + btoa(row.id_pengiriman)
                        return `<div class="d-flex order-actions">
                            <a id="btnprn" href="` + url_print1 + `" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a>
                            <a class="ms-3" data-bs-toggle="modal" data-bs-target="#modaledit" id="detailman" onclick="idTerpilih(${data})"><i class='lni lni-eye'></i></a>
                        </div>`;
                    }
                },
            ],
            'select': {
                'style': 'multi'
            },
        });

        function idTerpilih(id) {
            const url = "{{ url('/detailecer/') }}" + "/" + id;
            $.ajax({
                url,
                method: "GET",
                success: function(response) {
                    // console.log(response)
                    $('#modaledit').modal('show')
                    $('#no_resi').html(response['no_resi']);
                    document.getElementById("n_pengirim").setAttribute("value", response['n_pengirim']);
                    document.getElementById("n_penerima").setAttribute("value", response['n_penerima']);
                    document.getElementById("alamat_kir").setAttribute("value", response['alamat_kir']);
                    document.getElementById("alamat_ter").setAttribute("value", response['alamat_ter']);
                    document.getElementById("cab_kir").setAttribute("value", response['cab_kir']['nama_kota'] +
                        ' - ' + response['cab_kir']['kode_area']);
                    document.getElementById("cab_ter").setAttribute("value", response['cab_ter']['nama_kota'] +
                        ' - ' + response['cab_kir']['kode_area']);
                    document.getElementById("id_peng").setAttribute("value", id);
                    $('#stat').html(response['stat']);
                },
                error: function(jqXHR, textStatus, errorThrown) {}
            })
            $("#manifest").prop('disabled', true)
        }

        $('#form-edit').on('reset', function(event) {
            event.preventDefault() //jangan disubmit
            reset()
        });

        function reset() {
            $('#id_peng').remove();
            $('#nama').remove();
            $('#alamat').remove();
            $('#cab').remove();
            $('#lain').remove();
            $('#upload').remove();
            $('#tombolnya').remove();

            $('#isian').append(`<input id="id_peng" name="id_peng" type="hidden">
                                    <div class="row g-2" id="nama">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Pengirim</label>
                                            <input id="n_pengirim" type="text"
                                                   class="form-control @error('driver') is-invalid @enderror" name="driver" disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Penerima</label>
                                            <input id="n_penerima" type="text"
                                                   class="form-control @error('no_tlp') is-invalid @enderror" name="no_tlp" disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="alamat">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Alamat</label>
                                            <input id="alamat_kir" type="text"
                                                   class="form-control @error('nopol') is-invalid @enderror" name="nopol" disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Alamat</label>
                                            <input id="alamat_ter" type="text"
                                                   class="form-control @error('alamat_ter') is-invalid @enderror" name="nopol" disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="cab">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Cabang Pengirim</label>
                                            <input id="cab_kir" type="text"
                                                   class="form-control @error('cab_kir') is-invalid @enderror" name="nopol" disabled>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Cabang Penerima</label>
                                            <input id="cab_ter" type="text"
                                                   class="form-control @error('cab_ter') is-invalid @enderror" name="nopol" disabled>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="lain">
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Update Status</label>
                                            <select name="stat" id="stat" class="form-select" required></select>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Keterangan</label>
                                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"></textarea>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="upload">
                                        <div class="col-6 mb-2">
                                            <label for="inputAddress" class="form-label">Upload File</label>
                                            <input id="upload" type="file"
                                                   class="form-control @error('upload') is-invalid @enderror" name="upload"
                                                   value="{{ old('upload') }}" required autocomplete="upload">
                                        </div>
                                    </div>
                                    <div class="col-12" id="tombolnya">
                                        <button id="submit-edit" type="submit" class="btn btn-primary ">Simpan</button>
                                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" onclick="reset()">Reset</button>
                                    </div>`);
        }

        $('.dt-checkboxes-select-all').on('click', function() {
            var isChecked = $("#daftar_ecer-dt thead input[type='checkbox']").prop('checked')
            $("#ecer-terpilih").prop('disabled', isChecked)
            $("#ecer-terpilih").removeClass('btn btn-outline-warning')
            $("#ecer-terpilih").addClass('btn btn-warning')
            if (isChecked == true) {
                $("#ecer-terpilih").removeClass('btn btn-warning')
                $("#ecer-terpilih").addClass('btn btn-outline-warning')
            }
        });

        table.on('click', 'td:first-child', e => {
            let all_checkbox = $('#daftar_ecer-dt tbody .dt-checkboxes:checked')
            let status = (all_checkbox.length > 0)
            $("#ecer-terpilih").prop('disabled', !status)
            $("#ecer-terpilih").removeClass('btn btn-outline-warning')
            $("#ecer-terpilih").addClass('btn btn-warning')
            if (status == false) {
                $("#ecer-terpilih").removeClass('btn btn-warning')
                $("#ecer-terpilih").addClass('btn btn-outline-warning')
            }
        });

        function ecerTerpilih() {
            let all_id_pengiriman = []
            var rows_selected = table.column(0).checkboxes.selected();
            $.each(rows_selected, function(index, rowId) {
                all_id_pengiriman.push(rowId)
            })
            let ids = all_id_pengiriman.join(',')
            $('#oneByOneEceran').modal('show')
            $('#form-masal #jml_resi').val(all_id_pengiriman.length)
            $("#get_total_resi").html(all_id_pengiriman.length + " resi")
            const url = "{{ route('get_status_pengiriman') }}";
            $.ajax({
                url,
                method: "GET",
                success: function(res) {
                    $('#status_update').html(res.status_pengiriman)
                },
                error: function(e) {
                    $('#oneByOneEceran').modal('hide')
                    error_noti()
                }
            })
        }

        function submitEceranMasal() {
            let all_id_pengiriman = []
            var rows_selected = table.column(0).checkboxes.selected();
            $.each(rows_selected, function(index, rowId) {
                all_id_pengiriman.push(rowId)
            })
            var nama_driver = $('#form-masal input[name=driver]').val()
            var jml_resi = all_id_pengiriman.length
            var nomor_telephone = $('#form-masal input[name=no_tlp]').val()
            var nopol = $('#form-masal input[name=nopol]').val()
            var jenis_kendaraan = $('#form-masal input[name=jenis_kendaraan]').val()
            var status_update = $('#status_update').find(":selected").val()
            const url = "{{ route('muat_eceran.store') }}";
            $.ajax({
                url,
                method: "POST",
                data: {
                    driver: nama_driver,
                    jumlah_resi: jml_resi,
                    ids: all_id_pengiriman,
                    no_tlp: nomor_telephone,
                    nopol: nopol,
                    jenis_kendaraan: jenis_kendaraan,
                    status_update: status_update,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    if (res == true) {
                        $('#oneByOneEceran').modal('hide')
                        table.ajax.reload(null, false)
                        success_noti()
                    }
                },
                error: function(e) {
                    $('#oneByOneEceran').modal('hide')
                    error_noti()
                }
            })
        }
    </script>
@stop
