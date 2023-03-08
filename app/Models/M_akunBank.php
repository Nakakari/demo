<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class M_akunBank extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_bank',
        'no_rek',
        'an'
    ];
    protected $table = 'bank';
    protected $primaryKey = 'id_bank';
    public $timestamps = false;

    public static function getAll()
    {
        return DB::table('bank')
            ->select(
                '*'
            )
            ->get();
    }
}
