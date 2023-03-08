<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\M_cabang;
use App\Models\M_detailInvoice;
use App\Models\M_invoice;
use App\Models\M_jabatan;
use App\Models\M_pelanggan;
use App\Models\M_pelunasan;
use App\Models\M_pengiriman;
use App\Models\M_piutang;
use App\Models\M_riwayatPembayaran;
use App\Services\PiutangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class piutangController extends Controller
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
        return view('Admin.Piutang.v_piutang', $data);
    }

    public function list_piutang(Request $request)
    {
        $columns = [
            'nama_perusahaan',
            'kota',
            'alamat',
            'nama_spv',
            'tlp_spv',
            'k_perusahaan',
            'email_prshn'
        ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = DB::table('tbl_invoice')
            ->select(
                'pelanggan.nama_perusahaan',
                DB::raw('COUNT(is_lunas = 0) as inv_blm_lunas'),
                'tbl_invoice.id_pelanggan',
                //            DB::raw('(tbl_invoice.ttl_biaya_invoice*tbl_invoice.ppn/100) as get_total'),
                DB::raw('SUM(tbl_pelunasan.nominal_pelunasan) as ttl_pelunasan'),
                DB::raw('(SUM(tbl_invoice.ttl_biaya_invoice)-SUM(tbl_pelunasan.nominal_pelunasan)) as ttl_biaya_invoice'),
                DB::raw('SUM(tbl_invoice.ttl_biaya_invoice) as ttl_biaya')
            )
            ->rightjoin('pelanggan', 'tbl_invoice.id_pelanggan', '=', 'pelanggan.id_pelanggan')
            ->leftJoin('tbl_pelunasan', 'tbl_invoice.id_invoice', '=', 'tbl_pelunasan.id_invoice')
            ->groupBy(
                'pelanggan.nama_perusahaan',
                'tbl_invoice.id_pelanggan',
                //                'tbl_invoice.ttl_biaya_invoice',
                //                'tbl_invoice.ppn',
            )
            ->orderBy('tbl_invoice.id_pelanggan', "desc");

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pelanggan.nama_perusahaan) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pelanggan.kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
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

    public function detail_piutang($id_pelanggan)
    {
        $id = base64_decode($id_pelanggan);
        $data = [
            'kondisi' => M_cabang::getKondisi(),
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
            'getPerusahaan' => M_pelanggan::getNama($id),
            'id_pelanggan' => $id
        ];
        return view('Admin.Piutang.v_detailPiutang', $data);
    }

    public function list_detail_piutang(Request $request, $id_pelanggan)
    {
        $id_pelanggan = base64_decode($id_pelanggan);
        $columns = [
            'id_pengiriman', 'no_invoice',
            'biaya', 'tipe_pembayaran',
            'biaya_invoice'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_piutang())->listDetailPiutang($id_pelanggan);

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(tbl_invoice.id_invoice) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null || request()->input('sampai') != null) {
            $data = $data->whereBetween('tbl_invoice.jatuh_tempo', [request()->dari, request()->sampai]);
        }

        if (request()->input('kondisi') != null) {
            $data = $data->where('tbl_invoice.is_lunas', request()->kondisi);
        }

        $recordsFiltered = $data->get()->count();
        if ($request->input('length') != -1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy, $request->input('order.0.dir'))->get();
        $recordsTotal = $data->count();
        return response()->json([
            'test' => '1',
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function pelunasan(Request $request, PiutangService $service)
    {
        $this->validate($request, [
            'id_invoice'  => 'required',
            'nominal_pelunasan'  => 'required',
        ]);

        $id_invoice = $request->get('id_invoice');
        $nominal_pelunasan = str_ireplace(".", "", $request->get('nominal_pelunasan'));

        $get_id_pengiriman = DB::table('tbl_detail_invoice')
            ->select('id_pengiriman')
            ->where('id_invoice', '=', $id_invoice)
            ->get();

        $id_pengiriman = [];
        foreach ($get_id_pengiriman as $data) {
            $id_pengiriman[] = $data->id_pengiriman;
        }

        $get_ttl_biaya_invoice = M_invoice::where('id_invoice', $id_invoice)->firstOrFail();
        $get_biaya = $get_ttl_biaya_invoice['ttl_biaya_invoice'];

        if ($nominal_pelunasan > $get_biaya) {
            $res = 400;
        } else {
            $service = $service->inputPiutang($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya);
            $res = $service ? 204 : 400;
        }

        return response()->json()->setStatusCode($res);
    }

    public function update_pelunasan(Request $request, M_pelunasan $pelunasan, PiutangService $service)
    {
        // dd(request()->all());
        $id_invoice = $request->get('id_invoice');
        $nominal_pelunasan = str_ireplace(".", "", $request->get('update_nominal_pelunasan'));

        $get_id_pengiriman = DB::table('tbl_detail_invoice')
            ->select('id_pengiriman')
            ->where('id_invoice', '=', $id_invoice)
            ->get();

        $past_pelunasan = $pelunasan->where('id_invoice', $id_invoice)->firstOrFail();
        $update_peluansan = $nominal_pelunasan + $past_pelunasan['nominal_pelunasan'];
        // dd($nominal_pelunasan);

        $id_pengiriman = [];
        foreach ($get_id_pengiriman as $data) {
            $id_pengiriman[] = $data->id_pengiriman;
        }

        $get_ttl_biaya_invoice = M_invoice::where('id_invoice', $id_invoice)->firstOrFail();
        $get_biaya = $get_ttl_biaya_invoice['ttl_biaya_invoice'];

        if ($update_peluansan > $get_biaya) {
            $res = 400;
        } else {
            $service = $service->updatePiutang($id_invoice, $id_pengiriman, $nominal_pelunasan, $get_biaya, $update_peluansan);
            $res = $service ? 204 : 400;
        }

        return response()->json()->setStatusCode($res);
    }
}
