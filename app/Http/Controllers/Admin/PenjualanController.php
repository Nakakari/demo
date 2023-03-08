<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PenjualanExport;
use App\Http\Controllers\Controller;
use App\Models\M_cabang;
use Illuminate\Http\Request;
use App\Models\M_jabatan;
use App\Models\M_pengiriman;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PenjualanController extends Controller
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
            'kondisi' => M_cabang::getKondisi(),
        ];
        return view('Admin.Penjualan.v_penjualan', $data);
    }

    public function list_penjualan(Request $request)
    {
        $columns = [
            'id_pengiriman', 'no_resi', 'id_cabang', 'status_sent', 'id_pelanggan', 'nama_penerima', 'alamat_penerima', 'no_penerima',
            'isi_barang', 'berat', 'koli', 'no_ref', 'no_pelayanan', 'no_moda', 'biaya', 'tipe_pembayaran',
            'nama_pengirim', 'alamat_pengirim', 'tlp_pengirim', 'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_pengiriman())->listPenjualan();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.kota_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function detailPenjualan()
    {
        return 'okay';
    }

    public function TambahSuratKembali(Request $request)
    {
        // dd(request()->all());
        $this->validate($request, [
            'id_pengiriman'  => 'required',
            'no_resi' => 'required',
        ]);
        if (empty($request->get('keterangan')) && empty($request->get('tgl'))) {
            $data = [
                'id_pengiriman' => request()->get('id_pengiriman'),
                'no_resi' => request()->get('no_resi'),
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 1,
            ];
            $is_kondisi_resi = 1;
        } else if (empty($request->get('keterangan')) && !empty($request->get('tgl'))) {
            // dd(request()->all());
            $data = [
                'id_pengiriman' => request()->get('id_pengiriman'),
                'no_resi' => request()->get('no_resi'),
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 2,
            ];
            $is_kondisi_resi = 2;
        } else if (!empty($request->get('keterangan')) && empty($request->get('tgl'))) {
            // dd(request()->all());
            $data = [
                'id_pengiriman' => request()->get('id_pengiriman'),
                'no_resi' => request()->get('no_resi'),
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 0,
            ];
            $is_kondisi_resi = 0;
        } else {
            $data = [
                'id_pengiriman' => request()->get('id_pengiriman'),
                'no_resi' => request()->get('no_resi'),
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 3,
            ];
            $is_kondisi_resi = 3;
            // dd(request()->all());
        }

        DB::transaction(
            function () use ($request, $data, $is_kondisi_resi): void {
                DB::table('tbl_surat_kembali')->insert($data);
                $pengiriman = M_pengiriman::find($request->input('id_pengiriman'));
                $pengiriman->is_kondisi_resi = $is_kondisi_resi;
                $pengiriman->save();
            }

        );
        return response()->json(true);
    }

    public function updateSuratKembali(Request $request)
    {
        if (empty($request->get('keterangan')) && empty($request->get('tgl'))) {
            $data = [
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 1,
            ];
            $is_kondisi_resi = 1;
        } else if (empty($request->get('keterangan')) && !empty($request->get('tgl'))) {
            // dd(request()->all());
            $data = [
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 2,
            ];
            $is_kondisi_resi = 2;
        } else if (!empty($request->get('keterangan')) && empty($request->get('tgl'))) {
            // dd(request()->all());
            $data = [
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 0,
            ];
            $is_kondisi_resi = 0;
        } else {
            $data = [
                'tgl_surat_kembali' => request()->get('tgl'),
                'keterangan' => request()->get('keterangan'),
                'is_isi' => 3,
            ];
            $is_kondisi_resi = 3;
            // dd(request()->all());
        }

        DB::transaction(
            function () use ($request, $data, $is_kondisi_resi): void {
                DB::table('tbl_surat_kembali')->where('id_pengiriman', request()->get('id_pengiriman'))->update($data);
                $pengiriman = M_pengiriman::find($request->input('id_pengiriman'));
                $pengiriman->is_kondisi_resi = $is_kondisi_resi;
                $pengiriman->save();
            }

        );
        return response()->json(true);
    }

    public function excel(Request $request)
    {
        $id_cabang = $request->id_cabang;
        $dari_tanggal = $request->dari_tanggal;
        $sampai_tanggal = $request->sampai_tanggal;
        $filter_kondisi = request()->get('filter-kondisi');
        return Excel::download(new PenjualanExport($id_cabang, $dari_tanggal, $sampai_tanggal, $filter_kondisi), 'Laporan Penjualan.xlsx');
        // return (new PenjualanExport($id_cabang, $dari_tanggal, $sampai_tanggal, $filter_kondisi));
    }
}
