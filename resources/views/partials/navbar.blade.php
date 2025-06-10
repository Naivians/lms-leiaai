<div id="top_nav_container" style="border-bottom: 1px solid #ccc; background-color: #fff; padding: 20px;">
    <div id="top_nav" class="d-flex align-items-center justify-content-between">
        <div class="burger_menu">
            <i class="fa-solid fa-bars fs-5 cursor text-primary sidebar-toggle" data-bs-toggle="tooltip" title="hide/show sidebar"></i>
        </div>
        <div class="account_container d-flex align-items-center justify-content-between gap-2">
            <span class="fw-bold me-2 text-primary"></span>
            <i class="fa-regular fa-bell fs-4 cursor text-primary"></i>
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
                    <a href="#" id="logoutBtn" class="dropdown-item"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
                    {{-- <form action="{{ route('auth.logout') }}" method="POST">@csrf<input type="submit"
                            class="ms-2 btn btn-outline-primary w-75" value="Logout"></form> --}}
                </ul>
            </div>
        </div>
    </div>
</div>




