<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_pelanggan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_perusahaan', 'kota', 'alamat', 'nama_spv', 'tlp_spv', 'k_perusahaan', 'email_prshn'
    ];
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $timestamps = false;

    public static function getAll()
    {
        return DB::table('pelanggan')
            ->select(
                '*'
            )
            ->get();
    }

    public static function getNama($id_pelanggan)
    {
        return DB::table('pelanggan')
            ->select(
                'pelanggan.nama_perusahaan'
            )
            ->where('id_pelanggan', $id_pelanggan)
            ->first();
    }
    public static function detailPelanggan($id_pelanggan)
    {
        return DB::table('pelanggan')
            ->select(
                'nama_perusahaan',
                'kota',
                'alamat',
                'nama_spv',
                'tlp_spv',
                'k_perusahaan',
                'email_prshn'
            )
            ->where('id_pelanggan', $id_pelanggan)
            ->get();
    }

    public static function getStatusSent()
    {
        return DB::table('tbl_status_pengiriman')
            ->select(
                '*'
            )
            ->get();
    }

    public static function getModa()
    {
        return DB::table('moda')
            ->select(
                '*'
            )
            ->get();
    }

    public static function getPembayaran()
    {
        return DB::table('tipe_pembayaran')
            ->select(
                '*'
            )
            ->get();
    }

    public static function getPelayanan()
    {
        return DB::table('tipe_pelayanan')
            ->select(
                '*'
            )
            ->get();
    }
}
