<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\M_jabatan;
use App\Models\M_riwayatPembayaran;
use Illuminate\Http\Request;

class riwayatPembayaranController extends Controller
{

    public function index($id_invoice)
    {
        $id_invoice = base64_decode($id_invoice);
        $riwayatPembayaran = M_riwayatPembayaran::where('id_invoice', $id_invoice)->get();
        $jab = M_jabatan::getJab();
        return view('Admin.riwayatPembayaran.v_index', compact('riwayatPembayaran', 'jab', 'id_invoice'));
    }

    public function datatables(Request $request, $id_invoice)
    {
        $id_invoice = base64_decode($id_invoice);
        $columns = [
            'id_invoice',
            'pembayaran',
            'created_at'
        ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_riwayatPembayaran())->datatables($id_invoice);

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(tbl_invoice.no_invoice) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(riwayat_pembayaran.pembayaran) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
