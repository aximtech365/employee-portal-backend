<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@abccorp.com',
            'password' => Hash::make('password'),
            'department_id' => 1,
        ]);
        $admin->assignRole('admin');

        $managers = [
            ['name' => 'HR Manager', 'email' => 'manager.hr@abccorp.com', 'department_id' => 1],
            ['name' => 'Finance Manager', 'email' => 'manager.finance@abccorp.com', 'department_id' => 2],
            ['name' => 'IT Manager', 'email' => 'manager.it@abccorp.com', 'department_id' => 3],
            ['name' => 'Marketing Manager', 'email' => 'manager.marketing@abccorp.com', 'department_id' => 4],
        ];

        foreach ($managers as $managerData) {
            $manager = User::create([
                'name' => $managerData['name'],
                'email' => $managerData['email'],
                'password' => Hash::make('password'),
                'department_id' => $managerData['department_id'],
            ]);
            $manager->assignRole('manager');
        }

        $employees = [
            ['name' => 'John Doe', 'email' => 'employee1@abccorp.com', 'department_id' => 1],
            ['name' => 'Jane Smith', 'email' => 'employee2@abccorp.com', 'department_id' => 2],
            ['name' => 'Bob Johnson', 'email' => 'employee3@abccorp.com', 'department_id' => 3],
            ['name' => 'Alice Williams', 'email' => 'employee4@abccorp.com', 'department_id' => 4],
            ['name' => 'Charlie Brown', 'email' => 'employee5@abccorp.com', 'department_id' => 5],
            ['name' => 'Diana Prince', 'email' => 'employee6@abccorp.com', 'department_id' => 1],
            ['name' => 'Eve Davis', 'email' => 'employee7@abccorp.com', 'department_id' => 2],
        ];

        foreach ($employees as $employeeData) {
            $employee = User::create([
                'name' => $employeeData['name'],
                'email' => $employeeData['email'],
                'password' => Hash::make('password'),
                'department_id' => $employeeData['department_id'],
            ]);
            $employee->assignRole('employee');
        }
    }
}
