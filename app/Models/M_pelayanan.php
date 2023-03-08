<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_pelayanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_pelayanan', 'created_by', 'updated_by'
    ];
    protected $table = 'tipe_pelayanan';
}
