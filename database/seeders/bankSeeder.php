<?php

namespace Database\Seeders;

use App\Models\M_akunBank;
use Illuminate\Database\Seeder;

class bankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        M_akunBank::create([
            'nama_bank' => 'BRI',
            'no_rek' => '12312321',
            'an' => 'Ahmad',
            'created_by' => 1,
            'updated_by' => 1
        ]);
    }
}
