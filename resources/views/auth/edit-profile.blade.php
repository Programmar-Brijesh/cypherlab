@extends('layouts.app')

@section('title', 'Account Profile Settings – CypherLabs')
@section('meta_description', 'Update your personal profile information and security settings.')

@section('content')

<style>
    .saas-ep-wrapper {
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

    .ep-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .ep-ambient-glow {
        position: absolute;
        top: -120px;
        left: 50%;
        transform: translateX(-50%);
        width: 700px;
        height: 700px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.09) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .ep-container {
        max-width: 860px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HEADER BANNER */
    .ep-header-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(10, 15, 30, 0.95) 100%);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.75rem 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .ep-header-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #00d4ff, #a855f7, #06f7e3);
    }

    .ep-user-flex {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .ep-avatar-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00d4ff, #a855f7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.6rem;
        font-weight: 800;
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .ep-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.2rem;
    }

    .ep-sub {
        color: var(--text-muted);
        font-size: 0.86rem;
    }

    /* CARD SECTIONS */
    .saas-ep-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 18px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.35);
        transition: border-color 0.3s ease;
    }

    .saas-ep-card:hover {
        border-color: var(--border-glass-hover);
    }

    .ep-card-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .ep-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: radial-gradient(circle at 30% 30%, rgba(0, 212, 255, 0.2), rgba(168, 85, 247, 0.1));
        border: 1px solid rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 1rem;
    }

    .ep-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* FORM GRID & INPUTS */
    .ep-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .ep-full-width {
        grid-column: 1 / -1;
    }

    .ep-field-group {
        margin-bottom: 0.85rem;
    }

    .ep-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 0.4rem;
    }

    .ep-input-wrap {
        position: relative;
    }

    .ep-input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 0.9rem;
    }

    .ep-input {
        width: 100%;
        background: rgba(8, 12, 24, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        color: #ffffff;
        font-size: 0.9rem;
        transition: all 0.25s ease;
    }

    .ep-input:focus {
        outline: none;
        border-color: var(--neon-blue);
        background: rgba(8, 12, 24, 0.95);
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
    }

    .ep-toggle-pw {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .ep-toggle-pw:hover {
        color: var(--neon-blue);
    }

    /* BUTTONS */
    .ep-btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.8rem 1.75rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #00d4ff 0%, #a855f7 100%);
        border: none;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
    }

    .ep-btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 26px rgba(0, 212, 255, 0.45);
        color: #ffffff;
    }

    .ep-btn-glass {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.8rem 1.4rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.25s ease;
    }

    .ep-btn-glass:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .ep-alert-success {
        padding: 0.85rem 1rem;
        border-radius: 10px;
        background: rgba(34, 197, 94, 0.12);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #4ade80;
        font-size: 0.86rem;
        margin-bottom: 1.5rem;
    }

    .ep-alert-danger {
        padding: 0.85rem 1rem;
        border-radius: 10px;
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        font-size: 0.86rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 640px) {
        .ep-form-grid { grid-template-columns: 1fr; }
        .ep-header-card { flex-direction: column; text-align: center; }
        .ep-user-flex { flex-direction: column; }
    }
</style>

<div class="saas-ep-wrapper">
    <div class="ep-bg-grid"></div>
    <div class="ep-ambient-glow"></div>

    <div class="ep-container">
        
        <!-- HEADER CARD -->
        <div class="ep-header-card">
            <div class="ep-user-flex">
                <div class="ep-avatar-circle">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h1 class="ep-title">Account Profile Settings</h1>
                    <div class="ep-sub">Update your personal account credentials and security settings</div>
                </div>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="ep-btn-glass">
                    <i class="fas fa-arrow-left me-1"></i> Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="ep-alert-success">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="ep-alert-danger">
                <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="ep-alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf

            <!-- SECTION 1: PERSONAL DETAILS -->
            <div class="saas-ep-card">
                <div class="ep-card-head">
                    <div class="ep-card-icon"><i class="fas fa-id-card"></i></div>
                    <h2 class="ep-card-title">Personal Information</h2>
                </div>

                <div class="ep-form-grid">
                    <div class="ep-field-group">
                        <label class="ep-label" for="name">Full Name</label>
                        <div class="ep-input-wrap">
                            <i class="fas fa-user ep-input-icon"></i>
                            <input type="text" name="name" id="name" class="ep-input" placeholder="John Doe" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <div class="ep-field-group">
                        <label class="ep-label" for="phone">Phone Number</label>
                        <div class="ep-input-wrap">
                            <i class="fas fa-phone ep-input-icon"></i>
                            <input type="text" name="phone" id="phone" class="ep-input" placeholder="+91 9876543210" value="{{ old('phone', $user->phone) }}">
                        </div>
                    </div>

                    <div class="ep-field-group ep-full-width">
                        <label class="ep-label" for="email">Email Address</label>
                        <div class="ep-input-wrap">
                            <i class="fas fa-envelope ep-input-icon"></i>
                            <input type="email" name="email" id="email" class="ep-input" placeholder="name@company.com" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: PASSWORD & SECURITY -->
            <div class="saas-ep-card">
                <div class="ep-card-head">
                    <div class="ep-card-icon"><i class="fas fa-lock"></i></div>
                    <h2 class="ep-card-title">Security & Password</h2>
                </div>

                @if(empty($user->password))
                    <div style="color: var(--neon-cyan); font-size: 0.88rem; margin-bottom: 1.25rem; background: rgba(0, 212, 255, 0.08); padding: 0.85rem 1rem; border-radius: 10px; border: 1px solid rgba(0, 212, 255, 0.25);">
                        <i class="fas fa-shield-alt me-1"></i> Your account uses Google Sign In. Set up a password below to enable password login.
                    </div>
                    <div class="ep-form-grid">
                        <div class="ep-field-group">
                            <label class="ep-label" for="new_password">New Password</label>
                            <div class="ep-input-wrap">
                                <i class="fas fa-key ep-input-icon"></i>
                                <input type="password" name="new_password" id="new_password" class="ep-input" placeholder="Min. 8 characters" minlength="8">
                                <i class="fas fa-eye ep-toggle-pw" onclick="togglePw('new_password')"></i>
                            </div>
                        </div>

                        <div class="ep-field-group">
                            <label class="ep-label" for="new_password_confirmation">Confirm Password</label>
                            <div class="ep-input-wrap">
                                <i class="fas fa-check-circle ep-input-icon"></i>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="ep-input" placeholder="Repeat password">
                                <i class="fas fa-eye ep-toggle-pw" onclick="togglePw('new_password_confirmation')"></i>
                            </div>
                        </div>
                    </div>
                @else
                    <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.25rem;">
                        Leave password fields blank if you do not wish to change your current password.
                    </div>
                    <div class="ep-field-group mb-3">
                        <label class="ep-label" for="current_password">Current Password</label>
                        <div class="ep-input-wrap">
                            <i class="fas fa-unlock-alt ep-input-icon"></i>
                            <input type="password" name="current_password" id="current_password" class="ep-input" placeholder="Enter current password to verify changes">
                            <i class="fas fa-eye ep-toggle-pw" onclick="togglePw('current_password')"></i>
                        </div>
                    </div>

                    <div class="ep-form-grid">
                        <div class="ep-field-group">
                            <label class="ep-label" for="new_password">New Password</label>
                            <div class="ep-input-wrap">
                                <i class="fas fa-key ep-input-icon"></i>
                                <input type="password" name="new_password" id="new_password" class="ep-input" placeholder="Min. 8 characters" minlength="8">
                                <i class="fas fa-eye ep-toggle-pw" onclick="togglePw('new_password')"></i>
                            </div>
                        </div>

                        <div class="ep-field-group">
                            <label class="ep-label" for="new_password_confirmation">Confirm New Password</label>
                            <div class="ep-input-wrap">
                                <i class="fas fa-check-circle ep-input-icon"></i>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="ep-input" placeholder="Repeat new password">
                                <i class="fas fa-eye ep-toggle-pw" onclick="togglePw('new_password_confirmation')"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- ACTION BAR -->
            <div style="display:flex; justify-content:space-between; align-items:center; gap: 1rem; flex-wrap:wrap;">
                <a href="{{ route('dashboard') }}" class="ep-btn-glass">
                    <i class="fas fa-times"></i> Cancel
                </a>
                <button type="submit" class="ep-btn-submit">
                    <i class="fas fa-save"></i> Save Profile Changes
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function togglePw(id) {
    const input = document.getElementById(id);
    const icon = event.currentTarget;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

@endsection