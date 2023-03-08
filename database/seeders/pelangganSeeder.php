<?php

namespace Database\Seeders;

use App\Models\M_pelanggan;
use Illuminate\Database\Seeder;

class pelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        M_pelanggan::factory(20)->create();
    }
}
