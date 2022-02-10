<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProdiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama_prodi' => $this->faker->sentence(2),
            'faculty_id' => $this->faker->numberBetween(1, 15),
        ];
    }
}
