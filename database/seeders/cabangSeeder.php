<?php

namespace Database\Seeders;

use App\Models\M_cabang;
use Illuminate\Database\Seeder;

class cabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        M_cabang::create([
            'nama_kota' => 'Ngawi',
            'kode_area' => 'Ngw',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_cabang::create([
            'nama_kota' => 'Nganjuk',
            'kode_area' => 'Njk',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        M_cabang::create([
            'nama_kota' => 'Surabaya',
            'kode_area' => 'Sby',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
