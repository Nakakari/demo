<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_jabatan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_jabatan'
    ];
    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';
    public $timestamps = false;

    public static function getJab()
    {
        return DB::table('jabatan')
            ->select('*')
            ->get();
    }
}
