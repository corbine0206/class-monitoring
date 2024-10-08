@extends('layouts.app')

@section('title', 'Class Card')

@section('content')
<style>
    .square-table td {
        width: 100px;
        height: 100px;
        vertical-align: middle; /* Aligns content vertically */
    }
</style>
    <div class="container mt-5">
        <!-- Filters Section -->
        <form action="{{ route('class-card.index') }}" method="GET">
            <div class="row mb-4">
                <!-- Student Filter -->
                <div class="col-md-3">
                    <label for="student_id">Select Student:</label>
                    <select name="student_id" id="student_id" class="form-control">
                        <option value="">All Students</option>
                        @foreach($students as $studentOption)
                            <option value="{{ $studentOption->id }}" {{ request('student_id') == $studentOption->id ? 'selected' : '' }}>
                                {{ $studentOption->first_name }} {{ $studentOption->middle_name }} {{ $studentOption->last_name }} 
                                ({{ $studentOption->section->name ?? '' }} - {{ $studentOption->subject->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="subject_id">Select Subject:</label>
                    <select name="subject_id" id="subject_id" class="form-control" onchange="filterStudents()">
                        <option value="">All Subjects</option>
                        @if(isset($subjects))
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="section_id">Select Section:</label>
                    <select name="section_id" id="section_id" class="form-control" onchange="filterStudents()">
                        <option value="">All Sections</option>
                        @if(isset($sections))
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>

        <!-- Student Information Section -->
        @if(isset($message))
            <div class="card text-center mt-4">
                <div class="card-body">
                    <h5 class="card-title">No Students Found</h5>
                    <p class="card-text">{{ $message }}</p>
                </div>
            </div>
        @else
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card p-3">
                        <h5 class="mb-3">Student Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> {{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Gender:</strong> {{ $student->gender }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Course:</strong> {{ $student->course }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Section:</strong> {{ $student->section->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Subject:</strong> {{ $student->subject->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
        <!-- Exam Type Dropdown -->
        <div class="row mb-4">
            <div class="col-md-3">
                <label for="exam_type">Select Exam Type:</label>
                <select id="exam_type" class="form-control" onchange="showExamTables()">
                    <option value="prelim">Prelim</option>
                    <option value="midterm">Midterm</option>
                    <option value="finals">Finals</option>
                    <option value="exams">Exam</option>
                </select>
            </div>
        </div>
            <!-- Class Card Section -->
            <div class="row mb-4" id="examTables">
                <div class="col-md-12">
                    <div class="card p-3">
                        <h5 class="mb-3">Class Card</h5>
                        <div id="prelim-tables" class="exam-tables col-md-12">
                            <div class="row">
                                <h3>Prelim</h3>
                                <div class="col-md-4">
                                    <!-- Performance Tasks for Prelim -->
                                    <h6>Performance Tasks</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Performance Tasks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('prelim')->where('type', 'performance_task') as $performance_task)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 1)">
                                                        {{ $performance_task->score }}
                                                    </td>
                                                @endforeach

                                                @for ($i = $scores->get('prelim')->where('type', 'performance_task')->count(); $i < 5; $i++)
                                                <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 1)"></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('prelim')->where('type', 'performance_task') as $performance_task)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 1)">
                                                        {{ $performance_task->over_score }}
                                                    </td>
                                                @endforeach

                                                @for ($i = $scores->get('prelim')->where('type', 'performance_task')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 1)"></td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <!-- Quizzes for Prelim -->
                                    <h6>Quizzes</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Quizzes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('prelim')->where('type', 'quiz') as $quiz)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 2)">
                                                        {{ $quiz->score }}
                                                    </td>
                                                @endforeach
                                                
                                                <!-- Fill remaining cells with empty TDs to maintain the total of 5 columns -->
                                                @for ($i = $scores->get('prelim')->where('type', 'quiz')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 2)"></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('prelim')->where('type', 'quiz') as $quiz)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 2)">
                                                        {{ $quiz->over_score }}
                                                    </td>
                                                @endforeach
                                                <!-- Fill remaining cells with empty TDs to maintain the total of 5 columns -->
                                                @for ($i = $scores->get('prelim')->where('type', 'quiz')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 2)"></td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                

                                <div class="col-md-4">
                                    <!-- Recitation for Prelim -->
                                    <h6>Recitation</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Recitation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('prelim')->where('type', 'recitation') as $recitation)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 3)">
                                                        {{ $recitation->score }}
                                                    </td>
                                                @endforeach
                                                
                                                <!-- Fill remaining cells with empty TDs to maintain the total of 5 columns -->
                                                @for ($i = $scores->get('prelim')->where('type', 'recitation')->count(); $i < 5; $i++)
                                                <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 3)">
                                                </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('prelim')->where('type', 'recitation') as $recitation)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 3)">
                                                        {{ $recitation->over_score }}
                                                    </td>
                                                @endforeach
                                                
                                                <!-- Fill remaining cells with empty TDs to maintain the total of 5 columns -->
                                                @for ($i = $scores->get('prelim')->where('type', 'recitation')->count(); $i < 5; $i++)
                                                <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 3)">
                                                </td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="midterm-tables" class="exam-tables col-md-12"  style="display: none;">
                            <div class="row">
                                <h3>Midterm</h3>
                                <div class="col-md-4">
                                    <!-- Performance Tasks for Midterm -->
                                    <h6>Performance Tasks</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Performance Tasks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('midterm')->where('type', 'performance_task') as $performance_task)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 1)">
                                                        {{ $performance_task->score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('midterm')->where('type', 'performance_task')->count(); $i < 5; $i++)
                                                <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 1)">
                                                </td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('midterm')->where('type', 'performance_task') as $performance_task)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 1)">
                                                        {{ $performance_task->over_score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('midterm')->where('type', 'performance_task')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 1)">
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        
                                <div class="col-md-4">
                                    <!-- Quizzes for Midterm -->
                                    <h6>Quizzes</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Quizzes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('midterm')->where('type', 'quiz') as $quiz)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 2)">
                                                        {{ $quiz->score }}
                                                    </td>
                                                @endforeach
                                                
                                                @for ($i = $scores->get('midterm')->where('type', 'quiz')->count(); $i < 5; $i++)
                                                <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 2)"></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('midterm')->where('type', 'quiz') as $quiz)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 2)">
                                                        {{ $quiz->over_score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('midterm')->where('type', 'quiz')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 2)"></td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="col-md-4">
                                    <!-- Recitation for Midterm -->
                                    <h6>Recitation</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Recitation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('midterm')->where('type', 'recitation') as $recitation)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 3)">
                                                        {{ $recitation->score }}
                                                    </td>
                                                @endforeach
                                                
                                                @for ($i = $scores->get('midterm')->where('type', 'recitation')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 3)"></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('midterm')->where('type', 'recitation') as $recitation)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 3)">
                                                        {{ $recitation->over_score }}
                                                    </td>
                                                @endforeach
                                                
                                                @for ($i = $scores->get('midterm')->where('type', 'recitation')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 3)"></td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div id="finals-tables" class="exam-tables col-md-12" style="display: none;">
                            <div class="row">
                                <h3>Finals</h3>
                                <div class="col-md-4">
                                    <!-- Performance Tasks for Finals -->
                                    <h6>Performance Tasks</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Performance Tasks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('finals')->where('type', 'performance_task') as $performance_task)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 1)">
                                                        {{ $performance_task->score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('finals')->where('type', 'performance_task')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 1)"></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('finals')->where('type', 'performance_task') as $performance_task)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 1)">
                                                        {{ $performance_task->over_score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('finals')->where('type', 'performance_task')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 1)"></td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4">
                                    <!-- Quizzes for Finals -->
                                    <h6>Quizzes</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Quizzes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('finals')->where('type', 'quiz') as $quiz)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 2)">
                                                        {{ $quiz->score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('finals')->where('type', 'quiz')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 2)"></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('finals')->where('type', 'quiz') as $quiz)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 2)">
                                                        {{ $quiz->over_score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('finals')->where('type', 'quiz')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 2)"></td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        
                                <div class="col-md-4">
                                    <!-- Recitation for Finals -->
                                    <h6>Recitation</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Recitation</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach ($scores->get('finals')->where('type', 'recitation') as $recitation)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 3)">
                                                        {{ $recitation->score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('finals')->where('type', 'recitation')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 3)"></td>
                                                @endfor
                                            </tr>
                                            <tr>
                                                @foreach ($scores->get('finals')->where('type', 'recitation') as $recitation)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 3)">
                                                        {{ $recitation->over_score }}
                                                    </td>
                                                @endforeach
                        
                                                @for ($i = $scores->get('finals')->where('type', 'recitation')->count(); $i < 5; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 3)"></td>
                                                @endfor
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div id="exam-tables" class="exam-tables col-md-12"  style="display: none;">
                            <div class="row">
                                <h3>Major Exams</h3>
                                <div class="col-md-4">
                                    <!-- Performance Tasks for Midterm -->
                                    <h6>Prelim</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Prelim</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Lecture</td>
                                                @foreach ($scores->get('prelim')->where('type', 'lec') as $exam_prelim)
                                                    <td>
                                                        {{ $exam_prelim->score }} / {{ $exam_prelim->over_score }}
                                                    </td>
                                                @endforeach
                                                @for ($i = $scores->get('prelim')->where('type', 'lec')->count(); $i < 1; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 4)"></td>
                                                @endfor
                                               
                                            </tr>
                                            <tr>
                                                <td>Lab</td>
                                                @foreach ($scores->get('prelim')->where('type', 'lab') as $exam_prelim)
                                                    <td>
                                                        {{ $exam_prelim->score }} / {{ $exam_prelim->over_score }}
                                                    </td>
                                                @endforeach
                                                @for ($i = $scores->get('prelim')->where('type', 'lab')->count(); $i < 1; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 1, 5)"></td>
                                                @endfor

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        
                                <div class="col-md-4">
                                    <!-- Quizzes for Midterm -->
                                    <h6>Midterm</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Midterm</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Lecture</td>
                                                @foreach ($scores->get('midterm')->where('type', 'lec') as $exam_midterm)
                                                    <td>
                                                        {{ $exam_midterm->score }} / {{ $exam_midterm->over_score }}
                                                    </td>
                                                @endforeach
                                                @for ($i = $scores->get('midterm')->where('type', 'lec')->count(); $i < 1; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 4)"></td>
                                                @endfor
                                               
                                            </tr>
                                            <tr>
                                                <td>Lab</td>
                                                @foreach ($scores->get('midterm')->where('type', 'lab') as $exam_midterm)
                                                    <td>
                                                        {{ $exam_midterm->score }} / {{ $exam_midterm->over_score }}
                                                    </td>
                                                @endforeach
                                                @for ($i = $scores->get('midterm')->where('type', 'lab')->count(); $i < 1; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 2, 5)"></td>
                                                @endfor

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="col-md-4">
                                    <!-- Recitation for Midterm -->
                                    <h6>Finals</h6>
                                    <table class="table table-bordered text-center square-table">
                                        <thead>
                                            <tr>
                                                <th colspan="5">Finals</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Lecture</td>
                                                @foreach ($scores->get('finals')->where('type', 'lec') as $exam_finals)
                                                    <td>
                                                        {{ $exam_finals->score }} / {{ $exam_finals->over_score }}
                                                    </td>
                                                @endforeach
                                                @for ($i = $scores->get('finals')->where('type', 'lec')->count(); $i < 1; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 4)"></td>
                                                @endfor
                                               
                                            </tr>
                                            <tr>
                                                <td>Lab</td>
                                                @foreach ($scores->get('finals')->where('type', 'lab') as $exam_finals)
                                                    <td>
                                                        {{ $exam_finals->score }} / {{ $exam_finals->over_score }}
                                                    </td>
                                                @endforeach
                                                @for ($i = $scores->get('finals')->where('type', 'lab')->count(); $i < 1; $i++)
                                                    <td data-class-card-id="{{ $classCard->id }}" data-student-id="{{ $student->id }}" onclick="openPerformanceModal({{ $classCard->id }}, {{ $student->id }}, 3, 5)"></td>
                                                @endfor

                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        @endif
    </div>



    <!-- Performance Task Modal -->
    <div class="modal fade" id="performanceModal" tabindex="-1" aria-labelledby="performanceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="performanceModalLabel">Add Score</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="performanceForm" method="POST" action="{{ route('class-card.performance-task.store') }}">
                        @csrf
                        <input type="hidden" id="class_card_id" name="class_card_id">
                        <input type="hidden" id="student_id_performance" name="student_id">
                        <input type="hidden" id="term" name="term"> <!-- Use a value for term: 1 for prelim, 2 for midterm -->
                        <input type="hidden" id="type_activity" name="type_activity"> <!-- Use a value for type of activity: 1 for performance task, 2 for quiz, 3 recitation -->
                        <div class="mb-3">
                            <label for="performanceScore" class="form-label">Score</label>
                            <input type="number" class="form-control" id="performanceScore" placeholder="Enter score" name="score" required>
                        </div>
                        <div class="mb-3">
                            <label for="over" class="form-label">Over Score</label>
                            <input type="number" class="form-control" id="over" placeholder="Over score" name="over_score" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Score</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/class-card.js') }}"></script>
    <script>
        function showExamTables() {
            const examType = document.getElementById('exam_type').value;
            const examTables = document.querySelectorAll('.exam-tables');
            
            examTables.forEach(table => {
                table.style.display = 'none'; // Hide all tables
            });
    
            if (examType === 'prelim') {
                document.getElementById('prelim-tables').style.display = 'block'; // Show prelim tables
            } else if (examType === 'midterm') {
                document.getElementById('midterm-tables').style.display = 'block'; // Show midterm tables
            } else if (examType === 'finals') {
                document.getElementById('finals-tables').style.display = 'block'; // Show finals tables
            } else if (examType === 'exams') {
                document.getElementById('exam-tables').style.display = 'block'; // Show finals tables
            }
        }

        function openPerformanceModal(classCardId, studentId, term, type_activity) {
            // Ensure these IDs are set correctly
            $('#class_card_id').val(classCardId);
            $('#student_id_performance').val(studentId); // Populate the student_id
            console.log(studentId);
            $('#term').val(term); // Set the term input field value
            $('#type_activity').val(type_activity)
            $('#performanceModal').modal('show');
        }
    </script>
@endsection
