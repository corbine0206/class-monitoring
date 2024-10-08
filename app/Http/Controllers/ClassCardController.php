<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\ClassCard;
use App\Models\Score;
use App\Models\Section;
use App\Models\Subject;
use Illuminate\Support\Facades\Log;

class ClassCardController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated teacher's user_id
        $teacherId = auth()->user()->id;

        // Check if there are any students associated with the teacher
        $students = Student::whereHas('subject', function ($query) use ($teacherId) {
            $query->where('user_id', $teacherId);
        })->orderBy('id')->get();

        // If no students are found, return an empty collection to avoid errors
        if ($students->isEmpty()) {
            return view('class_card.index', [
                'students' => collect(), // Pass an empty collection
                'message' => 'There are no students yet.'
            ]);
        }

        // Retrieve the student_id from the request, if not provided, get the first student's ID
        $student_id = $request->input('student_id') ?? $students->first()->id;

        // Fetch the student, ensuring the student belongs to the authenticated teacher
        $student = $students->find($student_id);
        if (!$student) {
            return redirect()->route('class-card.index')->with('error', 'Student not found.');
        }
        $subjects = Subject::where('user_id', $teacherId)->get();
        $sections = Section::where('user_id', $teacherId)->get();
        // Fetch the class card for the student
        $classCard = ClassCard::where('student_id', $student->id)->first();

        // Retrieve scores and group them by term, ensure classCard exists to avoid null references
        $scores = $classCard 
            ? Score::where('class_card_id', $classCard->id)->get()->groupBy('term') 
            : collect(); // Return an empty collection if no class card found

        // Initialize the 'prelim', 'midterm', and 'finals' terms
        $scores = $scores->put('prelim', $scores->get('prelim', collect())); 
        $scores = $scores->put('midterm', $scores->get('midterm', collect())); 
        $scores = $scores->put('finals', $scores->get('finals', collect())); 

        // Get all student IDs that belong to the teacher
        $studentIds = $students->pluck('id')->toArray();

        // Determine previous and next student IDs
        $currentIndex = array_search($student_id, $studentIds);
        $prevStudentId = $currentIndex > 0 ? $studentIds[$currentIndex - 1] : null;
        $nextStudentId = $currentIndex < count($studentIds) - 1 ? $studentIds[$currentIndex + 1] : null;
        // return $scores;
        // Pass data to the view
        return view('class_card.index', compact('students', 'subjects', 'sections', 'student', 'classCard', 'scores', 'prevStudentId', 'nextStudentId'));
    }

    public function performanceTaskStore(Request $request)
    {
        $request->validate([
            'class_card_id' => 'required|exists:class_cards,id', // Validate that the class card exists
            'student_id' => 'required|exists:students,id', // Validate that the student exists
            'score' => 'required|numeric|min:0|max:100', // Score validation
            'over_score' => 'required|numeric|min:0', // Over score validation
            'term' => 'required|in:1,2,3', // Only allow specified terms
        ]);

        // Create a new score record
        $score = new Score();
        $score->class_card_id = $request->class_card_id;
        $score->student_id = $request->student_id;
        $score->score = $request->score;
        $score->over_score = $request->over_score; // Add over_score to the model
        $score->type = $request->type_activity; // Set type for performance task
        $score->term = $request->term; // Set the term

        // Save the score and check for success
        if ($score->save()) {
            return redirect()->back()->with('success', 'Performance task saved successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to save performance task.');
        }
    }


    public function filterStudents(Request $request)
    {
        $subjectId = $request->input('subject_id');
        $sectionId = $request->input('section_id');
    
        // Fetch students based on the selected subject and section, load their related section and subject
        $students = Student::with(['section', 'section.subject']) // Load section and its related subject
                    ->whereHas('subject', function ($query) use ($subjectId) {
                        if ($subjectId) {
                            $query->where('id', $subjectId);
                        }
                    })
                    ->whereHas('section', function ($query) use ($sectionId) {
                        if ($sectionId) {
                            $query->where('id', $sectionId);
                        }
                    })
                    ->get();
    
        return response()->json($students);
    }
    

}
