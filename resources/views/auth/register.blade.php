@extends('layouts.app')

@section('title', 'Create Account – CypherLabs Portal')
@section('meta_description', 'Join CypherLabs and unlock developer utilities. Register in seconds.')

@section('content')

<style>
    /* VIEWPORT-FIT NO SCROLL SAAS AUTH */
    .saas-auth-viewport {
        --neon-blue: #00d4ff;
        --neon-purple: #a855f7;
        --neon-cyan: #06f7e3;
        --bg-dark: #070913;
        --bg-card: rgba(13, 18, 33, 0.85);
        --border-glass: rgba(0, 212, 255, 0.18);
        --text-main: #f8fafc;
        --text-muted: #94a3b8;

        background: var(--bg-dark);
        min-height: calc(100vh - 75px);
        height: calc(100vh - 75px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem 1.5rem;
        position: relative;
        overflow: hidden;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .saas-auth-grid-bg {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 45px 45px;
        pointer-events: none;
    }

    .saas-auth-glow-top {
        position: absolute;
        top: -120px;
        left: 20%;
        width: 550px;
        height: 550px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.14) 0%, rgba(168, 85, 247, 0.05) 50%, transparent 70%);
        pointer-events: none;
    }

    .saas-auth-glow-bottom {
        position: absolute;
        bottom: -120px;
        right: 15%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.12) 0%, transparent 70%);
        pointer-events: none;
    }

    /* 2-COLUMN SPLIT CONTAINER */
    .saas-auth-split-container {
        width: 100%;
        max-width: 1080px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2.5rem;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    /* LEFT COLUMN - SAAS HERO SHOWCASE */
    .saas-showcase-box {
        padding-right: 1rem;
    }

    .saas-badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.3);
        color: var(--neon-blue);
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-family: 'Fira Code', monospace;
        margin-bottom: 1.25rem;
    }

    .saas-showcase-title {
        font-size: 2.3rem;
        font-weight: 800;
        color: #ffffff;
        line-height: 1.2;
        margin-bottom: 0.85rem;
        letter-spacing: -0.02em;
    }

    .saas-showcase-sub {
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .saas-feature-list {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }

    .saas-feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 12px;
        padding: 0.7rem 1rem;
        color: #e2e8f0;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .saas-feature-item i {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        background: rgba(0, 212, 255, 0.12);
        color: var(--neon-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    /* RIGHT COLUMN - COMPACT GLASS CARD */
    .saas-auth-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.6rem 1.5rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.08);
        position: relative;
    }

    .saas-auth-header {
        text-align: center;
        margin-bottom: 1.15rem;
    }

    .saas-auth-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }

    .saas-auth-sub {
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .saas-btn-google {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.25s ease;
        margin-bottom: 0.85rem;
    }

    .saas-btn-google:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.25);
        color: #ffffff;
    }

    .saas-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }

    .saas-divider-line {
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
    }

    .saas-divider-text {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-muted);
        letter-spacing: 0.08em;
        font-family: 'Fira Code', monospace;
    }

    .saas-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .saas-field-group {
        margin-bottom: 0.8rem;
    }

    .saas-label {
        display: block;
        font-size: 0.76rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 0.3rem;
    }

    .saas-input-wrap {
        position: relative;
    }

    .saas-input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 0.85rem;
    }

    .saas-input {
        width: 100%;
        background: rgba(8, 12, 24, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 0.65rem 0.85rem 0.65rem 2.3rem;
        color: #ffffff;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .saas-input:focus {
        outline: none;
        border-color: var(--neon-blue);
        background: rgba(8, 12, 24, 0.95);
        box-shadow: 0 0 14px rgba(0, 212, 255, 0.2);
    }

    .saas-btn-submit {
        width: 100%;
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #00d4ff 0%, #a855f7 100%);
        border: none;
        color: #ffffff;
        font-size: 0.85rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 5px 16px rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 0.85rem;
    }

    .saas-btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 212, 255, 0.45);
    }

    .saas-alert-danger {
        padding: 0.6rem 0.85rem;
        border-radius: 8px;
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        font-size: 0.78rem;
        margin-bottom: 0.85rem;
    }

    .email-feedback {
        font-size: 0.72rem;
        margin-top: 3px;
        display: block;
    }

    .saas-auth-footer {
        text-align: center;
        margin-top: 1rem;
        padding-top: 0.85rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .saas-auth-footer a {
        color: var(--neon-blue);
        font-weight: 600;
        text-decoration: none !important;
    }

    @media (max-width: 991px) {
        .saas-auth-viewport {
            min-height: auto;
            height: auto;
            padding: 2.5rem 1rem;
            overflow-y: auto;
        }
        .saas-auth-split-container {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .saas-showcase-box {
            padding-right: 0;
            text-align: center;
        }
        .saas-showcase-title { font-size: 1.8rem; }
        .saas-feature-list { display: none; }
    }
</style>

<div class="saas-auth-viewport">
    <div class="saas-auth-grid-bg"></div>
    <div class="saas-auth-glow-top"></div>
    <div class="saas-auth-glow-bottom"></div>

    <div class="saas-auth-split-container">
        
        <!-- LEFT COLUMN: RICH SAAS PLATFORM SHOWCASE -->
        <div class="saas-showcase-box">
            <div class="saas-badge-tag">
                <i class="fas fa-rocket me-1"></i> JOIN CYPHERLABS COMMUNITY
            </div>
            <h1 class="saas-showcase-title">
                Unlock Premium <span class="grad-text">Developer Tools</span>
            </h1>
            <p class="saas-showcase-sub">
                Create a free account in seconds to publish articles, save configurations, and access high-performance web utilities.
            </p>

            <div class="saas-feature-list">
                <div class="saas-feature-item">
                    <i class="fas fa-bolt"></i>
                    <span>Unlimited Access to QR, Image & PDF Suites</span>
                </div>
                <div class="saas-feature-item">
                    <i class="fas fa-edit"></i>
                    <span>Integrated Monaco IDE Blog CMS Editor</span>
                </div>
                <div class="saas-feature-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>100% Privacy First — No Data Selling or Tracking</span>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: COMPACT VIEWPORT FIT REGISTRATION CARD -->
        <div class="saas-auth-card">
            <div class="saas-auth-header">
                <h2 class="saas-auth-title">Create Account</h2>
                <p class="saas-auth-sub">Register in seconds for instant access</p>
            </div>

            <a href="{{ route('google.login') }}" class="saas-btn-google">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" width="16">
                <span>Register with Google</span>
            </a>

            <div class="saas-divider">
                <div class="saas-divider-line"></div>
                <span class="saas-divider-text">OR EMAIL REGISTRATION</span>
                <div class="saas-divider-line"></div>
            </div>

            @if ($errors->any())
            <div class="saas-alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="registerForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="saas-form-row">
                    <div class="saas-field-group">
                        <label class="saas-label" for="name">Full Name</label>
                        <div class="saas-input-wrap">
                            <i class="fas fa-user saas-input-icon"></i>
                            <input type="text" name="name" id="name" class="saas-input" placeholder="John Doe" value="{{ old('name') }}" required>
                        </div>
                    </div>

                    <div class="saas-field-group">
                        <label class="saas-label" for="phone">Phone</label>
                        <div class="saas-input-wrap">
                            <i class="fas fa-phone saas-input-icon"></i>
                            <input type="text" name="phone" id="phone" class="saas-input" placeholder="+91 9876543210" value="{{ old('phone') }}" required>
                        </div>
                    </div>
                </div>

                <div class="saas-field-group">
                    <label class="saas-label" for="email">Email Address</label>
                    <div class="saas-input-wrap">
                        <i class="fas fa-envelope saas-input-icon"></i>
                        <input type="email" name="email" id="email" class="saas-input" placeholder="name@company.com" value="{{ old('email') }}" required>
                    </div>
                    <span id="emailMsg" class="email-feedback"></span>
                </div>

                <div class="saas-form-row">
                    <div class="saas-field-group">
                        <label class="saas-label" for="password">Password</label>
                        <div class="saas-input-wrap">
                            <i class="fas fa-lock saas-input-icon"></i>
                            <input type="password" name="password" id="password" class="saas-input" placeholder="Min. 8 chars" required>
                        </div>
                    </div>

                    <div class="saas-field-group">
                        <label class="saas-label" for="confirm_password">Confirm</label>
                        <div class="saas-input-wrap">
                            <i class="fas fa-shield-alt saas-input-icon"></i>
                            <input type="password" id="confirm_password" class="saas-input" placeholder="Repeat password" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="saas-btn-submit" id="submitRegBtn">
                    <span>Create Account</span> <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="saas-auth-footer">
                Already have an account? <a href="{{ route('login.page') }}">Sign in now <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const pwd = document.getElementById('password').value;
    const confirmPwd = document.getElementById('confirm_password').value;
    if (pwd !== confirmPwd) {
        e.preventDefault();
        alert('Password and Confirm Password must match.');
        document.getElementById('confirm_password').focus();
    }
});

$(document).ready(function() {
    $("#email").on('keyup', function() {
        let email = $(this).val().trim();
        const emailMsg = document.getElementById('emailMsg');
        const submitBtn = document.getElementById('submitRegBtn');
        if (email.length === 0) {
            emailMsg.innerHTML = '';
            submitBtn.disabled = false;
            return;
        }
        $.ajax({
            url: "{{ route('check.email') }}",
            method: 'POST',
            data: {
                email: email,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.status === "taken") {
                    emailMsg.innerHTML = '<i class="fas fa-times-circle"></i> Email already registered';
                    emailMsg.style.color = '#ef4444';
                    submitBtn.disabled = true;
                } else {
                    emailMsg.innerHTML = '<i class="fas fa-check-circle"></i> Available';
                    emailMsg.style.color = '#22c55e';
                    submitBtn.disabled = false;
                }
            }
        });
    });
});
</script>
@endsection