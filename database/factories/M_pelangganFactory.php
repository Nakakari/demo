<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class M_pelangganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_perusahaan' => $this->faker->company(),
            'kota' => $this->faker->city(),
            'alamat' => $this->faker->address(),
            'nama_spv' => $this->faker->name(),
            'tlp_spv' => $this->faker->phoneNumber(),
            'k_perusahaan' => $this->faker->countryCode(),
            'email_prshn' => $this->faker->safeEmail(),
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
