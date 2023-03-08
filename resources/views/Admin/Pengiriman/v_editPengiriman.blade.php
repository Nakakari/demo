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
                    <li class="breadcrumb-item"><a href="/pengiriman"><i class="fadeIn animated bx bx-radar"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Pengiriman</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->
    <div class="card radius-10">
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
            <div class="alert border-0 border-start border-5 border-warning alert-dismissible fade show py-2">
                <div class="d-flex align-items-center">
                    <div class="font-35 text-warning"><i class='bx bx-info-circle'></i>
                    </div>
                    <div class="ms-3">
                        <strong>{{ session('gagal') }}</strong>.
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
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-5">
                <div class="card-title d-flex align-items-center">
                    <h5 class="mb-0 text-primary">Detail Surat Jalan</h5>
                </div>
                <hr>
                <form id="form-addPengiriman" class="row g-3" action="/update/pengiriman/{{ $id_pengiriman }}"
                    method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="col-md-4">
                        <label for="inputAddress" class="form-label">Np. Tracking / No. Resi</label>
                        <input id="no_resi" type="text" class="form-control " name="no_resi"
                            value="{{ old('no_resi', $item->no_resi) }}" readonly>
                        @error('no_resi')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="inputAddress" class="form-label">Np. Tracking / No. Resi (Manual)</label>
                        <input id="no_resi_manual" type="text" class="form-control " name="no_resi_manual"
                            value="{{ old('no_resi_manual', $item->no_resi_manual) }}">
                        @error('no_resi')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="inputAddress" class="form-label">Dari Cabang</label>
                        <input type="hidden" name="dari_cabang" value="{{ $item->dari_cabang }}" />
                        <input type="text" value="{{ strtoupper($item->asal_cabang->nama_kota) }}" class="form-control "
                            disabled>
                    </div>
                    <div class="col-md-4">
                        <label for="inputAddress" class="form-label">Warehouse Tujuan</label>
                        <select name="id_cabang_tujuan_edit" id="id_cabang_tujuan_edit" class="form-select" required>
                            <option disabled>--- Pilih Cabang Tujuan ---</option>
                            @foreach ($cab as $c)
                                <option value="{{ $c->id_cabang }}"
                                    {{ old('id_cabang_tujuan', $item->id_cabang_tujuan) == $c->id_cabang ? 'selected' : '' }}>
                                    {{ $c->nama_kota }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="inputAddress" class="form-label">Status</label>
                        <select name="status_sent" id="status_sent" class="form-select" required>
                            <option disabled>--- Pilih Status ---</option>
                            @foreach ($status as $st)
                                <option value="{{ $st->id_stst_pngrmn }}"
                                    {{ old('status_sent', $item->status_sent) == $st->id_stst_pngrmn ? 'selected' : '' }}>
                                    {{ $st->nama_status }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-md-4">
                        <label for="inputAddress" class="form-label">Tanggal</label>
                        <input id="tgl_masuk" type="date" class="form-control " name="tgl_masuk"
                            value="{{ $item->tgl_masuk }}">
                    </div>
                    <hr>
                    <div class="card-title d-flex align-items-center">
                        <h5 class="col-md-6 mb-0 text-primary">Detail Pengirim</h5>
                        <h5 class="col-md-6 mb-0 text-primary">Detail Penerima</h5>
                    </div>
                    <hr>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Nama Pengirim</label>
                        <input id="nama_pengirim" type="text" class="form-control " name="nama_pengirim" required
                            value="{{ old('nama_pengirim', strtoupper($item->nama_pengirim)) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Nama Penerima</label>
                        <input id="id_penerima" type="text" class="form-control " name="nama_penerima" required
                            value="{{ old('nama_penerima', strtoupper($item->nama_penerima)) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Kota Pengirim</label>
                        <input id="kota_pengirim" type="text"
                            class="form-control  @error('kota_pengirim') is-invalid @enderror" name="kota_pengirim"
                            value="{{ old('kota_pengirim', strtoupper($item->kota_pengirim)) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Kota Penerima</label>
                        <input id="kota_penerima" type="text" class="form-control " name="kota_penerima" required
                            value="{{ old('kota_penerima', strtoupper($item->kota_penerima)) }}">
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Alamat Pengirim</label>
                        <textarea class="form-control " name="alamat_pengirim" id="alamat_pengirim" placeholder="Alamat Pengirim..."
                            rows="3">{{ old('alamat_pengirim', strtoupper($item->alamat_pengirim)) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Alamat Penerima</label>
                        <textarea class="form-control " name="alamat_penerima" id="alamat_penerima" placeholder="Alamat Penerima..."
                            rows="3">{{ old('alamat_penerima', strtoupper($item->alamat_penerima)) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">No. Telephone</label>
                        <input id="tlp_pengirim" type="text" class="form-control " name="tlp_pengirim"
                            value="{{ old('tlp_pengirim', $item->tlp_pengirim) }}" required>

                        @error('tlp_pengirim')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">No. Telephone</label>
                        <input id="email" type="text"
                            class="form-control  @error('no_penerima') is-invalid @enderror" name="no_penerima"
                            value="{{ old('no_penerima', $item->no_penerima) }}" required>
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
                        <input id="email" type="text" class="form-control " name="isi_barang"
                            value="{{ old('isi_barang', strtoupper($item->isi_barang)) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Jumlah Koli</label>
                        <input id="email" type="input" class="form-control  @error('koli') is-invalid @enderror"
                            name="koli" value="{{ old('koli', number_format($item->koli, 0, ',', '.')) }}" required
                            onkeyup="this.value = formatCurrency(this.value);" />
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Asli</label>
                        <div class="input-group">
                            <input id="berat_kg" type="input" class="form-control " name="berat_kg"
                                value="{{ old('berat_kg', number_format($item->berat, 0, ',', '.')) }}"
                                onkeyup="this.value = formatCurrency(this.value);"><span
                                class="input-group-text">Kg</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Volume (Opsional)</label>
                        <div class="input-group">
                            <input id="berat_m" type="input" class="form-control " name="berat_m"
                                value="{{ old('berat_m', number_format($item->volume, 0, ',', '.')) }}"
                                onkeyup="this.value = formatCurrency(this.value);"><span
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
                        <select name="no_pelayanan" id="no_pelayanan" class="form-select" required>
                            <option disabled selected>Pilih Jenis Pelayanan .....</option>
                            @foreach ($plyn as $ply)
                                <option value="{{ $ply->id_pelayanan }}"
                                    {{ old('no_pelayanan', $item->no_pelayanan) == $ply->id_pelayanan ? 'selected' : '' }}>
                                    {{ strtoupper($ply->nama_pelayanan) }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('no_pelayanan'))
                            <span class="text-danger">{{ $errors->first('no_pelayanan') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Moda</label>
                        <input id="no_moda" type="text" class="form-control " name="no_moda" required
                            value="{{ old('no_moda', strtoupper($item->no_moda)) }}">
                        @if ($errors->has('no_moda'))
                            <span class="text-danger">{{ $errors->first('no_moda') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">Keterangan</label>
                        <textarea class="form-control " name="keterangan" id="keterangan" placeholder="Keterangan..." rows="3">{{ old('keterangan', strtoupper($item->keterangan)) }}</textarea>
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
                                type="text" class="form-control " min="0" name="bea" required
                                value="{{ old('bea', number_format($item->bea, 0, ',', '.')) }}"
                                onkeyup="this.value = formatCurrency(this.value); hitungPembayaran()">
                        </div>
                        @if ($errors->has('bea'))
                            <span class="text-danger">{{ $errors->first('bea') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Bea Penerus</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">Rp</span><input id="bea_penerus"
                                type="text" class="form-control " min="0" name="bea_penerus" required
                                value="{{ old('bea_penerus', number_format($item->bea_penerus, 0, ',', '.')) }}"
                                onkeyup="this.value = formatCurrency(this.value); hitungPembayaran()">
                        </div>
                        @if ($errors->has('bea_penerus'))
                            <span class="text-danger">{{ $errors->first('bea_penerus') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Bea Packing</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">Rp</span><input id="bea_packing"
                                type="text" class="form-control " min="0" name="bea_packing" required
                                value="{{ old('bea_packing', number_format($item->bea_packing, 0, ',', '.')) }}"
                                onkeyup="this.value = formatCurrency(this.value); hitungPembayaran()" />
                        </div>
                        @if ($errors->has('bea_packing'))
                            <span class="text-danger">{{ $errors->first('bea_packing') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Asuransi</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">Rp</span><input id="asuransi"
                                type="text" class="form-control " min="0" name="asuransi" required
                                value="{{ old('asuransi', number_format($item->asuransi, 0, ',', '.')) }}"
                                onkeyup="this.value = formatCurrency(this.value); hitungPembayaran()" />
                        </div>
                        @if ($errors->has('asuransi'))
                            <span class="text-danger">{{ $errors->first('asuransi') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Total Pembayaran</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="addon-wrapping">Rp</span><input id="ttl_biaya"
                                type="text" class="form-control " min="0" name="ttl_biaya" required
                                value="{{ old('ttl_biaya', number_format($item->biaya, 0, ',', '.')) }}" readonly>
                        </div>
                        @if ($errors->has('ttl_biaya'))
                            <span class="text-danger">{{ $errors->first('ttl_biaya') }}</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="inputAddress" class="form-label">Mode Pembayaran</label>
                        <select name="tipe_pembayaran" id="tipe_pembayaran" class="form-select" required>
                            <option disabled selected>Pilih Pembayaran .....</option>
                            @foreach ($pembayaran as $pb)
                                <option value="{{ $pb->id_pembayaran }}"
                                    {{ old('tipe_pembayaran', $item->tipe_pembayaran) == $pb->id_pembayaran ? 'selected' : '' }}>
                                    {{ strtoupper($pb->nama_tipe_pemb) }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('tipe_pembayaran'))
                            <span class="text-danger">{{ $errors->first('tipe_pembayaran') }}</span>
                        @endif
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success ">Update</button>
                        <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('modal')
@stop
@section('js')
    <script type="text/javascript">
        function hitungPembayaran() {
            let url = '{{ route('admin.pengiriman.hitung-pembayaran') }}';
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    bea: $('#bea').val(),
                    bea_penerus: $("#bea_penerus").val(),
                    bea_packing: $("#bea_packing").val(),
                    asuransi: $("#asuransi").val(),
                    // asuransi: 0,
                }
            }).then(function(response) {
                $("#ttl_biaya").val(response.ttl_biaya)
            });
        }
    </script>
@stop
