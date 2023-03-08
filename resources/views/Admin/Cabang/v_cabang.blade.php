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
                    <li class="breadcrumb-item active" aria-current="page">Cabang</li>
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
                    <h6 class="mb-2">Data Cabang</h6>
                </div>
                <div class="dropdown ms-auto mb-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#modalTambah">Tambah</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="cabang-dt">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Area</th>
                            <th>Kota Cabang</th>
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
@section('modal')
    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Cabang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="lni lni-car-alt me-1 font-22 text-primary"></i>
                                </div>
                                <h5 class="mb-0 text-primary">Tambah Kota</h5>
                            </div>
                            <hr>
                            <form class="row g-3" action="/upload/cabang" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Kota Cabang</label>
                                    <input id="email" type="text"
                                        class="form-control @error('nama_kota') is-invalid @enderror" name="nama_kota"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('nama_kota')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Kota Area</label>
                                    <input id="email" type="text"
                                        class="form-control @error('nama_kota') is-invalid @enderror" name="kode_area"
                                        value="{{ old('kode_area') }}" required autocomplete="email">

                                    @error('kode_area')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary ">Simpan</button>
                                    <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Modal Edit --}}
    @foreach ($cab as $c)
        <div class="modal fade" id="modalEdit{{ $c->id_cabang }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Cabang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card border-top border-0 border-4 border-primary">
                            <div class="card-body p-5">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="lni lni-car-alt me-1 font-22 text-primary"></i>
                                    </div>
                                    <h5 class="mb-0 text-primary">Tambah Kota</h5>
                                </div>
                                <hr>
                                <form class="row g-3" action="/upload/cabang/{{ $c->id_cabang }}" method="POST"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="col-md-6">
                                        <label for="inputAddress" class="form-label">Kota Cabang</label>
                                        <input type="hidden" name="id_cabang" value="{{ $c->id_cabang }}">
                                        <input id="email" type="text"
                                            class="form-control @error('nama_kota') is-invalid @enderror" name="nama_kota"
                                            value="{{ $c->nama_kota }}" required autocomplete="email">

                                        @error('nama_kota')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputAddress" class="form-label">Kota Area</label>
                                        <input id="email" type="text"
                                            class="form-control @error('kode_area') is-invalid @enderror" name="kode_area"
                                            value="{{ $c->kode_area }}" required autocomplete="email">

                                        @error('kode_area')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary ">Update</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
    {{-- Modal Hapus --}}
    @foreach ($cab as $c)
        <div class="modal fade" id="modalHapus{{ $c->id_cabang }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">Apakah Anda yakin menghapus data?</div>
                    <div class="modal-footer">
                        <a href="/delete_cabang/{{ $c->id_cabang }}" class="btn btn-primary">OK!</a>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@stop
@section('js')
    <script type="text/javascript">
        let list_cabang = [];

        const table = $("#cabang-dt").DataTable({
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
                url: "{{ url('list_cabang') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "nama_kota",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                "targets": 1,
                "data": "kode_area",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    list_cabang[row.id_cabang] = row;
                    return data;
                }
            }, {
                "targets": 2,
                "data": "nama_kota",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    list_cabang[row.id_cabang] = row;
                    return data;
                }
            }, {
                "targets": 3,
                "sortable": false,
                "data": "nama_kota",
                "render": function(data, type, row, meta) {
                    return `<div class="d-flex order-actions">
                            <a href="javascript:;" class="ms-3" data-bs-toggle="modal" data-bs-target="#modalEdit${row.id_cabang}"><i class='bx bxs-edit'></i></a>
						    <a href="javascript:;" class="ms-3" data-bs-toggle="modal" data-bs-target="#modalHapus${row.id_cabang}"><i class='bx bxs-trash'></i></a>
                        </div>`;
                }
                //  <a data-bs-toggle="modal" data-bs-target="#modalDetail"><i class='lni lni-eye'></i></a>
            }, ]
        });
    </script>
@stop
