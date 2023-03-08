<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class M_tracking extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid', 'pengiriman_id', 'status_sent', 'cabang_id', 'file_bukti', 'ket', 'created_by', 'updated_by'
    ];
    protected $table = 'tracking';
    protected $primaryKey = 'id_tracking';
    public $timestamps = true;

    public static function tambahTracking($id_pengiriman, $status_sent, $id_cabang, $file_bukti, $ket)
    {
        $form = [
            'status_sent' => $status_sent,
            'cabang_id' => $id_cabang,
            'created_by' => Auth::user()->id,
        ];
        if (is_array($id_pengiriman) || is_object($id_pengiriman)) {
            foreach ($id_pengiriman as $id_peng) {
                $form = array_merge($form, [
                    'uuid' => Uuid::uuid4()->toString(),
                    'pengiriman_id' => $id_peng
                ]);
                $data = M_tracking::create($form);
            }
        } else {
            $form = array_merge($form, [
                'uuid' => Uuid::uuid4()->toString(),
                'pengiriman_id' => $id_pengiriman,
                'file_bukti' => $file_bukti,
                'ket' => $ket,
            ]);
            $data = M_tracking::create($form);
        }

        return $data;
    }

    public static function getData($id)
    {
        return DB::table('tracking')
            ->select(
                'tbl_status_pengiriman.nama_status',
                DB::raw("(DATE_FORMAT(tracking.created_at,'%d %M %Y %H:%i:%s')) as created_at"),
                DB::raw("(DATE_FORMAT(tracking.updated_at,'%d %M %Y %H:%i:%s')) as updated_at"),
                DB::raw('UPPER(cabang.nama_kota) as nama_kota'),
                'tracking.file_bukti',
                'tracking.ket',
            )
            ->leftjoin('cabang', 'cabang.id_cabang', '=', 'tracking.cabang_id')
            ->leftjoin('tbl_status_pengiriman', 'tbl_status_pengiriman.id_stst_pngrmn', '=', 'tracking.status_sent')
            ->leftjoin('pengiriman', 'pengiriman.id_pengiriman', '=', 'tracking.pengiriman_id')
            ->where('pengiriman_id', $id)
            ->get();
    }

    public function status()
    {
        return $this->belongsTo(M_status_pengiriman::class, 'status_sent', 'id_stst_pngrmn');
    }

    public function pengiriman()
    {
        return $this->belongsTo(M_pengiriman::class, 'pengiriman_id', 'id_pengiriman');
    }
}
