<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\M_cabang;
use App\Models\M_instansi;
use App\Models\M_jabatan;
use App\Models\M_pelanggan;
use App\Models\M_pengiriman;
use App\Models\M_tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class PengirimanAdminController extends Controller
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
            'status' => M_cabang::getStatus(),
            'kondisi' => M_cabang::getKondisi(),
        ];
        return view('Admin.Pengiriman.v_pengiriman', $data);
        // dd($data['cab']);
    }

    public function print()
    {
        return view('Admin.Pengiriman.print');
    }
    public function print2($id_pengiriman)
    {
        $id_pengiriman = base64_decode($id_pengiriman);
        $pengiriman = M_pengiriman::getDetailData($id_pengiriman);
        $instansi = M_instansi::first();
        return view('Admin.Pengiriman.cetakresi', compact('pengiriman', 'instansi'));
    }

    public function list_pengiriman()
    {
        $columns = [
            'id_pengiriman', 'no_resi', 'id_cabang', 'status_sent', 'id_pelanggan', 'nama_penerima', 'alamat_penerima', 'no_penerima',
            'isi_barang', 'berat', 'koli', 'no_ref', 'no_pelayanan', 'no_moda', 'biaya', 'tipe_pembayaran',
            'nama_pengirim', 'alamat_pengirim', 'tlp_pengirim', 'dari_cabang'
        ];

        $orderBy = $columns[request()->input("order.0.column")];
        $data = M_pengiriman::select([
            'pengiriman.id_pengiriman',
            'pengiriman.no_resi',
            'pengiriman.no_resi_manual',
            'pengiriman.id_cabang_tujuan',
            'pengiriman.dari_cabang',
            'pengiriman.alamat_pengirim',
            'pengiriman.tlp_pengirim',
            'pengiriman.status_sent',
            'tbl_status_pengiriman.nama_status',
            'pengiriman.id_pelanggan',
            DB::raw("(DATE_FORMAT(pengiriman.tgl_masuk,'%d %M %Y')) as tgl_masuk"),
            'pengiriman.alamat_penerima',
            'pengiriman.no_penerima',
            'pengiriman.isi_barang',
            DB::Raw('IF(pengiriman.berat=0, volume,berat) AS kilo'),
            'pengiriman.koli',
            'pengiriman.no_ref',
            'pengiriman.no_pelayanan',
            'pengiriman.no_moda',
            'pengiriman.biaya',
            'pengiriman.tipe_pembayaran',
            'cabang.nama_kota',
            DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "1" THEN pengiriman.biaya
                        END) AS tagihan'),
            DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "2" THEN pengiriman.biaya
                        END) AS cod'),
            DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "3" THEN pengiriman.biaya
                        END) AS cash'),
            DB::raw('UPPER(pengiriman.nama_penerima) as nama_penerima'),
            DB::raw('UPPER(pengiriman.kota_penerima) as kota_penerima'),
            DB::raw('UPPER(pengiriman.nama_pengirim) as nama_pengirim'),
        ])
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->join('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'pengiriman.status_sent')
            ->leftjoin('tbl_surat_kembali', 'tbl_surat_kembali.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->orderBy('id_pengiriman', "asc");

        $recordsFiltered = $data->get()->count(); //menghitung data yang sudah difilter

        $dataWaktu = M_pengiriman::whereBetween('pengiriman.tgl_masuk', [request()->input('dari'), request()->input('sampai')])->count();

        if (request()->input("search.value")) {
            $data = $data->where(function ($query) {
                $query->whereRaw('LOWER(pengiriman.no_resi) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.no_resi_manual) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_pengirim) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.nama_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%'])
                    ->orWhereRaw('LOWER(pengiriman.kota_penerima) like ?', ['%' . strtolower(request()->input("search.value")) . '%']);
            });
        }

        if (request()->input('dari') != null || request()->input('sampai') != null) {
            $data = $data->whereBetween('pengiriman.tgl_masuk', [request()->dari, request()->sampai]);
        }

        if (request()->input('kondisi') != null) {
            $data = $data->where('pengiriman.is_kondisi_resi', request()->kondisi);
        }

        if (request()->input('cabang') != null) {
            $data = $data->where('pengiriman.dari_cabang', request()->cabang);
        }

        $data = $data
            ->skip(request()->input('start'))
            ->take(request()->input('length'))
            ->orderBy($orderBy, request()->input("order.0.dir"))
            ->get();
        $recordsTotal = $data->count();

        return response()->json([
            'draw' => request()->input('draw'),
            'data_waktu' => $dataWaktu,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'all_request' => request()->all(),
        ]);
    }

    public function showData($id_pengiriman)
    {
        $id_pengiriman = base64_decode($id_pengiriman);
        $data = [
            'jab' => M_jabatan::getJab(),
            'cab' => M_cabang::getAll(),
            'status' => M_cabang::getStatus(),
            'item' => M_pengiriman::findOrFail($id_pengiriman),
            'plyn' => M_pelanggan::getPelayanan(),
            'moda' => M_pelanggan::getModa(),
            'pembayaran' => M_pelanggan::getPembayaran(),
            'id_pengiriman' => $id_pengiriman
        ];
        return view('Admin.Pengiriman.v_editPengiriman', $data);
    }

    public function updateData(Request $request, $id_pengiriman)
    {
        // dd(request()->get('id_cabang_tujuan'));
        $no_resi = $request->get('no_resi');
        $this->validate(
            $request,
            [
                'no_resi' => ['required', Rule::unique('pengiriman')->ignore($no_resi, 'no_resi')],
                'id_cabang_tujuan_edit'  => 'required',
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
                'tipe_pembayaran'  => 'required',
                'ttl_biaya'  => 'required',
                'keterangan'  => 'required',
                'bea'  => 'required',
                'bea_penerus'  => 'required',
                'bea_packing'  => 'required',
                'asuransi'  => 'required',
            ],
            [
                'no_resi.required' => 'No Resi Harap diisi!',
                'id_cabang_tujuan_edit.required'  => 'Cabang Tujuan Mohon diisi!',
                'tgl_masuk.required'  => 'Tanggal Masuk Mohon diisi!',
                'nama_pengirim.required' => 'Nama Mohon diisi!',
                'alamat_pengirim.required' => 'Alamat Pengirim Mohon diisi!',
                'tlp_pengirim.required' => 'Telephone Pengirim Mohon diisi!',
                'status_sent.required'  => 'Status Kirim Mohon diisi!',
                'kota_pengirim.required'  => 'Kota Pengirim Mohon diisi!',
                'kota_penerima.required'  => 'Kota Penerima Mohon diisi!',
                'nama_penerima.required'  => 'Nama Penerima Mohon diisi!',
                'alamat_penerima.required'  => 'Alamat Penerima Mohon diisi!',
                'no_penerima.required'  => 'Nomor Penerima Mohon diisi!',
                'isi_barang.required'  => 'Isi Barang mohon diisi!',
                'koli.required'  => 'Koli mohon diisi!',
                'no_pelayanan.required'  => 'Nomor Pelayanan mohon diisi!',
                'no_moda.required'  => 'Nomor Moda mohon diisi!',
                'ttl_biaya.required'  => 'Total Biaya Mohon diisi!',
                'keterangan.required'  => 'Keterangan Mohon diisi!',
                'bea.required'  => 'Bea Mohon diisi!',
                'bea_penerus.required'  => 'Bea Penerus Mohon diisi!',
                'bea_packing.required'  => 'Bea Packing Mohon diisi!',
                'asuransi.required'  => 'Asuransi Mohon diisi!',
                'tipe_pembayaran.required'  => 'Tipe Pembayaran Mohon diisi!'
            ]
        );
        $data = M_pengiriman::find($id_pengiriman);
        $bea = $request->get('bea');
        $bea_penerus = $request->get('bea_penerus');
        $bea_packing = $request->get('bea_packing');
        $asuransi = $request->get('asuransi');
        // $asuransi = 0;
        $total1 = $request->get('ttl_biaya');

        $bea2 = str_ireplace(".", "", $bea);
        $bea_penerus2 = str_ireplace(".", "", $bea_penerus);
        $bea_packing2 = str_ireplace(".", "", $bea_packing);
        $asuransi2 = str_ireplace(".", "", $asuransi);
        $total2 = str_ireplace(".", "", $total1);

        $total = $bea2 + $bea_penerus2 + $bea_packing2 + $asuransi2;

        $form = [
            'no_resi' => $request->get('no_resi'),
            'no_resi_manual' => $request->get('no_resi_manual'),
            'id_cabang_tujuan' => $request->get('id_cabang_tujuan_edit'),
            'dari_cabang' => $data['dari_cabang'],
            'tgl_masuk' => $request->get('tgl_masuk'),
            'nama_pengirim' => $request->get('nama_pengirim'),
            'alamat_pengirim' => $request->get('alamat_pengirim'),
            'tlp_pengirim' => $request->get('tlp_pengirim'),
            'status_sent' => $request->get('status_sent'),
            'nama_penerima' => $request->get('nama_penerima'),
            'alamat_penerima' => $request->get('alamat_penerima'),
            'no_penerima' => $request->get('no_penerima'),
            'isi_barang' => $request->get('isi_barang'),
            'koli' => str_ireplace(".", "", $request->get('koli')),
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
            'is_buat' => $data['is_buat'],
            'is_kondisi_resi' => $data['is_kondisi_resi'],
            'created_by' => $data['created_by'],
            'updated_by' => Auth::user()->id,
        ];
        if ($total != $total2) {
            return back()->withInput()->with('gagal', 'Perhatikan Kolom Total Pembayaran ^^');
        } else {

            //berat ASLI kosong
            $form['volume'] = str_ireplace(".", "", $request->get('berat_m'));

            $form['berat'] = str_ireplace(".", "", $request->get('berat_kg'));


            $data->where('id_pengiriman', $id_pengiriman)->update($form);
            $tracking = M_tracking::where('pengiriman_id', $id_pengiriman)->orderBy('id_tracking', 'desc')->first();
            if ($tracking !== null) {
                $form_tracking = [
                    'status_sent' => $request->get('status_sent'),
                    'updated_by' => Auth::user()->id
                ];
                $update_tracking = M_tracking::where('id_tracking', $tracking->id_tracking)->update($form_tracking);
            }
            return redirect('pengiriman')->with('pesan', 'Selamat! Data berhasil diupdate');
        }
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
