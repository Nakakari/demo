<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Requests\MuatEceranRequest;
use App\Models\M_cabang;
use App\Models\M_detailMuatEceran;
use App\Models\M_jabatan;
use App\Models\M_muatEceran;
use App\Models\M_pengiriman;
use App\Models\M_resi_identitas;
use App\Models\M_status_pengiriman;
use App\Models\M_tracking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class DaftarEceranController extends Controller
{
    public function ecerMasal($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        $id_cabang = $id_cabang;
        return view('Sales.daftarEceran.v_daftarMasal', compact('cab', 'id_cabang', 'jab'));
    }

    public function listMasal($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $columns = [
            'driver', 'nopol', 'created_at', 'created_by'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_muatEceran())->dataTables($id_cabang);
        //dd($data);
        $recordsFiltered = $data->get()->count();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(muat_eceran.driver) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(muat_eceran.nopol) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }
        $data = $data
            ->skip(request()->input('start'))
            ->take(request()->input('length'))
            ->orderBy($orderBy, request()->input("order.0.dir"))
            ->get();
        $recordsTotal = $data->count();
        return response()->json([
            'draw' => request()->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'all_request' => request()->all()
        ]);
    }

    public function getStatusPengiriman(M_status_pengiriman $status_pengiriman)
    {
        $status_pengiriman = $status_pengiriman->statusEceran();
        $option = "<option disabled selected>--- Pilih Status ---</option>";
        foreach ($status_pengiriman as $s) {
            $option .= "<option value='$s->id_stst_pngrmn'>$s->nama_status</option>";
        }
        return response()->json([
            'status_pengiriman' => $option,
        ]);
    }

    public function store(MuatEceranRequest $request)
    {
        //        dd(request()->all());
        $data = $request->validated();
        $form = array_merge($data, [
            'created_by' => Auth::user()->id,
        ]);
        $explode_id = request()->input('ids');

        $resi_identitas = M_resi_identitas::query()
            ->whereIn('id_pengiriman', $explode_id)->get();

        try {
            DB::transaction(function () use ($form, $resi_identitas, $explode_id): void {
                $form['status_update'] = $form['status_update'] == 5 ? 9 : $form['status_update'];
                $muat_eceran = M_muatEceran::query()->create($form);
                M_pengiriman::query()->whereIn('id_pengiriman', $explode_id)->update([
                    'status_sent' => $form['status_update'],
                ]);
                foreach ($resi_identitas as $ri) {
                    $identitas_id = $ri->id_identitas;
                    DB::table('detail_muat_eceran')->insert([
                        'muat_eceran_id' => $muat_eceran->id_muat_eceran,
                        'identitas_id' => $identitas_id,
                        'status_sent' => $form['status_update'],
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString(),
                    ]);
                }
                M_tracking::tambahTracking($explode_id, $form['status_update'], Auth::user()->id_cabang, null, null);
            });
            $message = true;
        } catch (Throwable $e) {
            report($e);
            $message = $e;
        }
        return response()->json($message);
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
