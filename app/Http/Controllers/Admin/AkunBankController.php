<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\M_jabatan;
use App\Models\M_akunBank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AkunBankController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            // 'bank' => M_akunBank::getAll(),
            'jab' => M_jabatan::getJab(),
        ];
        return view('Admin.Bank.v_akunbank', $data);
        // dd($data['bank']);
    }

    public function listBank()
    {
        // dd(request()->all());
        $columns = [
            'nama_bank',
            'id_bank',
            'no_rek',
            'an'
        ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = M_akunBank::select('*');

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(bank.nama_bank) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(bank.no_rek) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function addBank(Request $request)
    {
        $this->validate($request, [
            'nama_bank' => ['required', 'string', 'max:255', 'unique:bank'],
            'no_rek' => 'required',
            'an' => 'required',
        ]);

        $data = new M_akunBank();
        $data->nama_bank = $request->get('nama_bank');
        $data->no_rek = $request->get('no_rek');
        $data->an = $request->get('an');
        $data->created_by = Auth::user()->id;

        $data->save();

        return redirect()->back()->with('pesan', 'Data Berhasil Ditambah!!');
    }

    public function editBank(Request $request)
    {
        // dd(request()->all());
        $this->validate($request, [
            'nama_bank'  => 'required',
            'no_rek'  => 'required',
            'an'  => 'required',
        ]);

        $update = [
            'nama_bank' => request()->get('nama_bank'),
            'an'   => request()->get('an'),
            'no_rek' => request()->get('no_rek'),
            'updated_by' => Auth::user()->id,
        ];

        DB::table('bank')->where('id_bank', request()->get('id_bank'))
            ->update($update);
        return response()->json(true);
    }

    public function hapusBank()
    {
        // dd(request()->all());
        $item = M_akunBank::findOrFail(request()->input('id_bank'));
        $item->delete();
        return response()->json(true);
    }
}
