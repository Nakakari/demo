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
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
        rel="stylesheet" />
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
                    <li class="breadcrumb-item"><a href="/invoice"><i class="fadeIn animated bx bx-archive"></i></a>
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
                            <p class="mb-0 text-secondary">Belum Jadi Invoice</p>
                            <h4 id="transaksi" class="my-1">555</h4>
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
                            <p class="mb-0 text-secondary">Belum Tertagih</p>
                            <h4 id="tonase" class="my-1 tonase">555</h4>
                            <h4 class="my-1 tonase2"></h4>
                            <p class="mb-0 font-13 text-danger"><strong>{{ $getPerusahaan->nama_perusahaan }}</strong></p>
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
                    <h6 class="mb-2">Invoice Pelanggan({{ $getPerusahaan->nama_perusahaan }})</h6>
                </div>
                <div class="dropdown ms-auto mb-2">
                    <button id="proses-data" class="btn btn-warning" onclick="prosesData({{ $id_pelanggan }})"
                        disabled>Proses
                        Data</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center mb-0" style="width:100%" id="penjualan-dt">
                    <thead class="table-light">
                        <tr>
                            <th colspan="15" style="text-align:Left" class="table-light">
                                Nama Perusahaan: {{ $getPerusahaan->nama_perusahaan }}</th>
                        </tr>
                        <tr>
                            <th rowspan="2"><input type="checkbox" class="form-check-input" id="head-cb"></th>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Tanggal</th>
                            <th rowspan="2">Resi</th>
                            <th rowspan="2">Resi (Manual)</th>
                            <th rowspan="2">Pengirim</th>
                            <th rowspan="2">Penerima</th>
                            <th rowspan="2">Kota Penerima</th>
                            <th rowspan="2">Kilo</th>
                            <th rowspan="2">Koli</th>
                            <th colspan="3">Pembayaran</th>
                            <th rowspan="2">Surat Kembali</th>
                            <th rowspan="2">Keterangan</th>
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
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th rowspan="2"></th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th colspan="3" class="sum"></th>
                        </tr>
                        <tr>
                            <th colspan="15">
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
    @include('Admin.Invoice.v_scriptShoPenjualan')
@stop
