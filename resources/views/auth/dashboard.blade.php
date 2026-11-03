@extends('layouts.app')

@section('title', ($user->is_admin ? 'Admin CMS Portal' : 'Developer Dashboard') . ' – CypherLabs')
@section('meta_description', 'Access your personal CypherLabs developer dashboard, manage tools, articles and account settings.')

@section('content')

<style>
    .saas-dashboard-wrapper {
        --neon-blue: #00d4ff;
        --neon-purple: #a855f7;
        --neon-cyan: #06f7e3;
        --bg-dark: #070913;
        --bg-card: rgba(13, 18, 33, 0.85);
        --border-glass: rgba(0, 212, 255, 0.18);
        --border-glass-hover: rgba(0, 212, 255, 0.4);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;

        background: var(--bg-dark);
        min-height: calc(100vh - 120px);
        padding: 2.5rem 1rem 4rem;
        position: relative;
        overflow: hidden;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-main);
    }

    .dash-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .dash-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 750px;
        height: 750px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.08) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .dash-container {
        max-width: 1100px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HEADER BANNER CARD */
    .user-profile-banner {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(10, 15, 30, 0.95) 100%);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .user-profile-banner::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #00d4ff, #a855f7, #06f7e3);
    }

    .user-info-flex {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .user-avatar-circle {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00d4ff, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.8rem;
        font-weight: 800;
        box-shadow: 0 0 25px rgba(0, 212, 255, 0.35);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .user-name-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .role-badge {
        font-size: 0.72rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .role-badge-admin {
        background: rgba(168, 85, 247, 0.15);
        border: 1px solid rgba(168, 85, 247, 0.4);
        color: #d8b4fe;
    }

    .role-badge-user {
        background: rgba(0, 212, 255, 0.12);
        border: 1px solid rgba(0, 212, 255, 0.35);
        color: var(--neon-blue);
    }

    .user-email-text {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .profile-actions-flex {
        display: flex;
        gap: 10px;
    }

    /* GRID LAYOUTS */
    .saas-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .saas-grid-3 {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    /* GLASS CARDS */
    .saas-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 18px;
        padding: 1.5rem;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
        transition: all 0.3s ease;
    }

    .saas-glass-card:hover {
        border-color: var(--border-glass-hover);
        transform: translateY(-3px);
    }

    .card-head-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .card-head-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: radial-gradient(circle at 30% 30%, rgba(0, 212, 255, 0.2), rgba(168, 85, 247, 0.1));
        border: 1px solid rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 1.1rem;
    }

    .card-head-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* TOOL LAUNCH CARD FOR USERS */
    .tool-launch-card {
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 1.2rem;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        text-decoration: none !important;
    }

    .tool-launch-card:hover {
        background: rgba(20, 30, 55, 0.85);
        border-color: var(--neon-blue);
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(0, 212, 255, 0.15);
    }

    .tool-launch-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(0, 212, 255, 0.12);
        border: 1px solid rgba(0, 212, 255, 0.25);
        color: var(--neon-blue);
        font-size: 1.15rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.85rem;
    }

    .tool-launch-name {
        color: #ffffff;
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.35rem;
    }

    .tool-launch-desc {
        color: var(--text-muted);
        font-size: 0.8rem;
        line-height: 1.45;
        margin-bottom: 1rem;
        flex: 1;
    }

    .tool-launch-action {
        color: var(--neon-blue);
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* BUTTONS & BADGES */
    .saas-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.6rem 1.2rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        text-decoration: none !important;
        transition: all 0.25s ease;
        border: none;
        cursor: pointer;
    }

    .saas-btn-primary {
        background: linear-gradient(135deg, #00d4ff 0%, #a855f7 100%);
        color: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 212, 255, 0.3);
    }

    .saas-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0, 212, 255, 0.45);
        color: #ffffff;
    }

    .saas-btn-glass {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
    }

    .saas-btn-glass:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.25);
    }

    .saas-btn-danger {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
    }

    .saas-btn-danger:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #ffffff;
    }

    /* ADMIN BLOG TABLE */
    .admin-table-wrap {
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .admin-table th {
        text-align: left;
        padding: 0.75rem 1rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.72rem;
        font-family: 'Fira Code', monospace;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .admin-table td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        color: #e2e8f0;
    }

    .admin-table tr:hover td {
        background: rgba(0, 212, 255, 0.04);
    }

    .status-pill-pub {
        padding: 3px 8px;
        border-radius: 4px;
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #4ade80;
        font-size: 0.7rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
    }

    .status-pill-draft {
        padding: 3px 8px;
        border-radius: 4px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: var(--text-muted);
        font-size: 0.7rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
    }

    @media (max-width: 768px) {
        .user-profile-banner { flex-direction: column; text-align: center; }
        .user-info-flex { flex-direction: column; }
        .saas-grid-2 { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-dashboard-wrapper">
    <div class="dash-bg-grid"></div>
    <div class="dash-ambient-glow"></div>

    <div class="dash-container">
        
        <!-- PROFILE BANNER -->
        <div class="user-profile-banner">
            <div class="user-info-flex">
                <div class="user-avatar-circle">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="user-name-title">
                        {{ $user->name }}
                        @if($user->is_admin)
                            <span class="role-badge role-badge-admin"><i class="fas fa-shield-alt me-1"></i> Admin Portal</span>
                        @else
                            <span class="role-badge role-badge-user"><i class="fas fa-check-circle me-1"></i> Developer Account</span>
                        @endif
                    </h1>
                    <div class="user-email-text">
                        <i class="far fa-envelope me-1"></i> {{ $user->email }}
                        <span class="mx-2">·</span>
                        <i class="far fa-calendar-alt me-1"></i> Joined {{ $user->created_at ? $user->created_at->format('M Y') : '2026' }}
                    </div>
                </div>
            </div>

            <div class="profile-actions-flex">
                <a href="{{ route('profile.edit') }}" class="saas-btn saas-btn-glass">
                    <i class="fas fa-user-cog"></i> Edit Profile
                </a>
                <a href="{{ route('logout') }}" class="saas-btn saas-btn-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger mb-4" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 12px; padding: 1rem;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div>
        @endif

        @if(!$user->is_admin)
            <!-- ============================================ -->
            <!-- REGULAR USER DASHBOARD (FAVORITES & UTILITIES) -->
            <!-- ============================================ -->

            <div class="saas-glass-card mb-4">
                <div class="card-head-row">
                    <div class="card-head-icon"><i class="fas fa-rocket"></i></div>
                    <h2 class="card-head-title">Developer Utilities Suite</h2>
                </div>

                <div class="saas-grid-3">
                    <a href="{{ url('/tools/qr-generator') }}" class="tool-launch-card">
                        <div class="tool-launch-icon"><i class="fas fa-qrcode"></i></div>
                        <div class="tool-launch-name">QR Architect</div>
                        <div class="tool-launch-desc">Custom high-res QR codes with SVG vectors and logo embedding.</div>
                        <div class="tool-launch-action">Launch Utility <i class="fas fa-arrow-right"></i></div>
                    </a>

                    <a href="{{ url('/image-compressor') }}" class="tool-launch-card">
                        <div class="tool-launch-icon"><i class="fas fa-compress-alt"></i></div>
                        <div class="tool-launch-name">Visual Forge</div>
                        <div class="tool-launch-desc">Client-side image compression up to 85% payload reduction.</div>
                        <div class="tool-launch-action">Launch Utility <i class="fas fa-arrow-right"></i></div>
                    </a>

                    <a href="{{ url('/JSON-Alchemist') }}" class="tool-launch-card">
                        <div class="tool-launch-icon"><i class="fab fa-js"></i></div>
                        <div class="tool-launch-name">JSON Alchemist</div>
                        <div class="tool-launch-desc">Format, minify, and validate JSON data structures in real-time.</div>
                        <div class="tool-launch-action">Launch Utility <i class="fas fa-arrow-right"></i></div>
                    </a>

                    <a href="{{ route('pdf.tools') }}" class="tool-launch-card">
                        <div class="tool-launch-icon"><i class="fas fa-file-pdf"></i></div>
                        <div class="tool-launch-name">PDF Werkstatt</div>
                        <div class="tool-launch-desc">Merge, split, and compress PDF documents securely.</div>
                        <div class="tool-launch-action">Launch Utility <i class="fas fa-arrow-right"></i></div>
                    </a>
                </div>
            </div>

            <div class="saas-grid-2">
                <!-- KNOWLEDGE HUB HUB CTA -->
                <div class="saas-glass-card">
                    <div class="card-head-row">
                        <div class="card-head-icon"><i class="fas fa-book-open"></i></div>
                        <h3 class="card-head-title">Engineering Knowledge Hub</h3>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.88rem; line-height: 1.6; margin-bottom: 1.25rem;">
                        Discover handcrafted architecture tutorials, Laravel best practices, and web performance optimization guides.
                    </p>
                    <a href="{{ route('blog.index') }}" class="saas-btn saas-btn-primary">
                        <i class="fas fa-newspaper"></i> Explore Engineering Articles
                    </a>
                </div>

                <!-- ACCOUNT SECURITY & SETTINGS -->
                <div class="saas-glass-card">
                    <div class="card-head-row">
                        <div class="card-head-icon"><i class="fas fa-shield-alt"></i></div>
                        <h3 class="card-head-title">Security & Account Settings</h3>
                    </div>
                    <p style="color: var(--text-muted); font-size: 0.88rem; line-height: 1.6; margin-bottom: 1.25rem;">
                        Manage your password credentials or remove your personal data securely.
                    </p>
                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <a href="{{ route('profile.edit') }}" class="saas-btn saas-btn-glass">
                            <i class="fas fa-lock"></i> Password & Info
                        </a>
                        <a href="{{ route('account.delete') }}" class="saas-btn saas-btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                            <i class="fas fa-trash-alt"></i> Delete Account
                        </a>
                    </div>
                </div>
            </div>

        @else
            <!-- ============================================ -->
            <!-- ADMIN USER DASHBOARD (CMS & CONTENT CRUD) -->
            <!-- ============================================ -->

            <div class="saas-grid-2">
                <!-- METRICS CARD -->
                <div class="saas-glass-card">
                    <div class="card-head-row">
                        <div class="card-head-icon"><i class="fas fa-chart-line"></i></div>
                        <h3 class="card-head-title">CMS Publishing Overview</h3>
                    </div>
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 0.85rem; text-align:center; margin-bottom: 1.25rem;">
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 0.85rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                            <div style="font-size: 1.4rem; font-weight: 800; color: #ffffff;">{{ $postCount ?? 0 }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase;">Total Articles</div>
                        </div>
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 0.85rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                            <div style="font-size: 1.4rem; font-weight: 800; color: var(--neon-cyan);">{{ $publishedCount ?? 0 }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase;">Published</div>
                        </div>
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 0.85rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                            <div style="font-size: 1.4rem; font-weight: 800; color: var(--text-muted);">{{ $draftCount ?? 0 }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase;">Drafts</div>
                        </div>
                        <div style="background: rgba(15, 23, 42, 0.6); padding: 0.85rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.06);">
                            <div style="font-size: 1.4rem; font-weight: 800; color: var(--neon-blue);">{{ number_format($totalViews ?? 0) }}</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: uppercase;">Total Views</div>
                        </div>
                    </div>
                    <div style="display:flex; gap: 10px; margin-top: 0.25rem;">
                        <a href="{{ route('admin.blog.create') }}" class="saas-btn saas-btn-primary" style="flex:1; justify-content: center;">
                            <i class="fas fa-plus-circle"></i> Create Post
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="saas-btn saas-btn-glass" style="flex:1; justify-content: center;">
                            <i class="fas fa-folder-tree"></i> Manage Categories
                        </a>
                    </div>
                </div>

                <!-- ADMIN ACCOUNT CARD -->
                <div class="saas-glass-card">
                    <div class="card-head-row">
                        <div class="card-head-icon"><i class="fas fa-user-shield"></i></div>
                        <h3 class="card-head-title">Admin Account Credentials</h3>
                    </div>
                    <div style="display:flex; flex-direction:column; gap: 0.6rem; font-size: 0.88rem; margin-bottom: 1.25rem;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:var(--text-muted);">Admin Name</span>
                            <span style="font-weight:600; color:#ffffff;">{{ $user->name }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:var(--text-muted);">Email Address</span>
                            <span style="font-weight:600; color:#ffffff;">{{ $user->email }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:var(--text-muted);">Role Privilege</span>
                            <span style="font-weight:600; color:var(--neon-blue);">Super Admin</span>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="saas-btn saas-btn-glass" style="width: 100%; justify-content: center;">
                        <i class="fas fa-user-edit"></i> Edit Admin Profile
                    </a>
                </div>
            </div>

            <!-- RECENT ADMIN BLOG POSTS TABLE -->
            <div class="saas-glass-card mb-4">
                <div class="card-head-row" style="justify-content: space-between;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="card-head-icon"><i class="fas fa-file-code"></i></div>
                        <h3 class="card-head-title">Manage Published Articles & Drafts</h3>
                    </div>
                    <div style="display:flex; gap: 8px;">
                        <a href="{{ route('admin.categories.index') }}" class="saas-btn saas-btn-glass" style="padding: 0.4rem 0.85rem; font-size: 0.78rem;">
                            <i class="fas fa-folder-tree"></i> Categories
                        </a>
                        <a href="{{ route('admin.blog.create') }}" class="saas-btn saas-btn-primary" style="padding: 0.4rem 0.85rem; font-size: 0.78rem;">
                            <i class="fas fa-plus"></i> New Post
                        </a>
                    </div>
                </div>

                @if(isset($posts) && count($posts) > 0)
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Article Title</th>
                                    <th>Status</th>
                                    <th>Views</th>
                                    <th>Date Created</th>
                                    <th style="text-align:right;">CRUD Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                <tr>
                                    <td style="font-weight: 600; color: #ffffff;">{{ $post->title }}</td>
                                    <td>
                                        @if($post->status === 'published')
                                            <span class="status-pill-pub">Published</span>
                                        @else
                                            <span class="status-pill-draft">Draft</span>
                                        @endif
                                    </td>
                                    <td style="color: var(--neon-cyan); font-family: 'Fira Code', monospace; font-size: 0.85rem;">
                                        <i class="far fa-eye me-1"></i> {{ number_format($post->views) }}
                                    </td>
                                    <td>{{ $post->created_at ? $post->created_at->format('M d, Y') : '—' }}</td>
                                    <td style="text-align:right;">
                                        <a href="{{ route('admin.blog.edit', $post->id) }}" class="saas-btn saas-btn-glass" style="padding: 0.35rem 0.75rem; font-size: 0.75rem; margin-right: 4px;" title="Edit in Monaco IDE">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this post?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="saas-btn saas-btn-danger" style="padding: 0.35rem 0.75rem; font-size: 0.75rem;" title="Delete Post">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div style="text-align: center; padding: 2.5rem 1rem;">
                        <i class="fas fa-folder-open" style="font-size: 2.2rem; color: var(--text-muted); margin-bottom: 0.75rem;"></i>
                        <h4 style="color: #ffffff; margin-bottom: 0.4rem;">No Articles Found</h4>
                        <p style="color: var(--text-muted); font-size: 0.88rem; margin-bottom: 1.25rem;">Start crafting your first engineering article using the developer Monaco IDE editor.</p>
                        <a href="{{ route('admin.blog.create') }}" class="saas-btn saas-btn-primary">
                            <i class="fas fa-plus-circle"></i> Create First Article
                        </a>
                    </div>
                @endif
            </div>

        @endif

    </div>
</div>

@endsection