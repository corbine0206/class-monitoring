<!-- resources/views/dashboard.blade.php -->

@extends('layouts.app')

@section('content')
    <h2>Welcome, {{ Auth::user()->name }}!</h2>
    <p>Email: {{ Auth::user()->email }}</p>
    <p>User Type: {{ Auth::user()->user_type }}</p>
    <!-- Add more content specific to your dashboard here -->
    <div class="row">
        @if (Auth::user()->isAdmin())
            <div class="col-md-6">x`</div>
        @else
        <div class="col-md-6">Teacher</div>
        @endif
        <div class="col-md-6">asdsad</div>
    </div>
@endsection
