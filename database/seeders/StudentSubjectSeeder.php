<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class StudentSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::all();
        $subjects = Subject::all();

        foreach ($students as $student) {
            // Each student enrolls in minimum of 3 subjects (out of 5 total)
            $student->subjects()->attach(
                $subjects->random(rand(3, 5))->pluck('id')->toArray(),
                ['enrollment_date' => now()->subMonths(rand(1, 6))->toDateString()]
            );
        }
    }
}