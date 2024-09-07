<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
          <img src="{{ asset('assets/img/logo.png') }}" alt="">
          <span class="d-none d-lg-block">NiceAdmin</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
          <input type="text" name="query" placeholder="Search" title="Enter search keyword">
          <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
  </div><!-- End Search Bar -->

  <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
          <!-- Profile Dropdown -->
          <li class="nav-item dropdown pe-3">
              <!-- Profile Icon and Name -->
              <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                  <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                  <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user->name }}</span>
              </a>
              <!-- Profile Dropdown Items -->
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                  <li>Logout</li>
                  <!-- ... -->
              </ul><!-- End Profile Dropdown Items -->
          </li><!-- End Profile Nav -->

      </ul>
  </nav><!-- End Icons Navigation -->
</header>
