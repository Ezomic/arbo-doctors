<?php

namespace Database\Factories;

use App\Models\MedicalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MedicalCase>
 */
class MedicalCaseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'tenant_id' => (string) Str::uuid(),
            'case_id' => (string) Str::uuid(),
            'doctor_user_id' => User::factory(),
            'employer_name' => fake()->company(),
            'employee_first_name' => fake()->firstName(),
            'employee_last_name' => fake()->lastName(),
            'status' => 'open',
            'opened_at' => now(),
        ];
    }
}
