<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class M_member_salesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::all()->random();
        return [
            'uuid' => $this->faker->uuid(),
            'kode' => $this->faker->unique()->countryCode(),
            'nama' => $this->faker->name(),
            'id_sales' => 2,
            'created_by' => 1,
            'updated_by' => 1,
        ];
    }
}
