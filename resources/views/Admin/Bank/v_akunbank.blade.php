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
                    <li class="breadcrumb-item active" aria-current="page">Akun Bank</li>
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
                    <h6 class="mb-2">Data Akun Bank</h6>
                </div>
                <div class="dropdown ms-auto mb-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#modalTambah">Tambah</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="bank-dt">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Bank</th>
                            <th>No. Rekening</th>
                            <th>Atas Nama</th>
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
                    <h5 class="modal-title">Tambah Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <h5 class="mb-0 text-primary">Tambah Bank</h5>
                            </div>
                            <hr>
                            <form class="row g-3" action="/add_bank" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-4">
                                    <label for="inputAddress" class="form-label">Nama Bank</label>
                                    <input id="email" type="text"
                                        class="form-control @error('nama_kota') is-invalid @enderror" name="nama_bank"
                                        required>

                                </div>
                                <div class="col-md-4">
                                    <label for="inputAddress" class="form-label">Nomor Rekening</label>
                                    <input id="email" type="text"
                                        class="form-control @error('nama_kota') is-invalid @enderror" name="no_rek"
                                        required>

                                </div>
                                <div class="col-md-4">
                                    <label for="inputAddress" class="form-label">Atas Nama</label>
                                    <input id="email" type="text"
                                        class="form-control @error('nama_kota') is-invalid @enderror" name="an"
                                        required>

                                </div>

                                <div class="col-md-12">
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
    {{-- @foreach ($bank as $b) --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Bank</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="lni lni-car-alt me-1 font-22 text-primary"></i>
                                </div>
                                <h5 class="mb-0 text-primary">Edit Bank</h5>
                            </div>
                            <hr>
                            <form id="form-edit" class="row g-3" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="col-md-4">
                                    <label for="inputAddress" class="form-label">Nama Bank</label>
                                    <input id="email" type="text" class="form-control" name="nama_bank" required>
                                    <input id="email" type="hidden" class="form-control" name="id_bank" required>

                                </div>
                                <div class="col-md-4">
                                    <label for="inputAddress" class="form-label">Nomor Rekening</label>
                                    <input id="email" type="text" class="form-control" name="no_rek" required>

                                </div>
                                <div class="col-md-4">
                                    <label for="inputAddress" class="form-label">Atas Nama</label>
                                    <input id="email" type="text" class="form-control" name="an" required>

                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- @endforeach --}}

@stop
@section('js')
    <script type="text/javascript">
        let listbank = [];

        const table = $("#bank-dt").DataTable({
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
                url: "{{ url('list_bank') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "data": "nama_bank",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    listbank[row.id_bank] = row;
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                "targets": 1,
                "data": "nama_bank",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 2,
                "data": "no_rek",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 3,
                "data": "an",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 4,
                "sortable": false,
                "data": "id_bank",
                "render": function(data, type, row, meta) {
                    return `<div class="d-flex order-actions">
                            <a  class="ms-3" onclick="edit(${row.id_bank})"><i class='bx bxs-edit'></i></a>
						    <a  class="ms-3" onclick="hapus(${row.id_bank})"><i class='bx bxs-trash'></i></a>
                        </div>`;
                }
                //  <a data-bs-toggle="modal" data-bs-target="#modalDetail"><i class='lni lni-eye'></i></a>
            }, ]
        });

        function edit(id_bank) {
            const bank = listbank[id_bank]
            $('#modalEdit').modal('show');
            $("#modalEdit [name='id_bank']").val(id_bank)
            $("#modalEdit [name='nama_bank']").val(bank.nama_bank)
            $("#modalEdit [name='no_rek']").val(bank.no_rek)
            $("#modalEdit [name='an']").val(bank.an)
        }

        $('#form-edit').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            editbank()
        });

        function editbank() {
            let form = $('#form-edit');
            const url = "{{ url('edit_bank') }}";
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    if (response === true) {
                        table.ajax.reload(null, false)
                        alert('Berhasil!')
                    }
                },
                error: function(e) {
                    alert('Something wrong!')
                }
            })
        }

        function hapus(id_bank) {
            const c = confirm("Hapus data?")
            if (c === true) {
                $.ajax({
                    url: "{{ url('hapus_bank') }}?id_bank=" + id_bank,
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response === true) {
                            table.ajax.reload(null, false)
                            alert('Berhasil Menghapus!')
                        }
                    },
                    error: function(e) {
                        //   console.log(e)
                        alert("Something wrong!")
                    }
                })
            }
        }

        $('#form-hapus').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            hapusbank()
        });
    </script>
@stop
