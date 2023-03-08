<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tipePembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipe_pembayaran')->insert([
            'nama_tipe_pemb' => 'Tagihan',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tipe_pembayaran')->insert([
            'nama_tipe_pemb' => 'COD',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tipe_pembayaran')->insert([
            'nama_tipe_pemb' => 'Cash',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
