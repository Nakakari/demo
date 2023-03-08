<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class kondisiResiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_kondisi_resi')->insert([
            'nama_kondisi_resi' => 'SURAT BELUM KEMBALI',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_kondisi_resi')->insert([
            'nama_kondisi_resi' => 'TANPA KETERANGAN',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
