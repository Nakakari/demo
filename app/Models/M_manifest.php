<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_manifest extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_manifest',
        'no_manifest',
        'driver',
        'no_tlp_driver',
        'nopol',
        'jenis_kendaraan',
        'tujuan',
        'estimasi_tiba',
        'tgl_buat',
        'status',
        'id_cabang'
    ];
    protected $table = 'manifest';
    protected $primaryKey = 'id_manifest';
    public $timestamps = false;

    public static function getModa()
    {
        return DB::table('moda')
            ->select(
                '*'
            )
            ->get();
    }

    public static function getJeken($jeken_id)
    {
        $get = DB::table('moda')
            ->select(
                'moda.nama_moda'
            )
            ->where('moda.id_moda', '=', $jeken_id)
            ->get()->toArray();
        $get2 = $get[0]['nama_moda'];
        return $get2;
    }

    public static function getTracking($id_cabang)
    {
        return DB::table('manifest')
            ->select('no_manifest')
            ->where('manifest.id_cabang', $id_cabang)
            ->where(DB::raw("(DATE_FORMAT(tgl_buat, '%m'))"), Date('m'))
            ->where(DB::raw("(DATE_FORMAT(tgl_buat, '%Y'))"), Date('Y'))
            ->orderBy('id_manifest', 'DESC')
            ->first();
    }

    public function detailManifest()
    {
        return $this->hasMany(M_detail_manifest::class, 'manifest_id', 'id_manifest');
    }
}
