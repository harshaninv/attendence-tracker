import React, { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage, useForm } from '@inertiajs/react';
import axios from 'axios'; // We'll use axios for fetching student list (not a full page reload)

export default function MarkAttendance({ subjects }) { // 'subjects' prop comes from Laravel controller
    const { flash } = usePage().props; // Access flash messages
    const user = usePage().props.auth.user; // Access authenticated user details

    const [students, setStudents] = useState([]);
    const today = new Date().toISOString().slice(0, 10); // Default to today's date

    // Inertia's useForm hook for managing form state and submission
    const { data, setData, post, processing, errors, reset } = useForm({
        subject_id: '',
        attendance_date: today,
        attendances: {}, // { student_id: status, ... }
    });

    // Effect to fetch students when a subject is selected
    useEffect(() => {
        if (data.subject_id) {
            const fetchStudents = async () => {
                try {
                    // Use Ziggy's route helper to generate the URL
                    const response = await axios.get(route('api.subjects.students', data.subject_id));
                    setStudents(response.data);

                    // Initialize attendance for all fetched students as 'present'
                    const initialAttendance = {};
                    response.data.forEach(student => {
                        initialAttendance[student.id] = 'present';
                    });
                    setData('attendances', initialAttendance); // Update only the 'attendances' part of the form data
                } catch (err) {
                    console.error('Failed to fetch students:', err);
                    // Handle error, e.g., show a message to the user
                }
            };
            fetchStudents();
        } else {
            setStudents([]);
            setData('attendances', {}); // Clear attendances if no subject is selected
        }
    }, [data.subject_id]); // Re-run when subject_id changes

    const handleAttendanceChange = (studentId, status) => {
        setData('attendances', {
            ...data.attendances,
            [studentId]: status
        });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        // Inertia handles the POST request to the store route
        post(route('attendances.store'), {
            onSuccess: () => {
                // Optionally reset the form or give feedback
                reset('attendances'); // Clear attendance selections
                // Can also reset subject_id and date if desired
            },
        });
    };

    // Render nothing if user is not a teacher (though controller handles this too)
    if (user.role !== 'teacher') {
        return <AuthenticatedLayout><Head title="Unauthorized" /><div className="p-6 text-gray-900">You are not authorized to access this page.</div></AuthenticatedLayout>;
    }

    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Mark Daily Attendance</h2>}
        >
            <Head title="Mark Attendance" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        {/* Flash messages */}
                        {flash.message && (
                            <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                                {flash.message}
                            </div>
                        )}
                        {errors.message && ( // Generic error message from controller
                            <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                {errors.message}
                            </div>
                        )}

                        <form onSubmit={handleSubmit}>
                            <div className="mb-4">
                                <label htmlFor="subject_id" className="block text-gray-700 text-sm font-bold mb-2">Subject:</label>
                                <select
                                    id="subject_id"
                                    className="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value={data.subject_id}
                                    onChange={(e) => setData('subject_id', e.target.value)}
                                    required
                                >
                                    <option value="">Select a Subject</option>
                                    {subjects.map(subject => (
                                        <option key={subject.id} value={subject.id}>{subject.name} ({subject.code})</option>
                                    ))}
                                </select>
                                {errors.subject_id && <div className="text-red-500 text-xs italic mt-1">{errors.subject_id}</div>}
                            </div>

                            <div className="mb-4">
                                <label htmlFor="attendance_date" className="block text-gray-700 text-sm font-bold mb-2">Date:</label>
                                <input
                                    type="date"
                                    id="attendance_date"
                                    className="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value={data.attendance_date}
                                    onChange={(e) => setData('attendance_date', e.target.value)}
                                    required
                                />
                                {errors.attendance_date && <div className="text-red-500 text-xs italic mt-1">{errors.attendance_date}</div>}
                            </div>

                            {students.length > 0 && (
                                <div className="mb-6">
                                    <h3 className="text-xl font-semibold mb-3">Students Enrolled:</h3>
                                    <div className="overflow-x-auto">
                                        <table className="min-w-full bg-white border border-gray-200">
                                            <thead>
                                                <tr>
                                                    <th className="py-2 px-4 border-b text-left">Registration No.</th>
                                                    <th className="py-2 px-4 border-b text-left">Student Name</th>
                                                    <th className="py-2 px-4 border-b text-left">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {students.map(student => (
                                                    <tr key={student.id}>
                                                        <td className="py-2 px-4 border-b">{student.registraion_number}</td>
                                                        <td className="py-2 px-4 border-b">{student.first_name} {student.last_name}</td>
                                                        <td className="py-2 px-4 border-b">
                                                            <label className="inline-flex items-center mr-4">
                                                                <input
                                                                    type="radio"
                                                                    className="form-radio text-blue-600"
                                                                    name={`attendance-${student.id}`}
                                                                    value="present"
                                                                    checked={data.attendances[student.id] === 'present'}
                                                                    onChange={() => handleAttendanceChange(student.id, 'present')}
                                                                />
                                                                <span className="ml-2">Present</span>
                                                            </label>
                                                            <label className="inline-flex items-center mr-4">
                                                                <input
                                                                    type="radio"
                                                                    className="form-radio text-red-600"
                                                                    name={`attendance-${student.id}`}
                                                                    value="absent"
                                                                    checked={data.attendances[student.id] === 'absent'}
                                                                    onChange={() => handleAttendanceChange(student.id, 'absent')}
                                                                />
                                                                <span className="ml-2">Absent</span>
                                                            </label>
                                                            <label className="inline-flex items-center mr-4">
                                                                <input
                                                                    type="radio"
                                                                    className="form-radio text-yellow-600"
                                                                    name={`attendance-${student.id}`}
                                                                    value="late"
                                                                    checked={data.attendances[student.id] === 'late'}
                                                                    onChange={() => handleAttendanceChange(student.id, 'late')}
                                                                />
                                                                <span className="ml-2">Late</span>
                                                            </label>
                                                            <label className="inline-flex items-center">
                                                                <input
                                                                    type="radio"
                                                                    className="form-radio text-gray-600"
                                                                    name={`attendance-${student.id}`}
                                                                    value="excused"
                                                                    checked={data.attendances[student.id] === 'excused'}
                                                                    onChange={() => handleAttendanceChange(student.id, 'excused')}
                                                                />
                                                                <span className="ml-2">Excused</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                        {errors['attendances'] && <div className="text-red-500 text-xs italic mt-1">{errors['attendances']}</div>}
                                    </div>
                                </div>
                            )}

                            <button
                                type="submit"
                                className="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                                disabled={processing || !data.subject_id || students.length === 0}
                            >
                                {processing ? 'Submitting...' : 'Mark Attendance'}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}