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
                    <li class="breadcrumb-item"><a href="/detail_piutang/{{ base64_encode($id_pelanggan) }}">Invoice
                            Pelanggan</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Invoice</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="card radius-10">
        <div class="card-body">
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <h5 class="mb-0 text-primary">Proses
                            {{ isset($_GET['sum']) ? base64_decode($_GET['sum']) : count($detailInvoiceList) }} Data
                            {{ $getPerusahaan->nama_perusahaan }}
                            menjadi Invoice</h5>
                        <div class="dropdown ms-auto mb-2">
                            <a id="proses-data" class="btn btn-warning"
                                href="{{ route('reselect.invoice', base64_encode($id_invoice)) }}">Pilih Ulang Resi</a>
                        </div>
                    </div>

                    <hr>
                    <form id="form-addPengiriman" class="row g-3" action="/update_invoice/{{ $id_invoice }}"
                        method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-4">
                            <label for="inputAddress" class="form-label">No. Invoice</label>
                            <input id="id_pengiriman" type="hidden" class="form-control" name="id_pengiriman"
                                value="{{ isset($_GET['id']) ? base64_decode($_GET['id']) : implode(', ', $detailInvoice) }}" />
                            <input id="no_invoice" type="text" class="form-control" name="no_invoice"
                                value="{{ old('no_invoice') ?? $invoice->no_invoice }}" />
                            <input type="hidden" name="id_pelanggan" value="{{ base64_encode($id_pelanggan) }}">
                            @if ($errors->has('no_resi'))
                                <span class="text-danger">{{ $errors->first('no_resi') }}</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="inputAddress" class="form-label">Akun Bank</label>
                            <select name="id_bank" id="id_bank" class="form-select" required>
                                <option disabled selected>--- Pilih Bank ---</option>
                                @foreach ($bank as $b)
                                    <option value="{{ $b->id_bank }}"
                                        {{ old('id_bank', $b->id_bank) == $invoice->id_bank ? 'selected' : '' }}>
                                        {{ $b->nama_bank }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('id_bank'))
                                <span class="text-danger">{{ $errors->first('id_bank') }}</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <label for="inputAddress" class="form-label">Jatuh Tempo</label>
                            <input id="jatuh_tempo" type="date" class="form-control" name="jatuh_tempo"
                                value="{{ old('jatuh_tempo') ?? $invoice->jatuh_tempo }}" />
                            @if ($errors->has('jatuh_tempo'))
                                <span class="text-danger">{{ $errors->first('jatuh_tempo') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">Pembuat</label>
                            <input id="pembuat" type="text" class="form-control" name="pembuat"
                                value="{{ old('pembuat') ?? $invoice->pembuat }}" />
                            @if ($errors->has('pembuat'))
                                <span class="text-danger">{{ $errors->first('pembuat') }}</span>
                            @endif
                        </div>
                        {{-- <div class="col-md-4">
                            <label for="inputAddress" class="form-label">Mengetahui</label>
                            <input id="mengetahui" type="text" class="form-control" name="mengetahui"
                                value="{{ old('mengetahui') ?? $invoice->mengetahui }}" />
                            @if ($errors->has('mengetahui'))
                                <span class="text-danger">{{ $errors->first('mengetahui') }}</span>
                            @endif
                        </div> --}}
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">Diterbitkan</label>
                            <input id="diterbitkan" type="date" class="form-control" name="diterbitkan"
                                value="{{ old('diterbitkan') ?? $invoice->diterbitkan }}" />
                            @if ($errors->has('diterbitkan'))
                                <span class="text-danger">{{ $errors->first('diterbitkan') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div class="col-md-3" id="id_klaim">
                            <label for="inputAddress" class="form-label">Klaim</label>
                        </div>
                        <div class="col-md-3" id="nominal_klaim">
                            <div class="d-flex align-items-center">
                                <div>
                                    <label for="inputAddress" class="form-label">Nominal</label>
                                </div>
                                <div class="dropdown ms-auto">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3" id="input-bea">
                            <label for="inputAddress" class="form-label">Bea Tambahan</label>
                        </div>
                        <div class="col-md-3" id="nominal_bea">
                            <div class="d-flex align-items-center">
                                <div>
                                    <label for="inputAddress" class="form-label">Nominal</label>
                                </div>
                                <div class="dropdown ms-auto">
                                    <div class="font-22 text-primary">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-3">
                            <label for="inputAddress" class="form-label">PPn</label>
                            <div class="input-group flex-nowrap">
                                <input id="ppn" type="input" class="form-control" name="ppn"
                                    placeholder="0.01" step="0.01" min="0" max="100"
                                    value="{{ old('ppn') ?? $invoice->ppn }}" /><span class="input-group-text"
                                    id="addon-wrapping">%</span>
                            </div>
                            @if ($errors->has('no_resi'))
                                <span class="text-danger">{{ $errors->first('no_resi') }}</span>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <label for="inputAddress" class="form-label">Keterangan</label>
                            <textarea class="form-control" name="keterangan" id="keterangan" placeholder="Keterangan..." rows="1">{{ old('keterangan') ?? $invoice->keterangan }}</textarea>
                            @if ($errors->has('keterangan'))
                                <span class="text-danger">{{ $errors->first('keterangan') }}</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success ">Simpan</button>
                            <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        var parts = window.location.search.substr(1).split("&");
        var $_GET = {};
        for (var i = 0; i < parts.length; i++) {
            var temp = parts[i].split("=");
            $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
        }

        // alert(atob($_GET.id)); // 2
        var maxInputAllowed = <?= count($detailInvoiceList) ?> * 3;

        $(document).ready(function() {
            var x = 0; //initlal text box count
            var y = 0; //initlal text box count
            var nominal_klaim = $("#nominal_klaim"); //Fields wrapper
            var klaim = $("#id_klaim"); //Fields wrapper
            @foreach ($invoice->klaim as $index => $klaim)
                @if ($loop->first)
                    $(klaim).append(
                        `<div class="input-group mb-2" id="new-klaim` + {{ $index }} + `"><input placeholder="Keterangan" type="text" name="klaim[]" class="form-control" value="{{ old('klaim') ?? $klaim->klaim }}" required/>
                           </div>`
                    )
                    $(nominal_klaim).append(
                        `<div class="input-group mb-2 sould-remove"><input placeholder="Nominal Klaim" type="input" name="nominal_klaim[]" class="form-control" onkeyup="this.value = formatCurrency(this.value);" value="{{ old('nominal_klaim') ?? number_format($klaim->nominal_klaim, 0, ',', '.') }}" required/>
                            <div class="font-22 text-primary ps-2"> <i class="fadeIn animated bx bx-plus-circle" id="buttonKlaim"
                                        ></i>
                                </div></div>`
                    );
                @else
                    $(klaim).append(
                        `<div class="input-group mb-2" id="new-klaim` + {{ $index }} + `"><input placeholder="Keterangan" type="text" name="klaim[]" class="form-control" value="{{ old('klaim') ?? $klaim->klaim }}" required/>
                           </div>`
                    )
                    $(nominal_klaim).append(
                        `<div class="input-group mb-2 sould-remove"><input placeholder="Nominal Klaim" type="input" name="nominal_klaim[]" class="form-control" onkeyup="this.value = formatCurrency(this.value);" value="{{ old('nominal_klaim') ?? number_format($klaim->nominal_klaim, 0, ',', '.') }}" required/>
                            <div class="font-22 text-primary ps-2"> <i class="fadeIn animated bx bx-minus-circle remove_field"
                                        ></i>
                                </div></div>`
                    );
                @endif
                var x = {{ $index }}
            @endforeach

            var bea = $('#input-bea')
            var bea_nominal = $('#nominal_bea')
            @foreach ($invoice->beaTambahan as $index => $beaTambahan)
                @if ($loop->first)
                    $(bea).append(
                        `<div class="input-group mb-2" id="new-bea` + {{ $index }} + `"><input placeholder="Keterangan" type="text" name="bea_tambahan[]" class="form-control" value="{{ old('bea_tambahan') ?? $beaTambahan->bea_tambahan }}"  required/>
                           </div>`
                    )
                    $(bea_nominal).append(
                        `<div class="input-group mb-2 sould-remove"><input placeholder="Nominal Bea Tambahan" type="input" name="nominal_bea[]" class="form-control" onkeyup="this.value = formatCurrency(this.value);" value="{{ old('nominal_bea') ?? number_format($beaTambahan->nominal_bea, 0, ',', '.') }}" required/>
                            <div class="font-22 text-primary ps-2"> <i class="fadeIn animated bx bx bx-plus-circle" id="buttonBea"
                                        ></i>
                                </div></div>`
                    ); //add input box
                @else
                    $(bea).append(
                        `<div class="input-group mb-2" id="new-bea` + {{ $index }} + `"><input placeholder="Keterangan" type="text" name="bea_tambahan[]" class="form-control" value="{{ old('bea_tambahan') ?? $beaTambahan->bea_tambahan }}"  required/>
                           </div>`
                    )
                    $(bea_nominal).append(
                        `<div class="input-group mb-2 sould-remove"><input placeholder="Nominal Bea Tambahan" type="input" name="nominal_bea[]" class="form-control" onkeyup="this.value = formatCurrency(this.value);" value="{{ old('nominal_bea') ?? number_format($beaTambahan->nominal_bea, 0, ',', '.') }}" required/>
                            <div class="font-22 text-primary ps-2"> <i class="fadeIn animated bx bx-minus-circle remove_field"
                                        ></i>
                                </div></div>`
                    ); //add input box
                @endif
                var y = {{ $index }}
            @endforeach

            var max_fields = maxInputAllowed; //maximum input boxes allowed
            var max_fields_bea = maxInputAllowed; //maximum input boxes allowed

            var add_button = $("#buttonKlaim"); //Add button ID
            var button_bea = $("#buttonBea"); //Add button ID

            $(add_button).click(function(e) { //on add input button click
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(klaim).append(
                        `<div class="input-group mb-2" id="new-klaim` + x + `"><input placeholder="Keterangan" type="text" name="klaim[]" class="form-control" required/>
                           </div>`
                    )
                    $(nominal_klaim).append(
                        `<div class="input-group mb-2 sould-remove"><input placeholder="Nominal Klaim" type="input" name="nominal_klaim[]" class="form-control" onkeyup="this.value = formatCurrency(this.value);" required/>
                            <div class="font-22 text-primary ps-2"> <i class="fadeIn animated bx bx-minus-circle remove_field"
                                        ></i>
                                </div></div>`
                    ); //add input box
                } else {
                    warning_noti()
                }
            });

            $(nominal_klaim).on("click", ".remove_field", function(e) { //user click on remove text
                e.preventDefault();
                document.getElementById("new-klaim" + x).remove();
                $(this).parent('div').parent('div').remove();
                x--;
                // console.log(x)
            })

            $(button_bea).click(function(e) { //on add input button click
                e.preventDefault();
                if (y < max_fields_bea) { //max input box allowed
                    y++; //text box increment
                    $(bea).append(
                        `<div class="input-group mb-2" id="new-bea` + y + `"><input placeholder="Keterangan" type="text" name="bea_tambahan[]" class="form-control"  required/>
                           </div>`
                    )
                    $(bea_nominal).append(
                        `<div class="input-group mb-2 sould-remove"><input placeholder="Nominal Bea Tambahan" type="input" name="nominal_bea[]" class="form-control" onkeyup="this.value = formatCurrency(this.value);" required/>
                            <div class="font-22 text-primary ps-2"> <i class="fadeIn animated bx bx-minus-circle remove_field"
                                        ></i>
                                </div></div>`
                    ); //add input box
                } else {
                    warning_noti()
                }
            });

            $(bea_nominal).on("click", ".remove_field", function(e) { //user click on remove text
                e.preventDefault();
                document.getElementById("new-bea" + y).remove();
                $(this).parent('div').parent('div').remove();
                y--;
            })
        });
    </script>
@stop
