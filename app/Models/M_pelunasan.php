<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_pelunasan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_invoice', 'nominal_pelunasan'
    ];
    protected $table = 'tbl_pelunasan';
    protected $primaryKey = 'id_pelunasan';
    public $timestamps = true;
}
