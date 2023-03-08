<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_kolicek extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_kolicek',
        'driver',
        'nopol',
        'tgl_buat',
        'jumlah_resi',
        'jumlah_koli',
        'jumlah_resi2',
        'jumlah_koli2',
        'id_cabang',
        'id_cabang2',
        'created_by',
        'updated_by'
    ];
    protected $table = 'koli_cek';
    protected $primaryKey = 'id_kolicek';
    public $timestamps = false;
}
