<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FornecedorFactory extends Factory
{
    public function definition()
    {
        return [
            'documento' => $this->faker->numerify('###########'),
            'nome' => $this->faker->company,
            'ativo' => $this->faker->boolean,
        ];
    }
}
