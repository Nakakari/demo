<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_klaimInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_invoice',
        'klaim',
        'nominal_klaim'
    ];
    protected $table = 'tbl_klaim_invoice';
    protected $primaryKey = 'id_klaim_invoice';
    public $timestamps = false;
}
