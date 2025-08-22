<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'department_id' => \App\Models\Department::inRandomOrder()->first()->id ?? \App\Models\Department::factory(),
            'position' => $this->faker->jobTitle,
            'salary' => $this->faker->randomFloat(2, 30000, 100000),
            'hire_date' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'date_of_birth' => $this->faker->dateTimeBetween('-60 years', '-20 years'),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'photo' => null,
        ];
    }
}