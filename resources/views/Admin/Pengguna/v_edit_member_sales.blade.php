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
                    <li class="breadcrumb-item"><a href="{{ route('pengguna.list-member', $member->sales->uuid) }}">List
                            Member</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Member</li>
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
                        <h5 class="mb-0 text-primary">Tambah Member</h5>
                    </div>
                    <hr>
                    <p>Tambah Member untuk akun sales: <b>{{ $member->sales->name }}</b></p>
                    <hr>
                    <form id="form-addPengiriman" class="row g-3" action="{{ route('pengguna.update-member', $uuid) }}"
                        method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">New Kode Member</label>
                            <input id="kode" type="text" class="form-control" name="kode"
                                value="{{ old('kode') }}" required>
                            <input type="hidden" name="id_sales" value="{{ $member->sales->id }}">
                            @if ($errors->has('kode'))
                                <span class="text-danger">{{ $errors->first('kode') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label for="inputAddress" class="form-label">
                                Nama</label>
                            <input id="nama" type="text" class="form-control" name="nama"
                                value="{{ old('nama') ?? $member->nama }}" required>
                            @if ($errors->has('nama'))
                                <span class="text-danger">{{ $errors->first('nama') }}</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Update</button>
                            <button type="reset" class="btn btn-danger" data-bs-dismiss="modal">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
