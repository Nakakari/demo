<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_detailkoli extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_detail_koli',
        'identitas_id',
        'koli_id',
        'cabang_id',
        'cabang_id2',
        'created_by',
        'updated_by'
    ];
    protected $table = 'detail_koli';
    protected $primaryKey = 'id_detail_koli';
    public $timestamps = false;
}
