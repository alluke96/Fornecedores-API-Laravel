<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fornecedor;

class FornecedorSeeder extends Seeder
{
    public function run()
    {
        Fornecedor::factory()->count(10)->create();
    }
}
