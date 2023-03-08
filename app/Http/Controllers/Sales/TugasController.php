<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\M_jabatan;
use App\Models\M_cabang;
use App\Models\M_detail_manifest;
use App\Models\M_pelanggan;
use App\Models\M_pengiriman;
use App\Models\M_manifest;
use App\Models\M_tracking;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        $totalOmset = M_pengiriman::getTotalOmsetTugas($id_cabang);
        $totalTransaksi = M_pengiriman::getTotalTransaksiTugas($id_cabang);
        $tonase = M_pengiriman::getTotalTonaseTugas($id_cabang);
        $jenis_kendaraan = M_manifest::getModa();

        $today = Carbon::now()->format('d-m-Y');
        $tgl = $today;
        return view('Sales.daftarTugas.v_daftarTugas', compact('jenis_kendaraan', 'jab', 'cab', 'id_cabang', 'totalOmset', 'totalTransaksi', 'tonase', 'tgl'));
    }

    public function listPengiriman($id_cabang)
    {
        $columns = [
            'id_pengiriman', 'no_resi', 'id_cabang', 'status_sent', 'id_pelanggan', 'nama_penerima', 'alamat_penerima', 'no_penerima',
            'isi_barang', 'berat', 'koli', 'no_ref', 'no_pelayanan', 'no_moda', 'biaya', 'tipe_pembayaran',
            'nama_pengirim', 'alamat_pengirim', 'tlp_pengirim', 'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];

        $data = (new M_pengiriman())->dataDaftarTugas($id_cabang);

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(cabang.nama_kota) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null && request()->input('sampai') != null) {
            $data = $data->whereBetween('pengiriman.tgl_masuk', [request()->dari, request()->sampai]);
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

    public function addManifest($id_cabang)
    {
        //        $id_cabang = $id_cabang;
        $kodeCab = M_cabang::getKode($id_cabang);

        $year = Carbon::now()->format('y');
        $month = Carbon::now()->format('m');

        $thnBln = $year . $month;

        $get = M_manifest::getTracking($id_cabang);
        if ($get == null) {
            $urut = '0001';
            $nomor = 'DM' . '-' . strtoupper($kodeCab->kode_area) . '-' . $thnBln . $urut;
        } else {
            $lastTrackingNoSequence = substr(
                $get->no_manifest,
                strlen($get->no_manifest) - 4,
                strlen($get->no_manifest)
            );
            $addedOne = (int) $lastTrackingNoSequence + 1;
            $urut = sprintf("%04s", $addedOne);
            $nomor = 'DM' . '-' . strtoupper($kodeCab->kode_area) . '-' . $thnBln . $urut;
        }

        echo $nomor;
    }

    public function insertManifest(Request $request)
    {
        $no_manifest = $request->no_manifest;

        $getm = M_manifest::query()
            ->where('no_manifest', '=', $no_manifest)
            ->get();

        if (count($getm) == 0) {
            $id_cabang = $request->id_cab;
            $driver = $request->driver;
            $no_tlp = $request->no_tlp;
            $nopol = $request->nopol;
            $jken = $request->jken;
            $id_pengiriman = $request->id;
            $tgl_buat = Carbon::today()->toDateString();

            $tujuan = $request->tujuan;
            $tujuan2 = "";

            $estimasi = $request->estimasi;
            $estimasi2 = date("Y-m-d", max(array_map('strtotime', $estimasi)));

            $i = count($tujuan);
            $ctujuan = [];
            foreach ($tujuan as $t) {
                $gettujuan = M_cabang::query()->where('nama_kota', '=', $t)->get();
                $ctujuan[] = $gettujuan[0]['id_cabang'];
                if ($i == 1) {
                    $tujuan2 .= $t;
                } else {
                    $tujuan2 .= $t . ' - ';
                }
                $i--;
            }

            $manifest = new M_manifest();
            $manifest->no_manifest = $no_manifest;
            $manifest->driver = $driver;
            $manifest->no_tlp_driver = $no_tlp;
            $manifest->nopol = $nopol;
            $manifest->jenis_kendaraan = $jken;
            $manifest->tujuan = $tujuan2;
            $manifest->estimasi_tiba = $estimasi2;
            $manifest->tgl_buat = $tgl_buat;
            $manifest->status = 0;
            $manifest->id_cabang = $id_cabang;
            $manifest->save();

            // $detail = [];
            $manifest_id = $manifest->id_manifest;
            foreach ($id_pengiriman as $idp) {
                $getpeng = M_pengiriman::find($idp);
                $getpeng->status_sent = 2;
                $getpeng->save();
                $ctujuan2 = $getpeng->id_cabang_tujuan;
                $idpeng = $getpeng->id_pengiriman;
                foreach ($ctujuan as $key => $value) {
                    $ntujuan = $tujuan[$key];
                    if ($ctujuan2 == $value) {
                        $estimasi2 = $estimasi[$key];
                        $detailm = new M_detail_manifest();
                        $detailm->pengiriman_id = $idpeng;
                        $detailm->manifest_id = $manifest_id;
                        $detailm->estimasi = $estimasi2;
                        $detailm->status = 0;
                        $detailm->save();

                        // Tracking
                        $get_tracking = M_tracking::where('pengiriman_id', $idpeng)->where('status_sent', 2)->where('cabang_id', $id_cabang)->orderBy('id_tracking', 'desc')->first();
                        if ($get_tracking == null) {
                            M_tracking::tambahTracking($idpeng, $getpeng->status_sent, $id_cabang, null, null);
                        }
                    }
                }
            }
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function showManifest()
    {
        // dd(request()->input('id'));
        $id_pengiriman = request()->get('id');
        $result = array();
        if (is_array(request()->get('id')) || is_object(request()->get('id'))) {
            foreach ($id_pengiriman as $key => $val) {
                $result[] = array(
                    'id_pengiriman'   => $_POST['id'][$key]
                );
            }
            // dd($result);
        }
        //MULTIPLE INSERT TO DETAIL TABLE
        // dd($result);
        $query = DB::table('pengiriman')
            ->select(
                'cabang.nama_kota as kota',
                'pengiriman.id_pengiriman as id'
            )
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->whereIn('pengiriman.id_pengiriman', $result)
            ->pluck('kota');

        // $item =  $this->M_trxUpload->detailwaktu_upload(request()->input('id_penelitian'));
        // return $item;
        // dd($query);
        return response()->json($query);
    }

    public function showFill($id_cabang)
    {
        if (request()->input('tgl_dari') != null || request()->input('tgl_sampai') != null) {
            $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->where('pengiriman.status_sent', 1)->count();
            $jumlah = M_pengiriman::select(DB::raw("SUM(biaya) as jumlah"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->where('pengiriman.status_sent', 1)->first();
            $berat = M_pengiriman::select(DB::raw("SUM(berat) as kg"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->where('pengiriman.status_sent', 1)->first();
            return [$dataWaktu, $jumlah, $berat];
        }
    }

    public function showFillAll($id_cabang)
    {
        if (request()->input('tgl_dari') != null && request()->input('tgl_sampai') != null) {
            $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->where('pengiriman.status_sent', 1)->count();
            $jumlah = M_pengiriman::select(DB::raw("SUM(biaya) as jumlah"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('pengiriman.status_sent', 1)->where('dari_cabang', $id_cabang)->first();
            $berat = M_pengiriman::select(DB::raw("SUM(berat) as kg"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('pengiriman.status_sent', 1)->where('dari_cabang', $id_cabang)->first();
            return [$dataWaktu, $jumlah, $berat];
        }
    }

    public function index_muat($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        $id_cabang = $id_cabang;
        return view('Sales.daftarMuat.v_muat', compact('cab', 'id_cabang', 'jab'));
    }

    public function listMuat()
    {
        $columns = [
            'id_manifest', 'no_manifest', 'driver', 'no_tlp_driver', 'nopol', 'jenis_kendaraan',
            'tujuan', 'estimasi_tiba', 'tgl_buat', 'status', 'id_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];

        $data = M_manifest::select([
            'manifest.id_manifest',
            'manifest.no_manifest',
            DB::raw('UPPER(manifest.driver) as driver'),
            'manifest.no_tlp_driver',
            DB::raw('UPPER(manifest.nopol) as nopol'),
            'manifest.jenis_kendaraan',
            DB::raw('UPPER(manifest.tujuan) as tujuan'),
            DB::raw("(DATE_FORMAT(manifest.estimasi_tiba,'%d %M %Y')) as estimasi_tiba"),
            DB::raw("(DATE_FORMAT(manifest.tgl_buat,'%d %M %Y')) as tgl_buat"),
            'manifest.status',
            'manifest.id_cabang',
        ])
            //            ->join('cabang', 'manifest.id_cabang', '=', 'cabang.id_cabang')
            // ->join('pelanggan', 'pelanggan.id_pelanggan', '=', 'pengiriman.id_pelanggan')
            //            ->join('moda', 'manifest.jenis_kendaraan', '=', 'moda.id_moda')
            ->orderBy('id_manifest', "desc");

        $recordsFiltered = $data->get()->count();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(manifest.no_manifest) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(manifest.driver) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(manifest.tujuan) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function detailManifest($id_manifest)
    {
        $id_cab = Auth::user()->id_cabang;
        $getman = M_manifest::query()->find($id_manifest);
        $noman = $getman->no_manifest;
        $driver = $getman->driver;
        $notlp = $getman->no_tlp_driver;
        $nopol = $getman->nopol;
        $jeken = $getman->jenis_kendaraan;
        $cab = $getman->id_cabang;
        $getDet = M_detail_manifest::query()
            ->where('manifest_id', '=', $id_manifest)
            ->orderBy('pengiriman_id', "ASC")
            ->get();
        $result = [];
        $status = [];
        foreach ($getDet as $key => $val) {
            $result[] = array(
                'pengiriman_id'   => $val['pengiriman_id']
            );
            $status[] = $val->status;
        }
        $query = DB::table('pengiriman')
            ->select(
                'cabang.nama_kota as kota',
                'pengiriman.id_pengiriman as id',
            )
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->whereIn('pengiriman.id_pengiriman', $result)
            ->orderBy('pengiriman.id_cabang_tujuan', "ASC")
            ->pluck('kota');

        $query2 = DB::table('pengiriman')
            ->select(
                'pengiriman.id_pengiriman as id',
                'pengiriman.id_cabang_tujuan as tujuan_id'
            )
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->whereIn('pengiriman.id_pengiriman', $result)
            ->orderBy('tujuan_id','ASC')
            ->pluck('tujuan_id');

        $data = [
            'no_manifest' => $noman,
            'driver' => $driver,
            'no_tlp_driver' => $notlp,
            'nopol' => $nopol,
            'jeken' => $jeken,
            'query' => $query,
            'tujuan_id' => $query2,
            'status_sent' => $status,
            'id_cab' => $id_cab,
            'cab' => $cab,
        ];
        return response()->json($data);
    }

    public function editdetailManifest(Request $request)
    {
        $cab_id = Auth::user()->id_cabang;
        $id_manifest = $request->id_manifest;
        $all_tujuan = $request->all_tujuan;
        $all_sampai = $request->all_sampai;
        $all_transit = $request->all_transit;
        $tgl_tiba = Carbon::today()->toDateString();

        $j_sampai = $request->j_sampai;
        $j_transit = $request->j_transit;

        $transit_id = [];
        $sampai_id = [];
        foreach ($all_tujuan as $key => $t) {
            $gettujuan = M_cabang::query()->where('nama_kota', '=', $t)->get();
            $id_cab = $gettujuan[0]['id_cabang'];
            $ctujuan[] = $id_cab;
            if ($j_transit > 0) {
                foreach ($all_transit as $tr) {
                    if ($key == $tr) {
                        $transit_id[] = $id_cab;
                    }
                }
            }
            if ($j_sampai > 0) {
                foreach ($all_sampai as $sa) {
                    if ($key == $sa) {
                        $sampai_id[] = $id_cab;
                    }
                }
            }
        }

        $getman = M_detail_manifest::query()
            ->where('manifest_id', '=', $id_manifest)
            ->get();
        foreach ($getman as $det) {
            $id_pengiriman = $det->pengiriman_id;
            $getpeng = M_pengiriman::query()->find($id_pengiriman);
            $tujuan = $getpeng->id_cabang_tujuan;
            if ($j_transit > 0) {
                foreach ($transit_id as $tran) {
                    if ($tran == $tujuan) {
                        $getpeng->status_sent = 3;
                        $getpeng->id_transit = $cab_id;
                        $getpeng->save();
                        $det->status = 2;
                        $det->save();
                        $get_new_status = 3;
                    }
                }
            }
            if ($j_sampai > 0) {
                foreach ($sampai_id as $samp) {
                    if ($samp == $tujuan) {
                        $getpeng->status_sent = 4;
                        $getpeng->tgl_tiba = $tgl_tiba;
                        $getpeng->save();
                        $det->status = 1;
                        $det->save();
                        $get_new_status = 4;
                    }
                }
            }
            M_tracking::tambahTracking($id_pengiriman, $get_new_status, Auth::user()->id_cabang, null, null);
        }
        $getmani = M_manifest::query()->find($id_manifest);
        $getmani->status = 1;
        $getmani->save();

        return response()->json(true);
    }

    public function index_ecer($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        $id_cabang = $id_cabang;
        return view('Sales.daftarEceran.v_daftarEceran', compact('cab', 'id_cabang', 'jab'));
    }

    public function listEcer($id_cabang)
    {
        $columns = [
            'no_resi', 'nama_pengirim', 'nama_penerima', 'pengiriman.no_resi_manual'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = (new M_pengiriman())->dataTables($id_cabang);

        $recordsFiltered = $data->get()->count();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function detailEcer($id_pengiriman)
    {
        $id_cab = Auth::user()->id_cabang;
        $getpeng = M_pengiriman::query()->find($id_pengiriman);
        $no_resi = $getpeng->no_resi;
        $pengirim = $getpeng->nama_pengirim;
        $penerima = $getpeng->nama_penerima;
        $alamat1 = $getpeng->alamat_pengirim;
        $alamat2 = $getpeng->alamat_penerima;
        $status = $getpeng->status_sent;
        $ket = $getpeng->ket;
        $fb = $getpeng->file_bukti;

        $cab_kir = $getpeng->dari_cabang;
        $cab_kir2 = M_cabang::query()->find($cab_kir);
        $cab_ter = $getpeng->id_cabang_tujuan;
        $cab_ter2 = M_cabang::query()->find($cab_ter);

        $stats = DB::table('tbl_status_pengiriman')
            ->select('*')
            ->where('id_stst_pngrmn', '=', 4)
            ->orWhere('id_stst_pngrmn', '=', 5)
            ->orWhere('id_stst_pngrmn', '=', 6)
            ->orWhere('id_stst_pngrmn', '=', 8) // Pengiriman ulang
            ->get();

        $option = "<option value='' >--- Pilih Status ---</option>";
        foreach ($stats as $s) {
            if ($s->id_stst_pngrmn == $status) {
                $option .= "<option value='$s->id_stst_pngrmn' selected>$s->nama_status</option>";
            } else {
                $option .= "<option value='$s->id_stst_pngrmn'>$s->nama_status</option>";
            }
        }

        $data = [
            'peng' => $getpeng,
            'no_resi' => $no_resi,
            'n_pengirim' => $pengirim,
            'n_penerima' => $penerima,
            'alamat_kir' => $alamat1,
            'alamat_ter' => $alamat2,
            'cab_kir' => $cab_kir2,
            'cab_ter' => $cab_ter2,
            'stat' => $option,
            'id_cab' => $id_cab,
            'ket' => $ket,
            'file_bukti' => $fb,
        ];
        return response()->json($data);
    }

    public function editdetailEcer(Request $request)
    {
        $this->validate($request, [
            "id_peng" => 'required',
            "stat" => 'required',
            'upload' => 'required|file|image|mimes:jpeg,png,jpg',
        ]);

        $id_pengiriman = $request->get('id_peng');

        $data = M_pengiriman::query()->find($id_pengiriman);
        $data->status_sent = $request->get('stat');
        $file = $request->file('upload');

        $nama_file = time() . "_" . $file->getClientOriginalName();

        // isi dengan nama folder tempat kemana file diupload
        $tujuan_upload = 'foto_bukti';
        $file->move($tujuan_upload, $nama_file);
         $data->file_bukti = $nama_file;
         if ($request->has('keterangan') || $request->get('keterangan') != null) {
             $data->ket = $request->get('keterangan');
         }
        $get_tracking = M_tracking::where('pengiriman_id', $id_pengiriman)->where('status_sent', 4)->orderBy('id_tracking', 'desc')->first();
        if ($get_tracking !== null && $request->get('stat') != 4) {
            M_tracking::tambahTracking($id_pengiriman, $request->get('stat'), Auth::user()->id_cabang, $nama_file, $request->get('keterangan'));
        } else {
            M_tracking::tambahTracking($id_pengiriman, $request->get('stat'), Auth::user()->id_cabang, $nama_file, $request->get('keterangan'));
        }

        $data->save();

        return redirect()->back()->with('pesan', 'Data Berhasil Diubah!!');
    }

    public function index_selesai($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        $id_cabang = $id_cabang;
        return view('Sales.daftarSelesai.v_daftarSelesai', compact('cab', 'id_cabang', 'jab'));
    }

    public function listSelesai($id_cabang)
    {
        $columns = [
            'id_pengiriman', 'tgl_tiba', 'no_resi', 'nama_pengirim', 'nama_penerima', 'id_cabang', 'status_sent',
        ];

        $orderBy = $columns[request()->input("order.0.column")];

        $data = M_pengiriman::select([
            // '*'
            'pengiriman.id_pengiriman',
            'pengiriman.no_resi',
            'pengiriman.id_cabang_tujuan',
            DB::raw("(DATE_FORMAT(pengiriman.tgl_tiba,'%d %M %Y')) as tgl_tiba"),
            'pengiriman.status_sent',
            DB::raw('UPPER(pengiriman.nama_penerima) as nama_penerima'),
            DB::raw('UPPER(cabang.nama_kota) as nama_kota'),
            DB::raw('UPPER(pengiriman.nama_pengirim) as nama_pengirim'),
        ])
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->where(function ($q) use ($id_cabang) {
                $q->where('pengiriman.dari_cabang', $id_cabang);
                $q->orWhere('pengiriman.id_cabang_tujuan', $id_cabang);
            })
            ->where('pengiriman.status_sent', 6)
            ->orderBy('id_pengiriman', "desc");

        $recordsFiltered = $data->get()->count();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function export_pdf()
    {
        $explode_id = array_map('intval', explode(',', request()->get('id_muat')));
        $items['pengiriman'] = M_manifest::find($explode_id);
        $pdf = PDF::loadView('Sales.daftarMuat.v_cetakPDF', $items)->setPaper('a4', 'landscape');
        return $pdf->stream('Cetak Daftar Muat.pdf');
        // dd($items);
    }
}
