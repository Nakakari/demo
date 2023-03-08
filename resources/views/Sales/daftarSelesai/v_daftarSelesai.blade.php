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
                        <li class="breadcrumb-item active" aria-current="page">Selesai</li>
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
                        <h6 class="mb-2">Daftar Selesai</h6>
                        <div class="col-12">
                            @foreach ($cab as $ca)
                                @if (Auth::user()->id_cabang == $ca->id_cabang)
                                    <p>Daftar selesai untuk cabang <b>{{ $ca->nama_kota }}
                                            ({{ $ca->kode_area }})
                                        </b>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" style="width:100%" id="selesai-dt">
                        <thead class="table-light">
                            <tr>
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
                    <h5 class="modal-title">Edit Resi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="reset()"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Detail Pengiriman</h5>
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
                                            <select name="stat" id="stat" class="form-select" disabled></select>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Keterangan</label>
                                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="upload">
                                        <div class="col-6 mb-2">
                                            <label for="inputAddress" class="form-label">Upload File</label><br>
                                            <img id="file_bukti" width="300" height="300"><br>
                                        </div>
                                    </div>
                                    <div class="col-12" id="tombolnya">
                                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal"
                                            onclick="reset()">Close</button>
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
    <script type="text/javascript">
        let list_selesai = [];

        const table = $("#selesai-dt").DataTable({
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
                url: "{{ url('') }}/daftar_selesai/{{ $id_cabang }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                    "targets": 0,
                    "data": "id_pengiriman",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                        list_selesai[row.id_pengiriman] = row;
                    }
                }, {
                    "targets": 1,
                    "data": "tgl_tiba",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return data;
                        // console.log(recordsTotal)
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
                    "sortable": false,
                    "data": "nama_pengirim",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 4,
                    "sortable": false,
                    "data": "nama_penerima",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 5,
                    "sortable": false,
                    "data": "nama_kota",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "targets": 6,
                    "sortable": false,
                    "data": "status_sent",
                    "render": function(data, type, row, meta) {
                        var tampilan = ``;
                        if (data == 6) {
                            tampilan +=
                                `<div class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3">` +
                                'Selesai' + `</div>`
                        }
                        return tampilan;
                    }
                },
                {
                    "targets": 7,
                    "sortable": false,
                    "data": "id_pengiriman",
                    "render": function(data, type, row, meta) {
                        const url_print1 = "/pengiriman/print/" + btoa(row.id_pengiriman)
                        return `<div class="d-flex order-actions">
                            <a id="btnprn" href="` + url_print1 + `" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a>
                            <a class="ms-3" data-bs-toggle="modal" href="" data-bs-target="#modaledit" id="detailman" onclick="idTerpilih(${data})"><i class='lni lni-eye'></i></a>
                        </div>`;
                    }
                },
            ]
        });

        function idTerpilih(id) {
            // console.log(id)
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
                    document.getElementById("file_bukti").setAttribute("src",
                        '{{ url('') }}/foto_bukti/' + response['file_bukti']);
                    $('#stat').html(response['stat']);
                    $('#keterangan').html(response['ket']);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // alert(jqXHR.responseJSON.errors.id_penelitian)
                }
            })
            $("#manifest").prop('disabled', true)
        }

        function reset() {
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
                                            <select name="stat" id="stat" class="form-select" disabled></select>
                                        </div>
                                        <div class="col-md">
                                            <label for="inputAddress" class="form-label">Keterangan</label>
                                            <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="row g-2" id="upload">
                                        <div class="col-6 mb-2">
                                            <label for="inputAddress" class="form-label">Upload File</label><br>
                                            <img id="file_bukti" width="300" height="300"><br>
                                        </div>
                                    </div>
                                    <div class="col-12" id="tombolnya">
                                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" onclick="reset()">Close</button>
                                    </div>`);
        }
    </script>
@stop
