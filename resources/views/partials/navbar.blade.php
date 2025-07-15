<div id="top_nav_container" style="border-bottom: 1px solid #ccc; background-color: #fff; padding: 20px;">
    <div id="top_nav" class="d-flex align-items-center justify-content-between">
        <div class="burger_menu">
            <div class="d-flex gap-2 align-items-center">
                <i class="fa-solid fa-bars fs-5 cursor text-primary sidebar-toggle" data-bs-toggle="tooltip"
                    title="hide/show sidebar"></i>
                @php
                    $currentRoute = Route::currentRouteName(); // e.g. "user.settings"
                    $pageName = ucwords(str_replace('.', ' ', Str::after($currentRoute, 'user.')));
                @endphp

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}" class="text-dark">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <a href="{{ url()->current() }}" class="text-dark">{{ $title == 'Dashboard' ? '' : $title }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="account_container d-flex align-items-center justify-content-between gap-2">
            <span class="fw-bold me-2 text-primary"></span>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('user.edit', ['userId' => Auth::id()]) }}" class="dropdown-item">
                            <i class="fa-solid fa-user me-2"></i> Profile
                        </a>
                    </li>
                    <a href="#" id="logoutBtn" class="dropdown-item"><i
                            class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
                </ul>
            </div>
        </div>
    </div>
</div>
