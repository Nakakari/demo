<?php

namespace Database\Factories;

use App\Models\M_akunBank;
use App\Models\M_pelanggan;
use App\Models\M_pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;

class M_invoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $bank = M_akunBank::all()->random();
        $pelanggan = M_pelanggan::all()->random();
        $pengguna = M_pengguna::all()->random();
        return [
            'no_invoice' => $this->faker->randomNumber(),
            'id_bank' => $bank->id_bank,
            'jatuh_tempo' => $this->faker->date(),
            'pembuat' => $this->faker->name(),
            'diterbitkan' => $this->faker->date(),
            'ppn' => $this->faker->randomDigit(1),
            'keterangan' => $this->faker->text(),
            'biaya_invoice' => $this->faker->numberBetween(1000000, 5000000),
            'ttl_biaya_invoice' => $this->faker->numberBetween(1000000, 5000000),
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'is_lunas' => $this->faker->numberBetween(0, 1),
            'created_by' => $pengguna->id,
            'updated_by' => $pengguna->id,
        ];
    }
}
