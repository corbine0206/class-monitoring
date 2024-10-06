@extends('layouts.app')

@section('title', 'Class Card')

@section('content')
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
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="section_id">Select Section:</label>
                    <select name="section_id" id="section_id" class="form-control" onchange="filterStudents()">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
        
                <!-- Filter Button -->
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

            <!-- Class Card Section -->
            <form action="{{ route('class-card.update', $student->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="card col-md-9">
                        <div class="row mb-4">
                            <!-- Prelim Section -->
                            <h5>Prelim</h5>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th>Performance Task</th>
                                        <th>Quizzes</th>
                                        <th>Recitation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Prepare variables to hold scores -->
                                    @php
                                        $performanceTasks = $scores['prelim']->where('type', 'performance_task');
                                        $quizzes = $scores['prelim']->where('type', 'quiz');
                                        $recitations = $scores['prelim']->where('type', 'recitation');
                                    @endphp
                                    
                                    <!-- Create a single row for prelim scores -->
                                    <tr>
                                        <td>
                                            @if($performanceTasks->isNotEmpty())
                                                @foreach($performanceTasks as $task)
                                                    <input type="text" name="prelim_performance_task[]" class="form-control" 
                                                        value="{{ $task->score }}" 
                                                        placeholder="Enter score">
                                                @endforeach
                                            @else
                                                <input type="text" name="prelim_performance_task[]" class="form-control" 
                                                    placeholder="Enter new performance task score">
                                            @endif
                                        </td>
                                        <td>
                                            @if($quizzes->isNotEmpty())
                                                @foreach($quizzes as $quiz)
                                                    <input type="text" name="prelim_quiz[]" class="form-control" 
                                                        value="{{ $quiz->score }}" 
                                                        placeholder="Enter score">
                                                @endforeach
                                            @else
                                                <input type="text" name="prelim_quiz[]" class="form-control" 
                                                    placeholder="Enter new quiz score">
                                            @endif
                                        </td>
                                        <td>
                                            @if($recitations->isNotEmpty())
                                                @foreach($recitations as $recitation)
                                                    <input type="text" name="prelim_recitation[]" class="form-control" 
                                                        value="{{ $recitation->score }}" 
                                                        placeholder="Enter score">
                                                @endforeach
                                            @else
                                                <input type="text" name="prelim_recitation[]" class="form-control" 
                                                    placeholder="Enter new recitation score">
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Provide fields for adding new Prelim scores -->
                                    <tr>
                                        <td>
                                            <input type="text" name="prelim_performance_task[]" class="form-control" 
                                                placeholder="Enter new performance task score">
                                        </td>
                                        <td>
                                            <input type="text" name="prelim_quiz[]" class="form-control" 
                                                placeholder="Enter new quiz score">
                                        </td>
                                        <td>
                                            <input type="text" name="prelim_recitation[]" class="form-control" 
                                                placeholder="Enter new recitation score">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Midterm Section -->
                            <div class="col-md-12 mb-4">
                                <h5>Midterm</h5>
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Performance Task</th>
                                            <th>Quizzes</th>
                                            <th>Recitation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $midtermPerformanceTasks = $scores['midterm']->where('type', 'performance_task');
                                            $midtermQuizzes = $scores['midterm']->where('type', 'quiz');
                                            $midtermRecitations = $scores['midterm']->where('type', 'recitation');
                                        @endphp

                                        <tr>
                                            <td>
                                                @if($midtermPerformanceTasks->isNotEmpty())
                                                    @foreach($midtermPerformanceTasks as $task)
                                                        <input type="text" name="midterm_performance_task[]" class="form-control" 
                                                            value="{{ $task->score }}" 
                                                            placeholder="Enter score">
                                                    @endforeach
                                                @else
                                                    <input type="text" name="midterm_performance_task[]" class="form-control" 
                                                        placeholder="Enter new performance task score">
                                                @endif
                                            </td>
                                            <td>
                                                @if($midtermQuizzes->isNotEmpty())
                                                    @foreach($midtermQuizzes as $quiz)
                                                        <input type="text" name="midterm_quiz[]" class="form-control" 
                                                            value="{{ $quiz->score }}" 
                                                            placeholder="Enter score">
                                                    @endforeach
                                                @else
                                                    <input type="text" name="midterm_quiz[]" class="form-control" 
                                                        placeholder="Enter new quiz score">
                                                @endif
                                            </td>
                                            <td>
                                                @if($midtermRecitations->isNotEmpty())
                                                    @foreach($midtermRecitations as $recitation)
                                                        <input type="text" name="midterm_recitation[]" class="form-control" 
                                                            value="{{ $recitation->score }}" 
                                                            placeholder="Enter score">
                                                    @endforeach
                                                @else
                                                    <input type="text" name="midterm_recitation[]" class="form-control" 
                                                        placeholder="Enter new recitation score">
                                                @endif
                                            </td>
                                        </tr>

                                        <!-- Provide fields for adding new Midterm scores -->
                                        <tr>
                                            <td>
                                                <input type="text" name="midterm_performance_task[]" class="form-control" 
                                                    placeholder="Enter new performance task score">
                                            </td>
                                            <td>
                                                <input type="text" name="midterm_quiz[]" class="form-control" 
                                                    placeholder="Enter new quiz score">
                                            </td>
                                            <td>
                                                <input type="text" name="midterm_recitation[]" class="form-control" 
                                                    placeholder="Enter new recitation score">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Finals Section -->
                            <div class="col-md-12">
                                <h5>Finals</h5>
                                <table class="table table-bordered text-center">
                                    <thead>
                                        <tr>
                                            <th>Performance Task</th>
                                            <th>Quizzes</th>
                                            <th>Recitation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $finalsPerformanceTasks = $scores['finals']->where('type', 'performance_task');
                                            $finalsQuizzes = $scores['finals']->where('type', 'quiz');
                                            $finalsRecitations = $scores['finals']->where('type', 'recitation');
                                        @endphp

                                        <tr>
                                            <td>
                                                @if($finalsPerformanceTasks->isNotEmpty())
                                                    @foreach($finalsPerformanceTasks as $task)
                                                        <input type="text" name="finals_performance_task[]" class="form-control" 
                                                            value="{{ $task->score }}" 
                                                            placeholder="Enter score">
                                                    @endforeach
                                                @else
                                                    <input type="text" name="finals_performance_task[]" class="form-control" 
                                                        placeholder="Enter new performance task score">
                                                @endif
                                            </td>
                                            <td>
                                                @if($finalsQuizzes->isNotEmpty())
                                                    @foreach($finalsQuizzes as $quiz)
                                                        <input type="text" name="finals_quiz[]" class="form-control" 
                                                            value="{{ $quiz->score }}" 
                                                            placeholder="Enter score">
                                                    @endforeach
                                                @else
                                                    <input type="text" name="finals_quiz[]" class="form-control" 
                                                        placeholder="Enter new quiz score">
                                                @endif
                                            </td>
                                            <td>
                                                @if($finalsRecitations->isNotEmpty())
                                                    @foreach($finalsRecitations as $recitation)
                                                        <input type="text" name="finals_recitation[]" class="form-control" 
                                                            value="{{ $recitation->score }}" 
                                                            placeholder="Enter score">
                                                    @endforeach
                                                @else
                                                    <input type="text" name="finals_recitation[]" class="form-control" 
                                                        placeholder="Enter new recitation score">
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type="text" name="finals_performance_task[]" class="form-control" 
                                                    placeholder="Enter new performance task score">
                                            </td>
                                            <td>
                                                <input type="text" name="finals_quiz[]" class="form-control" 
                                                    placeholder="Enter new quiz score">
                                            </td>
                                            <td>
                                                <input type="text" name="finals_recitation[]" class="form-control" 
                                                    placeholder="Enter new recitation score">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Navigation Arrows -->
                <div class="row mt-4 text-center">
                    <div class="col">
                        <a href="{{ route('class-card.index', ['student_id' => $prevStudentId]) }}" class="btn btn-secondary">&lt;</a>
                        <a href="{{ route('class-card.index', ['student_id' => $nextStudentId]) }}" class="btn btn-secondary">&gt;</a>
                    </div>
                </div>
                <!-- Submit Button -->
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary btn-block mt-4">Save Scores</button>
                </div>
            </form>
        @endif
    </div>

    <script src="{{ asset('js/class-card.js') }}"></script>
@endsection
