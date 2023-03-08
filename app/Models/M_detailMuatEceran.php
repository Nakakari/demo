<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_detailMuatEceran extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_detail_muat_eceran',
        'muat_eceran_id',
        'identitas_id',
        'status_sent',
    ];
    protected $table = 'detail_muat_eceran';
    protected $primaryKey = 'id_detail_muat_eceran';
}
