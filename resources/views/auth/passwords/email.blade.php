<!-- resources/views/auth/passwords/email.blade.php -->

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<!-- Forgot Password Form -->
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Send Password Reset Link</button>
</form>
