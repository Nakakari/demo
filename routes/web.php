<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CabangController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\Admin\JabatanController;
use App\Http\Controllers\Admin\PelangganController;
use App\Http\Controllers\Admin\AkunBankController;
use App\Http\Controllers\Admin\invoiceController;
use App\Http\Controllers\Admin\pelangganPenjualanController;
use App\Http\Controllers\Admin\PengirimanAdminController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\piutangController;
use App\Http\Controllers\Admin\riwayatPembayaranController;
use App\Http\Controllers\Sales\PengirimanController;
use App\Http\Controllers\Sales\TugasController;
use App\Http\Controllers\Checker\CheckerController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Sales\DaftarEceranController;
use App\Http\Controllers\trackingController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('company_profile', [trackingController::class, 'compro']);

Route::get('tracking', [trackingController::class, 'index'])->name('get-tracking');
Route::get('scantracking', [trackingController::class, 'scan'])->name('scan-tracking');
Route::post('tracking', [trackingController::class, 'search'])->name('post-tracking');

Auth::routes([
    'register' => false,
    'password.request' => false,
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('admin/home', [HomeController::class, 'adminHome'])->name('admin.home')->middleware('is_admin');
Route::get('sales/home', [HomeController::class, 'salesHome'])->name('sales.home')->middleware('is_sales');
Route::get('checker/home', [HomeController::class, 'checkerHome'])->name('checker.home')->middleware('is_checker');

// -------------------------------ADMIN-------------------------
Route::group(['middleware' => 'is_admin'], function () {
    // Cabang
    Route::get('/cabang', [CabangController::class, 'index'])->name('cabang');
    Route::post('/list_cabang', [CabangController::class, 'listCabang']);
    Route::post('/upload/cabang', [CabangController::class, 'addCabang']);
    Route::post('/upload/cabang/{id_cabang}', [CabangController::class, 'updateCabang']);
    Route::get('/delete_cabang/{id_cabang}', [CabangController::class, 'hapusCabang']);

    //Jabatan
    Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan');
    Route::post('/jenis_jabatan', [JabatanController::class, 'jenisJabatan']);
    Route::post('/add/jabatan', [JabatanController::class, 'addJabatan']);

    // Pengguna
    Route::controller(PenggunaController::class)->group(function () {
        Route::get('pengguna', 'index')->name('pengguna');
        Route::post('list_pengguna', 'listPengguna')->name('pengguna.list-pengguna');
        Route::get('edit/pengguna/{uuid}', 'editPengguna')->name('pengguna.edit-pengguna');
        Route::post('kodearea', 'kodeArea')->name('pengguna.kode-area');
        Route::post('kodeareaedited', 'kodeAreaEdited')->name('pengguna.kode-area-edit');
        Route::post('upload/pengguna', 'addPengguna')->name('pengguna.add-pengguna');
        Route::post('update/pengguna/{uuid}', 'updatePengguna')->name('pengguna.update-pengguna');
        Route::post('delete_pengguna', 'hapusPengguna')->name('pengguna.hapus-pengguna');
        Route::get('list_member_sales/{uuid}', 'listMemberSales')->name('pengguna.list-member');
        Route::post('dt_member_sales/{uuid}', 'dataMemberSales')->name('pengguna.dt-member');
        Route::get('create_member_sales/{uuid}', 'addMemberSales')->name('pengguna.add-member');
        Route::post('save_member_sales/{uuid}', 'saveMemberSales')->name('pengguna.save-member');
        Route::get('edit_member_sales/{uuid}', 'editMemberSales')->name('pengguna.edit-member');
        Route::post('update_member_sales/{uuid}', 'updateMemberSales')->name('pengguna.update-member');
        Route::post('delete_member_sales', 'destroyMemberSales')->name('pengguna.delete-member');
    });

    //Pelanggan
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan');
    Route::post('/data_pelanggan', [PelangganController::class, 'listPelanggan']);
    Route::post('/add/pelanggan', [PelangganController::class, 'addPelanggan']);
    Route::get('/detail_pelanggan', [PelangganController::class, 'detailPelanggan']);
    Route::post('/update/pelanggan/{id_pelanggan}', [PelangganController::class, 'updatePelanggan']);
    Route::get('/delete_pelanggan/{id_pelanggan}', [PelangganController::class, 'deletePelanggan']);

    //Akun Bank
    Route::get('/akunbank', [AkunBankController::class, 'index'])->name('akunbank');
    Route::post('/list_bank', [AkunBankController::class, 'listBank']);
    Route::post('/add_bank', [AkunBankController::class, 'addBank']);
    Route::post('/edit_bank', [AkunBankController::class, 'editBank']);
    Route::post('/hapus_bank', [AkunBankController::class, 'hapusBank']);

    //Penjualan Perusahaan
    Route::get('/penjualan_perusahaan', [PenjualanController::class, 'index']);
    Route::post('/data_penjualan', [PenjualanController::class, 'list_penjualan']);
    Route::get('/detail_penjualan/{id_pelanggan}', [PenjualanController::class, 'detailPenjualan']);
    Route::post('/add_surat_kembali', [PenjualanController::class, 'TambahSuratKembali']);
    Route::post('/edit_surat_kembali', [PenjualanController::class, 'updateSuratKembali']);
    Route::post('/excell_penjualan', [PenjualanController::class, 'excel']);

    //Penjualan Pelanggan
    Route::get('/penjualan_pelanggan', [pelangganPenjualanController::class, 'index']);
    Route::get('/transaksi_pelanggan/{id_pelanggan}', [pelangganPenjualanController::class, 'transPelanggan']);
    Route::post('/list_trans_pelanggan/{id_pelanggan}', [pelangganPenjualanController::class, 'list_transPelanggan']);
    Route::post('/ubahstatus_trans_pelanggan', [pelangganPenjualanController::class, 'update_transPelanggan']);

    //Olah data pelanggan
    Route::get('/add_transaksi_penjualan/{id_pelanggan}', [pelangganPenjualanController::class, 'addNewTransaksi']);
    Route::post('/except_list_trans_pelanggan/{id_pelanggan}', [pelangganPenjualanController::class, 'except_transPelanggan']);
    Route::post('/tambah_transaksi_pelanggan', [pelangganPenjualanController::class, 'uploadNewTransaksi']);

    Route::get('/pengiriman', [PengirimanAdminController::class, 'index']);
    Route::get('/print', [PengirimanAdminController::class, 'print']);
    Route::get('/print2/{id_pengiriman}', [PengirimanAdminController::class, 'print2']);
    Route::post('/data_pengiriman', [PengirimanAdminController::class, 'list_pengiriman']);
    Route::get('/edit_resi/{id_pengiriman}', [PengirimanAdminController::class, 'showData']);
    Route::post('/update/pengiriman/{id_pengiriman}', [PengirimanAdminController::class, 'updateData']);
    Route::get('admin/pengiriman/hitung-pembayaran', [PengirimanAdminController::class, 'hitungPembayaran'])->name('admin.pengiriman.hitung-pembayaran');

    Route::get('/coba-cetak', [PengirimanController::class, 'cetak']);

    //Invoice
    Route::get('/invoice', [invoiceController::class, 'index']);
    Route::post('/data_invoice_pelanggan', [invoiceController::class, 'list_pelanggan']);
    Route::post('/inv_pelanggan/{id_pelanggan}', [invoiceController::class, 'invPelanggan']);
    Route::get('/detail_invoice/{id_pelanggan}', [invoiceController::class, 'transPelanggan']);
    Route::get('/make_invoice/{id_pelanggan}', [invoiceController::class, 'invoice']);
    Route::post('/add_invoice/{id_pelanggan}', [invoiceController::class, 'push_invoice']);
    Route::get('/edit_invoice/{id_invoice}', [invoiceController::class, 'edit_invoice']);
    Route::get('/reselect_invoice/{id_invoice}', [invoiceController::class, 'reselect'])->name('reselect.invoice');
    Route::post('/reselect_inv_pelanggan/{id_pelanggan}', [invoiceController::class, 'reselectInvPelanggan']);
    Route::post('/update_invoice/{id}', [invoiceController::class, 'update_invoice']);

    // Piutang
    Route::get('/piutang', [piutangController::class, 'index']);
    Route::post('/list_piutang', [piutangController::class, 'list_piutang']);
    Route::get('/detail_piutang/{id_pelanggan}', [piutangController::class, 'detail_piutang'])->name('detail_piutang');
    Route::post('/list_detail_piutang/{id_pelanggan}', [piutangController::class, 'list_detail_piutang']);
    Route::post('/pelunasan_invoice', [piutangController::class, 'pelunasan']);
    Route::post('/update_pelunasan_invoice', [piutangController::class, 'update_pelunasan']);
    Route::get('/history/pembayaran/{id_invoice}', [riwayatPembayaranController::class, 'index'])->name('history.pembayaran');
    Route::post('/list_detail_riwayat_pembayaran/{id_invoice}', [riwayatPembayaranController::class, 'datatables'])->name('history.pembayaran.detail');
    Route::get('/cetak_invoice/{id_invoice}', [invoiceController::class, 'printInvoice'])->name('print.invoice');

    Route::prefix('setting')->group(function () {
        require __DIR__ . '/Settings/instansi.php';
    });
});

Route::group(['middleware' => 'is_sales'], function () {
    // Pengiriman
    Route::get('/pengiriman/{id_cabang}', [PengirimanController::class, 'index'])->name('pengiriman');
    Route::post('/pengiriman/{id_cabang}', [PengirimanController::class, 'listPengiriman']);
    Route::post('/konfirmasi_member', [PengirimanController::class, 'konfirmasi'])->name('konfirmasi.member');
    Route::get('/add/pengiriman/{id_cabang}', [PengirimanController::class, 'addPengiriman'])->name('tambah.pengiriman');
    Route::get('/pengiriman/print/{id_pengiriman}', [PengirimanController::class, 'printResi']);
    Route::get('/identitas/print/{id_pengiriman}', [PengirimanController::class, 'printIdentitas'])->name('identitas');
    Route::get('hitung-pembayaran', [PengirimanController::class, 'hitungPembayaran'])->name('pengiriman.hitung-pembayaran');

    Route::post('/kodeareapengguna', [PenggunaController::class, 'kodeArea']);
    Route::post('/kodepelanggan', [PengirimanController::class, 'kodePelanggan']);
    Route::post('/upload/pengiriman/{id_cabang}', [PengirimanController::class, 'uploadDataPengiriman']);
    Route::post('/listpengiriman/update_status', [PengirimanController::class, 'updateStatus']);
    Route::post('/show_fill/{id_cabang}', [PengirimanController::class, 'showFill']);
    Route::post('/show_fill_kondisi/{id_cabang}', [PengirimanController::class, 'showFillKondisi']);
    Route::post('/show_fill_all/{id_cabang}', [PengirimanController::class, 'showFillAll']);
    Route::post('/show_fill_tanpa_filter/{id_cabang}', [PengirimanController::class, 'showFillTanpaFilter']);

    Route::get('/daftartugas/{id_cabang}', [TugasController::class, 'index'])->name('daftartugas');
    Route::post('/daftar_tugas/{id_cabang}', [TugasController::class, 'listPengiriman']);
    Route::get('/add_manifest/{id_cabang}', [TugasController::class, 'addManifest']);
    Route::post('/insertmanifest', [TugasController::class, 'insertManifest']);
    Route::post('/showmanifest', [TugasController::class, 'showManifest']);

    Route::post('/tampil_filter/{id_cabang}', [TugasController::class, 'showFill']);
    Route::post('/show_fill_all2/{id_cabang}', [TugasController::class, 'showFillAll']);
    Route::post('/tampil_filter/{id_cabang}', [PengirimanController::class, 'showFill']);
    Route::post('/show_fill_all2/{id_cabang}', [PengirimanController::class, 'showFill']);

    Route::get('/daftarmuat/{id_cabang}', [TugasController::class, 'index_muat'])->name('daftarmuat');
    Route::post('/daftar_muat/{id_cabang}', [TugasController::class, 'listMuat']);
    Route::get('/detailmanifest/{id_manifest}', [TugasController::class, 'detailManifest']);
    Route::post('/editdetailmanifest', [TugasController::class, 'editdetailManifest']);
    Route::post('/pdf_daftar_muat', [TugasController::class, 'export_pdf']);

    Route::get('/daftarecer/{id_cabang}', [TugasController::class, 'index_ecer'])->name('daftarecer');
    Route::post('/list_ecer/{id_cabang}', [TugasController::class, 'listEcer']);
    Route::get('/detailecer/{id_pengiriman}', [TugasController::class, 'detailEcer']);
    Route::post('/editdetailecer', [TugasController::class, 'editdetailEcer']);

    Route::get('/daftarselesai/{id_cabang}', [TugasController::class, 'index_selesai'])->name('daftarselesai');
    Route::post('/daftar_selesai/{id_cabang}', [TugasController::class, 'listSelesai']);

    Route::get('/sales_checker_naik/{id_cabang}', [PengirimanController::class, 'ceknaik']);
    Route::post('/sales_list_naik/{id_cabang}', [PengirimanController::class, 'list_naik']);
    Route::get('/sales_resi_naik/{id_cek}', [PengirimanController::class, 'resinaik']);
    Route::post('/sales_resi_detail/{id_cek}', [PengirimanController::class, 'list_resinaik']);
    Route::get('/sales_detail_naik/{id_cek}/{id_pengiriman}', [PengirimanController::class, 'detailnaik']);
    Route::post('/sales_list_detail/{id_cek}/{id_pengiriman}', [PengirimanController::class, 'list_detailnaik']);

    Route::get('/sales_checker_turun/{id_cabang}', [PengirimanController::class, 'cekturun']);
    Route::post('/sales_list_turun/{id_cabang}', [PengirimanController::class, 'list_turun']);
    Route::get('/sales_resi_turun/{id_cek}', [PengirimanController::class, 'resiturun']);
    Route::post('/sales_resi_detail2/{id_cek}', [PengirimanController::class, 'list_resiturun']);
    Route::get('/sales_detail_turun/{id_cek}/{id_pengiriman}', [PengirimanController::class, 'detailturun']);
    Route::post('/sales_list_detail2/{id_cek}/{id_pengiriman}', [PengirimanController::class, 'list_detailturun']);

    Route::get('/get_status_pengiriman', [DaftarEceranController::class, 'getStatusPengiriman'])->name('get_status_pengiriman');
    Route::post('tambah_muat_eceran', [DaftarEceranController::class, 'store'])->name('muat_eceran.store');
});

Route::group(['middleware' => 'is_checker'], function () {
    // Naik
    Route::get('/checker_naik/{id_cabang}', [CheckerController::class, 'index'])->name('checker');
    Route::post('/list_naik/{id_cabang}', [CheckerController::class, 'list_index']);
    Route::get('/resi_naik/{id_cek}', [CheckerController::class, 'resinaik']);
    Route::post('/resi_detail/{id_cek}', [CheckerController::class, 'list_resi']);
    Route::get('/detail_naik/{id_cek}/{id_pengiriman}', [CheckerController::class, 'detail']);
    Route::post('/list_detail/{id_cek}/{id_pengiriman}', [CheckerController::class, 'list_detail']);

    Route::post('/insertnaik', [CheckerController::class, 'insertNaik']);
    Route::get('/scan_identitas/{id_cek}', [CheckerController::class, 'scan']);
    Route::get('/cek/{id_cek}/{nama_identitas}', [CheckerController::class, 'cekIdentitas'])->name('cekidentitas');

    // Turun
    Route::get('/checker_turun/{id_cabang}', [CheckerController::class, 'index2'])->name('checker2');
    Route::post('/list_turun/{id_cabang}', [CheckerController::class, 'list_index2']);
    Route::get('/resi_turun/{id_cek}', [CheckerController::class, 'resiturun']);
    Route::post('/resi_detail2/{id_cek}', [CheckerController::class, 'list_resi2']);
    Route::get('/detail_turun/{id_cek}/{id_pengiriman}', [CheckerController::class, 'detail2']);
    Route::post('/list_detail2/{id_cek}/{id_pengiriman}', [CheckerController::class, 'list_detail2']);

    Route::get('/scan_identitas2/{id_cek}', [CheckerController::class, 'scan2']);
    Route::get('/cek2/{id_cek}/{nama_identitas}', [CheckerController::class, 'cekIdentitas2'])->name('cekidentitas2');

    Route::get('/checker_ecer/{id_cabang}', [CheckerController::class, 'index_ecer']);
    Route::post('/list_ecer_checker/{id_cabang}', [CheckerController::class, 'listEcer']);
});
