<?php

namespace Database\Factories;

use App\Models\M_pengiriman;
use Illuminate\Database\Eloquent\Factories\Factory;

class M_resi_identitasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_pengiriman' => M_pengiriman::factory(),
            'nama_identitas' => M_pengiriman::factory(),
        ];
    }
}
