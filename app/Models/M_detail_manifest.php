<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_detail_manifest extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_detail_manifest',
        'pengiriman_id',
        'manifest_id',
        'estimasi',
    ];
    protected $table = 'detail_manifest';
    protected $primaryKey = 'id_detail_manifest';
    public $timestamps = false;

    public function pengiriman()
    {
        return $this->belongsTo(M_pengiriman::class, 'pengiriman_id', 'id_pengiriman');
    }
}
