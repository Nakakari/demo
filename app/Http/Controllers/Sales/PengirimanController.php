<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\M_kolicek;
use Illuminate\Http\Request;
use App\Models\M_jabatan;
use App\Models\M_cabang;
use App\Models\M_instansi;
use App\Models\M_member_sales;
use App\Models\M_pelanggan;
use App\Models\M_pengiriman;
use App\Models\M_tracking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PengirimanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id_cabang, Request $request)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();
        $totalOmset = M_pengiriman::getTotalOmset($id_cabang);
        $totalTransaksi = M_pengiriman::getTotalTransaksi($id_cabang);
        $tonase = M_pengiriman::getTotalTonase($id_cabang);
        $id_cabang = $id_cabang;
        $cari_print = '';
        if ($request->has('cari_print')) {
            $cari_print = $request->cari_print;
        }

        $baru = DB::table('pengiriman')
            ->where('dari_cabang', $id_cabang)
            ->latest('tgl_masuk')->first();
        $lama = DB::table('pengiriman')
            ->where('dari_cabang', $id_cabang)
            ->oldest('tgl_masuk')->first();
        //        $baru2 = date('d-m-Y', strtotime($baru->tgl_masuk));
        //        $lama2 = date('d-m-Y', strtotime($lama->tgl_masuk));

        if ($baru == null || $lama == null) {
            $tgl = '-';
        } else {
            $baru2 = date('d-m-Y', strtotime($baru->tgl_masuk));
            $lama2 = date('d-m-Y', strtotime($lama->tgl_masuk));
            if ($baru2 != $lama2) {
                $tgl = $baru2 . ' s.d. ' . $lama2;
            } else {
                $tgl = $baru2;
            }
        }

        //        dd($tgl);
        return response()->view('Sales.Pengiriman.v_pengiriman', compact('cari_print', 'jab', 'cab', 'id_cabang', 'totalOmset', 'totalTransaksi', 'tonase', 'tgl'));
        // dd($totalTransaksi);
    }

    public function addPengiriman($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $l = M_pengiriman::getLastUrut($id_cabang);
        $jab =  M_jabatan::getJab();
        $cab = M_cabang::getAll();

        $status_sent = 1;
        $moda = M_pelanggan::getModa();
        $pembayaran = M_pelanggan::getPembayaran();
        $plgn = M_pelanggan::all();
        $plyn = M_pelanggan::getPelayanan();
        $kodeCab = M_cabang::getKode($id_cabang);
        $today = Carbon::today()->toDateString();

        $year = Carbon::now()->format('y');
        $month = Carbon::now()->format('m');

        $thnBln = $year . $month;

        $get = M_pengiriman::getTracking($id_cabang);
        if ($get == null) {
            $urut = '0001';
            $nomor = strtoupper($kodeCab->kode_area) . '-' . $thnBln . $urut;
        } else {
            $lastTrackingNoSequence = substr(
                $get->no_resi,
                strlen($get->no_resi) - 4,
                strlen($get->no_resi)
            );
            $addedOne = (int) $lastTrackingNoSequence + 1;
            $urut = sprintf("%04s", $addedOne);
            $nomor = strtoupper($kodeCab->kode_area) . '-' . $thnBln . $urut;
        }

        // dd($nomor);

        return view('Sales.Pengiriman.v_addPengiriman', compact('jab', 'cab', 'status_sent', 'moda', 'nomor', 'pembayaran', 'plgn', 'plyn', 'id_cabang', 'today'));
    }

    public function kodePelanggan(Request $request)
    {
        $data = M_pelanggan::where('id_pelanggan', $request->get('id_pelanggan'))
            ->pluck('alamat', 'tlp_spv', 'id_pelanggan');
        return response()->json($data);
    }

    public function uploadDataPengiriman(Request $request, $id_cabang)
    {
        // dd(request()->all());
        $this->validate($request, [
            'no_resi' => 'required|unique:pengiriman,no_resi',
            'id_cabang_tujuan'  => 'required',
            'dari_cabang'  => 'required',
            'tgl_masuk'  => 'required',
            'nama_pengirim' => 'required',
            'alamat_pengirim' => 'required',
            'tlp_pengirim' => 'required',
            'status_sent'  => 'required',
            'kota_pengirim'  => 'required',
            'kota_penerima'  => 'required',
            'nama_penerima'  => 'required',
            'alamat_penerima'  => 'required',
            'no_penerima'  => 'required',
            'isi_barang'  => 'required',
            'koli'  => 'required',
            'no_pelayanan'  => 'required',
            'no_moda'  => 'required',
            'ttl_biaya'  => 'required',
            'keterangan'  => 'required',
            'bea'  => 'required',
            'bea_penerus'  => 'required',
            'bea_packing'  => 'required',
            'asuransi'  => 'required',
            'tipe_pembayaran'  => 'required'
        ], [
            'no_resi.required' => 'Mohon diisi!',
            'id_cabang_tujuan.required'  => 'Mohon diisi!',
            'dari_cabang.required'  => 'Mohon diisi!',
            'tgl_masuk.required'  => 'Mohon diisi!',
            'nama_pengirim.required' => 'Mohon diisi!',
            'alamat_pengirim.required' => 'Mohon diisi!',
            'tlp_pengirim.required' => 'Mohon diisi!',
            'status_sent.required'  => 'Mohon diisi!',
            'kota_pengirim.required'  => 'Mohon diisi!',
            'kota_penerima.required'  => 'Mohon diisi!',
            'nama_penerima.required'  => 'Mohon diisi!',
            'alamat_penerima.required'  => 'Mohon diisi!',
            'no_penerima.required'  => 'Mohon diisi!',
            'isi_barang.required'  => 'Mohon diisi!',
            'koli.required'  => 'Mohon diisi!',
            'no_pelayanan.required'  => 'Mohon diisi!',
            'no_moda.required'  => 'Mohon diisi!',
            'ttl_biaya.required'  => 'Mohon diisi!',
            'keterangan.required'  => 'Mohon diisi!',
            'bea.required'  => 'Mohon diisi!',
            'bea_penerus.required'  => 'Mohon diisi!',
            'bea_packing.required'  => 'Mohon diisi!',
            'asuransi.required'  => 'Mohon diisi!',
            'tipe_pembayaran.required'  => 'Mohon diisi!'
        ]);

        $get_no_resi = M_pengiriman::where('no_resi', $request->get('no_resi'))->first();
        $member_sales = M_member_sales::where('uuid', $request->uuid)->first();

        $bea = $request->get('bea');
        $bea_penerus = $request->get('bea_penerus');
        $bea_packing = $request->get('bea_packing');
        $asuransi = $request->get('asuransi');
        // $asuransi = 0;
        $total1 = $request->get('ttl_biaya');

        $bea2 = str_ireplace(".", "", $bea);
        $bea_penerus2 = str_ireplace(".", "", $bea_penerus);
        $bea_packing2 = str_ireplace(".", "", $bea_packing);
        $asuransi2 = str_ireplace(
            ".",
            "",
            $asuransi
        );
        $total2 = str_ireplace(".", "", $total1);

        $total = $bea2 + $bea_penerus2 + $bea_packing2 + $asuransi2;

        $form = [
            'no_resi' => $request->get('no_resi'),
            'no_resi_manual' => $request->get('no_resi_manual'),
            'id_cabang_tujuan' => $request->get('id_cabang_tujuan'),
            'dari_cabang' => $id_cabang,
            'tgl_masuk' => $request->get('tgl_masuk'),
            'nama_pengirim' => $request->get('nama_pengirim'),
            'alamat_pengirim' => $request->get('alamat_pengirim'),
            'tlp_pengirim' => $request->get('tlp_pengirim'),
            'status_sent' => $request->get('status_sent'),
            'nama_penerima' => $request->get('nama_penerima'),
            'alamat_penerima' => $request->get('alamat_penerima'),
            'no_penerima' => $request->get('no_penerima'),
            'isi_barang' => $request->get('isi_barang'),
            'koli' => $request->get('koli'),
            'kota_penerima' => $request->get('kota_penerima'),
            'kota_pengirim' => $request->get('kota_pengirim'),
            'keterangan' => $request->get('keterangan'),
            'no_pelayanan' => $request->get('no_pelayanan'),
            'no_moda' => $request->get('no_moda'),
            'biaya' => $total2,
            'tipe_pembayaran' => $request->get('tipe_pembayaran'),
            'bea' => $bea2,
            'bea_penerus' => $bea_penerus2,
            'bea_packing' => $bea_packing2,
            'asuransi' => $asuransi2,
            'is_buat' => 0,
            'id_member_sales' => $member_sales->id_member_sales,
            'is_kondisi_resi' => 1,
            'created_by' => Auth::user()->id,
        ];

        if ($get_no_resi == null) {
            if ($total != $total2) {
                return back()->withInput()->with('gagal', 'Perhatikan Kolom Total Biaya ^^');
            } else {

                $form['volume'] = str_ireplace(".", "", $request->get('berat_m'));

                $form['berat'] = str_ireplace(".", "", $request->get('berat_kg'));

                DB::transaction(function () use ($form, $request, $id_cabang): void {
                    $data = M_pengiriman::create($form);
                    M_pengiriman::addKoli($request->get('koli'), $data->id_pengiriman);
                    M_tracking::tambahTracking($data->id_pengiriman, $request->get('status_sent'), Auth::user()->id_cabang, null, null);
                });
                $pesan = "pesan";
                $info = "Selamat! Data berhasil disimpan";
            }
        } else {
            $info = "gagal";
            $pesan = "Duplicate data detected";
        }
        return redirect('pengiriman/' . base64_encode($id_cabang))->with($pesan, $info);
    }

    public function listPengiriman($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $columns = [
            'id_pengiriman', 'no_resi', 'id_cabang', 'status_sent', 'id_pelanggan', 'nama_penerima', 'alamat_penerima', 'no_penerima',
            'isi_barang', 'berat', 'koli', 'no_ref', 'no_pelayanan', 'no_moda', 'biaya', 'tipe_pembayaran',
            'nama_pengirim', 'alamat_pengirim', 'tlp_pengirim', 'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];

        $data = (new M_pengiriman())->getListPengiriman($id_cabang);

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.no_resi_manual) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.kota_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null && request()->input('sampai') != null) {
            $data = $data->whereBetween('pengiriman.tgl_masuk', [request()->dari, request()->sampai]);
        }

        if (request()->input('kondisi') != null) {
            $data = $data->where('pengiriman.status_sent', request()->kondisi);
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

    public function updateStatus(Request $request)
    {
        $pengiriman = M_pengiriman::find($request->input('id_pengiriman'));
        $pengiriman->status_sent = $request->status_sent;
        $pengiriman->save();
        return response()->json(true);
    }

    public function showFill($id_cabang)
    {
        if (request()->input('tgl_dari') != null || request()->input('tgl_sampai') != null) {
            $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->count();
            $jumlah = M_pengiriman::select(DB::raw("SUM(biaya) as jumlah"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->first();
            $berat = M_pengiriman::select(DB::raw("SUM(berat) as kg"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->first();

            $dari = date('d-m-Y', strtotime(request()->tgl_dari));
            $sampai = date('d-m-Y', strtotime(request()->tgl_sampai));
            return [$dataWaktu, $jumlah, $berat, $dari, $sampai];
        }
    }

    public function showFill2($id_cabang)
    {
        if (request()->input('tgl_dari') != null || request()->input('tgl_sampai') != null) {
            $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->count();
            $jumlah = M_pengiriman::select(DB::raw("SUM(biaya) as jumlah"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->first();
            $berat = M_pengiriman::select(DB::raw("SUM(berat) as kg"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('dari_cabang', $id_cabang)->first();

            $dari = date('d-m-Y', strtotime(request()->tgl_dari));
            $sampai = date('d-m-Y', strtotime(request()->tgl_sampai));
            return [$dataWaktu, $jumlah, $berat, $dari, $sampai];
        }
    }

    public function showFillAll($id_cabang)
    {
        if (request()->input('tgl_dari') != null && request()->input('tgl_sampai') != null && request()->input('status') != null) {
            $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->count();
            $jumlah = M_pengiriman::select(DB::raw("SUM(biaya) as jumlah"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->first();
            $berat = M_pengiriman::select(DB::raw("SUM(berat) as kg"))->whereBetween('pengiriman.tgl_masuk', [request()->tgl_dari, request()->tgl_sampai])->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->first();
            return [$dataWaktu, $jumlah, $berat];
        }
    }

    public function showFillKondisi($id_cabang)
    {
        if (request()->input('status') != null) {
            $data = M_pengiriman::where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->count();
            $tgl_awal = M_pengiriman::select('pengiriman.tgl_masuk')->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->first();
            $tgl_akhir = M_pengiriman::select('pengiriman.tgl_masuk')->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->latest('tgl_masuk')->first();
            $berat = M_pengiriman::select(DB::raw("SUM(berat) as kg"))->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->first();
            $jumlah = M_pengiriman::select(DB::raw("SUM(biaya) as jumlah"))->where('pengiriman.status_sent', request()->status)->where('dari_cabang', $id_cabang)->first();
            return [$data, $tgl_awal, $tgl_akhir, $berat, $jumlah];
        }
    }

    public function printResi($id_pengiriman)
    {
        $id_pengiriman = base64_decode($id_pengiriman);
        $pengiriman = M_pengiriman::getDetailData($id_pengiriman);
        $instansi = M_instansi::first();
        return view('Sales.cetakresi2', compact('pengiriman', 'instansi'));
    }

    public function printIdentitas($id_pengiriman)
    {
        $id_pengiriman = base64_decode($id_pengiriman);
        $cari = M_pengiriman::query()->find($id_pengiriman);
        $getp = $cari->no_pelayanan;
        $pelayanan = DB::table('tipe_pelayanan')->where('id_pelayanan', '=', $getp)->first();
        $instansi = M_instansi::first();
        return view('Sales.cetakidentitas', compact('cari', 'pelayanan', 'instansi'));
    }

    public function showFillTanpaFilter($id_cabang)
    {
        $totalOmset = M_pengiriman::getTotalOmset($id_cabang);
        $totalTransaksi = M_pengiriman::getTotalTransaksi($id_cabang);
        $tonase = M_pengiriman::getTotalTonase($id_cabang);
        $tgl_awal = M_pengiriman::select('pengiriman.tgl_masuk')->where('dari_cabang', $id_cabang)->first();
        $tgl_akhir = M_pengiriman::select('pengiriman.tgl_masuk')->where('dari_cabang', $id_cabang)->latest('tgl_masuk')->first();
        return [$totalOmset, $totalTransaksi, $tonase, $tgl_awal, $tgl_akhir];
    }

    public function cetak()
    {
        return view('Admin.Pengiriman.cetak');
    }

    public function konfirmasi(M_member_sales $member)
    {
        $kode = request()->get('kode');
        $id_sales = request()->get('id_sales');

        $member = $member->where('kode', $kode)->where('id_sales', $id_sales)->first();
        // dd($member->exists());
        if ($member->exists()) {
            $uuid = $member->uuid;
            $pesan = $uuid;
        } else {
            $pesan = null;
        }
        return response()->json($pesan);
    }

    public function ceknaik($id_cabang, Request $request)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Sales.cekKoli.v_identitas', compact('id_cabang', 'cab', 'jab'));
    }

    public function list_naik($id_cabang)
    {
        $id_cabang = base64_decode($id_cabang);
        $columns = [
            'id_kolicek',
            'nopol',
            'driver',
            'tgl_buat',
            'jumlah_resi',
            'jumlah_koli',
            'created_by',
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        //        \DB::enableQueryLog();
        $data = DB::table('koli_cek')
            ->join('users', 'users.id', '=', 'koli_cek.created_by')
            ->join('cabang', 'cabang.id_cabang', '=', 'koli_cek.id_cabang')
            ->select(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi',
                'koli_cek.jumlah_koli',
                'koli_cek.created_by',
                'cabang.nama_kota',
                'users.name',
                //                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi',
                'koli_cek.jumlah_koli',
                'koli_cek.created_by',
                'cabang.nama_kota',
                'users.name',
                //                'resi_identitas.id_pengiriman',
            )
            ->where('koli_cek.id_cabang', '=', $id_cabang)
            ->orderBy('koli_cek.id_kolicek', "DESC");
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

        return view('Sales.cekKoli.v_resi', compact('cek', 'id_cabang', 'id_cek', 'cab', 'jab'));
    }

    public function list_resinaik($id_cek)
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

    public function detailnaik($id_cek, $id_pengiriman, Request $request)
    {
        $id_cabang = auth()->user()->id_cabang;
        $id_cek = base64_decode($id_cek);
        $id_pengiriman = base64_decode($id_pengiriman);
        $cek2 = M_pengiriman::query()->find($id_pengiriman);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Sales.cekKoli.v_detail', compact('cek2', 'cek', 'id_cabang', 'id_pengiriman', 'id_cek', 'cab', 'jab'));
    }

    public function list_detailnaik($id_cek, $id_pengiriman)
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
            ->join('resi_identitas', 'detail_koli.identitas_id', '=', 'resi_identitas.id_identitas')
            ->join('users', 'users.id', '=', 'detail_koli.created_by')
            ->join('cabang', 'cabang.id_cabang', '=', 'detail_koli.cabang_id')
            ->select(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
                'cabang.nama_kota',
                'users.name',
                //                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
                'cabang.nama_kota',
                'users.name',
                //                'resi_identitas.id_pengiriman',
            )
            ->where('resi_identitas.id_pengiriman', '=', $id_pengiriman)
            ->where('detail_koli.koli_id', '=', $id_cek)
            ->orderBy('resi_identitas.id_identitas', "ASC");
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


    public function cekturun($id_cabang, Request $request)
    {
        $id_cabang = base64_decode($id_cabang);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Sales.cekKoli.turun.v_identitas', compact('id_cabang', 'cab', 'jab'));
    }

    public function list_turun($id_cabang)
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
            ->join('users', 'users.id', '=', 'koli_cek.created_by')
            ->join('cabang', 'cabang.id_cabang', '=', 'koli_cek.id_cabang')
            ->select(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi2',
                'koli_cek.jumlah_koli2',
                'cabang.nama_kota',
                'users.name',
                //                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'koli_cek.id_kolicek',
                'koli_cek.nopol',
                'koli_cek.driver',
                'koli_cek.tgl_buat',
                'koli_cek.jumlah_resi2',
                'koli_cek.jumlah_koli2',
                'cabang.nama_kota',
                'users.name',
                //                'resi_identitas.id_pengiriman',
            )
            ->whereRaw('FIND_IN_SET(?, id_cabang2)', [$id_cabang])
            ->orderBy('koli_cek.id_kolicek', "DESC");
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

        return view('Sales.cekKoli.turun.v_resi', compact('cek', 'id_cabang', 'id_cek', 'cab', 'jab'));
    }

    public function list_resiturun($id_cek)
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
            ->where('detail_koli.updated_by', '!=', null)
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

    public function detailturun($id_cek, $id_pengiriman, Request $request)
    {
        $id_cabang = auth()->user()->id_cabang;
        $id_cek = base64_decode($id_cek);
        $id_pengiriman = base64_decode($id_pengiriman);
        $cek2 = M_pengiriman::query()->find($id_pengiriman);
        $cek = M_kolicek::query()->find($id_cek);
        $cab = M_cabang::getAll();
        $jab =  M_jabatan::getJab();

        return view('Sales.cekKoli.turun.v_detail', compact('cek2', 'cek', 'id_cabang', 'id_pengiriman', 'id_cek', 'cab', 'jab'));
    }

    public function list_detailturun($id_cek, $id_pengiriman)
    {
        $id_cek = base64_decode($id_cek);
        $id_pengiriman = base64_decode($id_pengiriman);
        $id_cab = auth()->user()->id_cabang;
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
            ->join('resi_identitas', 'detail_koli.identitas_id', '=', 'resi_identitas.id_identitas')
            ->leftJoin('users', 'users.id', '=', 'detail_koli.updated_by')
            ->leftJoin('cabang', 'cabang.id_cabang', '=', 'detail_koli.cabang_id2')
            ->select(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
                'cabang.nama_kota',
                'users.name',
                //                DB::raw('COUNT(resi_identitas.id_pengiriman) as jresi'),
            )
            ->groupBy(
                'detail_koli.id_detail_koli',
                'detail_koli.identitas_id',
                'detail_koli.koli_id',
                'resi_identitas.nama_identitas',
                'detail_koli.created_by',
                'detail_koli.updated_by',
                'cabang.nama_kota',
                'users.name',
                //                'resi_identitas.id_pengiriman',
            )
            ->where('resi_identitas.id_pengiriman', '=', $id_pengiriman)
            ->where('detail_koli.koli_id', '=', $id_cek)
            //            ->where('detail_koli.cabang_id2', '=', $id_cab)
            ->orderBy('resi_identitas.id_identitas', "ASC");
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

    public function hitungPembayaran(Request $request)
    {
        try {
            $bea = intval(str_ireplace('.', '', $request->query('bea')));
            $bea_penerus = intval(str_ireplace('.', '', $request->query('bea_penerus')));
            $bea_packing = intval(str_ireplace('.', '', $request->query('bea_packing')));
            $asuransi = intval(str_ireplace('.', '', $request->query('asuransi')));
            $ttl_biaya = $bea + $bea_penerus + $bea_packing + $asuransi;
            $data = [
                'ttl_biaya' => number_format($ttl_biaya, 0, ',', '.'),
            ];
            return response()->json($data);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
