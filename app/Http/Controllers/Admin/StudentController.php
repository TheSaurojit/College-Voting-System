<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of students with filtering and search.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        // Filter by class
        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        // Filter by semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        // Search by name, phone, or roll number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('roll_no', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:20', 'unique:students,phone'],
            'program_type' => ['required', 'in:+2,Degree'],
            'semester'     => ['nullable', 'required_if:program_type,Degree', 'string', 'max:20'],
            'class'        => ['nullable', 'required_if:program_type,+2', 'in:11,12'],
            'roll_no'      => ['required', 'string', 'max:50'],
        ]);

        $data = $request->only(['name', 'phone', 'roll_no']);
        if ($request->program_type === '+2') {
            $data['class'] = $request->class;
            $data['semester'] = null;
        } else {
            $data['class'] = null;
            $data['semester'] = $request->semester;
        }

        Student::create($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully.');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load('votes.candidate', 'votes.post');

        return view('admin.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:20', 'unique:students,phone,' . $student->id],
            'program_type' => ['required', 'in:+2,Degree'],
            'semester'     => ['nullable', 'required_if:program_type,Degree', 'string', 'max:20'],
            'class'        => ['nullable', 'required_if:program_type,+2', 'in:11,12'],
            'roll_no'      => ['required', 'string', 'max:50'],
        ]);

        $data = $request->only(['name', 'phone', 'roll_no']);
        if ($request->program_type === '+2') {
            $data['class'] = $request->class;
            $data['semester'] = null;
        } else {
            $data['class'] = null;
            $data['semester'] = $request->semester;
        }

        $student->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified student and their votes.
     */
    public function destroy(Student $student)
    {
        // Delete related votes first
        $student->votes()->delete();

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student and their votes deleted successfully.');
    }

    /**
     * Remove the specified students and their votes in bulk.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => ['required', 'array'],
            'ids.*' => ['required', 'integer', 'exists:students,id'],
        ]);

        $ids = $request->input('ids');

        // Delete votes of these students first
        \App\Models\Vote::whereIn('student_id', $ids)->delete();

        // Delete the students
        Student::whereIn('id', $ids)->delete();

        return redirect()->route('admin.students.index')
            ->with('success', count($ids) . ' students and their votes deleted successfully.');
    }
}
