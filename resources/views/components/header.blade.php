@php
    $currentUser = Auth::user() ?? session('user');
    $isLoggedIn = !empty($currentUser);
    $isAdmin = $isLoggedIn && !empty($currentUser->is_admin);
    $currentPath = request()->path();
@endphp

<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-terminal me-2 text-cyan"></i>CypherLabs
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ $currentPath == '/' ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-tools me-1"></i> Tools
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Str::startsWith($currentPath, ['blog', 'article']) ? 'active' : '' }}" href="{{ route('blog.index') }}">
                        <i class="fas fa-newspaper me-1"></i> Blog
                    </a>
                </li>
                @if($isLoggedIn)
                <li class="nav-item">
                    <a class="nav-link {{ Str::startsWith($currentPath, 'dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                @endif
                @if($isAdmin)
                <li class="nav-item">
                    <a class="nav-link {{ Str::startsWith($currentPath, 'admin/blog') ? 'active' : '' }}" href="{{ route('admin.blog.create') }}">
                        <i class="fas fa-plus-circle me-1"></i> Create Post
                    </a>
                </li>
                @endif
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                @if($isLoggedIn)
                    <div class="nav-user-info me-2 text-nowrap d-none d-md-inline-block" style="color: #a0aec0; font-size: 0.88rem; font-family: 'Fira Code', monospace;">
                        <i class="fas fa-user-circle text-cyan me-1"></i>
                        <span>{{ $currentUser->name ?? $currentUser->email }}</span>
                        @if($isAdmin)
                            <span class="badge bg-danger ms-1" style="font-size: 0.68rem; vertical-align: middle;">ADMIN</span>
                        @endif
                    </div>
                    <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm rounded-pill">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                @else
                    <a href="{{ route('login.page') }}" class="btn btn-outline-primary btn-sm me-1">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </a>
                    <a href="{{ route('register.page') }}" class="btn btn-primary-gradient btn-sm">
                        <i class="fas fa-user-plus me-1"></i> Register
                    </a>
                @endif
            </div>

        </div>
    </div>
</nav>