<?php

namespace Database\Seeders;

use App\Models\M_pengiriman;
use App\Models\M_resi_identitas;
use Illuminate\Database\Seeder;

class pengirimanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        M_pengiriman::factory()->count(10000)
            ->create()->each(
                function ($pengiriman) {
                    M_resi_identitas::factory()->create([
                        'id_pengiriman' => $pengiriman->id_pengiriman,
                        'nama_identitas' => $pengiriman->no_resi . "-koli" . 1
                    ]);
                }
            );
    }
}
