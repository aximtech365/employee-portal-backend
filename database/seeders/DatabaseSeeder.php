<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            DocumentCategorySeeder::class,
            UserSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
