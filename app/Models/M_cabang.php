<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_cabang extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_kota', 'kode_area'
    ];
    protected $table = 'cabang';
    protected $primaryKey = 'id_cabang';
    public $timestamps = false;

    public static function getAll()
    {
        return DB::table('cabang')
            ->select(DB::raw('UPPER(cabang.nama_kota) as nama_kota'), 'cabang.id_cabang', DB::raw('UPPER(cabang.kode_area) as kode_area'))
            ->get();
    }

    public static function getKode($id_cabang)
    {
        return DB::table('cabang')
            ->select('kode_area')
            ->where('id_cabang', $id_cabang)
            ->first();
    }

    public static function getStatus()
    {
        return DB::table('tbl_status_pengiriman')
            ->select('*')
            ->get();
    }

    public static function getKondisi()
    {
        return DB::table('tbl_kondisi_resi')
            ->select('id_kondisi_resi', 'nama_kondisi_resi')
            ->get();
    }
}
