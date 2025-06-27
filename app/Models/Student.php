<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'registraion_number',
        'first_name',
        'last_name',
        'email',
    ];

    //many to many relationship with subjects through student_subject pivot table
    public function subjects(){
        return $this->belongsToMany(Subject::class, 'student_subject', 'student_id', 'subject_id')
                    ->withPivot('enrollment_date')
                    ->withTimestamps();
    }

    //reltionship with attendences
    public function attendances(){
        return $this->hasMany(Attendance::class);
    }
}
