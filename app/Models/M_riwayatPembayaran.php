<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_riwayatPembayaran extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_invoice', 'pembayaran', 'created_by', 'updated_by'
    ];
    protected $table = 'riwayat_pembayaran';
    protected $primaryKey = 'id_riwayat_pembayaran';
    public $timestamps = true;

    public function datatables($id_invoice)
    {
        return M_riwayatPembayaran::select([
            'riwayat_pembayaran.pembayaran',
            'riwayat_pembayaran.created_at',
            'tbl_invoice.no_invoice'
        ])
            ->leftjoin('tbl_invoice', 'tbl_invoice.id_invoice', '=', 'riwayat_pembayaran.id_invoice')
            ->where('riwayat_pembayaran.id_invoice', $id_invoice)
            ->orderBy('id_riwayat_pembayaran', "asc");
    }
}
