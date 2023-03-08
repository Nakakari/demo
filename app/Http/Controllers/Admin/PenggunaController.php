<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pengguna\PenggunaRequest;
use Illuminate\Http\Request;
use App\Models\M_pengguna;
use App\Models\M_cabang;
use App\Models\M_jabatan;
use App\Models\M_member_sales;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Stmt\TryCatch;
use Ramsey\Uuid\Uuid;
use Throwable;

class PenggunaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->jab = new M_jabatan();
        $this->cab = new M_cabang();
        $this->pengguna = new M_pengguna();
    }

    public function index()
    {
        $data = [
            'cab' => $this->cab->getAll(),
            'jab' => $this->jab->getJab(),
            'usr' => $this->pengguna->getAll(),
        ];
        return view('Admin.Pengguna.v_akun', $data);
    }

    public function jenisJabatan()
    {
        $columns = [
            'nama_jabatan',
        ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = M_jabatan::select('*');
        // $data2 = M_cabang::select('*');

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(cabang.nama_kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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
            // 'data2' => $data2,
            'all_request' => request()->all()
        ]);
    }

    public function listPengguna()
    {
        $columns = [
            'name',
            'id',
        ];
        $orderBy = $columns[request()->input("order.0.column")];
        $data = M_pengguna::select([
            'users.uuid',
            'users.name',
            'users.peran',
            'users.email',
            'users.password',
            'users.file_foto',
            'users.tgl_lhr',
            'users.alamat',
            'users.id_cabang',
            'users.id',
            'cabang.nama_kota',
            'cabang.kode_area',
            'jabatan.nama_jabatan'
        ])
            ->join('cabang', 'users.id_cabang', '=', 'cabang.id_cabang')
            ->join('jabatan', 'users.peran', '=', 'jabatan.id_jabatan')
            ->orderBy('id', "asc");

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(cabang.nama_kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(cabang.kode_area) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(users.name) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(users.alamat) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(users.email) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(jabatan.nama_jabatan) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        $data = $data
            ->skip(request()->input('start'))
            ->take(request()->input('length'))
            ->orderBy($orderBy, request()->input("order.0.dir"))
            ->get();
        $recordsTotal = $data->count();

        // dd($data);

        return response()->json([
            'draw' => request()->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'all_request' => request()->all()
        ]);
    }

    public function kodeArea(Request $request)
    {
        $data = M_cabang::where('id_cabang', $request->get('id_cabang'))
            ->pluck('kode_area', 'id_cabang');
        return response()->json($data);
    }

    public function kodeAreaEdited(Request $request)
    {
        $data = M_cabang::where('id_cabang', $request->get('id_cabangEdited'))
            ->pluck('kode_area', 'id_cabang');
        return response()->json($data);
    }

    public function addPengguna(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            "id_cabang" => 'required',
            "tgl_lhr" => 'required',
            "alamat" => 'required',
            "id_jabatan" => 'required',
            'file_foto' => 'required|file|image|mimes:jpeg,png,jpg',
        ]);
        if (request()->get('id_jabatan') == 5) {
            $user = $user->where('id_cabang', request()->get('id_cabang'))->where('peran', 5)->first();
            if ($user === null) {
                $data = new M_pengguna();
                $data->uuid = Uuid::uuid4()->toString();
                $data->name = $request->get('name');
                $data->password = Hash::make($request->get('password'));
                $data->email = $request->get('email');
                $data->id_cabang = $request->get('id_cabang');
                $data->tgl_lhr = $request->get('tgl_lhr');
                $data->alamat = $request->get('alamat');
                $data->peran = $request->get('id_jabatan');
                $file = $request->file('file_foto');
                $data->created_by = Auth::user()->id;

                $nama_file = time() . "_" . $file->getClientOriginalName();

                // isi dengan nama folder tempat kemana file diupload
                $tujuan_upload = 'foto_pengguna';
                $file->move($tujuan_upload, $nama_file);
                $data->file_foto = $nama_file;

                $data->save();

                $message = 'pesan';
                $message_info = 'Data Berhasil Disimpan';
            } else {
                $message = 'gagal';
                $message_info = 'Data Telah Ada';
            }
        } else {
            $data = new M_pengguna();
            $data->uuid = Uuid::uuid4()->toString();
            $data->name = $request->get('name');
            $data->password = Hash::make($request->get('password'));
            $data->email = $request->get('email');
            $data->id_cabang = $request->get('id_cabang');
            $data->tgl_lhr = $request->get('tgl_lhr');
            $data->alamat = $request->get('alamat');
            $data->peran = $request->get('id_jabatan');
            $file = $request->file('file_foto');
            $data->created_by = Auth::user()->id;

            $nama_file = time() . "_" . $file->getClientOriginalName();

            // isi dengan nama folder tempat kemana file diupload
            $tujuan_upload = 'foto_pengguna';
            $file->move($tujuan_upload, $nama_file);
            $data->file_foto = $nama_file;

            $data->save();

            $message = 'pesan';
            $message_info = 'Data Berhasil Disimpan';
        }
        return redirect()->back()->with($message, $message_info);
    }

    public function editPengguna(string $uuid, User $user)
    {
        $user = $user->where('uuid', $uuid)->first();
        $jab =  $this->jab->getJab();
        $cabang = $this->cab->getAll();
        return view('Admin.Pengguna.v_edit_pengguna', compact('user', 'jab', 'cabang'));
    }

    public function updatePengguna(PenggunaRequest $request, string $uuid, User $user)
    {
        $data = $request->validated();
        $user = $user->where('uuid', $uuid)->first();
        $form = array_merge($data, [
            'updated_by' => Auth::user()->id,
        ]);
        try {
            if (isset($data['file_foto'])) {
                $declare_path = 'foto_pengguna/';
                \File::exists(public_path($declare_path . $user['file_foto'])) ?: \File::delete(public_path($declare_path . $user['file_foto']));
                $file = $data['file_foto'];
                $file_name = time() . "_" . $file->getClientOriginalName();
                $file->move($declare_path, $file_name);
                $form['file_foto'] = $file_name;
                $form['password'] = Hash::make($form['password']);
            }
            $form['password'] = Hash::make($form['password']);
            $user = $user->where('uuid', $uuid)->update($form);

            $message = 'pesan';
            $message_info = 'Data Berhasil Diupdate';
        } catch (Throwable $e) {
            report($e);
            $message = 'gagal';
            $message_info = $e;
        }
        return redirect()->route('pengguna')->with($message, $message_info);
    }

    public function hapusPengguna(User $user)
    {
        $id = request()->get('id_user');

        $user->where('id', '=', $id)->delete();
        return response()->json(true);
    }

    public function addMemberSales(string $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $jab =  $this->jab->getJab();
        return view('Admin.Pengguna.v_tambah_member_sales', compact('user', 'jab', 'uuid'));
    }

    public function listMemberSales(string $uuid)
    {
        $jab =  $this->jab->getJab();
        return view('Admin.Pengguna.v_list_member_sales', compact('jab', 'uuid'));
    }

    public function dataMemberSales(string $uuid)
    {
        $columns = [
            'nama',
            'kode'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_member_sales())->dataTables($uuid);

        // kiro2 bos kodingan bawah iki iso diringkes neh ng model ora yo?
        $recordsFiltered = $data->get()->count();
        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(member_sales.nama) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(member_sales.kode) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function saveMemberSales(Request $request, string $uuid)
    {
        $this->validate($request, [
            'kode' => ['required', 'unique:member_sales', 'max:255'],
            'id_sales' => ['required'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $data = new M_member_sales();
        $data->uuid = Uuid::uuid4()->toString();
        $data->kode = $request->kode;
        $data->nama = $request->nama;
        $data->id_sales = $request->id_sales;
        $data->created_by = Auth::user()->id;
        $data->save();
        return redirect()->route('pengguna.list-member', $uuid)->with('pesan', 'Berhasil');
    }

    public function editMemberSales(string $uuid)
    {
        $member = M_member_sales::where('uuid', $uuid)->first();
        $jab =  $this->jab->getJab();
        return view('Admin.Pengguna.v_edit_member_sales', compact('member', 'jab', 'uuid'));
    }

    public function updateMemberSales(string $uuid, Request $request, M_member_sales $member)
    {
        $this->validate($request, [
            'kode' => ['required', 'max:255'],
            'id_sales' => ['required'],
            'nama' => ['required', 'string', 'max:255'],
        ]);

        $form = [
            'kode' => $request->kode,
            'id_sales' => $request->id_sales,
            'nama' => $request->nama,
            'updated_by' => Auth::user()->id,
        ];
        try {
            $member = $member->where('uuid', $uuid)->update($form);
            $message = 'pesan';
            $message_info = 'Data Berhasil Diupdate';
        } catch (Throwable $e) {
            report($e);
            $message = 'gagal';
            $message_info = $e;
        }
        return redirect()->route('pengguna.edit-member', $uuid)->with($message, $message_info);
    }

    public function destroyMemberSales(M_member_sales $member)
    {
        $id = request()->get('id_member_sales');

        $member->where('id_member_sales', '=', $id)->delete();
        return response()->json(true);
    }
}
