<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\M_cabang;
use App\Models\M_jabatan;
use App\Models\M_pelanggan;
use App\Models\M_pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class pelangganPenjualanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
        ];
        return view('Admin.Penjualan.v_penjualanPelanggan', $data);
    }

    public function transPelanggan($id_pelanggan)
    {
        $id_pelanggan = base64_decode($id_pelanggan);
        $data = [
            'kondisi' => M_cabang::getKondisi(),
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
            'getPerusahaan' => M_pelanggan::getNama($id_pelanggan),
            'id_pelanggan' => $id_pelanggan
        ];
        return view('Admin.Penjualan.v_showPenjualanPelanggan', $data);
    }

    public function list_transPelanggan($id_pelanggan)
    {
        $id_pelanggan = base64_decode($id_pelanggan);
        $columns = [
            'id_pengiriman', 'no_resi', 'id_cabang', 'status_sent', 'id_pelanggan', 'nama_penerima', 'alamat_penerima', 'no_penerima',
            'isi_barang', 'berat', 'koli', 'no_ref', 'no_pelayanan', 'no_moda', 'biaya', 'tipe_pembayaran',
            'nama_pengirim', 'alamat_pengirim', 'tlp_pengirim', 'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = M_pengiriman::select([
            'pengiriman.id_pengiriman',
            'pengiriman.no_resi',
            'pengiriman.id_cabang_tujuan',
            'pengiriman.dari_cabang',
            'pengiriman.nama_pengirim',
            'pengiriman.alamat_pengirim',
            'pengiriman.tlp_pengirim',
            'pengiriman.status_sent',
            'tbl_status_pengiriman.nama_status',
            'pengiriman.id_pelanggan',
            'pengiriman.tgl_masuk',
            'pengiriman.nama_penerima',
            'pengiriman.alamat_penerima',
            'pengiriman.kota_penerima',
            'pengiriman.no_penerima',
            'pengiriman.isi_barang',
            'pengiriman.is_kondisi_resi',
            DB::Raw('IFNULL(pengiriman.volume, berat) AS kilo'),
            'pengiriman.koli',
            'pengiriman.no_ref',
            'pengiriman.no_pelayanan',
            'pengiriman.no_moda',
            'pengiriman.biaya',
            'pengiriman.tipe_pembayaran',
            'cabang.nama_kota',
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "1" THEN pengiriman.biaya
                        END) AS tagihan'),
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "2" THEN pengiriman.biaya
                        END) AS cod'),
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "3" THEN pengiriman.biaya
                        END) AS cash'),
            'tbl_surat_kembali.tgl_surat_kembali as tgl',
            'tbl_surat_kembali.keterangan as ket',
        ])
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->leftjoin('tbl_surat_kembali', 'tbl_surat_kembali.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->join('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'pengiriman.status_sent')
            ->where('pengiriman.id_pelanggan', $id_pelanggan)
            ->orderBy('id_pengiriman', "asc");

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->input('dari'), request()->input('sampai')])->count();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(cabang.nama_kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null || request()->input('sampai') != null) {
            $data = $data->whereBetween('pengiriman.tgl_masuk', [request()->dari, request()->sampai]);
        }

        if (request()->input('kondisi') != null) {
            $data = $data->where('pengiriman.is_kondisi_resi', request()->kondisi);
        }

        if (request()->input('cabang') != null) {
            $data = $data->where('pengiriman.dari_cabang', request()->cabang);
        }

        $data = $data
            ->skip(request()->input('start'))
            ->take(request()->input('length'))
            ->orderBy($orderBy, request()->input("order.0.dir"))
            ->get();
        $recordsTotal = $data->count();

        return response()->json([
            'draw' => request()->input('draw'),
            'data_waktu' => $dataWaktu,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'all_request' => request()->all(),
        ]);
    }

    public function addNewTransaksi($id_pelanggan)
    {
        $id_pelanggan = base64_decode($id_pelanggan);
        $data = [
            'status' => M_cabang::getStatus(),
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
            'getPerusahaan' => M_pelanggan::getNama($id_pelanggan),
            'id_pelanggan' => $id_pelanggan
        ];
        return view('Admin.Penjualan.v_addNewTransaksi', $data);
    }

    public function except_transPelanggan(Request $request, $id_pelanggan)
    {
        $id_pelanggan = base64_decode($id_pelanggan);
        $columns = [
            'id_pengiriman', 'no_resi', 'id_cabang', 'status_sent', 'id_pelanggan', 'nama_penerima', 'alamat_penerima', 'no_penerima',
            'isi_barang', 'berat', 'koli', 'no_ref', 'no_pelayanan', 'no_moda', 'biaya', 'tipe_pembayaran',
            'nama_pengirim', 'alamat_pengirim', 'tlp_pengirim', 'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = M_pengiriman::select([
            'pengiriman.id_pengiriman',
            'pengiriman.no_resi',
            'pengiriman.id_cabang_tujuan',
            'pengiriman.dari_cabang',
            'pengiriman.nama_pengirim',
            'pengiriman.alamat_pengirim',
            'pengiriman.tlp_pengirim',
            'pengiriman.status_sent',
            'tbl_status_pengiriman.nama_status',
            'pengiriman.id_pelanggan',
            'pengiriman.tgl_masuk',
            'pengiriman.nama_penerima',
            'pengiriman.alamat_penerima',
            'pengiriman.kota_penerima',
            'pengiriman.no_penerima',
            'pengiriman.isi_barang',
            DB::Raw('IFNULL(pengiriman.volume, berat) AS kilo'),
            'pengiriman.koli',
            'pengiriman.no_ref',
            'pengiriman.no_pelayanan',
            'pengiriman.no_moda',
            'pengiriman.biaya',
            'pengiriman.tipe_pembayaran',
            'cabang.nama_kota',
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "1" THEN pengiriman.biaya
                        END) AS tagihan'),
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "2" THEN pengiriman.biaya
                        END) AS cod'),
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "3" THEN pengiriman.biaya
                        END) AS cash'),
            'tbl_surat_kembali.tgl_surat_kembali as tgl',
            'tbl_surat_kembali.keterangan as ket',
        ])
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->leftjoin('tbl_surat_kembali', 'tbl_surat_kembali.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->join('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'pengiriman.status_sent')
            ->where('pengiriman.status_sent', '!=', 7)
            ->whereNull('pengiriman.id_pelanggan')
            ->orderBy('id_pengiriman', "asc");

        //$recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->input('dari'), request()->input('sampai')])->count();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(cabang.nama_kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null || request()->input('sampai') != null) {
            $data = $data->whereBetween('pengiriman.tgl_masuk', [request()->dari, request()->sampai]);
        }

        if (request()->input('kondisi') != null) {
            $data = $data->where('pengiriman.status_sent', request()->kondisi);
        }

        if (request()->input('cabang') != null) {
            $data = $data->where('pengiriman.id_cabang_tujuan', request()->cabang);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    public function uploadNewTransaksi()
    {
        // dd(request()->all());
        $data = [
            'id_pelanggan' => request()->get('id_pelanggan'),
        ];
        DB::table('pengiriman')->whereIn('id_pengiriman', request()->get('id'))
            ->update($data);
        return response()->json(true);
    }

    public function update_transPelanggan()
    {
        $data = [
            'id_pelanggan' => request()->get('id_pelanggan'),
        ];
        DB::table('pengiriman')->where('id_pengiriman', request()->get('id_pengiriman'))
            ->update($data);
        return response()->json(true);
    }
}
