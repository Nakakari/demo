<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_pengguna extends Model
{
    use HasFactory;
    protected $fillable = [
        'uuid', 'name', 'peran', 'email', 'password', 'file_foto', 'tgl_lhr', 'alamat', 'id_cabang'
    ];
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    public static function getAll()
    {
        return DB::table('users')
            ->select('*')
            ->get();
    }
}
