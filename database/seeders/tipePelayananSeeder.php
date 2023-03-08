<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class tipePelayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipe_pelayanan')->insert([
            'nama_pelayanan' => 'One Day Service',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
        DB::table('tipe_pelayanan')->insert([
            'nama_pelayanan' => 'Cargo',
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
