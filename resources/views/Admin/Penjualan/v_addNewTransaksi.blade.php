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
                    <li class="breadcrumb-item"><a href="/penjualan_pelanggan"><i
                                class="fadeIn animated bx bx-archive"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a
                            href="/transaksi_pelanggan/{{ base64_encode($id_pelanggan) }}">Pelanggan</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Data</li>
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
                    <h6 class="mb-2">Penginputan Data untuk Transaksi Pelanggan</h6>
                </div>
                <div class="dropdown ms-auto mb-2">
                    <button type="button" class="btn btn-success" id="add_trans" onclick="addNewTrans()" disabled>Olah
                        Data</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center" style="width:100%" id="pengiriman-dt">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" class="form-check-input" id="head-cb"></th>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Resi</th>
                            <th>Pengirim</th>
                            <th>Penerima</th>
                            <th>Kota Penerima</th>
                            <th>Kilo</th>
                            <th>Koli</th>
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
    <div class="modal fade" id="modal-addNewTrans" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Transaksi Pelanggan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="reset()"></button>
                </div>
                <form id="form-newTrans" class="row g-3" enctype="multipart/form-data">
                    <input type="hidden" value="{{ $id_pelanggan }}" />
                    <div class="modal-body">Apakah Anda yakin untuk mengolah <span id="count_resi_oyy"></span> data ke dalam
                        list
                        transaksi
                        {{ $getPerusahaan->nama_perusahaan }}?</div>
                    <div class="modal-footer" id="tombolnya">
                        <button type="reset" id="treset" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script type="text/javascript">
        var id_pelanggan = <?= $id_pelanggan ?>;
        let list_pengiriman = [];

        const table = $("#pengiriman-dt").DataTable({
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, 'All Data']
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
                url: "{{ url('') }}/except_list_trans_pelanggan/{{ base64_encode($id_pelanggan) }}",
                type: "POST",
                data: function(d) {
                    d._token = "{{ csrf_token() }}"
                }
            },
            "columnDefs": [{
                'targets': '_all',
                visible: true
            }, {
                "targets": 0,
                "data": "id_pengiriman",
                "sortable": false,
                "render": function(data, type, row, meta) {
                    return `<input type="checkbox" class="cb-child form-check-input" value="${row.id_pengiriman}">`;
                }
            }, {
                "targets": 1,
                "data": "id_pengiriman",
                "render": function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            }, {
                "targets": 2,
                "data": "tgl_masuk",
                "render": function(data, type, row, meta) {
                    list_pengiriman[row.id_pengiriman] = row;
                    return data;
                }
            }, {
                "targets": 3,
                "data": "no_resi",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 4,
                "data": "nama_pengirim",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 5,
                "data": "nama_penerima",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 6,
                "data": "kota_penerima",
                "render": function(data, type, row, meta) {
                    return data;
                }
            }, {
                "targets": 7,
                "data": "kilo",
                "render": function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            }, {
                "targets": 8,
                "data": "koli",
                "render": function(data, type, row, meta) {
                    return formatRupiah(data);
                }
            }, ]
        });

        $('#head-cb').on('click', function() {
            var isChecked = $('#head-cb').prop('checked')
            $('.cb-child').prop('checked', isChecked)
            $("#add_trans").prop('disabled', !isChecked)
        });

        $('#pengiriman-dt tbody').on('click', '.cb-child', function() {
            if ($(this).prop('checked') != true) {
                $('#head-cb').prop('checked', false)
            }
            let all_checkbox = $('#pengiriman-dt tbody .cb-child:checked')
            let manifest_status = (all_checkbox.length > 0)
            $("#add_trans").prop('disabled', !manifest_status)
        });

        function addNewTrans() {
            $('#modal-addNewTrans').modal('show')
            let checkbox_selected = $('#pengiriman-dt tbody .cb-child:checked')
            let all_id_pengiriman = []
            $.each(checkbox_selected, function(index, elm) {
                all_id_pengiriman.push(elm.value)
            });
            $('#count_resi_oyy').text(all_id_pengiriman.length);

        }

        function reset() {
            var isChecked = false
            $('#head-cb').prop('checked', isChecked)
            $('.cb-child').prop('checked', isChecked)
            $("#add_trans").prop('disabled', !isChecked)
        }

        $('#form-newTrans').on('reset', function(event) {
            event.preventDefault() //jangan disubmit
            reset()
        });

        $('#form-newTrans').on('submit', function(event) {
            event.preventDefault() //jangan disubmit
            submitForm()
        });

        function submitForm() {
            let checkbox_selected = $('#pengiriman-dt tbody .cb-child:checked')
            let all_id_pengiriman = []
            $.each(checkbox_selected, function(index, elm) {
                all_id_pengiriman.push(elm.value)
            });

            const url = "{{ url('tambah_transaksi_pelanggan') }}";
            $.ajax({
                url,
                method: "POST",
                data: {
                    id: all_id_pengiriman,
                    id_pelanggan: id_pelanggan,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response === true) {
                        $('#modal-addNewTrans').modal('hide')
                        table.ajax.reload(null, false)
                        window.location.href = "/transaksi_pelanggan/" + btoa(id_pelanggan);
                    }
                },
                error: function(e) {
                    $('#modal-addNewTrans').modal('hide')
                    error_noti()
                }
            })
        }
    </script>
@stop
