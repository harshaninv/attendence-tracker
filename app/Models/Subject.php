<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject_code',
    ];

    // relationship with student many to many via pivot table
    public function students() {
        return $this->belongsToMany(Student::class, 'student_subject', 'subject_id', 'student_id')
                    ->withPivot('enrollment_date')
                    ->withTimestamps();
    }

    // relationship with student many to many via pivot table
    public function teachers() {
        return $this->belongsToMany(User::class, 'teacher_subject', 'subject_id', 'user_id');
    }

    // realtionship woth attenences
    public function attendances() {
        return $this->hasMany(Attendance::class);
    }
}
