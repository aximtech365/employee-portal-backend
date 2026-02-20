<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Human Resources',
                'Finance',
                'Information Technology',
                'Marketing',
                'Operations',
            ]),
        ];
    }
}
