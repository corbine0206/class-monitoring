<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    protected $teacherId;

    public function __construct($teacherId)
    {
        $this->teacherId = $teacherId;
    }

    public function collection()
    {
        return Student::with(['section', 'section.subject', 'scores'])
            ->whereHas('section', function ($query) {
                $query->where('user_id', $this->teacherId);
            })
            ->get()
            ->map(function ($student) {
                // Calculate sums for different score types
                $prelimPerformanceTask = $this->calculateScoreSum($student->scores, 'performance_task', 'prelim');
                $midtermPerformanceTask = $this->calculateScoreSum($student->scores, 'performance_task', 'midterm');
                $finalPerformanceTask = $this->calculateScoreSum($student->scores, 'performance_task', 'final');

                // Calculate sums for quizzes
                $prelimQuiz = $this->calculateScoreSum($student->scores, 'quiz', 'prelim');
                $midtermQuiz = $this->calculateScoreSum($student->scores, 'quiz', 'midterm');
                $finalQuiz = $this->calculateScoreSum($student->scores, 'quiz', 'final');

                // Calculate sums for recitations
                $prelimRecitation = $this->calculateScoreSum($student->scores, 'recitation', 'prelim');
                $midtermRecitation = $this->calculateScoreSum($student->scores, 'recitation', 'midterm');
                $finalRecitation = $this->calculateScoreSum($student->scores, 'recitation', 'final');

                return [
                    'ID' => $student->id,
                    'First Name' => $student->first_name,
                    'Middle Name' => $student->middle_name,
                    'Last Name' => $student->last_name,
                    'Section' => optional($student->section)->name,
                    'Prelim Performance Task' => $prelimPerformanceTask,
                    'Midterm Performance Task' => $midtermPerformanceTask,
                    'Final Performance Task' => $finalPerformanceTask,
                    'Prelim Quiz' => $prelimQuiz,
                    'Midterm Quiz' => $midtermQuiz,
                    'Final Quiz' => $finalQuiz,
                    'Prelim Recitation' => $prelimRecitation,
                    'Midterm Recitation' => $midtermRecitation,
                    'Final Recitation' => $finalRecitation,
                ];
            });
    }

    protected function calculateScoreSum($scores, $type, $term)
    {
        return $scores->where('type', $type)->where('term', $term)->sum('score');
    }

    public function headings(): array
    {
        return [
            'ID',
            'First Name',
            'Middle Name',
            'Last Name',
            'Section',
            'Prelim Performance Task',
            'Midterm Performance Task',
            'Final Performance Task',
            'Prelim Quiz',
            'Midterm Quiz',
            'Final Quiz',
            'Prelim Recitation',
            'Midterm Recitation',
            'Final Recitation',
        ];
    }
}
