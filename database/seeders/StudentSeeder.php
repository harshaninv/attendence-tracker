<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        // Student::factory()->count(20)->create(); // Create 20 dummy students
        Student::create([
            'registraion_number' => 'STU-001',
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'email' => 'alice@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-002',
            'first_name' => 'Bob',
            'last_name' => 'Johnson',
            'email' => 'bob@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-003',
            'first_name' => 'Charlie',
            'last_name' => 'Brown',
            'email' => 'charlie@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-004',
            'first_name' => 'Diana',
            'last_name' => 'Prince',
            'email' => 'diana@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-005',
            'first_name' => 'Eve',
            'last_name' => 'Adams',
            'email' => 'eve@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-006',
            'first_name' => 'Frank',
            'last_name' => 'White',
            'email' => 'frank@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-007',
            'first_name' => 'Grace',
            'last_name' => 'Hall',
            'email' => 'grace@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-008',
            'first_name' => 'Henry',
            'last_name' => 'Green',
            'email' => 'henry@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-009',
            'first_name' => 'Ivy',
            'last_name' => 'King',
            'email' => 'ivy@example.com',
        ]);
        Student::create([
            'registraion_number' => 'STU-010',
            'first_name' => 'Jack',
            'last_name' => 'Lee',
            'email' => 'jack@example.com',
        ]);
    }
}