<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user - use firstOrCreate to avoid duplicates on re-deploy
        User::firstOrCreate(
            ['email' => 'admin@scieval.com'],
            [
                'name'              => 'Admin Kumar',
                'password'          => Hash::make('Admin@1234'),
                'role'              => 'admin',
                'institution'       => 'Science Evaluation Platform',
                'email_verified_at' => now(),
            ]
        );

        // If old admin email exists, update it to new one
        User::where('email', 'admin@scienceeval.com')->update([
            'email'             => 'admin@scieval.com',
            'password'          => Hash::make('Admin@1234'),
            'email_verified_at' => now(),
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
            User::firstOrCreate(
                ['email' => $student['email']],
                [
                    'name'              => $student['name'],
                    'password'          => Hash::make('password'),
                    'role'              => 'student',
                    'institution'       => 'Government Engineering College',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
