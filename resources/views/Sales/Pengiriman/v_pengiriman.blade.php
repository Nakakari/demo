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
                        <li class="breadcrumb-item active" aria-current="page">Pengiriman</li>
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
                                    Rp {{ number_format($totalOmset->jumlah, 0, ',', '.') }}</h4>
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
                                <h4 id="transaksi" class="my-1"> {{ number_format($totalTransaksi, 0, ',', '.') }} </h4>
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
                                <h4 id="tonase" class="my-1 tonase">
                                    @if ($tonase->kg != 0)
                                        {{ number_format($tonase->kg, 0, ',', '.') }}
                                    @else
                                        0
                                    @endif
                                </h4>
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
                    <div class="card-body">
                        <label for="inputAddress" class="form-label">Kondisi</label>
                        <div class="col-12">
                            <select class="form-select" id="filter-kondisi" onchange="filter()">
                                <option value="">Pilih Kondisi</option>
                                <option value="1">Order Masuk</option>
                                <option value="7">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="GET" action="{{ route('pengiriman', base64_encode(Auth::user()->id_cabang)) }}">
            <input type="hidden" name="cari_print" id="cari_print" value="{{ $cari_print }}">
        </form>

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
                        <h6 class="mb-2">Data Pengiriman Barang</h6>
                        <div class="col-12">
                            @foreach ($cab as $ca)
                                @if (Auth::user()->id_cabang == $ca->id_cabang)
                                    <p>Daftar data pengiriman dari cabang <b>{{ $ca->nama_kota }}
                                            ({{ $ca->kode_area }})
                                        </b>
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="dropdown ms-auto mb-2">
                        {{-- <a class="btn btn-success" href="/add/pengiriman/{{ base64_encode($id_cabang) }}">Tambah</a> --}}
                        <a class="btn btn-success" onclick="konfirmasi()">Tambah</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center" style="width:100%" id="pengiriman-dt">
                        <thead class="table-light">
                            <tr>
                                <th>No. Tracking</th>
                                <th>Tracking (Manual)</th>
                                <th>Nama Pengirim</th>
                                <th>Kota Penerima</th>
                                <th>Tanggal Masuk</th>
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
    <!-- Modal -->
    <div class="modal fade" id="detail-pengiriman" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body p-4">
                        <div class="card-title d-flex align-items-center">
                            <h5 class="mb-0 text-primary">Detail Surat Jalan</h5>
                        </div>
                        <hr>
                        <form id="form-addPengiriman" class="row g-3" action="" method="POST"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Np. Tracking / No. Resi</label>
                                <input id="no_resi" type="text" class="form-control" name="no_resi" readonly>
                                @error('no_resi')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Status Resi</label>
                                <input id="status" type="text" class="form-control" name="status" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Warehouse Tujuan</label>
                                <input type="text" id="warehouse_tujuan" class="form-control" readonly>
                                {{-- @endforeach --}}
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Tanggal</label>
                                <input id="tgl_masuk" type="text" class="form-control" name="tgl_masuk"
                                    value="" readonly>
                            </div>
                            <hr>
                            <div class="card-title d-flex align-items-center">
                                <h5 class="col-md-6 mb-0 text-primary">Detail Pengirim</h5>
                                <h5 class="col-md-6 mb-0 text-primary">Detail Penerima</h5>
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Nama Pengirim</label>
                                <input id="nama_pengirim" type="text"
                                    class="form-control @error('nama_pengirim') is-invalid @enderror" name="nama_pengirim"
                                    required autocomplete="email">
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Nama Penerima</label>
                                <input id="id_penerima" type="text"
                                    class="form-control @error('nama_kota') is-invalid @enderror" name="nama_penerima"
                                    required autocomplete="email">
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Kota Pengirim</label>
                                <input id="nama_kota" type="text"
                                    class="form-control @error('nama_kota') is-invalid @enderror" name="nama_kota"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Kota Penerima</label>
                                <input id="kota_penerima" type="text"
                                    class="form-control @error('kota_penerima') is-invalid @enderror" name="kota_penerima"
                                    required autocomplete="email">
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Alamat Pengirim</label>
                                <textarea class="form-control" name="alamat_pengirim" id="alamat_pengirim" placeholder="Alamat Pengirim..."
                                    rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Alamat Penerima</label>
                                <textarea class="form-control" name="alamat_penerima" id="alamat_penerima" placeholder="Alamat Penerima..."
                                    rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">No. Telephone</label>
                                <input id="tlp_pengirim" type="text"
                                    class="form-control @error('tlp_pengirim') is-invalid @enderror" name="tlp_pengirim"
                                    value="{{ old('tlp_pengirim') }}" required>

                                @error('tlp_pengirim')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">No. Telephone</label>
                                <input id="email" type="text"
                                    class="form-control @error('no_penerima') is-invalid @enderror" name="no_penerima"
                                    required>
                                @error('no_penerima')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <hr>
                            <div class="card-title d-flex align-items-center">
                                <h5 class="col-md-6 mb-0 text-primary">Detail Barang</h5>

                            </div>
                            <hr>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Isi Barang</label>
                                <input id="email" type="text" class="form-control" name="isi_barang" required>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Jumlah Koli</label>
                                <input id="email" type="number"
                                    class="form-control @error('nama_kota') is-invalid @enderror" name="koli" required
                                    autocomplete="email">
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Asli</label>
                                <div class="input-group">
                                    <input id="berat_kg" type="number" class="form-control" name="berat_kg"><span
                                        class="input-group-text">Kg</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Volume (Opsional)</label>
                                <div class="input-group">
                                    <input id="berat_m" type="text"
                                        class="form-control @error('nama_kota') is-invalid @enderror" name="berat_m"><span
                                        class="input-group-text">M3</span>
                                </div>
                            </div>
                            <hr>
                            <div class="card-title d-flex align-items-center">
                                <h5 class="col-md-6 mb-0 text-primary">Detail Pengiriman</h5>
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Pelayanan</label>
                                <input id="nama_pelayanan" type="text"
                                    class="form-control @error('nama_pelayanan') is-invalid @enderror"
                                    name="nama_pelayanan">
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Moda</label>
                                <input id="no_moda" type="text"
                                    class="form-control @error('no_moda') is-invalid @enderror" name="no_moda">
                            </div>
                            <div class="col-12">
                                <label for="inputAddress" class="form-label">Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan..." rows="3">{{ old('keterangan') }}</textarea>
                                @if ($errors->has('keterangan'))
                                    <span class="text-danger">{{ $errors->first('keterangan') }}</span>
                                @endif
                            </div>
                            <hr>
                            <div class="card-title d-flex align-items-center">

                                <h5 class="col-md-6 mb-0 text-primary">Detail Pembayaran</h5>
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Bea</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">Rp</span><input id="bea"
                                        type="text" class="form-control" name="bea" required
                                        placeholder="Isi 0 jika kosong" value="{{ old('bea') }}">
                                </div>
                                @if ($errors->has('bea'))
                                    <span class="text-danger">{{ $errors->first('bea') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Bea Penerus</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">Rp</span><input id="bea_penerus"
                                        type="text" class="form-control" name="bea_penerus" required placeholder="0"
                                        value="{{ old('bea_penerus') }}">
                                </div>
                                @if ($errors->has('bea_penerus'))
                                    <span class="text-danger">{{ $errors->first('bea_penerus') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Bea Packing</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">Rp</span><input id="bea_packing"
                                        type="text" class="form-control" name="bea_packing" required placeholder="0"
                                        value="{{ old('bea_packing') }}" />
                                </div>
                                @if ($errors->has('bea_packing'))
                                    <span class="text-danger">{{ $errors->first('bea_packing') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Asuransi</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">Rp</span><input id="asuransi"
                                        type="text" class="form-control" name="asuransi" required placeholder="0"
                                        value="{{ old('asuransi') }}" />
                                </div>
                                @if ($errors->has('asuransi'))
                                    <span class="text-danger">{{ $errors->first('asuransi') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Total Pembayaran</label>
                                <div class="input-group flex-nowrap">
                                    <span class="input-group-text" id="addon-wrapping">Rp</span><input id="ttl_biaya"
                                        type="text" class="form-control" name="ttl_biaya" required
                                        value="{{ old('ttl_biaya') }}" readonly placeholder="Klik untuk menghitung"
                                        onblur="calculate()">
                                </div>
                                @if ($errors->has('ttl_biaya'))
                                    <span class="text-danger">{{ $errors->first('ttl_biaya') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Mode Pembayaran</label>
                                <input id="tipe_pembayaran" type="text"
                                    class="form-control @error('tipe_pembayaran') is-invalid @enderror"
                                    name="tipe_pembayaran">
                                @if ($errors->has('tipe_pembayaran'))
                                    <span class="text-danger">{{ $errors->first('tipe_pembayaran') }}</span>
                                @endif
                            </div>

                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-status" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Data Pengiriman Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="reset()"></button>
                </div>
                <form id="form-batal-trans" class="row g-3" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body" id="show-text"></div>
                    <input type="hidden" name="id_pengiriman" id="id_pengiriman" />
                    <input type="hidden" name="status_sent" id="status_sent" />
                    <div class="modal-footer" id="tombolnya">
                        <button type="reset" id="treset" class="btn btn-danger"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Lanjut!</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-konfirmasi" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Masukkan Kode Anda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="reset_konfirmasi()"></button>
                </div>
                <form id="form-konfirmasi" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="modal-body" id="show-text">
                        <input type="hidden" name="id_sales" value="{{ Auth::user()->id }}">
                        <input type="password" class="form-control form-control-sm" name="kode" id="kode"
                            required />
                    </div>
                    <div class="modal-footer" id="tombolnya">
                        <button type="reset" id="treset" class="btn btn-danger"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Next</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        let list_pengiriman = [];

        const table = $("#pengiriman-dt").DataTable({
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
                url: "{{ url('/pengiriman/' . base64_encode($id_cabang)) }}",
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
                    "data": "no_resi",
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        list_pengiriman[row.id_pengiriman] = row;
                        return data;
                    }
                }, {
                    "targets": 1,
                    "sortable": false,
                    "data": "no_resi_manual",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 2,
                    "sortable": false,
                    "data": "nama_pengirim",
                    "render": function(data, type, row, meta) {
                        return data;
                        // console.log(recordsTotal)
                    }
                }, {
                    "targets": 3,
                    "sortable": false,
                    "data": "kota_penerima",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                }, {
                    "targets": 4,
                    "sortable": false,
                    "data": "tgl_masuk",
                    "render": function(data, type, row, meta) {
                        return data;
                    }
                },
                {
                    "targets": 5,
                    "sortable": false,
                    "data": "status_sent",
                    "render": function(data, type, row, meta) {
                        return `<div ` + row.class + `>` +
                            row.nama_status + `</div>`;
                    }
                }, {
                    "targets": 6,
                    "sortable": false,
                    "data": "no_resi",
                    "render": function(data, type, row, meta) {
                        const url_print1 = "/pengiriman/print/" + btoa(row.id_pengiriman)
                        const url_print2 = "/identitas/print/" + btoa(row.id_pengiriman)
                        return `<div class="d-flex order-actions">
                            <a class="ms-3" data-bs-toggle="modal" href="" data-bs-target="#modal-status" onclick="toggleStatus('${row.id_pengiriman}')"><i class="fadeIn animated bx bx-reset"></i></a>
                            <a href="` + url_print1 + `" id="btnprn" target="_blank" class="ms-3"><i class="lni lni-printer"></i></a>
                            <a href="` + url_print2 + `" id="btnprn" target="_blank" class="ms-3"><i class="lni lni-archive"></i></a>
                            <a class="ms-3" data-bs-toggle="modal" href="" data-bs-target="#detail-pengiriman" onclick="detail('${row.id_pengiriman}')"><i class='lni lni-eye'></i></a>
                        </div>`;
                    }
                },
            ]
        });

        let cari = '';
        if ($("#cari_print").val() != cari) {
            cari = $("#cari_print").val();
            $('.dataTables_filter input').val(cari);
            table.search(cari).draw();
            // console.log('ada');
        }

        function toggleStatus(id_pengiriman) {
            let pengiriman = list_pengiriman[id_pengiriman]
            $('#modal-status').modal('show')
            $("#modal-status [name='id_pengiriman']").val(id_pengiriman)
            $('#modal-status #show-text').html(`Apakah Anda yakin mengubah status pengiriman ` + pengiriman.no_resi + `?`);
            let status_update = ''
            if (pengiriman.status_sent == 1) {
                status_update = 7
            }
            $("#modal-status [name='status_sent']").val(status_update)
        }

        $('#form-batal-trans').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            status()
        });

        function status() {
            let form = $('#form-batal-trans');

            const url = "{{ url('') }}/listpengiriman/update_status";
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(res) {
                    if (res === true) {
                        $('#modal-status').modal('hide')
                        table.ajax.reload(null, false)
                        success_noti()
                    }
                },
                error: function(e) {
                    $('#modal-status').modal('hide')
                    error_noti()
                }
            })
        }

        function fill_show(dari_tanggal, sampai_tanggal, filter_kondisi) {
            // console.log(dari_tanggal, sampai_tanggal, filter_kondisi)
            $.ajax({
                url: "{{ url('') }}/show_fill/{{ $id_cabang }}",
                method: 'POST',
                data: {
                    tgl_dari: dari_tanggal,
                    tgl_sampai: sampai_tanggal,
                    status: filter_kondisi,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    // let num = res[1].jumlah;
                    if (res[1].jumlah != null || res[2].kg != null) {
                        $('#transaksi').text(formatRupiah(res[0]));
                        //Tonase
                        $('#tonase').addClass('tonase2').text(formatRupiah(res[2].kg));
                        $(this).addClass('tonase2').removeClass('tonase');
                        //Total Omset
                        var n = res[1].jumlah;
                        $('#omsetheading').addClass('omsetheading2').text('Rp ' + formatRupiah(nilai));
                        $(this).addClass('omsetheading2').removeClass('omsetheading');
                        $('#p_omset').addClass('p_omset2').text(res[3] + ' s.d. ' + res[4]);
                        $(this).addClass('p_omset2').removeClass('p_omset');
                    } else {
                        $('#transaksi').text(formatRupiah(res[0]));
                        //Tonase
                        $('#tonase').addClass('tonase2').text('0');
                        $(this).addClass('tonase2').removeClass('tonase');
                        //Total Omset
                        $('#omsetheading').addClass('omsetheading2').text('Rp 0');
                        $(this).addClass('omsetheading2').removeClass('omsetheading');
                        $('#p_omset').addClass('p_omset2').text(res[3] + ' s.d. ' + res[4]);
                        $(this).addClass('p_omset2').removeClass('p_omset');
                    }
                    // console.log(res[2])

                },
                error: function(e) {
                    console.log(e)

                }
            })
        }

        function fill_show_kondisi(filter_kondisi) {
            // console.log(dari_tanggal, sampai_tanggal, filter_kondisi)
            $.ajax({
                url: "{{ url('') }}/show_fill_kondisi/{{ $id_cabang }}",
                method: 'POST',
                data: {
                    status: filter_kondisi,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    // console.log(res[3])
                    $('#transaksi').text(formatRupiah(res[0]));
                    //Tonase
                    if (res[3].kg != null || res[4].jumlah != null) {
                        var n = res[4].jumlah;
                        //Total Omset
                        $('#omsetheading').addClass('omsetheading2').text('Rp' + formatRupiah(n));
                        $(this).addClass('omsetheading2').removeClass('omsetheading');
                        $('#p_omset').addClass('p_omset2').text(res[1].tgl_masuk + ' s.d. ' + res[2].tgl_masuk);
                        $(this).addClass('p_omset2').removeClass('p_omset');
                        $('#tonase').addClass('tonase2').text(formatRupiah(res[3].kg));
                        $(this).addClass('tonase2').removeClass('tonase');
                    } else {
                        $('#tonase').addClass('tonase2').text('0');
                        $(this).addClass('tonase2').removeClass('tonase');
                        $('#omsetheading').addClass('omsetheading2').text('Rp0');
                    }

                },
                error: function(e) {
                    console.log(e)

                }
            })
        }

        function fill_show_all(dari_tanggal, sampai_tanggal, filter_kondisi) {
            // console.log(dari_tanggal, sampai_tanggal, filter_kondisi)
            $.ajax({
                url: "{{ url('') }}/show_fill_all/{{ $id_cabang }}",
                method: 'POST',
                data: {
                    tgl_dari: dari_tanggal,
                    tgl_sampai: sampai_tanggal,
                    status: filter_kondisi,
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
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

        function filter() {
            table.ajax.reload(null, false)
            // console.log(recordsTotal)
            var dari_tanggal = $('#dari_tanggal').val();
            var sampai_tanggal = $('#sampai_tanggal').val();
            var filter_kondisi = $('#filter-kondisi').val();
            // var rowCount = $("#pengiriman-dt > tbody").find('tr').length;
            // console.log(filter_kondisi);
            if (dari_tanggal != '' && sampai_tanggal != '' && filter_kondisi != '') {
                fill_show_all(dari_tanggal, sampai_tanggal, filter_kondisi);
            } else if (filter_kondisi != '') {
                fill_show_kondisi(filter_kondisi);
                // alert('aa')
            } else if (dari_tanggal != '' || sampai_tanggal != '') {
                fill_show(dari_tanggal, sampai_tanggal, filter_kondisi);
            } else {
                fill_tanpa_filter();

            }

        }

        function detail(id_pengiriman) {
            const pengiriman = list_pengiriman[id_pengiriman]
            // console.log(pengiriman)
            $('#detail-pengiriman').modal('show')
            $("#detail-pengiriman [name='no_resi']").val(pengiriman.no_resi)
            $("#detail-pengiriman [name='no_resi_manual']").val(pengiriman.no_resi_manual)
            $("#detail-pengiriman [name='tgl_masuk']").val(pengiriman.tgl_masuk)
            $("#detail-pengiriman [name='status']").val(pengiriman.nama_status)
            $("#detail-pengiriman #warehouse_tujuan").val(pengiriman.nama_kota)
            $("#detail-pengiriman [name='nama_pengirim']").val(pengiriman.nama_pengirim)
            $("#detail-pengiriman [name='nama_penerima']").val(pengiriman.nama_penerima)
            $("#detail-pengiriman [name='nama_kota']").val(pengiriman.nama_kota)
            $("#detail-pengiriman [name='kota_penerima']").val(pengiriman.kota_penerima)
            $("#detail-pengiriman [name='alamat_penerima']").val(pengiriman.alamat_penerima)
            $("#detail-pengiriman [name='alamat_pengirim']").val(pengiriman.alamat_pengirim)
            $("#detail-pengiriman [name='tlp_pengirim']").val(pengiriman.tlp_pengirim)
            $("#detail-pengiriman [name='no_penerima']").val(pengiriman.no_penerima)
            $("#detail-pengiriman [name='isi_barang']").val(pengiriman.isi_barang)
            $("#detail-pengiriman [name='koli']").val(formatRupiah(pengiriman.koli))
            $("#detail-pengiriman [name='berat_kg']").val(formatRupiah(pengiriman.berat))
            $("#detail-pengiriman [name='berat_m']").val(formatRupiah(pengiriman.volume))
            $("#detail-pengiriman [name='nama_pelayanan']").val(pengiriman.nama_pelayanan)
            $("#detail-pengiriman [name='no_moda']").val(pengiriman.no_moda)
            $("#detail-pengiriman [name='keterangan']").val(pengiriman.keterangan)

            $("#detail-pengiriman [name='bea']").val(formatRupiah(pengiriman.bea))
            $("#detail-pengiriman [name='bea_penerus']").val(formatRupiah(pengiriman.bea_penerus))
            $("#detail-pengiriman [name='bea_packing']").val(formatRupiah(pengiriman.bea_packing))
            $("#detail-pengiriman [name='asuransi']").val(formatRupiah(pengiriman.asuransi))
            $("#detail-pengiriman [name='ttl_biaya']").val(formatRupiah(pengiriman.biaya))
            $("#detail-pengiriman [name='tipe_pembayaran']").val(pengiriman.nama_tipe_pemb)
        }

        function cetakPDF(id_pengiriman) {
            const pengiriman = list_pengiriman[id_pengiriman]
            console.log(pengiriman)
            $('#modal-detail').modal('show')
        }

        $(document).ready(function() {
            $('#btnprn').printPage();
        });

        function konfirmasi() {
            $('#modal-konfirmasi').modal('show')
        }

        function reset_konfirmasi() {
            $("#form-konfirmasi #kode").val('')
        }

        $("#form-konfirmasi").on('submit', function(event) {
            event.preventDefault()
            submit_konfirmasi()
        })

        function submit_konfirmasi() {
            let form = $("#form-konfirmasi")
            const url = "{{ route('konfirmasi.member') }}"
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(res) {
                    if (res != null) {
                        const get_url = "/add/pengiriman/" + btoa(<?= $id_cabang ?>) + "?unique=" + res
                        window.location.href = get_url
                    } else {
                        $("#modal-konfirmasi").modal('hide')
                        error_noti()
                    }
                    reset_konfirmasi()
                },
                error: function(e) {
                    $("#modal-konfirmasi").modal('hide')
                    error_noti()
                    reset_konfirmasi()
                }
            })
        }
    </script>
@stop
