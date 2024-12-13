<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Telefone;

class TelefoneSeeder extends Seeder
{
    public function run()
    {
        Telefone::factory()->count(15)->create();
    }
}
