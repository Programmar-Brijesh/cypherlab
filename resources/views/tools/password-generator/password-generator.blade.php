@extends('layouts.app')

@section('title', 'Free Secure Random Password Generator – Web Crypto API | CypherLab')
@section('meta_description', 'Generate cryptographically secure random passwords with Web Crypto API. Custom length, character rules, and real-time entropy score. 100% private tool by CypherLab.')

@push('schema')
{!! \App\Services\SeoService::generateSoftwareSchema('Crypto Vault - Secure Password Generator', 'Generate cryptographically secure random passwords using the Web Crypto API.', route('tools.password-generator')) !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Tools' => route('home'),
    'Password Generator' => route('tools.password-generator'),
]) !!}
{!! \App\Services\SeoService::generateFaqSchema([
    'Are these passwords truly random?' => 'Yes! The generator relies on window.crypto.getRandomValues(), providing cryptographically secure random numbers rather than predictable Math.random().',
    'Are generated passwords stored in a database?' => 'No! Password generation occurs entirely in your local browser runtime. Zero data is transmitted or stored.'
]) !!}
@endpush

@section('content')

<style>
    .saas-pass-wrapper {
        --neon-blue: #00d4ff;
        --neon-cyan: #06f7e3;
        --neon-purple: #a855f7;
        --neon-pink: #ff2e9e;
        --accent-green: #22c55e;
        --accent-gold: #ffcc44;
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

    .pass-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .pass-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.1) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .pass-container {
        max-width: 1120px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .pass-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .pass-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 14px;
        border-radius: 30px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.3);
        color: var(--neon-blue);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-family: 'Fira Code', monospace;
        margin-bottom: 1rem;
    }

    .pass-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .pass-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* STUDIO WORKSPACE GRID */
    .pass-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* GLASS CARDS */
    .pass-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
    }

    .pass-card-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .pass-card-icon {
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

    .pass-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* INPUT CONTROLS */
    .pass-field-group {
        margin-bottom: 1.25rem;
    }

    .pass-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 0.45rem;
    }

    .slider-len-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .len-num-input {
        width: 70px;
        background: rgba(8, 12, 24, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        padding: 0.5rem 0.75rem;
        color: #ffffff;
        font-size: 0.9rem;
        font-family: 'Fira Code', monospace;
        text-align: center;
    }

    .len-slider {
        flex: 1;
        accent-color: var(--neon-blue);
        cursor: pointer;
    }

    /* CHECKBOX GRID */
    .char-options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .char-check-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(8, 12, 24, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 0.65rem 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .char-check-pill:hover {
        border-color: var(--neon-blue);
        background: rgba(0, 212, 255, 0.08);
    }

    .char-check-pill input[type="checkbox"] {
        accent-color: var(--neon-blue);
        width: 16px;
        height: 16px;
    }

    .char-check-text {
        font-size: 0.82rem;
        color: #e2e8f0;
        font-weight: 500;
    }

    /* OUTPUT VAULT BOX */
    .vault-output-box {
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(0, 212, 255, 0.25);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), inset 0 0 15px rgba(0, 212, 255, 0.08);
    }

    .vault-pass-text {
        font-family: 'Fira Code', monospace;
        font-size: 1.35rem;
        color: var(--neon-cyan);
        letter-spacing: 0.05em;
        word-break: break-all;
        flex: 1;
    }

    /* STRENGTH METER */
    .strength-meter-wrap {
        margin-bottom: 1.5rem;
    }

    .strength-head {
        display: flex;
        justify-content: space-between;
        font-size: 0.76rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        text-transform: uppercase;
        margin-bottom: 0.45rem;
    }

    .strength-bar-track {
        width: 100%;
        height: 8px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.08);
        overflow: hidden;
    }

    .strength-bar-fill {
        height: 100%;
        width: 0%;
        border-radius: 10px;
        transition: width 0.4s ease, background-color 0.4s ease;
        background: var(--neon-pink);
    }

    /* BUTTONS */
    .pass-btn-primary {
        width: 100%;
        padding: 0.85rem 1.5rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #00d4ff 0%, #a855f7 100%);
        border: none;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .pass-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #ffffff;
    }

    .pass-btn-glass {
        padding: 0.75rem 1.25rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        font-size: 0.85rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .pass-btn-glass:hover:not(:disabled) {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    .pass-btn-glass:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* FEATURES GRID */
    .pass-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .pass-feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .pass-feature-card:hover {
        border-color: var(--neon-cyan);
        transform: translateY(-4px);
    }

    .pass-feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(0, 212, 255, 0.12);
        color: var(--neon-blue);
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    /* TOAST */
    .pass-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .pass-toast {
        background: #0f172a;
        border: 1px solid var(--neon-blue);
        border-left: 4px solid var(--neon-blue);
        color: #ffffff;
        padding: 0.85rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: 'Fira Code', monospace;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    @media (max-width: 850px) {
        .pass-studio-grid { grid-template-columns: 1fr; }
        .char-options-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-pass-wrapper">
    <div class="pass-bg-grid"></div>
    <div class="pass-ambient-glow"></div>

    <div class="pass-container">
        
        <!-- HERO HEADER -->
        <div class="pass-hero-box">
            <div class="pass-hero-badge">
                <i class="fas fa-shield-alt"></i> CRYPTO VAULT GENERATOR
            </div>
            <h1 class="pass-hero-title">
                Generate Cryptographically <span class="grad-text">Secure Passwords</span>
            </h1>
            <p class="pass-hero-sub">
                Create unbreachable passwords using Web Crypto API (`crypto.getRandomValues()`). 100% private client-side execution with real-time entropy analysis.
            </p>
        </div>

        <!-- STUDIO WORKSPACE -->
        <div class="pass-studio-grid">
            
            <!-- LEFT PANEL: VAULT PARAMETERS -->
            <div class="pass-glass-card">
                <div class="pass-card-head">
                    <div class="pass-card-icon"><i class="fas fa-sliders-h"></i></div>
                    <h2 class="pass-card-title">Vault Parameters</h2>
                </div>

                <div class="pass-field-group">
                    <div class="pass-label">
                        <span>Password Length</span>
                        <span id="lenDisplay" style="color:var(--neon-blue); font-family:'Fira Code'; font-weight:700;">16 Chars</span>
                    </div>
                    <div class="slider-len-row">
                        <input type="range" id="lengthSlider" class="len-slider" min="4" max="64" value="16">
                        <input type="number" id="lengthInput" class="len-num-input" min="4" max="64" value="16">
                    </div>
                </div>

                <div class="pass-field-group">
                    <div class="pass-label"><span>Character Set Rules</span></div>
                    <div class="char-options-grid">
                        <label class="char-check-pill">
                            <input type="checkbox" id="includeUppercase" checked onchange="generatePassword()">
                            <span class="char-check-text">A-Z Uppercase</span>
                        </label>
                        <label class="char-check-pill">
                            <input type="checkbox" id="includeLowercase" checked onchange="generatePassword()">
                            <span class="char-check-text">a-z Lowercase</span>
                        </label>
                        <label class="char-check-pill">
                            <input type="checkbox" id="includeNumbers" checked onchange="generatePassword()">
                            <span class="char-check-text">0-9 Digits</span>
                        </label>
                        <label class="char-check-pill">
                            <input type="checkbox" id="includeSymbols" checked onchange="generatePassword()">
                            <span class="char-check-text">!@#$%^&* Symbols</span>
                        </label>
                    </div>
                </div>

                <button type="button" class="pass-btn-primary mt-auto" onclick="generatePassword()">
                    <i class="fas fa-bolt"></i> Generate Secure Password
                </button>
            </div>

            <!-- RIGHT PANEL: VAULT OUTPUT & ENTROPY -->
            <div class="pass-glass-card">
                <div class="pass-card-head">
                    <div class="pass-card-icon"><i class="fas fa-key"></i></div>
                    <h2 class="pass-card-title">Vault Output Stage</h2>
                </div>

                <div class="vault-output-box">
                    <div id="passwordDisplay" class="vault-pass-text">••••••••••••••••</div>
                    <i class="fas fa-eye" id="eyeToggle" style="color:var(--text-muted); cursor:pointer; font-size:1.1rem;" onclick="togglePassVisibility()"></i>
                </div>

                <!-- STRENGTH METER -->
                <div class="strength-meter-wrap">
                    <div class="strength-head">
                        <span style="color:var(--text-muted);">Entropy Strength Score</span>
                        <span id="strengthText" style="color:var(--accent-green);">100% Very Strong</span>
                    </div>
                    <div class="strength-bar-track">
                        <div id="strengthBar" class="strength-bar-fill" style="width: 100%; background-color: var(--accent-green);"></div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: auto;">
                    <button type="button" id="copyBtn" class="pass-btn-glass" onclick="copyPassword()">
                        <i class="fas fa-copy"></i> Copy Password
                    </button>
                    <button type="button" class="pass-btn-glass" onclick="generatePassword()">
                        <i class="fas fa-sync-alt"></i> Regenerate
                    </button>
                </div>
            </div>

        </div>

        <!-- FEATURES GRID -->
        <div class="pass-features-grid">
            <div class="pass-feature-card">
                <div class="pass-feature-icon"><i class="fas fa-user-shield"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Web Crypto API</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Uses `crypto.getRandomValues()` for hardware-level entropy.</p>
            </div>

            <div class="pass-feature-card">
                <div class="pass-feature-icon"><i class="fas fa-lock"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">100% Zero Logging</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Generated passwords reside strictly inside browser RAM.</p>
            </div>

            <div class="pass-feature-card">
                <div class="pass-feature-icon"><i class="fas fa-tachometer-alt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Entropy Analyzer</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Evaluates password length and charset combinations in real-time.</p>
            </div>
        </div>

    </div>
</div>

<div class="pass-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    let currentPassword = '';
    let isHidden = false;

    const lengthInput = document.getElementById('lengthInput');
    const lengthSlider = document.getElementById('lengthSlider');
    const includeUppercase = document.getElementById('includeUppercase');
    const includeLowercase = document.getElementById('includeLowercase');
    const includeNumbers = document.getElementById('includeNumbers');
    const includeSymbols = document.getElementById('includeSymbols');

    const passwordDisplay = document.getElementById('passwordDisplay');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const lenDisplay = document.getElementById('lenDisplay');

    const charSets = {
        upper: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        lower: 'abcdefghijklmnopqrstuvwxyz',
        numbers: '0123456789',
        symbols: '!@#$%^&*()_+-=[]{}|;:,.<>?'
    };

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'pass-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-blue);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    lengthSlider.addEventListener('input', () => {
        lengthInput.value = lengthSlider.value;
        lenDisplay.textContent = lengthSlider.value + ' Chars';
        generatePassword();
    });

    lengthInput.addEventListener('input', () => {
        let val = parseInt(lengthInput.value) || 16;
        val = Math.min(64, Math.max(4, val));
        lengthSlider.value = val;
        lenDisplay.textContent = val + ' Chars';
        generatePassword();
    });

    function generatePassword() {
        const length = parseInt(lengthInput.value) || 16;
        let chars = '';
        if (includeUppercase.checked) chars += charSets.upper;
        if (includeLowercase.checked) chars += charSets.lower;
        if (includeNumbers.checked) chars += charSets.numbers;
        if (includeSymbols.checked) chars += charSets.symbols;

        if (chars.length === 0) {
            currentPassword = '';
            passwordDisplay.innerHTML = '<span style="color:#fca5a5; font-size:0.9rem;">Select at least 1 character set</span>';
            updateStrength(null);
            return;
        }

        const array = new Uint32Array(length);
        crypto.getRandomValues(array);
        let pass = '';
        for (let i = 0; i < length; i++) {
            pass += chars[array[i] % chars.length];
        }
        currentPassword = pass;

        if (isHidden) {
            passwordDisplay.textContent = '•'.repeat(pass.length);
        } else {
            passwordDisplay.textContent = pass;
        }

        updateStrength(pass);
    }

    function updateStrength(pass) {
        if (!pass) {
            strengthBar.style.width = '0%';
            strengthText.textContent = '0% Empty';
            return;
        }

        let score = 0;
        if (pass.length >= 8) score += 20;
        if (pass.length >= 12) score += 20;
        if (pass.length >= 16) score += 20;
        if (/[A-Z]/.test(pass)) score += 10;
        if (/[a-z]/.test(pass)) score += 10;
        if (/[0-9]/.test(pass)) score += 10;
        if (/[^A-Za-z0-9]/.test(pass)) score += 10;

        let label = 'Weak';
        let color = 'var(--neon-pink)';

        if (score >= 80) {
            label = `${score}% Unbreachable`;
            color = '#22c55e';
        } else if (score >= 50) {
            label = `${score}% Medium`;
            color = 'var(--accent-gold)';
        } else {
            label = `${score}% Vulnerable`;
            color = 'var(--neon-pink)';
        }

        strengthBar.style.width = score + '%';
        strengthBar.style.backgroundColor = color;
        strengthText.textContent = label;
        strengthText.style.color = color;
    }

    function togglePassVisibility() {
        const eye = document.getElementById('eyeToggle');
        if (!currentPassword) return;
        isHidden = !isHidden;
        if (isHidden) {
            passwordDisplay.textContent = '•'.repeat(currentPassword.length);
            eye.className = 'fas fa-eye-slash';
        } else {
            passwordDisplay.textContent = currentPassword;
            eye.className = 'fas fa-eye';
        }
    }

    function copyPassword() {
        if (!currentPassword) return showToast('No password generated to copy.');
        navigator.clipboard.writeText(currentPassword).then(() => {
            showToast('Password copied to clipboard!');
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        generatePassword();
    });
</script>
@endsection