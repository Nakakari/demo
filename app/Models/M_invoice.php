<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_invoice',
        'id_bank',
        'jatuh_tempo',
        'pembuat',
        // 'mengetahui',
        'diterbitkan',
        'ppn',
        'keterangan',
        'biaya_invoice',
        'ttl_biaya_invoice',
        'id_pelanggan',
        'is_lunas',
        'created_by',
        'updated_by',
    ];
    protected $table = 'tbl_invoice';
    protected $primaryKey = 'id_invoice';
    public $timestamps = true;

    public function klaim()
    {
        return $this->hasMany(M_klaimInvoice::class, 'id_invoice', 'id_invoice');
    }

    public function beaTambahan()
    {
        return $this->hasMany(M_beaTambahanInvoice::class, 'id_invoice', 'id_invoice');
    }

    public function detailInvoice()
    {
        return $this->hasMany(M_detailInvoice::class, 'id_invoice', 'id_invoice');
    }

    public function pelanggan()
    {
        return $this->hasOne(M_pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function bank()
    {
        return $this->hasOne(M_akunBank::class, 'id_bank', 'id_bank');
    }
}
