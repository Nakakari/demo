<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_resi_identitas extends Model
{
    use HasFactory;
    protected $table = 'resi_identitas';
    protected $primaryKey = 'id_identitas';
    protected $fillable = ['id_identitas', 'id_pengiriman', 'nama_identitas'];
    public $timestamps = false;
}
