<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_beaTambahanInvoice extends Model
{
    protected $fillable = [
        'id_invoice',
        'bea_tambahan',
        'nominal_bea'
    ];
    protected $table = 'tbl_bea_tambahan_invoice';
    protected $primaryKey = 'id_bea_tambahan_invoice';
    public $timestamps = false;
}
