<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class statusPengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Order Masuk',
            'class' => 'class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Perjalanan Ke Kota Tujuan',
            'class' => 'class="badge rounded-pill text-primary bg-light-primary p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Transit',
            'class' => 'class="badge rounded-pill text-warning bg-light-warning p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Sampai di Warehouse Kota Tujuan',
            'class' => 'class="badge rounded-pill text-success bg-light-success p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Proses Antar Kurir',
            'class' => 'class="badge rounded-pill text-purple bg-light-purple p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Diterima dengan Baik',
            'class' => 'class="badge rounded-pill text-info bg-light-info p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Cancelled',
            'class' => 'class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tbl_status_pengiriman')->insert([
            'nama_status' => 'Pengiriman Ulang',
            'class' => 'class="badge rounded-pill text-danger bg-light-danger p-2 text-uppercase px-3"',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
