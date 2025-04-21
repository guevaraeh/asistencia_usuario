<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'lastname' => 'Docentes',
            'email' => 'admin@example.com',
            'username' => 'admin123',
            'is_admin' => 1,
            'phone' => '34534567',
            'password' => Hash::make('administrador'),
            'remember_token' => Str::random(10),
        ]);

        User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/
    }
}
