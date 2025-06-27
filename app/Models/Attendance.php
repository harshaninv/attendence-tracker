<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'subject_id',
        'attendance_date',
        'status',
        'recorded_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    // relationship with student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // relationship with subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // relationship with the user (teacher) who recorded it
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
