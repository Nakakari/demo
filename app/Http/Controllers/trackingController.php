<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\M_pengiriman;
use App\Models\M_tracking;
use Illuminate\Http\Request;
use Throwable;

class trackingController extends Controller
{
    public function index(Request $request)
    {
        $search = '';
        if ($request->has('search')) {
            $search = $request->search;
        }
        return view('Customer.v_tracking', compact('search'));
    }

    public function scan(Request $request)
    {
        $search = '';
        if ($request->has('search')) {
            $search = $request->search;
        }
        return redirect('tracking/')->with('search', $search);
    }

    public function search()
    {
        try {
            $get_no_resi = M_pengiriman::where('no_resi', request()->get('search'))->orWhere('no_resi_manual', request()->get('search'))->orderBy('id_pengiriman', 'desc')->first();
            $data = M_tracking::getData($get_no_resi->id_pengiriman);
            // $data = M_tracking::where('pengiriman_id', $get_no_resi->id_pengiriman)->get();
            $res = [
                'keterangan' => 'Keterangan Paket',
                'data' => $data,
                'pengiriman' => $get_no_resi,
            ];
            if (count($data) < 0) {
                $res['status'] = false;
                return response()->json($res);
            } else {
                $res['status'] = true;
                return response()->json($res);
            }
        } catch (Throwable $e) {
            report($e);
            return response()->json($e);
        }
    }

    public function compro(){
        return view('Customer.compro.v_compro');
    }
}
