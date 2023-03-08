<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class M_pengiriman extends Model
{
    use HasFactory;
    protected $fillable = [
        'dari_cabang', 'no_resi', 'no_resi_manual', 'nama_pengirim', 'kota_pengirim',
        'alamat_pengirim', 'tlp_pengirim', 'id_cabang_tujuan', 'status_sent', 'id_pelanggan', 'nama_penerima',
        'kota_penerima', 'alamat_penerima', 'no_penerima', 'id_member_sales',
        'isi_barang', 'berat', 'volume', 'koli', 'no_pelayanan', 'no_moda', 'bea', 'bea_penerus', 'keterangan',
        'bea_packing', 'asuransi', 'biaya', 'tipe_pembayaran',  'tgl_masuk', 'created_by', 'updated_by'
    ];
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $timestamps = false;

    public function getListPengiriman($id_cabang)
    {
        return M_pengiriman::select([
            'pengiriman.id_pengiriman',
            'pengiriman.no_resi',
            'pengiriman.no_resi_manual',
            'pengiriman.id_cabang_tujuan',
            'pengiriman.dari_cabang',
            'pengiriman.alamat_pengirim',
            'pengiriman.tlp_pengirim',
            'pengiriman.status_sent',
            'tbl_status_pengiriman.nama_status',
            'tbl_status_pengiriman.class',
            'pengiriman.id_pelanggan',
            DB::raw("(DATE_FORMAT(pengiriman.tgl_masuk,'%d %M %Y')) as tgl_masuk"),
            DB::raw('UPPER(pengiriman.nama_penerima) as nama_penerima'),
            DB::raw('UPPER(pengiriman.kota_penerima) as kota_penerima'),
            DB::raw('UPPER(pengiriman.nama_pengirim) as nama_pengirim'),
            'pengiriman.alamat_penerima',
            'pengiriman.no_penerima',
            'pengiriman.isi_barang',
            'pengiriman.berat',
            'pengiriman.volume',
            'pengiriman.koli',
            'pengiriman.no_ref',
            'tipe_pelayanan.nama_pelayanan',
            'pengiriman.no_moda',
            'pengiriman.keterangan',
            'pengiriman.bea',
            'pengiriman.bea_penerus',
            'pengiriman.bea_packing',
            'pengiriman.asuransi',
            'pengiriman.biaya',
            'pengiriman.tipe_pembayaran',
            'cabang.nama_kota',
            'tipe_pembayaran.nama_tipe_pemb'
        ])
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->join('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'pengiriman.status_sent')
            ->join('tipe_pelayanan', 'tipe_pelayanan.id_pelayanan', '=', 'pengiriman.no_pelayanan')
            ->join('tipe_pembayaran', 'tipe_pembayaran.id_pembayaran', '=', 'pengiriman.tipe_pembayaran')
            ->where('pengiriman.dari_cabang', $id_cabang)
            ->orderBy('id_pengiriman', "desc");
    }
    public function dataTables($id_cabang)
    {
        return
            M_pengiriman::select([
                'pengiriman.id_pengiriman',
                'pengiriman.no_resi',
                'pengiriman.id_cabang_tujuan',
                DB::raw("(DATE_FORMAT(pengiriman.tgl_tiba,'%d %M %Y')) as tgl_tiba"),
                'pengiriman.status_sent',
                DB::raw('UPPER(pengiriman.nama_penerima) as nama_penerima'),
                DB::raw('UPPER(pengiriman.kota_penerima) as kota_penerima'),
                DB::raw('UPPER(pengiriman.nama_pengirim) as nama_pengirim'),
                'tbl_status_pengiriman.nama_status',
                'tbl_status_pengiriman.class'
            ])
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->join('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'pengiriman.status_sent')
            ->where('pengiriman.id_cabang_tujuan', $id_cabang)
            ->where(function ($r) {
                $r->where('pengiriman.status_sent', 4);
                $r->orWhere('pengiriman.status_sent', 5);
                $r->orWhere('pengiriman.status_sent', 8);
                $r->orWhere('pengiriman.status_sent', 9);
            })
            ->orderBy('id_pengiriman', "desc");
    }

    public function dataDaftarTugas($id_cabang)
    {
        return  M_pengiriman::select([
            // '*'
            'pengiriman.id_pengiriman',
            'pengiriman.no_resi',
            'pengiriman.id_cabang_tujuan',
            'pengiriman.dari_cabang',
            'pengiriman.id_transit',
            'pengiriman.alamat_pengirim',
            'pengiriman.tlp_pengirim',
            'pengiriman.status_sent',
            'tbl_status_pengiriman.nama_status',
            'tbl_status_pengiriman.class',
            'pengiriman.id_pelanggan',
            DB::raw("(DATE_FORMAT(pengiriman.tgl_masuk,'%d %M %Y')) as tgl_masuk"),
            'pengiriman.alamat_penerima',
            'pengiriman.no_penerima',
            'pengiriman.isi_barang',
            'pengiriman.berat',
            'pengiriman.koli',
            'pengiriman.no_ref',
            'pengiriman.no_pelayanan',
            'pengiriman.no_moda',
            'pengiriman.biaya',
            'pengiriman.tipe_pembayaran',
            'cabang.nama_kota',
            DB::raw('UPPER(pengiriman.nama_penerima) as nama_penerima'),
            DB::raw('UPPER(pengiriman.kota_penerima) as kota_penerima'),
            DB::raw('UPPER(pengiriman.nama_pengirim) as nama_pengirim'),
        ])
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            // ->join('pelanggan', 'pelanggan.id_pelanggan', '=', 'pengiriman.id_pelanggan')
            ->join('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'pengiriman.status_sent')
            //            ->where('pengiriman.dari_cabang', $id_cabang)
            ->where(function ($q) use ($id_cabang) {
                $q->where('pengiriman.dari_cabang', $id_cabang);
                $q->orWhere('pengiriman.id_transit', $id_cabang);
            })
            ->where(function ($r) {
                $r->where('pengiriman.status_sent', 1);
                $r->orWhere('pengiriman.status_sent', 3);
            })
            ->orderBy('id_pengiriman', "desc");
    }

    public function listPenjualan()
    {
        return M_pengiriman::select([
            'pengiriman.id_pengiriman',
            'pengiriman.no_resi',
            'pengiriman.isi_barang',
            'pengiriman.no_resi_manual',
            'tbl_surat_kembali.no_resi as noResi',
            'pengiriman.id_cabang_tujuan',
            'pengiriman.dari_cabang',
            'pengiriman.alamat_pengirim',
            'pengiriman.tlp_pengirim',
            'pengiriman.status_sent',
            'tbl_status_pengiriman.nama_status',
            'pengiriman.id_pelanggan',
            DB::raw("(DATE_FORMAT(pengiriman.tgl_masuk,'%d %M %Y')) as tgl_masuk"),
            DB::Raw('IFNULL(pengiriman.volume, berat) AS kilo'),
            'pengiriman.koli',
            'pengiriman.no_ref',
            'pengiriman.no_pelayanan',
            'pengiriman.no_moda',
            'pengiriman.biaya',
            'pengiriman.tipe_pembayaran',
            'pengiriman.is_kondisi_resi',
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "1" THEN pengiriman.biaya
                        END) AS tagihan'),
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "2" THEN pengiriman.biaya
                        END) AS cod'),
            DB::raw('(CASE 
                        WHEN pengiriman.tipe_pembayaran = "3" THEN pengiriman.biaya
                        END) AS cash'),
            DB::raw("(DATE_FORMAT(tbl_surat_kembali.tgl_surat_kembali,'%d %M %Y')) as tgl"),
            'tbl_surat_kembali.tgl_surat_kembali as tgl',
            'tbl_surat_kembali.keterangan as ket',
            DB::raw('UPPER(pengiriman.nama_penerima) as nama_penerima'),
            DB::raw('UPPER(pengiriman.kota_penerima) as kota_penerima'),
            DB::raw('UPPER(pengiriman.nama_pengirim) as nama_pengirim'),
        ])
            ->leftjoin('tbl_surat_kembali', 'tbl_surat_kembali.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->join('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'pengiriman.status_sent')
            ->where('pengiriman.status_sent', '!=', 7)
            ->orderBy('id_pengiriman', "asc");
    }

    public function invPelanggan($id_pelanggan)
    {
        return
            M_pengiriman::select([
                'pengiriman.id_pengiriman',
                'pengiriman.no_resi',
                'pengiriman.no_resi_manual',
                'pengiriman.nama_pengirim',
                'pengiriman.id_pelanggan',
                DB::raw("(DATE_FORMAT(pengiriman.tgl_masuk,'%d %M %Y')) as tgl_masuk"),
                'pengiriman.nama_penerima',
                'pengiriman.kota_penerima',
                'pengiriman.is_kondisi_resi',
                'pengiriman.is_buat',
                DB::Raw('IFNULL(pengiriman.volume, berat) AS kilo'),
                'pengiriman.koli',
                'pengiriman.biaya',
                'pengiriman.tipe_pembayaran',
                DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "1" THEN pengiriman.biaya
                        END) AS tagihan'),
                DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "2" THEN pengiriman.biaya
                        END) AS cod'),
                DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "3" THEN pengiriman.biaya
                        END) AS cash'),
                'tbl_surat_kembali.tgl_surat_kembali as tgl',
                'tbl_surat_kembali.keterangan as ket',
            ])

            ->leftjoin('tbl_surat_kembali', 'tbl_surat_kembali.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->leftjoin('tbl_detail_invoice', 'pengiriman.id_pengiriman', '=', 'tbl_detail_invoice.id_pengiriman')
            ->where('pengiriman.id_pelanggan', $id_pelanggan)
            ->where('pengiriman.tipe_pembayaran', 1)
            ->whereNull('tbl_detail_invoice.id_pengiriman')
            ->orderBy('id_pengiriman', "asc");
    }

    public function resinvPelanggan($id_pelanggan)
    {
        return
            M_pengiriman::select([
                'pengiriman.id_pengiriman',
                'pengiriman.no_resi',
                'pengiriman.no_resi_manual',
                'pengiriman.nama_pengirim',
                'pengiriman.id_pelanggan',
                DB::raw("(DATE_FORMAT(pengiriman.tgl_masuk,'%d %M %Y')) as tgl_masuk"),
                'pengiriman.nama_penerima',
                'pengiriman.kota_penerima',
                'pengiriman.is_kondisi_resi',
                'pengiriman.is_buat',
                DB::Raw('IFNULL(pengiriman.volume, berat) AS kilo'),
                'pengiriman.koli',
                'pengiriman.biaya',
                'pengiriman.tipe_pembayaran',
                DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "1" THEN pengiriman.biaya
                        END) AS tagihan'),
                DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "2" THEN pengiriman.biaya
                        END) AS cod'),
                DB::raw('(CASE
                        WHEN pengiriman.tipe_pembayaran = "3" THEN pengiriman.biaya
                        END) AS cash'),
                'tbl_surat_kembali.tgl_surat_kembali as tgl',
                'tbl_surat_kembali.keterangan as ket',
            ])

            ->leftjoin('tbl_surat_kembali', 'tbl_surat_kembali.id_pengiriman', '=', 'pengiriman.id_pengiriman')
            ->leftjoin('tbl_detail_invoice', 'pengiriman.id_pengiriman', '=', 'tbl_detail_invoice.id_pengiriman')
            ->where('pengiriman.id_pelanggan', $id_pelanggan)
            ->where('pengiriman.tipe_pembayaran', 1)
            // ->whereNotNull('tbl_detail_invoice.id_invoice', 3)
            ->orderBy('id_pengiriman', "asc");
    }

    public static function getTotalOmset($id_cabang)
    {
        return DB::table('pengiriman')
            ->select(
                DB::raw("SUM(biaya) as jumlah")
            )
            ->where('dari_cabang', $id_cabang)
            ->first();
    }

    public static function getTotalOmsetTugas($id_cabang)
    {
        return DB::table('pengiriman')
            ->select(
                DB::raw("SUM(biaya) as jumlah")
            )
            ->where('dari_cabang', $id_cabang)
            ->where('pengiriman.status_sent', 1)
            ->first();
    }

    public static function getTotalTonase($id_cabang)
    {
        return DB::table('pengiriman')
            ->select(
                DB::raw("SUM(berat) as kg")
            )
            ->where('dari_cabang', $id_cabang)
            ->first();
    }

    public static function getTotalTonaseTugas($id_cabang)
    {
        return DB::table('pengiriman')
            ->select(
                DB::raw("SUM(berat) as kg")
            )
            ->where('dari_cabang', $id_cabang)
            //            ->orWhere('pengiriman.status_sent', 3)
            ->where('pengiriman.status_sent', 1)
            //            ->orWhere('pengiriman.status_sent', 2)
            ->first();
    }

    public static function getTotalTransaksi($id_cabang)
    {
        return DB::table('pengiriman')
            ->where('dari_cabang', $id_cabang)
            ->count();
    }

    public static function getTotalTransaksiTugas($id_cabang)
    {
        return DB::table('pengiriman')
            ->where('dari_cabang', $id_cabang)
            //            ->orWhere('pengiriman.status_sent', 3)
            ->where('pengiriman.status_sent', 1)
            //            ->orWhere('pengiriman.status_sent', 2)
            ->count();
    }

    public static function getDetailData($id_pengiriman)
    {
        return DB::table('pengiriman')
            ->select(
                '*',
                'member_sales.nama as nama_member'
            )
            ->join('cabang', 'pengiriman.id_cabang_tujuan', '=', 'cabang.id_cabang')
            ->join('tipe_pembayaran', 'tipe_pembayaran.id_pembayaran', '=', 'pengiriman.tipe_pembayaran')
            ->join('tipe_pelayanan', 'tipe_pelayanan.id_pelayanan', '=', 'pengiriman.no_pelayanan')
            ->join('member_sales', 'member_sales.id_member_sales', '=', 'pengiriman.id_member_sales')
            ->where('id_pengiriman', $id_pengiriman)
            ->first();
    }

    public static function getLastUrut($id_cabang)
    {
        return DB::table('pengiriman')
            ->select(
                'pengiriman.no_resi'
            )
            ->join('cabang', 'pengiriman.dari_cabang', '=', 'cabang.id_cabang')
            ->where('dari_cabang', $id_cabang)
            ->latest();
    }

    public static function getTracking($id_cabang)
    {
        return DB::table('pengiriman')
            ->select('no_resi')
            ->where('pengiriman.dari_cabang', $id_cabang)
            ->where(DB::raw("(DATE_FORMAT(tgl_masuk, '%m'))"), Date('m'))
            ->where(DB::raw("(DATE_FORMAT(tgl_masuk, '%Y'))"), Date('Y'))
            ->orderBy('id_pengiriman', 'DESC')
            ->first();
    }

    public static function addKoli($jkoli, $idpeng)
    {
        $pengiriman = M_pengiriman::getDetailData($idpeng);
        $noresi = $pengiriman->no_resi;
        $jkoli = $jkoli == 0 ? 1 : $jkoli;
        for ($i = 1; $i <= $jkoli; $i++) {
            $create[] = [
                'id_pengiriman' => $idpeng,
                'nama_identitas' => $noresi . '-koli' . $i,
            ];
        }
        $insert = DB::table('resi_identitas')
            ->insert($create);
        return $insert;
    }

    public static function cekIdentitas($nama_identitas)
    {
        return DB::table('resi_identitas')
            ->where('nama_identitas', '=', $nama_identitas)->first();
    }

    public static function updateIdentitas($nama_identitas, $status)
    {
        return DB::table('resi_identitas')
            ->where('nama_identitas', '=', $nama_identitas)
            ->update([
                'status' => $status,
            ]);
    }

    public static function updateIdentitas2($nama_identitas, $status)
    {
        return DB::table('resi_identitas')
            ->where('nama_identitas', '=', $nama_identitas)
            ->update([
                'status2' => $status,
            ]);
    }

    public function cabang()
    {
        return $this->belongsTo(M_cabang::class, 'id_cabang_tujuan', 'id_cabang');
    }

    public function asal_cabang()
    {
        return $this->belongsTo(M_cabang::class, 'dari_cabang', 'id_cabang');
    }

    public function member_sales()
    {
        return $this->belongsTo(M_member_sales::class, 'id_member_sales', 'id_member_sales');
    }
}
