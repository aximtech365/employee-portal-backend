<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Policy', 'Report', 'Template', 'Guide', 'Form', 'Other']),
            'description' => fake()->sentence(),
        ];
    }
}
