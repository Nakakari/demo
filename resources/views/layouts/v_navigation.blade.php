<!--navigation-->
<ul class="metismenu" id="menu">
    @if (Auth::user()->peran == 1)
        <li>
            <a href="/admin/home" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="lni lni-key"></i>
                </div>
                <div class="menu-title">Master Data</div>
            </a>
            <ul>
                <li>
                <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>
                        Cabang & Pengguna
                    </a>
                    <ul>
                        <li> <a href="/cabang"><i class="bx bx-right-arrow-alt"></i>Cabang</a>
                        <li> <a href="/pengguna"><i class="bx bx-right-arrow-alt"></i>Pengguna</a>
                        <li> <a href="/jabatan"><i class="bx bx-right-arrow-alt"></i>Jenis Jabatan</a>
                    </ul>
                </li>
                <li> <a href="/pelanggan"><i class="bx bx-right-arrow-alt"></i>Pelanggan</a>
                <li> <a href="/akunbank"><i class="bx bx-right-arrow-alt"></i>Akun Bank</a>

                </li>
            </ul>
        </li>
        <li>
            <a href="/pengiriman" class="parent-icon">
                <div class="parent-icon"><i class="fadeIn animated bx bx-radar"></i>
                </div>
                <div class="menu-title">Pengiriman</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="fadeIn animated bx bx-archive"></i>
                </div>
                <div class="menu-title">Transaksi</div>
            </a>
            <ul>
                <li>
                <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>
                        Penjualan
                    </a>
                    <ul>
                        <li> <a href="/penjualan_perusahaan"><i class="bx bx-right-arrow-alt"></i>Perusahaan</a>
                        <li> <a href="/penjualan_pelanggan"><i class="bx bx-right-arrow-alt"></i>Pelanggan</a>
                    </ul>
                </li>
                <li> <a href="/invoice"><i class="bx bx-right-arrow-alt"></i>Invoice</a>
                <li> <a href="/piutang"><i class="bx bx-right-arrow-alt"></i>Piutang</a>

                </li>
            </ul>
        </li>
        {{-- <li>
            <a href="/penjualan" class="parent-icon">
                <div class="parent-icon"><i class="fadeIn animated bx bx-archive"></i>
                </div>
                <div class="menu-title">Penjualan</div>
            </a>
        </li> --}}
        {{-- <li>
            <a href="/piutang" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Piutang</div>
            </a>
        </li> --}}
    @elseif (Auth::user()->peran == 5)
        <li>
            <a href="/sales/home" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="/pengiriman/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">Pengiriman</div>
            </a>
        </li>
        <li>
            <a href="/daftartugas/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class="fadeIn animated bx bx-spreadsheet"></i>
                </div>
                <div class="menu-title">Daftar Tugas</div>
            </a>
        </li>
        <li>
            <a href="/daftarecer/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class="lni lni-bookmark"></i>
                </div>
                <div class="menu-title">Daftar Eceran</div>
            </a>
        </li>
        <li>
            <a href="/daftarselesai/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i data-feather="check-circle"></i>
                </div>
                <div class="menu-title">Misi Selesai</div>
            </a>
        </li>
        <li>
            <a href="/daftarmuat/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class="fadeIn animated bx bx-archive"></i>
                </div>
                <div class="menu-title">Daftar Muat</div>
            </a>
        </li>
        <li>
            <a href="/sales_checker_naik/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-up-arrow'></i>
                </div>
                <div class="menu-title">Cek Koli Naik</div>
            </a>
        </li>
        <li>
            <a href="/sales_checker_turun/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-down-arrow'></i>
                </div>
                <div class="menu-title">Cek Koli Turun</div>
            </a>
        </li>
    @elseif (Auth::user()->peran == 7)
        <li>
            <a href="/checker/home" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="/checker_naik/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-up-arrow'></i>
                </div>
                <div class="menu-title">Cek Koli Naik</div>
            </a>
        </li>
        <li>
            <a href="/checker_turun/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class='bx bx-down-arrow'></i>
                </div>
                <div class="menu-title">Cek Koli Turun</div>
            </a>
        </li>
        <li>
            <a href="/checker_ecer/{{ base64_encode(Auth::user()->id_cabang) }}" class="parent-icon">
                <div class="parent-icon"><i class="lni lni-bookmark"></i>
                </div>
                <div class="menu-title">Daftar Eceran</div>
            </a>
        </li>
    @elseif (Auth::user()->is_admin == null)
        <li>
            <a href="/pembelajaranku" class="parent-icon">
                <div class="parent-icon"><i class="fadeIn animated bx bx-spreadsheet"></i>
                </div>
                <div class="menu-title">Pembelajaran</div>
            </a>
        </li>
    @endif
    {{-- @endforeach --}}

</ul>
<!--end navigation-->
