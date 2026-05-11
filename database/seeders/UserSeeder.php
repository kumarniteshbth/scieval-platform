<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'        => 'Admin Kumar',
            'email'       => 'admin@scienceeval.com',
            'password'    => Hash::make('password'),
            'role'        => 'admin',
            'institution' => 'Science Evaluation Platform',
        ]);

        // Sample students
        $students = [
            ['name' => 'Arjun Sharma',   'email' => 'arjun@student.com'],
            ['name' => 'Priya Patel',    'email' => 'priya@student.com'],
            ['name' => 'Rohit Singh',    'email' => 'rohit@student.com'],
            ['name' => 'Ananya Gupta',   'email' => 'ananya@student.com'],
            ['name' => 'Dev Malhotra',   'email' => 'dev@student.com'],
        ];

        foreach ($students as $student) {
            User::create([
                'name'        => $student['name'],
                'email'       => $student['email'],
                'password'    => Hash::make('password'),
                'role'        => 'student',
                'institution' => 'Government Engineering College',
            ]);
        }
    }
}
