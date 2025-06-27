<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function getTeacherSubjects() {
        if(auth()->user()->role !== 'teacher'){
            return response()->json(['message' => 'Unauthorized access'], 403);


        }

        $teacherSubjects = auth()->user()->subjects()->select('subjects.id', 'subjects.name', 'subjects.subject_code')->get();

        return response()->json($teacherSubjects);
    }

    public function getStudentsForSubject(Subject $subject) {
        if(auth()->user()->role === 'teacher' && !auth()->user()->subjects->contains($subject->id)){
            return response()->json(['message' => 'Unauthorized to view students for this subject'], 403);
        }

        $students = $subject->students()->select('students.id', 'students.registration_number', 'students.first_name', 'students.last_name')->get();

        return response()->json($students);
    }

    public function getAllSubjects(){
        $subjects = Subject::select('id', 'name', 'code')->get();
        return response()->json($subjects);
    }

}
