import React, { useState, useEffect, useCallback } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, usePage, router } from '@inertiajs/react'; // Import router from Inertia
import { pickBy } from 'lodash'; // To filter out empty values from filters

export default function AttendanceDashboard({ studentsAttendance, subjects, filters: initialFilters }) {
    const { flash, auth } = usePage().props;
    const user = auth.user;

    // Initialize filter state with props from Laravel
    const [filters, setFilters] = useState({
        start_date: initialFilters.start_date || new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().slice(0, 10),
        end_date: initialFilters.end_date || new Date().toISOString().slice(0, 10),
        subject_id: initialFilters.subject_id || '',
        search_term: initialFilters.search_term || '',
        per_page: initialFilters.per_page || 15,
    });

    // Function to apply filters by making an Inertia GET request
    const applyFilters = useCallback(() => {
        // Filter out empty string or null values from filters object
        const queryParams = pickBy(filters);
        // Use Inertia.router.get to make a visit to the current page with new query parameters
        router.get(route('attendances.dashboard'), queryParams, {
            preserveState: true, // Keep the current scroll position and form input values
            preserveScroll: true, // Maintain scroll position
        });
    }, [filters]); // Re-run when filters state changes

    // Effect to apply filters when filters state changes
    // Use a debounce for search term to prevent too many requests
    useEffect(() => {
        const handler = setTimeout(() => {
            applyFilters();
        }, 300); // Debounce for 300ms

        return () => {
            clearTimeout(handler);
        };
    }, [filters.start_date, filters.end_date, filters.subject_id, filters.search_term]); // Re-apply when these specific filters change


    const handleFilterChange = (e) => {
        const { name, value } = e.target;
        setFilters(prev => ({ ...prev, [name]: value, page: 1 })); // Reset to page 1 on filter change
    };

    const handlePerPageChange = (e) => {
        setFilters(prev => ({ ...prev, per_page: e.target.value, page: 1 }));
        // Call applyFilters immediately for per_page change if desired, or let the useEffect handle it.
        // For immediate change, uncomment: applyFilters();
    };

    // Pagination handlers
    const handlePageChange = (pageUrl) => {
        // Inertia's router.get handles pagination links directly if they come from Laravel's paginator
        router.get(pageUrl, {}, { preserveState: true, preserveScroll: true });
    };


    return (
        <AuthenticatedLayout
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Attendance Dashboard</h2>}
        >
            <Head title="Attendance Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        {/* Flash messages */}
                        {flash.message && (
                            <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                                {flash.message}
                            </div>
                        )}

                        {/* Filters Section */}
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div>
                                <label htmlFor="start_date" className="block text-gray-700 text-sm font-bold mb-2">Start Date:</label>
                                <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                    className="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value={filters.start_date}
                                    onChange={handleFilterChange}
                                />
                            </div>
                            <div>
                                <label htmlFor="end_date" className="block text-gray-700 text-sm font-bold mb-2">End Date:</label>
                                <input
                                    type="date"
                                    id="end_date"
                                    name="end_date"
                                    className="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value={filters.end_date}
                                    onChange={handleFilterChange}
                                />
                            </div>
                            <div>
                                <label htmlFor="subject_id" className="block text-gray-700 text-sm font-bold mb-2">Subject:</label>
                                <select
                                    id="subject_id"
                                    name="subject_id"
                                    className="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value={filters.subject_id}
                                    onChange={handleFilterChange}
                                >
                                    <option value="">All Subjects</option>
                                    {subjects.map(subject => (
                                        <option key={subject.id} value={subject.id}>{subject.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <label htmlFor="search_term" className="block text-gray-700 text-sm font-bold mb-2">Search Student:</label>
                                <input
                                    type="text"
                                    id="search_term"
                                    name="search_term"
                                    placeholder="Name or Reg. No."
                                    className="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value={filters.search_term}
                                    onChange={handleFilterChange}
                                />
                            </div>
                            <div>
                                <label htmlFor="per_page" className="block text-gray-700 text-sm font-bold mb-2">Records Per Page:</label>
                                <select
                                    id="per_page"
                                    name="per_page"
                                    className="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value={filters.per_page}
                                    onChange={handlePerPageChange}
                                >
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                            </div>
                        </div>

                        {/* Attendance Data Table */}
                        {studentsAttendance.data.length > 0 ? (
                            <div className="overflow-x-auto">
                                <table className="min-w-full bg-white border border-gray-200">
                                    <thead>
                                        <tr>
                                            <th className="py-2 px-4 border-b text-left">Reg. No.</th>
                                            <th className="py-2 px-4 border-b text-left">Student Name</th>
                                            <th className="py-2 px-4 border-b text-left">Subject</th>
                                            <th className="py-2 px-4 border-b text-left">Days Present</th>
                                            <th className="py-2 px-4 border-b text-left">Total Recorded Days</th>
                                            <th className="py-2 px-4 border-b text-left">Attendance %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {studentsAttendance.data.map(record => (
                                            <tr key={`${record.student_id}-${record.subject_id}`}> {/* Unique key */}
                                                <td className="py-2 px-4 border-b">{record.registraion_number}</td>
                                                <td className="py-2 px-4 border-b">{record.first_name} {record.last_name}</td>
                                                <td className="py-2 px-4 border-b">{record.subject_name}</td>
                                                <td className="py-2 px-4 border-b">{record.present_count}</td>
                                                <td className="py-2 px-4 border-b">{record.total_recorded_attendances}</td>
                                                <td className="py-2 px-4 border-b font-bold">
                                                    {record.percentage_attendance}%
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        ) : (
                            <div className="text-center py-8 text-gray-500">
                                No attendance data found for the selected filters.
                            </div>
                        )}

                        {/* Pagination Controls */}
                        {studentsAttendance.links.length > 3 && ( // Only show if more than prev, 1, next
                            <div className="flex justify-center mt-6">
                                <nav className="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    {studentsAttendance.links.map((link, index) => (
                                        <button
                                            key={index}
                                            onClick={() => link.url && handlePageChange(link.url)}
                                            disabled={!link.url}
                                            dangerouslySetInnerHTML={{ __html: link.label }}
                                            className={`relative inline-flex items-center px-4 py-2 border text-sm font-medium
                                                ${link.active ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'}
                                                ${!link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'}
                                                ${index === 0 ? 'rounded-l-md' : ''}
                                                ${index === studentsAttendance.links.length - 1 ? 'rounded-r-md' : ''}
                                            `}
                                        />
                                    ))}
                                </nav>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}