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

        // Check if there are any students associated with the teacher
        $sections = Section::where('user_id', $teacherId)->orderBy('id')->get();
        $subjects = Subject::where('user_id', $teacherId)->orderBy('id')->get();
        // If no students are found, return a message to the view
        if ($students->isEmpty()) {
            return view('class_card.index')->with('message', 'There are no students yet.');
        }

        // Retrieve the student_id from the request, if not provided get the first student's ID
        $student_id = $request->input('student_id') ?? $students->first()->id;

        // Fetch the student, ensuring the student belongs to the authenticated teacher
        $student = $students->find($student_id);
        if (!$student) {
            return redirect()->route('class-card.index')->with('error', 'Student not found.');
        }

        // Fetch the class card for the student
        $classCard = ClassCard::where('student_id', $student->id)->first();

        // Retrieve scores and group them by term, ensure classCard exists to avoid null references
        $scores = $classCard 
            ? Score::where('class_card_id', $classCard->id)->get()->groupBy('term') 
            : collect(); // Return an empty collection if no class card found

        $scores = $scores->put('prelim', $scores->get('prelim', collect())); // Initialize 'prelim' if not exists
        $scores = $scores->put('midterm', $scores->get('midterm', collect())); // Initialize 'midterm' if not exists
        $scores = $scores->put('finals', $scores->get('finals', collect())); // Initialize 'finals' if not exists

        // Get all student IDs that belong to the teacher
        $studentIds = $students->pluck('id')->toArray();

        // Determine previous and next student IDs
        $currentIndex = array_search($student_id, $studentIds);
        $prevStudentId = $currentIndex > 0 ? $studentIds[$currentIndex - 1] : null;
        $nextStudentId = $currentIndex < count($studentIds) - 1 ? $studentIds[$currentIndex + 1] : null;

        // Pass data to the view
        return view('class_card.index', compact('students', 'sections', 'subjects', 'student', 'classCard', 'scores', 'prevStudentId', 'nextStudentId'));
    }


    public function update(Request $request, $student_id)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            // Use 'array' validation to handle multiple inputs
            'prelim_performance_task.*' => 'nullable|integer',
            'prelim_quiz.*' => 'nullable|integer',
            'prelim_recitation.*' => 'nullable|integer',
            'midterm_performance_task.*' => 'nullable|integer',
            'midterm_quiz.*' => 'nullable|integer',
            'midterm_recitation.*' => 'nullable|integer',
            'finals_performance_task.*' => 'nullable|integer',
            'finals_quiz.*' => 'nullable|integer',
            'finals_recitation.*' => 'nullable|integer',
            'prelims_exam' => 'nullable|integer',
            'midterms_exam' => 'nullable|integer',
            'finals_exam' => 'nullable|integer',
        ]);

        // Retrieve the class card for the student
        $classCard = ClassCard::where('student_id', $student_id)->first();

        if (!$classCard) {
            return redirect()->route('class-card.index', ['student_id' => $student_id])
                            ->withErrors('Class card not found.');
        }

        // Define terms and types for lookup
        $terms = ['prelim', 'midterm', 'finals'];
        $types = ['performance_task', 'quiz', 'recitation'];

        // Update or create scores for each term and type
        foreach ($terms as $term) {
            foreach ($types as $type) {
                // Handle multiple inputs
                $scores = $request->input("{$term}_{$type}");

                if (is_array($scores)) {
                    foreach ($scores as $score) {
                        if (!is_null($score)) {
                            Score::updateOrCreate(
                                [
                                    'class_card_id' => $classCard->id,
                                    'student_id' => $student_id,
                                    'type' => array_search($type, $types) + 1,
                                    'term' => array_search($term, $terms) + 1
                                ],
                                ['score' => $score]
                            );
                        }
                    }
                }
            }
        }

        // Update or create exam scores
        foreach (['prelims_exam', 'midterms_exam', 'finals_exam'] as $exam) {
            $score = $request->input($exam);

            if (!is_null($score)) {
                Score::updateOrCreate(
                    [
                        'class_card_id' => $classCard->id,
                        'student_id' => $student_id,
                        'type' => array_search($exam, ['prelims_exam', 'midterms_exam', 'finals_exam']) + 4,
                        'term' => array_search(explode('_', $exam)[0], ['prelims', 'midterms', 'finals']) + 1
                    ],
                    ['score' => $score]
                );
            }
        }

        return redirect()->route('class-card.index', ['student_id' => $student_id])
                        ->with('success', 'Scores updated successfully.');
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
