<div id="sidebar" class="bg-light text-white vh-100 p-2">
    <div class="logo_container text-center d-flex align-items-center justify-content-center py-3">
        <img src="{{ asset('assets/img/leiaai_logo.png') }}" alt="Logo" class="img-fluid mb-2"
            style="max-height: 60px;">
        <hr class="border-light">
    </div>

    <nav class="nav flex-column">
        <a href="{{ url('/') }}" class="nav-link text-dark mb-2"><i class="fa-solid fa-house me-2"></i>
            Dashboard</a>
        <a href="{{ route('user.Home') }}" class="nav-link text-dark mb-2"><i class="fa-solid fa-user me-2"></i>
            Users</a>
        <a href="{{ route('class.index') }}" class="nav-link text-dark mb-2"><i class="fa-solid fa-users me-2"></i>
            Classes</a>
        <a href="#" class="nav-link text-dark mb-2"><i class="fa-solid fa-award me-2"></i> Courses</a>
        <a href="#" class="nav-link text-dark mb-2"><i class="fa-solid fa-book me-2"></i> Contents</a>
        <a href="#" class="nav-link text-dark mb-2"><i class="fa-solid fa-bookmark me-2"></i> Assessments</a>
        <a href="#" class="nav-link text-dark mb-2"><i class="fa-solid fa-chart-line me-2"></i> Progress</a>
        <a href="#" class="nav-link text-dark mb-2"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
    </nav>
    <div class="footer_container">
        <p class="m-0 mt-3 border border-1">&copy; {{ date('Y') }} LEIAAI LMS. All rights reserved. </p>
    </div>
</div>
