<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            instansiSeeder::class,
            jenisJabatanSeeder::class,
            cabangSeeder::class,
            penggunaSeeder::class,
            memberSalesSeeder::class,
            bankSeeder::class,
            tipePembayaranSeeder::class,
            tipePelayananSeeder::class,
            kondisiResiSeeder::class,
            statusPengirimanSeeder::class,
            pelangganSeeder::class,
            pengirimanSeeder::class,
            resiIdentitasSeeder::class,
        ]);
    }
}
