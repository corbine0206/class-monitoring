<?php

namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the currently authenticated teacher's ID
        $teacherId = auth()->user()->id;

        // Fetch students related to the teacher's sections or subjects
        $students = Student::where('user_id', Auth::id())->get();

        // // Return the view with the list of students
        return view('student.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_number' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'date_of_birth' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'course' => 'required|string|max:255',
        ]);

        Student::create([
            'student_number' => $request->student_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'course' => $request->course,
            'user_id' => Auth::id(),

        ]);

        return redirect()->route('students.index')->with('success', 'Subject created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {

        $request->validate([
            'student_number' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'date_of_birth' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'course' => 'required|string|max:255',
        ]);

        if ($student->user_id !== Auth::id()) {
            return redirect()->route('students.index')->with('error', 'You are not authorized to update this student.');
        }

        $student->update([
            'student_number' => $request->edit_student_number,
            'first_name' => $request->edit_first_name,
            'last_name' => $request->edit_last_name,
            'middle_name' => $request->edit_middle_name,
            'date_of_birth' => $request->edit_date_of_birth,
            'gender' => $request->edit_gender,
            'course' => $request->edit_course,
        ]);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
