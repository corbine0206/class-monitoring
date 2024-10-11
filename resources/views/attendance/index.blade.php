@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<div class="container mt-5">

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
        <h4>Lecture Attendance</h4>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Days</th>
                    @for ($i = 1; $i <= 18; $i++)
                        <th>{{ $i }}</th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach (['Mon' => 1, 'Tue' => 2, 'Wed' => 3, 'Thu' => 4, 'Fri' => 5, 'Sat' => 6] as $day => $dayNum)
                <tr>
                    <td>{{ $day }}</td>
                    @for ($i = 1; $i <= 18; $i++)
                        <td>
                            @php
                                // Check for existing attendance record for this day and date
                                $attendance = $attendanceRecords
                                                ->where('day', $dayNum)
                                                ->where('attendance_date', $i)
                                                ->first();
                            @endphp
        
                            <!-- Check button for present (status = 1) -->
                            <button class="attendance-btn" 
                                    data-day="{{ $dayNum }}" 
                                    data-type="1" 
                                    data-status="1" 
                                    data-attendance-date="{{ $i }}" 
                                    title="Present"
                                    style="{{ $attendance && $attendance->status === 1 ? 'background-color: green; color: white;' : '' }}">
                                ✔️
                            </button>
        
                            <!-- Cross button for absent (status = 2) -->
                            <button class="attendance-btn" 
                                    data-day="{{ $dayNum }}" 
                                    data-type="1" 
                                    data-status="2" 
                                    data-attendance-date="{{ $i }}" 
                                    title="Absent"
                                    style="{{ $attendance && $attendance->status === 2 ? 'background-color: red; color: white;' : '' }}">
                                ❌
                            </button>
                        </td>
                    @endfor
                </tr>
                @endforeach
            </tbody>
        </table>
        
        
<!-- Laboratory Attendance Table -->
<h4>Laboratory Attendance</h4>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Days</th>
            @for ($i = 1; $i <= 18; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach (['Mon' => 1, 'Tue' => 2, 'Wed' => 3, 'Thu' => 4, 'Fri' => 5, 'Sat' => 6] as $day1 => $dayNumLab)
        <tr>
            <td>{{ $day1 }}</td>
            @for ($j = 1; $j <= 18; $j++)
                <td>
                    @php
                        // Check for existing attendance record for laboratory
                        $attendance_lab = $labAttendanceRecords->Where('day', $dayNumLab)
                                        ?->firstWhere('attendance_date', $j)
                    @endphp

                    <!-- Check button for present -->
                    <button class="attendance-btn" 
                            data-day="{{ $dayNumLab }}" 
                            data-type="2" 
                            data-status="1" 
                            data-attendance-date="{{ $j }}" 
                            title="Present"
                            class="btn btn-success btn-sm"
                            style="{{ $attendance_lab && $attendance_lab->status === 1 ? 'background-color: green; color: white;' : '' }}">
                            
                        ✔️
                    </button>

                    <!-- Cross button for absent -->
                    <button class="attendance-btn" 
                            data-day="{{ $dayNumLab }}" 
                            data-type="2" 
                            data-status="2"  
                            data-attendance-date="{{ $j }}" 
                            title="Absent"
                            class="btn btn-danger btn-sm"
                            style="{{ $attendance_lab && $attendance_lab->status === 2 ? 'background-color: red; color: white;' : '' }}">
                            
                        ❌
                    </button>

                </td>
            @endfor
        </tr>
        @endforeach
    </tbody>
</table>

    @endif
    <!-- Navigation Arrows -->
    <div class="row mt-4 text-center">
        <div class="col">
            <a href="{{ route('attendance.index', ['student_id' => $prevStudentId]) }}" class="btn btn-secondary">&lt;</a>
            <a href="{{ route('attendance.index', ['student_id' => $nextStudentId]) }}" class="btn btn-secondary">&gt;</a>
        </div>
    </div>
</div>
{{-- <script src="{{ asset('js/attendance.js') }}"></script>
 --}}
 <script>
    // Pass PHP variables into JavaScript
    const studentId = {{ $student->id }};
    const subjectId = {{ $student->subject_id }};
    const sectionId = {{ $student->section_id }};
</script>
        
<script>
    document.querySelectorAll('.attendance-btn').forEach(button => {
    button.addEventListener('click', function() {
        const day = this.getAttribute('data-day');
        const type = this.getAttribute('data-type');
        const status = this.getAttribute('data-status'); // 1 = Present, 2 = Absent
        const date = this.getAttribute('data-attendance-date'); // Get the attendance date

        fetch('{{ route('attendance.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                student_id: studentId,  // Use the JS variable passed from PHP
                subject_id: subjectId,  // Use the JS variable passed from PHP
                section_id: sectionId,  // Use the JS variable passed from PHP
                day: day,
                attendance_date: parseInt(date), // Pass the tiny integer date
                type: type,
                status: status
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Parse JSON response
        })
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

</script>
@endsection
