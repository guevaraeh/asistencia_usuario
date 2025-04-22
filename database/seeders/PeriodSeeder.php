<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::factory()->create([
            'name' => 'Primero',
        ]);

        Period::factory()->create([
            'name' => 'Segundo',
        ]);

        Period::factory()->create([
            'name' => 'Tercero',
        ]);

        Period::factory()->create([
            'name' => 'Cuarto',
        ]);

        Period::factory()->create([
            'name' => 'Quinto',
        ]);

        Period::factory()->create([
            'name' => 'Sexto',
        ]);
    }
}
