<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_member_sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_member_sales', 'uuid', 'kode', 'nama', 'id_sales', 'created_by', 'updated_by'
    ];
    protected $table = 'member_sales';

    public function dataTables($uuid)
    {
        return M_member_sales::select([
            'kode', 'nama', 'id_member_sales', 'member_sales.uuid',
        ])
            ->join('users', 'users.id', '=', 'member_sales.id_sales')
            ->where('users.uuid', $uuid);
    }

    public function sales()
    {
        return $this->belongsTo(User::class, 'id_sales', 'id');
    }
}
