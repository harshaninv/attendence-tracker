<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin for testing admin role
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Teacher One (the one for primary testing)
        User::create([
            'name' => 'Teacher One',
            'email' => 'teacher@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Teacher Two
        User::create([
            'name' => 'Teacher Two',
            'email' => 'teacher2@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        // Teacher Three
        User::create([
            'name' => 'Teacher Three',
            'email' => 'teacher3@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);


        User::factory()->count(2)->create(['role' => 'teacher']); // Create more dummy teachers
    }
}