<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssistanceTeacher>
 */
class AssistanceTeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $counter = User::count();
        $periods = Period::pluck('name');

        $educational_platforms = [
            "Moodle Institucional", 
            "Google Meet", 
            fake()->word(), 
            "Moodle Institucional, Google Meet", 
            "Google Meet, ".fake()->word(), 
            "Moodle Institucional, ".fake()->word(), 
            "Moodle Institucional, Google Meet, ".fake()->word()
        ];

        return [
            'user_id' => fake()->numberBetween(2, $counter),
            //'teacher_id' => rand(1, $counter),
            'training_module' => fake()->randomElement(["Profesional/Especialidad", "Transversal/Empleabilidad"]),
            'period' => fake()->randomElement($periods),
            'turn' => fake()->randomElement(["Diurno","Nocturno"]),
            'didactic_unit' => fake()->paragraph(),
            'checkin_time' => date('Y-m-d H:i', time()),
            'departure_time' => date('Y-m-d H:i', strtotime('+3 hour')),
            'theme' => fake()->sentence(),
            'place' => fake()->randomElement(["Aula","Laboratorio","Taller",fake()->word()]),
            'educational_platforms' => fake()->randomElement($educational_platforms),
            'remarks' => fake()->paragraph(),
            'remember_token' => Str::random(50)
        ];
    }
}
