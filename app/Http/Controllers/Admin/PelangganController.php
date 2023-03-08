<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\M_pengguna;
use App\Models\M_cabang;
use App\Models\M_jabatan;
use App\Models\M_pelanggan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function __construct(Request $request)
    {
        $this->M_pelanggan = new M_pelanggan();
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            // 'cab' => M_cabang::getAll(),
            'jab' => M_jabatan::getJab(),
            'plg' => M_pelanggan::getAll()
        ];
        return view('Admin.Pelanggan.v_pelanggan', $data);
        // dd($data['usr']);
    }

    public function listPelanggan()
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
        $data = M_pelanggan::select('*')->orderBy('id_pelanggan', "asc");

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pelanggan.nama_perusahaan) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pelanggan.kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function addpelanggan(Request $request)
    {
        $this->validate($request, [
            'nama_perusahaan' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'nama_spv' => 'required',
            'tlp_spv' => 'required',
            'k_perusahaan' => 'required',
            'email_prshn' => ['required', 'string', 'email', 'max:255']
        ]);

        $data = new M_pelanggan();
        $data->nama_perusahaan = $request->get('nama_perusahaan');
        $data->kota = $request->get('kota');
        $data->alamat = $request->get('alamat');
        $data->nama_spv = $request->get('nama_spv');
        $data->tlp_spv = $request->get('tlp_spv');
        $data->k_perusahaan = $request->get('k_perusahaan');
        $data->email_prshn = $request->get('email_prshn');
        $data->created_by = Auth::user()->id;

        $data->save();

        return redirect()->back()->with('pesan', 'Data Berhasil Ditambah!!');
    }

    public function updatePelanggan(Request $request, $id_pelanggan)
    {
        $this->validate($request, [
            'nama_perusahaan' => 'required',
            'kota' => 'required',
            'alamat' => 'required',
            'nama_spv' => 'required',
            'tlp_spv' => 'required',
            'k_perusahaan' => 'required',
            'email_prshn' => ['required', 'string', 'email', 'max:255']
        ]);

        $data = M_pelanggan::find($id_pelanggan);
        $data->nama_perusahaan = $request->get('nama_perusahaan');
        $data->kota = $request->get('kota');
        $data->alamat = $request->get('alamat');
        $data->nama_spv = $request->get('nama_spv');
        $data->tlp_spv = $request->get('tlp_spv');
        $data->k_perusahaan = $request->get('k_perusahaan');
        $data->email_prshn = $request->get('email_prshn');
        $data->updated_by = Auth::user()->id;

        $data->update();

        return redirect()->back()->with('pesan', 'Data Berhasil Diupdate!!');
    }

    public function detailPelanggan()
    {
        $item =  $this->M_pelanggan->detailPelanggan(request()->input('id_pelanggan'));
        return $item;
    }

    public function deletePelanggan($id_pelanggan)
    {
        $item = M_pelanggan::findOrFail($id_pelanggan);
        $item->delete();
        return redirect()->back()->with('pesan', 'Data Berhasil Dihapus!!');
    }
}
