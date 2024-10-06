@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <h2 class="mb-4">Welcome, {{ Auth::user()->name }}!</h2>

    <div class="row text-center">
        <!-- Total Students Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3>{{ $studentsCount }}</h3>
                    <p>Total Students</p>
                </div>
            </div>
        </div>

        <!-- Total Subjects Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3>{{ $subjectsCount }}</h3>
                    <p>Total Subjects</p>
                </div>
            </div>
        </div>

        <!-- Total Sections Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3>{{ $sectionsCount }}</h3>
                    <p>Total Sections</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
