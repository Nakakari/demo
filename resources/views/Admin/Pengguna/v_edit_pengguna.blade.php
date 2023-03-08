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
                    <li class="breadcrumb-item"><a href="{{ route('pengguna') }}">Pengguna</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Pengguna</li>
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
                        <h5 class="mb-0 text-primary">Edit Pengguna</h5>
                    </div>
                    <hr>
                    <form class="row g-3" action="{{ route('pengguna.update-pengguna', $user->uuid) }}" method="POST"
                        enctype="multipart/form-data" id="formEdit">
                        {{ csrf_field() }}
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">Nama Lengkap</label>
                            <input id="namaedited" type="text" class="form-control" name="name"
                                value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-3">
                            <label for="inputAddress" class="form-label">Tanggal Lahir</label>
                            <input id="tgl_lhredited" type="date"
                                class="form-control @error('nama_kota') is-invalid @enderror" name="tgl_lhr"
                                value="{{ $user->tgl_lhr }}" required>

                            @error('kode_area')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="inputAddress" class="form-label">Foto</label>
                            <input id="file_fotoedited" type="file"
                                class="form-control @error('nama_kota') is-invalid @enderror" name="file_foto">
                            {{-- <img src="{{ url('') }}/foto_pengguna/{{ $user->file_foto }}"
                                            alt="{{ $user->name }}" width="50" height="60"> --}}

                            @error('kode_area')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">Email</label>
                            <input id="emailedited" type="text"
                                class="form-control @error('nama_kota') is-invalid @enderror" name="email"
                                value="{{ $user->email }}" required autocomplete="email">

                            @error('nama_kota')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">New Password</label>
                            <input id="passedited" type="password"
                                class="form-control @error('nama_kota') is-invalid @enderror" name="password"
                                autocomplete="email" required>
                        </div>
                        <div class="col-md-4">
                            <label for="inputAddress" class="form-label">Nama Cabang</label>
                            <select name="id_cabang" id="nama_kotaEdited" class="form-select" required>
                                <option value="">Pilih Cabang .....</option>
                                @foreach ($cabang as $cab)
                                    <option value="{{ $cab->id_cabang }}"
                                        {{ $cab->id_cabang == $user->id_cabang ? 'selected' : '' }}>
                                        {{ $cab->nama_kota }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="inputAddress" class="form-label">Kode Cabang</label>
                            <select name="kode_area" id="id_cabangEdited" class="form-control" disabled>
                            </select>

                            @error('kode_area')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="inputAddress" class="form-label">Jabatan</label>
                            <select name="peran" id="peran" class="form-select" required>
                                <option value="">Pilih Jabatan...</option>
                                @foreach ($jab as $j)
                                    <option value="{{ $j->id_jabatan }}"
                                        {{ $j->id_jabatan == $user->peran ? 'selected' : '' }}>
                                        {{ $j->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="inputAddress" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="almtedited" placeholder="Jl. Perkutut..." rows="3">{{ $user->alamat }}</textarea>
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
@stop
@section('js')
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#nama_kotaEdited').on('change', function() {
                $.ajax({
                    url: '{{ url('kodeareaedited') }}',
                    method: 'POST',
                    data: {
                        id_cabangEdited: $(this).val()
                    },
                    success: function(response) {
                        $('#id_cabangEdited').empty();

                        $.each(response, function(id_cabang, kode_area) {
                            // console.log(kode_area)
                            $('#id_cabangEdited').append(new Option(kode_area,
                                kode_area))
                        })
                    }
                })
            });
        });
    </script>
@stop
