<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TelefoneFactory extends Factory
{
    public function definition()
    {
        return [
            'telefone' => $this->faker->phoneNumber,
        ];
    }
}
