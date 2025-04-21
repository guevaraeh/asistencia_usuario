<?php

namespace Database\Seeders;

use App\Models\AssistanceTeacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssistanceTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssistanceTeacher::factory(100)->create();
    }
}
