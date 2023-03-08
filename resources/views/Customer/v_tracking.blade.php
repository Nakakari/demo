<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--plugins-->
    <link href="{{ asset('template') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ asset('template') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ asset('template') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('template') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('template') }}/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('template') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/css/icons.css" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('template') }}/assets/css/dark-theme.css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/css/semi-dark.css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/css/header-colors.css" />
    <style type="text/css">
        .hidden {
            display: none;
        }

        /* .myimgdivtoggle {
            display: none;
        } */
    </style>
    <title>{{ title() }}</title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--start page wrapper -->
        <div class="page-wrapper" style="margin-right: 10%">
            <div class="page-content">
                @if (session('search'))
                    <input type="hidden" value="{{ session('search') }}" id="search">
                @endif
                <!--end breadcrumb-->
                <div class="">
                    <div class="">
                        <div class="row mb-4">
                            <div class="col-lg-8 col-md-10 col-sm-10">
                                <form class="form-group" method="POST" enctype="multipart/form-data"
                                    id="form-tracking">
                                    {{ csrf_field() }}
                                    <h5>Nomor Resi</h5>
                                    <span class="mb-2 text-muted">Masukkan Nomor Resi lalu Tekan Enter</span>
                                    <input id="trackingNumber" type="text" class="form-control mt-2" name="search"
                                        placeholder="Masukkan 12 Digit Nomor Resi (Contoh: SUB-20157213)">
                                    <button type="submit" id="submit-tracking" hidden>Button</button>
                                </form>
                            </div>

                        </div>
                        <div class="container py-2 hidden" id="kosong">
                            <h2 class="font-weight-light text-center text-muted py-3">Data Tidak Ada :')</h2>
                        </div>
                        <div class="row hidden" id="keterangan-resi">
                            <div class="col-md-12">
                                <h6 id="keterangan-judul"></h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="font-weight-bold" id="kota_pengirim"></p>
                                        <p id="kota_penerima"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p id="nama-pengirim"></p>
                                        <p id="nama-penerima"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container py-2 hidden" id="tracking-resi">
                            <h2 class="font-weight-light text-center text-muted py-3">Tracking Resi</h2>
                            <!-- timeline item Odd -->
                            <div class="row g-0">
                                <div class="col-sm">
                                    <!--spacer-->
                                </div>
                                <!-- timeline item Odd center dot -->
                                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                                    <div class="row h-50">
                                        <div class="col">&nbsp;</div>
                                        <div class="col">&nbsp;</div>
                                    </div>
                                    <h5 class="m-2">
                                        <span class="badge rounded-pill bg-light border">&nbsp;</span>
                                    </h5>
                                    <div class="row h-50">
                                        <div class="col border-end">&nbsp;</div>
                                        <div class="col">&nbsp;</div>
                                    </div>
                                </div>
                                <!-- timeline item Odd event content -->
                                <div class="col-sm py-2">
                                    <div class="card radius-15">
                                        <div class="card-body">
                                            <div class="float-end text-muted small" id="tgl">Jan 9th 2019 7:00 AM
                                            </div>
                                            <h4 class="card-title text-muted">Day 1 Orientation</h4>
                                            <p class="card-text">Welcome to the campus, introduction and get started
                                                with the tour.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--/row-->
                            <!-- timeline item Even -->
                            <div class="row g-0">
                                <div class="col-sm py-2">
                                    <div class="card radius-15">
                                        <div class="card-body">
                                            <div class="float-end text-muted small">Jan 12th 2019 11:30 AM</div>
                                            <h4 class="card-title">Day 4 Wrap-up</h4>
                                            <p>Join us for lunch in Bootsy's cafe across from the Campus Center.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
                                    <div class="row h-50">
                                        <div class="col border-end">&nbsp;</div>
                                        <div class="col">&nbsp;</div>
                                    </div>
                                    <h5 class="m-2">
                                        <span class="badge rounded-pill bg-light border">&nbsp;</span>
                                    </h5>
                                    <div class="row h-50">
                                        <div class="col">&nbsp;</div>
                                        <div class="col">&nbsp;</div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <!--spacer-->
                                </div>
                            </div>
                            <!--/row-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        {{-- <div class="overlay toggle-icon"></div> --}}
        <!--end overlay-->
        <!--Start Back To Top Button-->
        {{-- <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a> --}}
        <!--End Back To Top Button-->
        {{-- <footer class="page-footer">
            <p class="mb-0">Copyright 2022-{{ date('Y') }} © {{ nama_instansi() }}</p>
        </footer> --}}
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="{{ asset('template') }}/assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="{{ asset('template') }}/assets/js/jquery.min.js"></script>
    <script src="{{ asset('template') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ asset('template') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ asset('template') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--app JS-->
    <script src="{{ asset('template') }}/assets/js/app.js"></script>
    <script type="text/javascript">
        $("#form-tracking").on('submit', function(event) {
            event.preventDefault()
            tracking()
        })

        {{-- console.log($("#trackingNumber").val("<?= $_GET['search'] ?>")); --}}
        let search = '';
        if ($("#search").val() != undefined) {
            search = $("#search").val();
            $("#trackingNumber").val(search);
            tracking();
        }

        function tracking() {
            let form = $("#form-tracking")
            const url = "{{ route('post-tracking') }}"
            $.ajax({
                url,
                method: "POST",
                data: form.serialize(),
                success: function(res) {
                    if (Object.keys(res).length > 0 && res['status'] == true) {
                        $('#kosong').addClass('hidden')
                        $('#keterangan-resi').removeClass('hidden')
                        $('#keterangan-judul').html(res['keterangan'].toUpperCase())
                        $('#kota_pengirim').html(`Kota Asal: ` + res["pengiriman"].kota_pengirim.toUpperCase())
                        $('#kota_penerima').html(`Kota Tujuan: ` + res["pengiriman"].kota_penerima
                            .toUpperCase())
                        $('#nama-penerima').html(`Penerima: ` + res["pengiriman"].nama_penerima.toUpperCase())
                        $('#nama-pengirim').html(`Pengirim: ` + res["pengiriman"].nama_pengirim.toUpperCase())
                        $('#tracking-resi').removeClass('hidden')
                        $('#tracking-resi').children().remove()
                        let display_tracking =
                            `<h2 class="font-weight-light text-center text-muted py-3">Tracking Resi</h2>`
                        let span = `<span class="badge rounded-pill bg-light border">&nbsp;</span>`
                        var data = res['data'];
                        let image = ``
                        let y = data.length - 1;
                        for (let i = 0; i < data.length; i++) {

                            if (data[i].file_bukti != null) {
                                image =
                                    `<a class="togglebtn mb-2" onclick="event.preventDefault(); $('.myimgdivtoggle` +
                                    i + `').toggle();">Image</a>
                                            <div class="myimgdivtoggle` + i + `" style="display:none;">
                                                <img src="{{ url('') }}/foto_bukti/${data[i].file_bukti}" alt="Detail Resi" width="400px" height="400px"/>
                                            </div>`
                            }
                            if (i % 2) {
                                if (i == data.length - 1) {
                                    span = `<span class="badge rounded-pill bg-primary">&nbsp;</span>`
                                }
                                display_tracking += `<div class="row g-0">
                                <div class="col-sm py-2">
                                    <div class="card radius-15">
                                        <div class="card-body">
                                            <div class="float-end text-muted small">${data[i].updated_at}</div>
                                            <h4 class="card-title">${data[i].nama_status}</h4>
                                            <p>Posisi: ${data[i].nama_kota}</p>
                                            <p>Keterangan: ${data[i].ket || ''}</p>
                                            ` + image + `
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-1 text-center flex-column d-none d-sm-flex">
									<div class="row h-50">
										<div class="col border-end">&nbsp;</div>
										<div class="col">&nbsp;</div>
									</div>
									<h5 class="m-2">
									` + span + `
								</h5>
									<div class="row h-50">
										<div class="col border-end">&nbsp;</div>
										<div class="col">&nbsp;</div>
									</div>
								</div>
								<div class="col-sm">
									<!--spacer-->
								</div>
                            </div>`

                            } else {
                                if (i == data.length - 1) {
                                    span = `<span class="badge rounded-pill bg-primary">&nbsp;</span>`
                                }
                                display_tracking += `
                                <div class="row g-0">
                                <div class="col-sm">
									<!--spacer-->
								</div>
								<div class="col-sm-1 text-center flex-column d-none d-sm-flex">
									<div class="row h-50">
										<div class="col border-end">&nbsp;</div>
										<div class="col">&nbsp;</div>
									</div>
									<h5 class="m-2">` + span + `
                                </h5>
									<div class="row h-50">
										<div class="col border-end">&nbsp;</div>
										<div class="col">&nbsp;</div>
									</div>
								</div>
								<div class="col-sm py-2">
									<div class="card radius-15">
										<div class="card-body">
											<div class="float-end text-muted small">${data[i].updated_at}</div>
											<h4 class="card-title">${data[i].nama_status}</h4>
											<p>Posisi: ${data[i].nama_kota}</p>
											<p>Keterangan: ${data[i].ket || ''}</p>
                                            ` + image + `
										</div>
									</div>
								</div>
                            </div>`
                            }
                        }
                        $('#tracking-resi').append(display_tracking)
                    } else {
                        $('#keterangan-resi').addClass('hidden')
                        $('#tracking-resi').addClass('hidden')
                        $('#kosong').removeClass('hidden')
                    }
                },
                error: function(e) {
                    alert('Something Wrong!')
                }
            })
        }
    </script>
</body>

</html>
