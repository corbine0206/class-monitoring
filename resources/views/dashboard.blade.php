@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Welcome, {{ Auth::user()->name }}!</h2>
    <p>Email: {{ Auth::user()->email }}</p>
    <p>User Type: {{ Auth::user()->user_type }}</p>
    <!-- Add more content specific to your dashboard here -->
    <div class="row">
        @if (Auth::user()->isAdmin())
            <div class="col-md-6">
                <h3>Admin Section</h3>
                <!-- Add admin-specific content here -->
                <p>This is the admin section.</p>
            </div>
        @else
            <div class="col-md-6">
                <h3>Teacher Section</h3>
                <!-- Add teacher-specific content here -->
                <p>This is the teacher section.</p>
            </div>
        @endif
        <div class="col-md-6">
            <h3>General Section</h3>
            <!-- Add general content here -->
            <p>This is a general section available to all users.</p>
        </div>
    </div>
@endsection
