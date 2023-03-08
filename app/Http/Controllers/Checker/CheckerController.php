<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\M_cabang;
use App\Models\M_detail_manifest;
use App\Models\M_detailkoli;
use App\Models\M_jabatan;
use App\Models\M_kolicek;
use App\Models\M_manifest;
use App\Models\M_pengiriman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->M_pengiriman = new M_pengiriman();
    }

    public function index($id_cabang, Request $request)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Checker.cek_naik.v_identitas', compact('id_cabang','cab','jab'));
    }

    public function list_index($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $columns = [
            'id_kolicek',
            'nopol',
            'driver',
            'tgl_buat',
            'jumlah_resi',
            'jumlah_koli',
        ];

        $orderBy = $columns[request()->input("order.0.column")];
//        \DB::enableQueryLog();
        $data = DB::table('koli_cek')
            ->select(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi',
                'koli_cek.jumlah_koli',
//                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi',
                'koli_cek.jumlah_koli',
//                'resi_identitas.id_pengiriman',
            )
            ->where('koli_cek.id_cabang','=',$id_cabang)
            ->orderBy('koli_cek.id_kolicek', "DESC")
        ;
//        ->get();
//        dd($data);
//        dd(\DB::getQueryLog());
        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(koli_cek.nopol) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(koli_cek.driver) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function resinaik($id_cek, Request $request)
    {
        $id_cabang = auth()->user()->id_cabang;
        $id_cek = base64_decode($id_cek);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Checker.cek_naik.v_resi', compact('cek', 'id_cabang', 'id_cek', 'cab', 'jab'));
    }

    public function list_resi($id_cek)
    {
        $id_cek = base64_decode($id_cek);
        $columns = [
            'id_detail_koli',
            'identitas_id',
            'koli_id',
            'cabang_id',
            'created_by',
            'updated_by',
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        //        \DB::enableQueryLog();
        $data = DB::table('detail_koli')
            ->join('koli_cek', 'koli_cek.id_kolicek', '=', 'detail_koli.koli_id')
            ->join('resi_identitas', 'detail_koli.identitas_id', '=', 'resi_identitas.id_identitas')
            ->join('pengiriman', 'pengiriman.id_pengiriman', '=', 'resi_identitas.id_pengiriman')
            ->select(
                DB::raw("COUNT(IF((resi_identitas.id_pengiriman = pengiriman.id_pengiriman) AND (detail_koli.koli_id = koli_cek.id_kolicek), 1, NULL)) as jidn"),
                'detail_koli.koli_id',
                'pengiriman.id_pengiriman',
                'pengiriman.no_resi',
            )
            ->groupBy(
                'detail_koli.koli_id',
                'pengiriman.id_pengiriman',
                'pengiriman.no_resi',
            )
            ->where('detail_koli.koli_id', '=', $id_cek)
            ->orderBy('pengiriman.id_pengiriman', "ASC");
        //        ->get();
        //        dd($data);
        //        dd(\DB::getQueryLog());
        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(detail_koli.identitas_id) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(resi_identitas.nama_identitas) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function insertNaik(Request $request)
    {
        $driver = $request->driver;
        $nopol = $request->nopol;
        $tgl_buat = Carbon::today()->toDateString();

        $koli = new M_kolicek();
        $koli->driver = $driver;
        $koli->nopol = $nopol;
        $koli->tgl_buat = $tgl_buat;
        $koli->id_cabang = auth()->user()->id_cabang;
        $koli->created_by = auth()->user()->id;
        $koli->save();

        return response()->json(true);
    }

    public function scan($id_cek)
    {
        $id_cek = base64_decode($id_cek);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        return view('Checker.cek_naik.v_scan', compact('cek', 'cab', 'jab'));
    }

    public function cekIdentitas($id_cek, $nama_identitas)
    {
        $sentence = $nama_identitas;
        $word = "koli";
        $pos = strpos($sentence, $word);

        $koli_id = $id_cek;
        $created_by = auth()->user()->id;
        $cabang_id = auth()->user()->id_cabang;

        if ($pos !== false) { //jika scan nya data koli
            $identitas = M_pengiriman::cekIdentitas($sentence);


            if ($identitas === null) {
                $info = "gagal";
                $pesan = "Whops :( Data tidak ada!";
            } else {
                $identitas_id = $identitas->id_identitas;
                $getdetail = M_detailkoli::query()
                    ->where('cabang_id', '=', $cabang_id)
                    ->where('identitas_id', '=', $identitas_id)
                    ->where('koli_id', '=', $koli_id)
                    ->get();
                $hget = count($getdetail);

                if ($hget == 0) {
                    $koli = new M_detailkoli();
                    $koli->koli_id = $koli_id;
                    $koli->identitas_id = $identitas_id;
                    $koli->cabang_id = $cabang_id;
                    $koli->created_by = $created_by;
                    $koli->save();
                    $info = "pesan";
                    $pesan = "Selamat! Data Identitas Resi berhasil diupdate.";

                    //hitung resi
                    $resi = [];
                    $getresi = M_detailkoli::query()
                        ->where('koli_id', '=', $id_cek)->get();
                    foreach ($getresi as $gr) {
                        $id_identitas = $gr->identitas_id;
                        $getresi2 = DB::table('resi_identitas')
                            ->where('id_identitas', '=', $id_identitas)->first();
                        $resi[] = $getresi2->id_pengiriman;
                    }
                    $clear_array = array_unique($resi);
                    $hresi = count($clear_array);

                    //hitung koli
                    $getkoli = M_detailkoli::query()
                        ->where('koli_id', '=', $id_cek)->get();
                    $hkoli = count($getkoli);

                    $resikolicek = M_kolicek::query()->find($id_cek);
                    $resikolicek->jumlah_resi = $hresi;
                    $resikolicek->jumlah_koli = $hkoli;
                    $resikolicek->save();
                } else {
                    $info = "pesan";
                    $pesan = "Data Identitas Resi Sudah Pernah Berhasil di Scan.";
                }
            }
        } else { //jika yang di scan resi
            $dataresi = M_pengiriman::query()
                ->where('no_resi', '=', $sentence)->first();

            if ($dataresi === null) {
                $info = "gagal";
                $pesan = "Whops :( Data tidak ada!";
            } else {
                $idpeng = $dataresi->id_pengiriman;

                $dataidentitas = DB::table('resi_identitas')
                    ->where('id_pengiriman', '=', $idpeng)->get();
                $data = [];
                foreach ($dataidentitas as $di) {
                    $ididen = $di->id_identitas;
                    $getdetail = M_detailkoli::query()
                        ->where('cabang_id', '=', $cabang_id)
                        ->where('identitas_id', '=', $ididen)
                        ->where('koli_id', '=', $koli_id)
                        ->get();
                    $hget = count($getdetail);

                    if ($hget == 0) {
                        $koli = new M_detailkoli();
                        $koli->koli_id = $koli_id;
                        $koli->identitas_id = $ididen;
                        $koli->cabang_id = $cabang_id;
                        $koli->created_by = $created_by;
                        $koli->save();
                        $info = "pesan";
                        $pesan = "Selamat! Data Identitas Resi berhasil diupdate.";

                        //hitung resi
                        $resi = [];
                        $getresi = M_detailkoli::query()
                            ->where('koli_id', '=', $id_cek)->get();
                        foreach ($getresi as $gr) {
                            $id_identitas = $gr->identitas_id;
                            $getresi2 = DB::table('resi_identitas')
                                ->where('id_identitas', '=', $id_identitas)->first();
                            $resi[] = $getresi2->id_pengiriman;
                        }
                        $clear_array = array_unique($resi);
                        $hresi = count($clear_array);

                        //hitung koli
                        $getkoli = M_detailkoli::query()
                            ->where('koli_id', '=', $id_cek)->get();
                        $hkoli = count($getkoli);

                        $resikolicek = M_kolicek::query()->find($id_cek);
                        $resikolicek->jumlah_resi = $hresi;
                        $resikolicek->jumlah_koli = $hkoli;
                        $resikolicek->save();
                    } else {
                        $info = "pesan";
                        $pesan = "Data Identitas Resi Sudah Pernah Berhasil di Scan.";
                    }
                }
            }
        }

        $idcek2 = base64_encode($id_cek);
        return redirect('/scan_identitas/' . $idcek2)->with($info, $pesan);
    }

    public function detail($id_cek, $id_pengiriman, Request $request)
    {
        $id_cabang = auth()->user()->id_cabang;
        $id_cek = base64_decode($id_cek);
        $id_pengiriman = base64_decode($id_pengiriman);
        $cek2 = M_pengiriman::query()->find($id_pengiriman);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Checker.cek_naik.v_detail', compact('cek2','cek', 'id_cabang', 'id_pengiriman', 'id_cek', 'cab', 'jab'));
    }

    public function list_detail($id_cek, $id_pengiriman)
    {
        $id_cek = base64_decode($id_cek);
        $id_pengiriman = base64_decode($id_pengiriman);
        $columns = [
            'id_detail_koli',
            'identitas_id',
            'koli_id',
            'cabang_id',
            'created_by',
            'updated_by',
        ];

        $orderBy = $columns[request()->input("order.0.column")];
//        \DB::enableQueryLog();
        $data = DB::table('detail_koli')
            ->join('resi_identitas', 'detail_koli.identitas_id','=','resi_identitas.id_identitas')
            ->select(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
//                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
//                'resi_identitas.id_pengiriman',
            )
            ->where('resi_identitas.id_pengiriman', '=', $id_pengiriman)
            ->where('detail_koli.koli_id', '=', $id_cek)
            ->orderBy('resi_identitas.id_identitas', "ASC")
        ;
//        ->get();
//        dd($data);
//        dd(\DB::getQueryLog());
        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(detail_koli.identitas_id) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(resi_identitas.nama_identitas) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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


    public function index2($id_cabang, Request $request)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Checker.cek_turun.v_identitas', compact('id_cabang','cab','jab'));
    }

    public function list_index2($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $columns = [
            'id_kolicek',
            'nopol',
            'driver',
            'tgl_buat',
            'jumlah_resi2',
            'jumlah_koli2',
        ];

        $orderBy = $columns[request()->input("order.0.column")];
//        \DB::enableQueryLog();
        $data = DB::table('koli_cek')
            ->select(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi2',
                'koli_cek.jumlah_koli2',
//                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi2',
                'koli_cek.jumlah_koli2',
//                'resi_identitas.id_pengiriman',
            )
//            ->whereRaw('FIND_IN_SET(?, id_cabang2)', [$id_cabang])
            ->orderBy('koli_cek.id_kolicek', "DESC")
        ;
//        ->get();
//        dd($data);
//        dd(\DB::getQueryLog());
        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(koli_cek.nopol) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(koli_cek.driver) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function resiturun($id_cek, Request $request)
    {
        $id_cabang = auth()->user()->id_cabang;
        $id_cek = base64_decode($id_cek);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Checker.cek_turun.v_resi', compact('cek', 'id_cabang', 'id_cek', 'cab', 'jab'));
    }

    public function list_resi2($id_cek)
    {
        $id_cek = base64_decode($id_cek);
        $columns = [
            'id_detail_koli',
            'identitas_id',
            'koli_id',
            'cabang_id',
            'created_by',
            'updated_by',
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        //        \DB::enableQueryLog();
        $data = DB::table('detail_koli')
            ->join('koli_cek', 'koli_cek.id_kolicek', '=', 'detail_koli.koli_id')
            ->join('resi_identitas', 'detail_koli.identitas_id', '=', 'resi_identitas.id_identitas')
            ->join('pengiriman', 'pengiriman.id_pengiriman', '=', 'resi_identitas.id_pengiriman')
            ->select(
                DB::raw("COUNT(IF((resi_identitas.id_pengiriman = pengiriman.id_pengiriman) AND (detail_koli.koli_id = koli_cek.id_kolicek), 1, NULL)) as jidn"),
                'detail_koli.koli_id',
                'pengiriman.id_pengiriman',
                'pengiriman.no_resi',
            )
            ->groupBy(
                'detail_koli.koli_id',
                'pengiriman.id_pengiriman',
                'pengiriman.no_resi',
            )
            ->where('detail_koli.koli_id','=',$id_cek)
            ->where('detail_koli.updated_by','!=',null)
            ->orderBy('pengiriman.id_pengiriman', "ASC");
        //        ->get();
        //        dd($data);
        //        dd(\DB::getQueryLog());
        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(detail_koli.identitas_id) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(resi_identitas.nama_identitas) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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

    public function scan2($id_cek)
    {
        $id_cek = base64_decode($id_cek);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        //        dd($cek);

        return view('Checker.cek_turun.v_scan', compact('cek', 'cab', 'jab'));
    }

    public function cekIdentitas2($id_cek, $nama_identitas)
    {
        $sentence = $nama_identitas;
        $word = "koli";
        $pos = strpos($sentence, $word);

        $koli_id = $id_cek;
        $updated_by = auth()->user()->id;
        $cabang_id = auth()->user()->id_cabang;

        if ($pos !== false) { //jika scan nya data koli
            $identitas = M_pengiriman::cekIdentitas($sentence);
            if ($identitas === null) {
                $info = "gagal";
                $pesan = "Whops :( Data tidak ada!";
            } else {
                $identitas_id = $identitas->id_identitas;

                $getdetail = M_detailkoli::query()
                    ->where('cabang_id2', '=', $cabang_id)
                    ->where('identitas_id', '=', $identitas_id)
                    ->where('koli_id', '=', $koli_id)
                    ->get();
                $hget = count($getdetail);

                if ($hget == 0) {
                    $dkoli = M_detailkoli::query()
                        ->where('identitas_id', '=', $identitas_id)
                        ->where('koli_id', '=', $koli_id)
                        ->first();
                    $dkoli->cabang_id2 = $cabang_id;
                    $dkoli->updated_by = $updated_by;
                    $dkoli->save();
                    $info = "pesan";
                    $pesan = "Selamat! Data Identitas Resi berhasil diupdate.";

                    //hitung resi
                    $resi = [];
                    $getresi = M_detailkoli::query()
                        ->where('koli_id', '=', $id_cek)
                        ->where('cabang_id2', '!=', null)
                        ->get();
                    foreach ($getresi as $gr) {
                        $id_identitas = $gr->identitas_id;
                        $getresi2 = DB::table('resi_identitas')
                            ->where('id_identitas', '=', $id_identitas)->first();
                        $resi[] = $getresi2->id_pengiriman;
                    }
                    $clear_array = array_unique($resi);
                    $hresi = count($clear_array);

                    //hitung koli
                    $getkoli = M_detailkoli::query()
                        ->where('koli_id', '=', $id_cek)
                        ->where('cabang_id2', '!=', null)
                        ->get();
                    $hkoli = count($getkoli);

                    $resikolicek = M_kolicek::query()->find($id_cek);
                    $cabang2 = $resikolicek->id_cabang2;
                    $c2 = explode(",", $cabang2);

                    $cc = 0;
                    foreach ($c2 as $c) {
                        if ($c == $cabang_id) {
                            $cc += 1;
                        }
                    }

                    if ($cc == 0) {
                        $cabang2 = $cabang2 . ',' . $cabang_id;
                    }

                    $resikolicek->jumlah_resi2 = $hresi;
                    $resikolicek->jumlah_koli2 = $hkoli;
                    $resikolicek->id_cabang2 = $cabang2;
                    $resikolicek->updated_by = $updated_by;
                    $resikolicek->save();
                } else {
                    $info = "pesan";
                    $pesan = "Data Identitas Resi Sudah Pernah Berhasil di Scan.";
                }
            }
        } else { //jika yang di scan resi
            $dataresi = M_pengiriman::query()
                ->where('no_resi', '=', $sentence)->first();

            if ($dataresi === null) {
                $info = "gagal";
                $pesan = "Whops :( Data tidak ada!";
            } else {
                $idpeng = $dataresi->id_pengiriman;

                $dataidentitas = DB::table('resi_identitas')
                    ->where('id_pengiriman', '=', $idpeng)->get();
                $data = [];
                foreach ($dataidentitas as $di) {
                    $ididen = $di->id_identitas;
                    $getdetail = M_detailkoli::query()
                        ->where('cabang_id2', '=', $cabang_id)
                        ->where('identitas_id', '=', $ididen)
                        ->where('koli_id', '=', $koli_id)
                        ->get();
                    $hget = count($getdetail);

                    if ($hget == 0) {
                        $dkoli = M_detailkoli::query()
                            ->where('identitas_id', '=', $ididen)
                            ->where('koli_id', '=', $koli_id)
                            ->first();
                        $dkoli->cabang_id2 = $cabang_id;
                        $dkoli->updated_by = $updated_by;
                        $dkoli->save();
                        $info = "pesan";
                        $pesan = "Selamat! Data Identitas Resi berhasil diupdate.";

                        //hitung resi
                        $resi = [];
                        $getresi = M_detailkoli::query()
                            ->where('koli_id', '=', $id_cek)
                            ->where('cabang_id2', '!=', null)
                            ->get();
                        foreach ($getresi as $gr) {
                            $id_identitas = $gr->identitas_id;
                            $getresi2 = DB::table('resi_identitas')
                                ->where('id_identitas', '=', $id_identitas)->first();
                            $resi[] = $getresi2->id_pengiriman;
                        }
                        $clear_array = array_unique($resi);
                        $hresi = count($clear_array);

                        //hitung koli
                        $getkoli = M_detailkoli::query()
                            ->where('koli_id', '=', $id_cek)
                            ->where('cabang_id2', '!=', null)
                            ->get();
                        $hkoli = count($getkoli);

                        $resikolicek = M_kolicek::query()->find($id_cek);
                        $cabang2 = $resikolicek->id_cabang2;
                        $c2 = explode(",", $cabang2);

                        $cc = 0;
                        foreach ($c2 as $c) {
                            if ($c == $cabang_id) {
                                $cc += 1;
                            }
                        }

                        if ($cc == 0) {
                            $cabang2 = $cabang2 . ',' . $cabang_id;
                        }

                        $resikolicek->jumlah_resi2 = $hresi;
                        $resikolicek->jumlah_koli2 = $hkoli;
                        $resikolicek->id_cabang2 = $cabang2;
                        $resikolicek->updated_by = $updated_by;
                        $resikolicek->save();
                    } else {
                        $info = "pesan";
                        $pesan = "Data Identitas Resi Sudah Pernah Berhasil di Scan.";
                    }
                }
            }
        }

        $idcek2 = base64_encode($id_cek);
        //        dd($pesan);
        return redirect('/scan_identitas2/' . $idcek2)->with($info, $pesan);
    }

    public function detail2($id_cek, $id_pengiriman, Request $request)
    {
        $id_cabang = auth()->user()->id_cabang;
        $id_cek = base64_decode($id_cek);
        $id_pengiriman = base64_decode($id_pengiriman);
        $cek2 = M_pengiriman::query()->find($id_pengiriman);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Checker.cek_turun.v_detail', compact('cek2','cek', 'id_cabang', 'id_pengiriman', 'id_cek', 'cab', 'jab'));
    }

    public function list_detail2($id_cek, $id_pengiriman)
    {
        $id_cek = base64_decode($id_cek);
        $id_pengiriman = base64_decode($id_pengiriman);
        $columns = [
            'id_detail_koli',
            'identitas_id',
            'koli_id',
            'cabang_id',
            'created_by',
            'updated_by',
        ];

        $orderBy = $columns[request()->input("order.0.column")];
//        \DB::enableQueryLog();
        $data = DB::table('detail_koli')
            ->join('resi_identitas', 'detail_koli.identitas_id','=','resi_identitas.id_identitas')
            ->select(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
//                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
//                'resi_identitas.id_pengiriman',
            )
            ->where('resi_identitas.id_pengiriman', '=', $id_pengiriman)
            ->where('detail_koli.koli_id', '=', $id_cek)
            ->where('detail_koli.cabang_id2','!=',null)
            ->orderBy('resi_identitas.id_identitas', "ASC")
        ;
//        ->get();
//        dd($data);
//        dd(\DB::getQueryLog());
        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(detail_koli.identitas_id) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(resi_identitas.nama_identitas) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
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


    public function index_ecer($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        $id_cabang = $id_cabang;
        return view('Checker.daftarEceran.V_eceranCheck', compact('cab', 'id_cabang', 'jab'));
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
}
