@extends('layouts.app')

@section('title', 'Base64 Vanguard – Real-Time Base64 Encoder & Decoder | CypherLabs')
@section('meta_description', 'Fast, secure, and private Base64 encoder and decoder. Full UTF-8 Unicode & emoji support. 100% client-side tool.')

@section('content')

<style>
    .saas-b64-wrapper {
        --neon-blue: #00d4ff;
        --neon-cyan: #06f7e3;
        --neon-purple: #a855f7;
        --neon-pink: #ff2e9e;
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

    .b64-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .b64-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.09) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .b64-container {
        max-width: 1140px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .b64-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .b64-hero-badge {
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

    .b64-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .b64-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* STUDIO WORKSPACE GRID */
    .b64-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    /* GLASS CARDS */
    .b64-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
    }

    .b64-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .b64-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .b64-card-icon {
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
        font-family: 'Fira Code', monospace;
    }

    .b64-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* CODE EDITORS */
    .b64-editor-textarea {
        width: 100%;
        min-height: 400px;
        height: 400px;
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        color: #ffffff;
        font-family: 'Fira Code', 'SF Mono', monospace;
        font-size: 0.88rem;
        line-height: 1.6;
        resize: vertical;
        transition: all 0.25s ease;
    }

    .b64-editor-textarea:focus {
        outline: none;
        border-color: var(--neon-blue);
        background: rgba(8, 12, 24, 0.98);
        box-shadow: 0 0 18px rgba(0, 212, 255, 0.2);
    }

    .b64-output-viewer {
        width: 100%;
        min-height: 400px;
        height: 400px;
        overflow: auto;
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        color: var(--neon-cyan);
        font-family: 'Fira Code', 'SF Mono', monospace;
        font-size: 0.88rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-all;
    }

    /* STATUS BADGE */
    .status-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        text-transform: uppercase;
    }

    .badge-valid {
        background: rgba(0, 212, 255, 0.12);
        border: 1px solid rgba(0, 212, 255, 0.35);
        color: var(--neon-blue);
    }

    .badge-invalid {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        color: #fca5a5;
    }

    /* ACTION BAR */
    .b64-action-bar {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .b64-btn-primary {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #00d4ff 0%, #06f7e3 100%);
        border: none;
        color: #070913;
        font-size: 0.88rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .b64-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .b64-btn-glass {
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        font-size: 0.86rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .b64-btn-glass:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    /* FEATURES GRID */
    .b64-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .b64-feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .b64-feature-card:hover {
        border-color: var(--neon-purple);
        transform: translateY(-4px);
    }

    .b64-feature-icon {
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
    .b64-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .b64-toast {
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
        .b64-studio-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-b64-wrapper">
    <div class="b64-bg-grid"></div>
    <div class="b64-ambient-glow"></div>

    <div class="b64-container">
        
        <!-- HERO HEADER -->
        <div class="b64-hero-box">
            <div class="b64-hero-badge">
                <i class="fas fa-shield-haltered me-1"></i> BASE64 VANGUARD STUDIO
            </div>
            <h1 class="b64-hero-title">
                Real-Time <span class="grad-text">Base64 Encoder & Decoder</span>
            </h1>
            <p class="b64-hero-sub">
                Convert text, URLs, and JSON into Base64 or decode back instantly. Full UTF-8 Unicode & emoji support with sub-1ms client-side performance.
            </p>
        </div>

        <!-- STUDIO WORKSPACE GRID -->
        <div class="b64-studio-grid">
            
            <!-- LEFT PANEL: INPUT EDITOR -->
            <div class="b64-glass-card">
                <div class="b64-card-head">
                    <div class="b64-head-left">
                        <div class="b64-card-icon"><i class="fas fa-arrow-right-to-bracket"></i></div>
                        <h2 class="b64-card-title">Input Content</h2>
                    </div>
                    <div style="display:flex; gap:6px;">
                        <button type="button" class="b64-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="loadSampleText()">Sample</button>
                        <button type="button" class="b64-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="clearInput()">Clear</button>
                    </div>
                </div>

                <textarea id="inputText" class="b64-editor-textarea" placeholder="Paste text to encode or Base64 string to decode..." oninput="updateCharCount()"></textarea>
                <div style="font-size:0.75rem; color:var(--text-muted); text-align:right; margin-top:6px; font-family:'Fira Code';" id="charCounter">0 chars</div>
            </div>

            <!-- RIGHT PANEL: RESULT STAGE -->
            <div class="b64-glass-card">
                <div class="b64-card-head">
                    <div class="b64-head-left">
                        <div class="b64-card-icon"><i class="fas fa-arrow-left-from-bracket"></i></div>
                        <h2 class="b64-card-title">Transformed Result</h2>
                    </div>
                    <span id="statusBadge" class="status-badge-pill badge-valid">
                        <i class="fas fa-info-circle me-1"></i> READY
                    </span>
                </div>

                <pre id="outputBox" class="b64-output-viewer"><span style="color:var(--text-muted);">Ready — enter text and click Encode or Decode...</span></pre>
            </div>

        </div>

        <!-- ACTION BUTTONS BAR -->
        <div class="b64-action-bar">
            <button type="button" class="b64-btn-primary" onclick="handleEncode()">
                <i class="fas fa-lock"></i> Encode to Base64
            </button>
            <button type="button" class="b64-btn-primary" onclick="handleDecode()" style="background: linear-gradient(135deg, #a855f7 0%, #00d4ff 100%);">
                <i class="fas fa-unlock-alt"></i> Decode from Base64
            </button>
            <button type="button" class="b64-btn-glass" onclick="copyOutput()">
                <i class="fas fa-copy"></i> Copy Result
            </button>
            <button type="button" class="b64-btn-glass" onclick="downloadResult()">
                <i class="fas fa-download"></i> Download .txt
            </button>
        </div>

        <!-- FEATURES GRID -->
        <div class="b64-features-grid">
            <div class="b64-feature-card">
                <div class="b64-feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Zero Data Leakage</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">All operations execute 100% inside your local browser memory.</p>
            </div>

            <div class="b64-feature-card">
                <div class="b64-feature-icon"><i class="fas fa-globe"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">UTF-8 & Emoji Ready</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Encodes complex Unicode text, special symbols, and emojis cleanly.</p>
            </div>

            <div class="b64-feature-card">
                <div class="b64-feature-icon"><i class="fas fa-bolt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Sub-1ms Performance</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Uses native browser `TextEncoder` & `btoa` binaries for instant transforms.</p>
            </div>
        </div>

    </div>
</div>

<div class="b64-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    const inputText = document.getElementById('inputText');
    const outputBox = document.getElementById('outputBox');
    const statusBadge = document.getElementById('statusBadge');
    const charCounter = document.getElementById('charCounter');

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'b64-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-blue);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function updateCharCount() {
        charCounter.textContent = `${inputText.value.length} chars`;
    }

    function setStatus(text, isValid = true) {
        statusBadge.innerHTML = `<i class="fas fa-${isValid ? 'check-circle' : 'exclamation-circle'} me-1"></i> ${text}`;
        statusBadge.className = `status-badge-pill ${isValid ? 'badge-valid' : 'badge-invalid'}`;
    }

    function encodeToBase64(str) {
        const utf8Bytes = new TextEncoder().encode(str);
        let binary = '';
        for (let i = 0; i < utf8Bytes.length; i++) {
            binary += String.fromCharCode(utf8Bytes[i]);
        }
        return btoa(binary);
    }

    function decodeFromBase64(base64Str) {
        const binary = atob(base64Str);
        const uint8Array = new Uint8Array(binary.length);
        for (let i = 0; i < binary.length; i++) {
            uint8Array[i] = binary.charCodeAt(i);
        }
        return new TextDecoder('utf-8').decode(uint8Array);
    }

    function handleEncode() {
        const raw = inputText.value;
        if (!raw.trim()) {
            outputBox.innerHTML = '<span style="color:#fca5a5;">Please enter text to encode.</span>';
            setStatus('EMPTY INPUT', false);
            return showToast('Input is empty.');
        }
        try {
            const result = encodeToBase64(raw);
            outputBox.textContent = result;
            setStatus('ENCODED', true);
            showToast('Base64 encoding complete!');
        } catch (e) {
            outputBox.innerHTML = `<span style="color:#fca5a5;">Encoding error: ${e.message}</span>`;
            setStatus('ERROR', false);
        }
    }

    function handleDecode() {
        const raw = inputText.value.trim();
        if (!raw) {
            outputBox.innerHTML = '<span style="color:#fca5a5;">Please enter a Base64 string to decode.</span>';
            setStatus('EMPTY INPUT', false);
            return showToast('Input is empty.');
        }
        try {
            const result = decodeFromBase64(raw);
            outputBox.textContent = result;
            setStatus('DECODED', true);
            showToast('Base64 decoding complete!');
        } catch (e) {
            outputBox.innerHTML = '<span style="color:#fca5a5;"><i class="fas fa-exclamation-triangle me-1"></i> Invalid Base64 String format.</span>';
            setStatus('INVALID BASE64', false);
            showToast('Invalid Base64 format error.');
        }
    }

    function loadSampleText() {
        inputText.value = "Base64 Vanguard Studio 🔥\nClean, secure & ultra-fast developer tool.\n100% private UTF-8 encoding.";
        updateCharCount();
        handleEncode();
    }

    function clearInput() {
        inputText.value = '';
        outputBox.innerHTML = '<span style="color:var(--text-muted);">Ready — enter text and click Encode or Decode...</span>';
        setStatus('READY', true);
        updateCharCount();
        showToast('Input cleared');
    }

    function copyOutput() {
        const text = outputBox.textContent;
        if (!text || text.includes('Ready — enter text')) return showToast('No result to copy.');
        navigator.clipboard.writeText(text).then(() => showToast('Result copied to clipboard!'));
    }

    function downloadResult() {
        const text = outputBox.textContent;
        if (!text || text.includes('Ready — enter text')) return showToast('No result to download.');
        const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'base64-vanguard-result.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('.txt file downloaded!');
    }
</script>
@endsection