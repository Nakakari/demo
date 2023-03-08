<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_status_pengiriman extends Model
{
    use HasFactory;
    protected $table = 'tbl_status_pengiriman';

    public function statusEceran()
    {
        return M_status_pengiriman::where('id_stst_pngrmn', '=', 4)
            ->orWhere('id_stst_pngrmn', '=', 5)
            ->orWhere('id_stst_pngrmn', '=', 6)
            ->orWhere('id_stst_pngrmn', '=', 8)
            ->get();
    }
}
