<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nidn' => $this->faker->numerify('##########'),
            'nama' => $this->faker->name(),
            'jabatan_id' => $this->faker->numberBetween(1, 2),
            'prodi_id' => $this->faker->numberBetween(1, 10),
            'handphone' => $this->faker->numerify('############'),
            'email' => $this->faker->email(),
        ];
    }
}
