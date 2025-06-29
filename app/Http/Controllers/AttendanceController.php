<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function markAttendanceForm()
    {
        // Ensure the authenticated user is a teacher
        if (auth()->user()->role !== 'teacher') {
            return Inertia::render('Forbidden');
        }

        $teacherSubjects = auth()->user()->subjects()->select('subjects.id', 'subjects.name', 'subjects.subject_code')->get();

        return Inertia::render(
            'Attendance/Mark',
            [
                'subjects' => $teacherSubjects,
                'flash' => [
                    // flash messages passed to the frontend
                    'message' => session('message'),
                ],
            ]
        );
    }

    public function store(Request $request)
    {

        $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'attendance_date' => ['required', 'date'],
            'attendances' => ['required', 'array', 'min:1'], 

            'attendances.*' => [
                'required',
                Rule::in(['present', 'absent']),
                // Custom rule to check if the array key (which Laravel treats as student_id) exists in the students table
                function ($attribute, $value, $fail) {
                    // $attribute will be in the format 'attendances.STUDENT_ID'
                    $parts = explode('.', $attribute);
                    $studentId = end($parts); // Get the last part, which is the student ID

                    // Validate if the extracted student ID is numeric and exists in the students table
                    if (!is_numeric($studentId) || !DB::table('students')->where('id', $studentId)->exists()) {
                        $fail("The student ID ({$studentId}) for attendance is invalid or not found.");
                    }
                },
            ],
        ]);

        // $request->validate([
        //     'subject_id' => ['required', 'exists:subjects,id'],
        //     'attendance_date' => ['required', 'date'],
        //     'attendances.*.student_id' => ['required', 'exists:students,id'],
        //     'attendances.*.status' => ['required', 'in:present,absent'],
        // ]);


        // dd($request->all());

        $subjectId = $request->input('subject_id');
        $attendanceDate = $request->input('attendance_date');
        $teacherId = auth()->id(); 

        // Authorization: Check if the logged-in user is a teacher AND assigned to this subject
        if (auth()->user()->role !== 'teacher' || !auth()->user()->subjects->contains($subjectId)) {
            // Redirect back with an error if unauthorized
            return back()->withErrors(['message' => 'Unauthorized to mark attendance for this subject.']);
        }

        DB::beginTransaction(); // Start a database transaction

        try {
            // dd($request->all());
            // First, delete any existing attendance records for this subject and date
            Attendance::where('subject_id', $subjectId)
                ->where('attendance_date', $attendanceDate)
                ->delete();

            // Create new attendance records based on the submitted data
            foreach ($request->input('attendances') as $studentId => $status) {
                // Ensure $studentId is explicitly cast to an integer for database consistency
                $studentId = (int) $studentId;

                // Check if the student is actually enrolled in this specific subject
                $isStudentEnrolled = DB::table('student_subject')
                    ->where('student_id', $studentId)
                    ->where('subject_id', $subjectId)
                    ->exists();

                if ($isStudentEnrolled) {
                    // Create the attendance record
                    Attendance::create([
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'attendance_date' => $attendanceDate,
                        'status' => $status,
                        'recorded_by' => $teacherId, // Associate with the teacher who recorded it
                    ]);
                } else {
                    // Optional: Log a warning if an attempt is made to mark attendance
                    // for a student not enrolled in the selected subject.
                    \Log::warning("Attendance marking: Student ID '{$studentId}' not enrolled in Subject ID '{$subjectId}'. Record skipped.");

                }
            }

            DB::commit(); // Commit the transaction if all operations are successful

            // Redirect back with a success flash message for Inertia
            return back()->with('message', 'Attendance marked successfully.');

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of any error
            // Redirect back with an error flash message for Inertia
            return back()->withErrors(['message' => 'Failed to mark attendance. Error: ' . $e->getMessage()]);
        }
    }

// Displays the attendance dashboard with filters and calculated percentages.
    public function dashboard(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subWeek()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());
        $subjectId = $request->input('subject_id');
        $searchTerm = $request->input('search_term');
        $perPage = $request->input('per_page', 15); // Default pagination limit

        // Calculate total possible attendance days within the date range.
        // Note: This simple calculation assumes classes every day. Adjust if needed (e.g., weekdays only).
        $totalDaysPeriod = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

        $query = DB::table('attendances')
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('subjects', 'attendances.subject_id', '=', 'subjects.id')
            ->select(
                'students.id as student_id',
                'students.registraion_number',
                'students.first_name',
                'students.last_name',
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                DB::raw('COUNT(CASE WHEN attendances.status = "present" THEN 1 END) as present_count'),
                DB::raw('COUNT(attendances.id) as total_recorded_attendances') // Count of all records for student-subject in range
            )
            ->whereBetween('attendance_date', [$startDate, $endDate]);

        // Apply filters
        if ($subjectId) {
            $query->where('attendances.subject_id', $subjectId);
        }

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('students.first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('students.last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('students.registraion_number', 'like', '%' . $searchTerm . '%'); // Corrected typo here
            });
        }

        // Grouping is crucial for aggregation
        $query->groupBy('students.id', 'subjects.id', 'students.registraion_number', 'students.first_name', 'students.last_name', 'subjects.name');
        $query->orderBy('students.first_name')->orderBy('subjects.name'); // Order for consistent results

        // Paginate the results (essential for performance with many records)
        $studentsAttendance = $query->paginate($perPage)->withQueryString(); // withQueryString keeps existing filters on pagination links

        // Calculate percentage attendance on the collection after fetching
        $studentsAttendance->getCollection()->transform(function ($item) {
            $item->percentage_attendance = ($item->total_recorded_attendances > 0)
                ? round(($item->present_count / $item->total_recorded_attendances) * 100, 2)
                : 0;
            return $item;
        });

        // Get all subjects to populate the filter dropdown on the frontend
        $allSubjects = Subject::select('id', 'name')->get();

        return Inertia::render('Attendance/Dashboard', [ // Maps to resources/js/Pages/Attendance/Dashboard.jsx
            'studentsAttendance' => $studentsAttendance,
            'subjects' => $allSubjects,
            'filters' => [ // Pass back current filter values to maintain state on frontend
                'start_date' => $startDate,
                'end_date' => $endDate,
                'subject_id' => $subjectId,
                'search_term' => $searchTerm,
                'per_page' => $perPage,
            ],
            'flash' => [
                'message' => session('message'), // Flash message for success or errors
            ],
        ]);
    }
}