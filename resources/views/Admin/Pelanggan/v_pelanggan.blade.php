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
                    <li class="breadcrumb-item active" aria-current="page">Pelanggan</li>
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
                    <h6 class="mb-2">Data Pelanggan</h6>
                </div>
                <div class="dropdown ms-auto mb-2">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#modalTambah">Tambah</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="width:100%" id="pelanggan-dt">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Perusahaan</th>
                            <th>Informasi</th>
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
                    <h5 class="modal-title">Tambah Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body p-5">
                            <div class="card-title d-flex align-items-center">
                                <div><i class="lni lni-car-alt me-1 font-22 text-primary"></i>
                                </div>
                                <h5 class="mb-0 text-primary">Tambah Pelanggan</h5>
                            </div>
                            <hr>
                            <form class="row g-3" action="/add/pelanggan" method="POST" enctype="multipart/form-data"
                                id="form-pelanggan">
                                {{ csrf_field() }}
                                <div class="col-md-8">
                                    <label for="inputAddress" class="form-label">Nama Perusahaan</label>
                                    <input id="nama_perusahaan" type="text" class="form-control" name="nama_perusahaan"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label for="inputAddress" class="form-label">Kota</label>
                                    <input id="kota" type="text" class="form-control" name="kota" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputAddress" class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" id="alamat" placeholder="Jl. Perkutut..." rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Nama SPV</label>
                                    <input id="nama_spv" type="text" class="form-control" name="nama_spv" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Telepon SPV</label>
                                    <input id="tlp_spv" type="text" class="form-control" name="tlp_spv" required
                                        placeholder="+62..">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Kode Perusahaan</label>
                                    <input id="k_perusahaan" type="text" class="form-control" name="k_perusahaan"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAddress" class="form-label">Email</label>
                                    <input id="email_prshn" type="text" class="form-control" name="email_prshn"
                                        required autocomplete="email">
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
    @foreach ($plg as $p)
        <div class="modal fade" id="modalEdit{{ $p->id_pelanggan }}" tabindex="-1" aria-hidden="true">
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
                                <form class="row g-3" action="/update/pelanggan/{{ $p->id_pelanggan }}" method="POST"
                                    enctype="multipart/form-data" id="form-pelanggan">
                                    {{ csrf_field() }}
                                    <div class="col-md-8">
                                        <label for="inputAddress" class="form-label">Nama Perusahaan</label>
                                        <input type="hidden" name="id_pelanggan" value="{{ $p->id_pelanggan }}">
                                        <input id="nama_perusahaan" type="text" class="form-control"
                                            name="nama_perusahaan" value="{{ $p->nama_perusahaan }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="inputAddress" class="form-label">Kota</label>
                                        <input id="kota" type="text" class="form-control" name="kota"
                                            value="{{ $p->kota }}" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="inputAddress" class="form-label">Alamat</label>
                                        <textarea class="form-control" name="alamat" id="alamat" placeholder="Jl. Perkutut..." rows="3">{{ $p->alamat }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputAddress" class="form-label">Nama SPV</label>
                                        <input id="nama_spv" type="text" class="form-control" name="nama_spv"
                                            value="{{ $p->nama_spv }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputAddress" class="form-label">Telepon SPV</label>
                                        <input id="tlp_spv" type="text" class="form-control" name="tlp_spv"
                                            value="{{ $p->tlp_spv }}" required placeholder="+62..">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputAddress" class="form-label">Kode Perusahaan</label>
                                        <input id="k_perusahaan" type="text" class="form-control" name="k_perusahaan"
                                            value="{{ $p->k_perusahaan }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputAddress" class="form-label">Email</label>
                                        <input id="email_prshn" type="text" class="form-control" name="email_prshn"
                                            value="{{ $p->email_prshn }}" required autocomplete="email">
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="reset" class="btn btn-danger"
                                            data-bs-dismiss="modal">Reset</button>
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
    @foreach ($plg as $p)
        <div class="modal fade" id="modalHapus{{ $p->id_pelanggan }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">Apakah Anda yakin menghapus data?</div>
                    <div class="modal-footer">
                        <a href="/delete_pelanggan/{{ $p->id_pelanggan }}" class="btn btn-primary">OK!</a>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@stop
@section('js')
    <script type="text/javascript">
        let list_pelanggan = [];

        const table = $("#pelanggan-dt").DataTable({
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
                url: "{{ url('data_pelanggan') }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                "targets": 0,
                "sortable": false,
                "data": "id_pelanggan",
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                "targets": 1,
                "sortable": false,
                "data": "nama_perusahaan",
                "render": function(data, type, row, meta) {
                    list_pelanggan[row.id_pelanggan] = row;
                    return data + `<br>` + row.email_prshn;
                }
            }, {
                "targets": 2,
                "sortable": false,
                "data": "id_pelanggan",
                "render": function(data, type, row, meta) {
                    return `<p><b>Kota: </b>` + row.kota + `<br>` +
                        `<b> Alamat:` + row.alamat + `</b> -- ` + row.kota + `<br>` +
                        `<b>SPV: </b>` + row.nama_spv + ` ` + row.tlp_spv + `</p>`;
                }
            }, {
                "targets": 3,
                "sortable": false,
                "data": "id_pelanggan",
                "render": function(data, type, row, meta) {
                    return `<div class="d-flex order-actions">
                            <a href="javascript:;" class="ms-3" data-bs-toggle="modal" data-bs-target="#modalEdit${row.id_pelanggan}"><i class='bx bxs-edit'></i></a>
						    <a href="javascript:;" class="ms-3" data-bs-toggle="modal" data-bs-target="#modalHapus${row.id_pelanggan}"><i class='bx bxs-trash'></i></a>
                        </div>`;
                }
                //  <a data-bs-toggle="modal" data-bs-target="#modalDetail"><i class='lni lni-eye'></i></a>
                //  <a class="ms-3" onclick="editPelanggan(${row.id_pelanggan})"><i class='bx bxs-edit'></i></a>
            }, ]
        });

        function editPelanggan(id_pelanggan) {
            // console.log(id_waktu_upload)
            $.ajax({
                url: "{{ url('detail_pelanggan') }}?id_pelanggan=" + id_pelanggan,
                success: function(res) {
                    // console.log(res)
                    const pelanggan = list_pelanggan[id_pelanggan]

                    // SET SEMUA KE DEFAULT
                    $("#form-pelanggan input:not([name='_token'])").val('')
                    $("#form-pelanggan textarea").val('')

                    // var awal = moment(new Date(res.awal_waktu_upload)).format('YYYY-MM-DDTHH:mm')
                    // var akhir = moment(new Date(res.akhir_waktu_upload)).format('YYYY-MM-DDTHH:mm')
                    $('#modalTambah').modal('show')
                    $("#modalTambah #nama_perusahaan").val(pelanggan.nama_perusahaan)
                    $("#form-pelanggan [name='nama_perusahaan']").val(res.nama_perusahaan)

                    // $('#btn-updateWaktu').removeClass('hidden')
                    // $('#btn-simpanWaktu').addClass('simpan')
                    // document.getElementById("id_ref_dokumen").disabled = true;
                },
                error: function(e) {
                    console.log(e)
                    alert("Something wrong!")
                }
            })
        }
    </script>
@stop
