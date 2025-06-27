<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        Subject::create(['name' => 'Mathematics I', 'subject_code' => 'MA101']);
        Subject::create(['name' => 'Physics I', 'subject_code' => 'PH101']);
        Subject::create(['name' => 'Computer Science Fundamentals', 'subject_code' => 'CS101']);
        Subject::create(['name' => 'Introduction to Programming', 'subject_code' => 'CS102']);
        Subject::create(['name' => 'English for Academics', 'subject_code' => 'EN101']);
    }
}