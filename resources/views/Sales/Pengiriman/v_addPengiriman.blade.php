@extends('layouts.main')

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
                        <li class="breadcrumb-item"><a href="/pengiriman/{{ base64_encode(Auth::user()->id_cabang) }}"><i
                                    class="bx bx-cart"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Pengiriman</li>
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
                                <h6 class="mb-0 text-danger">Wahh :(</h6>
                                <div>Mohon perhatikan lagi kelengkapan data!</div>
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
                        <form id="form-addPengiriman" class="row g-3" action="/upload/pengiriman/{{ $id_cabang }}"
                            method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Np. Tracking / No. Resi</label>
                                <input id="no_resi" type="text" class="form-control" name="no_resi"
                                    value="{{ $nomor }}" readonly>
                                <input id="dari_cabang" type="hidden" class="form-control" name="dari_cabang"
                                    value="{{ $id_cabang }}">
                                <input id="status_sent" type="hidden" class="form-control" name="status_sent"
                                    value="{{ $status_sent }}">
                                <input type="hidden" name="uuid" value={{ $_GET['unique'] }}>

                                @if ($errors->has('no_resi'))
                                    <span class="text-danger">{{ $errors->first('no_resi') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Np. Tracking / No. Resi Manual
                                    (Opsional)</label>
                                <input id="no_resi_manual" type="text" class="form-control" name="no_resi_manual"
                                    value="{{ old('no_resi_manual') }}">
                                @if ($errors->has('no_resi_manual'))
                                    <span class="text-danger">{{ $errors->first('no_resi_manual') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Warehouse Tujuan</label>
                                <select name="id_cabang_tujuan" id="id_cabang_tujuan" class="form-select" required>
                                    <option disabled selected>--- Pilih Cabang Tujuan ---</option>
                                    @foreach ($cab as $c)
                                        <option value="{{ $c->id_cabang }}"
                                            {{ old('id_cabang_tujuan') == $c->id_cabang ? 'selected' : '' }}>
                                            {{ $c->nama_kota }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('id_cabang_tujuan'))
                                    <span class="text-danger">{{ $errors->first('id_cabang_tujuan') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Tanggal</label>
                                <input id="tgl_masuk" type="text" class="form-control" name="tgl_masuk"
                                    value="{{ $today }}" readonly>
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
                                    required autocomplete="email" value="{{ old('nama_pengirim') }}">
                                @if ($errors->has('nama_pengirim'))
                                    <span class="text-danger">{{ $errors->first('nama_pengirim') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Nama Penerima</label>
                                <input id="id_penerima" type="text"
                                    class="form-control @error('nama_kota') is-invalid @enderror" name="nama_penerima"
                                    required autocomplete="email" value="{{ old('nama_penerima') }}">
                                @if ($errors->has('nama_penerima'))
                                    <span class="text-danger">{{ $errors->first('nama_penerima') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Kota Pengirim</label>
                                @foreach ($cab as $ca)
                                    @if (Auth::user()->id_cabang == $ca->id_cabang)
                                        <input id="kota_pengirim" type="text"
                                            class="form-control @error('nama_pengirim') is-invalid @enderror"
                                            name="kota_pengirim" value="{{ $ca->nama_kota }}" readonly>
                                    @endif
                                @endforeach
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Kota Penerima</label>
                                <input id="kota_penerima" type="text"
                                    class="form-control @error('kota_penerima') is-invalid @enderror" name="kota_penerima"
                                    required value="{{ old('kota_penerima') }}">
                                @if ($errors->has('kota_penerima'))
                                    <span class="text-danger">{{ $errors->first('kota_penerima') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Alamat Pengirim</label>
                                <textarea class="form-control" name="alamat_pengirim" id="alamat_pengirim" placeholder="Alamat Pengirim..."
                                    rows="3">{{ old('alamat_pengirim') }}</textarea>
                                @if ($errors->has('alamat_pengirim'))
                                    <span class="text-danger">{{ $errors->first('alamat_pengirim') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Alamat Penerima</label>
                                <textarea class="form-control" name="alamat_penerima" id="alamat_penerima" placeholder="Alamat Penerima..."
                                    rows="3">{{ old('alamat_penerima') }}</textarea>
                                @if ($errors->has('alamat_penerima'))
                                    <span class="text-danger">{{ $errors->first('alamat_penerima') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">No. Telephone</label>
                                <input id="tlp_pengirim" type="text" class="form-control" name="tlp_pengirim"
                                    value="{{ old('tlp_pengirim') }}" required>

                                @if ($errors->has('tlp_pengirim'))
                                    <span class="text-danger">{{ $errors->first('tlp_pengirim') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">No. Telephone</label>
                                <input id="email" type="text" class="form-control" name="no_penerima" required
                                    value="{{ old('no_penerima') }}">
                                @if ($errors->has('no_penerima'))
                                    <span class="text-danger">{{ $errors->first('no_penerima') }}</span>
                                @endif
                            </div>
                            <hr>
                            <div class="card-title d-flex align-items-center">
                                <h5 class="col-md-6 mb-0 text-primary">Detail Barang</h5>
                            </div>
                            <hr>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Isi Barang</label>
                                <input id="email" type="text" class="form-control" name="isi_barang"
                                    value="{{ old('isi_barang') }}" required>
                                @if ($errors->has('isi_barang'))
                                    <span class="text-danger">{{ $errors->first('isi_barang') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Jumlah Koli</label>
                                <input id="email" type="input"
                                    class="form-control @error('nama_kota') is-invalid @enderror" name="koli" required
                                    value="{{ old('koli') ?? 0 }}" min="0"
                                    onkeyup="this.value = formatCurrency(this.value);" />
                                @if ($errors->has('koli'))
                                    <span class="text-danger">{{ $errors->first('koli') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Asli</label>
                                <div class="input-group">
                                    <input id="berat_kg" type="input" class="form-control" name="berat_kg"
                                        min="0" value="{{ old('berat_kg') ?? 0 }}"
                                        onkeyup="this.value = formatCurrency(this.value);" /><span
                                        class="input-group-text">Kg</span>
                                </div>
                                @if ($errors->has('berat_kg'))
                                    <span class="text-danger">{{ $errors->first('berat_kg') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Volume (Opsional)</label>
                                <div class="input-group">
                                    <input id="berat_m" type="input" class="form-control" name="berat_m"
                                        min="0" value="{{ old('berat_m') ?? 0 }}"
                                        onkeyup="this.value = formatCurrency(this.value);" /><span
                                        class="input-group-text">M3</span>
                                </div>
                                @if ($errors->has('berat_m'))
                                    <span class="text-danger">{{ $errors->first('berat_m') }}</span>
                                @endif
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
                                            {{ old('no_pelayanan') == $ply->id_pelayanan ? 'selected' : '' }}>
                                            {{ strtoupper($ply->nama_pelayanan) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('no_pelayanan'))
                                    <span class="text-danger">{{ $errors->first('no_pelayanan') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="inputAddress" class="form-label">Moda</label>
                                <input id="no_moda" type="text" class="form-control" name="no_moda" required
                                    value="{{ old('no_moda') }}">
                                @if ($errors->has('no_moda'))
                                    <span class="text-danger">{{ $errors->first('no_moda') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
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
                                        type="text" class="form-control" min="0" name="bea" required
                                        placeholder="Isi 0 jika kosong" value="{{ old('bea') ?? 0 }}"
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
                                        type="text" class="form-control" min="0" name="bea_penerus" required
                                        placeholder="Isi 0 jika kosong" value="{{ old('bea_penerus') ?? 0 }}"
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
                                        type="text" class="form-control" min="0" name="bea_packing" required
                                        placeholder="Isi 0 jika kosong" value="{{ old('bea_packing') ?? 0 }}"
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
                                        type="text" class="form-control" min="0" name="asuransi" required
                                        placeholder="0" value="{{ old('asuransi') ?? 0 }}"
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
                                        type="text" class="form-control" name="ttl_biaya" required
                                        value="{{ old('ttl_biaya') ?? 0 }}" readonly />
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
                                            {{ old('tipe_pembayaran') == $pb->id_pembayaran ? 'selected' : '' }}>
                                            {{ strtoupper($pb->nama_tipe_pemb) }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('tipe_pembayaran'))
                                    <span class="text-danger">{{ $errors->first('tipe_pembayaran') }}</span>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary button-prevent">Simpan</button>
                                <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p>Whoops!</p>
    @endif
@stop
@section('js')
    <script type="text/javascript">
        document.getElementById('ttl_biaya').value = 0
        const textarea = document.getElementById('id_pelanggan');

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#nama_kota').on('change', function() {
                $.ajax({
                    url: '{{ url('kodeareapengguna') }}',
                    method: 'POST',
                    data: {
                        id_cabang: $(this).val()
                    },
                    success: function(response) {
                        $('#id_cabang').empty();

                        $.each(response, function(id_cabang, kode_area) {
                            // console.log(kode_area)
                            $('#id_cabang').append(new Option(kode_area, kode_area))
                        })
                    }
                })
            });
        });

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#nama_perusahaan').on('change', function() {
                $.ajax({
                    url: '{{ url('kodepelanggan') }}',
                    method: 'POST',
                    data: {
                        id_pelanggan: $(this).val()
                    },
                    success: function(response) {
                        $('#id_pelanggan').empty();
                        $("#form-addPengiriman textarea").val('')
                        $("#form-addPengiriman [name='tlp_spv']").val('')

                        $.each(response, function(tlp_spv, alamat_penerima) {
                            textarea.value = alamat_penerima;
                            $("#form-addPengiriman [name='tlp_spv']").val(tlp_spv)
                        })
                    }
                })
            });
        });

        function hitungPembayaran() {
            let url = '{{ route('pengiriman.hitung-pembayaran') }}';
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
        $('#form-addPengiriman').on('submit', function(event) {
            $('#form-addPengiriman .button-prevent').attr('disabled', 'true');
        });
    </script>
@stop
