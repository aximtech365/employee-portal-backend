<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'file_name' => fake()->word() . '.pdf',
            'file_path' => 'documents/' . fake()->uuid() . '.pdf',
            'file_type' => 'pdf',
            'file_size' => fake()->numberBetween(10000, 10000000),
            'category_id' => DocumentCategory::factory(),
            'department_id' => Department::factory(),
            'uploaded_by' => User::factory(),
            'access_level' => fake()->randomElement(['public', 'department', 'private']),
            'download_count' => fake()->numberBetween(0, 100),
        ];
    }
}
