<div id="sidebar" class="bg-light text-white vh-100 p-2">
    <div class="logo_container text-center d-flex align-items-center justify-content-center py-3">
        <img src="{{ asset('assets/img/leiaai_logo.png') }}" alt="Logo" class="img-fluid mb-2"
            style="max-height: 60px;">
        <hr class="border-light">
    </div>

    <nav class="nav flex-column">


        <a href="{{ route('user.dashboard') }}"
            class="nav-link {{ request()->routeIs('user.dashboard') ? 'active text-primary' : 'text-dark' }} mb-1"><i
                class="fa-solid fa-house me-2"></i>
            Dashboard</a>
        @can('admin_lvl1')
            <a href="{{ route('user.index') }}"
                class="nav-link {{ request()->routeIs('user.index') ? 'active text-primary' : 'text-dark' }} mb-1"><i
                    class="fa-solid fa-user me-2"></i>
                Users</a>
        @endcan

        <a href="{{ route('class.index') }}"
            class="nav-link {{ request()->routeIs('class.index') ? 'active text-primary' : 'text-dark' }}"><i
                class="fa-solid fa-house-user me-2"></i>Classes </a>

        @can('not_for_sp_fi')
            <a href="{{ route('course.index') }}"
                class="nav-link {{ request()->routeIs('course.index') ? 'active text-primary' : 'text-dark' }} mb-1"><i
                    class="fa-solid fa-award me-2"></i>
                Courses</a>
            <a href="{{ route('assessment.index') }}"
                class="nav-link {{ request()->routeIs('assessment.index') ? 'active text-primary' : 'text-dark' }} mb-1"><i
                    class="fa-solid fa-bookmark me-2"></i> Assessments</a>
        @endcan

        <a href="{{ route('assessment.show.progress') }}"
            class="nav-link {{ request()->routeIs('assessment.show.progress') ? 'active text-primary' : 'text-dark' }} mb-1"><i
                class="fa-solid fa-chart-line me-2"></i> Progress</a>

        <a href="{{ route('class.archives') }}"
            class="nav-link {{ request()->routeIs('class.archives') ? 'active text-primary' : 'text-dark' }} mb-1"><i
                class="fa-solid fa-box-archive me-2"></i>
            Archive Class
        </a>

    </nav>
    <div class="footer_container">
        <p class="m-0 mt-3 border border-1">&copy; {{ date('Y') }} LEIAAI LMS. All rights reserved. </p>
    </div>
</div>
