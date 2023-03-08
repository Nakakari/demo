<?php

namespace Database\Factories;

use App\Models\M_invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class M_klaimInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $invoice = M_invoice::all()->random();
        return [
            'id_invoice' => $invoice->id_invoice,
            'klaim' => $this->faker->title(),
            'nominal_klaim' => $this->faker->numberBetween(100000, 500000),
        ];
    }
}
