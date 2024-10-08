<aside id="sidebar" class="sidebar">
    <!-- Sections, Subjects, and Manage Students Nav (only for teacher) -->
    @if(auth()->check() && auth()->user()->user_type === 'teacher')
    <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#subject-section-nav" data-bs-toggle="collapse" href="#">
            <i class="bi bi-book"></i><span>Subjects, Sections & Students</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="subject-section-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
                <a class="nav-link collapsed" href="{{ route('subjects.index') }}">
                    <i class="bi bi-card-text"></i><span>Subjects</span>
                </a>
            </li>
            <li>
                <a class="nav-link collapsed" href="{{ route('sections.index') }}">
                    <i class="bi bi-list-ol"></i><span>Sections</span>
                </a>
            </li>
            <li>
                <a class="nav-link collapsed" href="{{ route('students.index') }}">
                    <i class="bi bi-people"></i><span>Manage Students</span>
                </a>
            </li>
        </ul>
    </li>

    <!-- Class Card Nav (only for teacher) -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('class-card.index') }}">
            <i class="bi bi-journal"></i><span>Class Cards</span>
        </a>
    </li>
    @endif

    <!-- User Management (only for admin) -->
    @if(auth()->check() && auth()->user()->user_type === 'admin')
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('users.index') }}">
            <i class="bi bi-people-fill"></i><span>User Management</span>
        </a>
    </li>

    <!-- User Registration (only for admin) -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('register') }}">
            <i class="bi bi-person-plus-fill"></i><span>Register New User</span>
        </a>
    </li>
    @endif

</aside>
