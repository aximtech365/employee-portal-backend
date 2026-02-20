<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Human Resources',
            'Finance',
            'Information Technology',
            'Marketing',
            'Operations',
        ];

        foreach ($departments as $dept) {
            Department::create(['name' => $dept]);
        }
    }
}
