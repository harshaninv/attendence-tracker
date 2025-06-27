<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeacherSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        $subjects = Subject::all();

        foreach ($teachers as $teacher) {
            // Assign 2-3 random subjects to each teacher
            $teacher->subjects()->attach(
                $subjects->random(rand(2, 3))->pluck('id')->toArray()
            );
        }
    }
}