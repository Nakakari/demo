<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_muatEceran extends Model
{
    use HasFactory;
    protected $fillable = [
        'driver',
        'nopol',
        'no_tlp',
        'jenis_kendaraan',
        'jumlah_resi',
        'created_by',
        'updated_by',
    ];
    protected $table = 'muat_eceran';
    protected $primaryKey = 'id_muat_eceran';

    public function dataTables($id_cabang)
    {
        return
            M_muatEceran::query()->select([
                'muat_eceran.id_muat_eceran',
                'muat_eceran.driver',
                'muat_eceran.nopol',
                'muat_eceran.jumlah_resi',
                'muat_eceran.created_by',
                'users.id_cabang',
                DB::raw("(DATE_FORMAT(muat_eceran.created_at,'%d %M %Y')) as tgl_buat"),
                DB::raw("(DATE_FORMAT(muat_eceran.created_at,'%T')) as jam_buat"),
            ])
            ->join('users', 'muat_eceran.created_by', '=', 'users.id')
            ->where('users.id_cabang', $id_cabang)
            ->orderBy('id_muat_eceran', "desc");
    }
}
