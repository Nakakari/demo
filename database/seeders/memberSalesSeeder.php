<?php

namespace Database\Seeders;

use App\Models\M_member_sales;
use Illuminate\Database\Seeder;

class memberSalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        M_member_sales::factory(10)->create();
    }
}
