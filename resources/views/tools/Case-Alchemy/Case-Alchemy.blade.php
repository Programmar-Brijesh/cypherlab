@extends('layouts.app')

@section('title', 'Case Alchemy – Smart Text Case Converter Studio | CypherLabs')
@section('meta_description', 'Convert text case instantly: UPPERCASE, lowercase, Title Case, Sentence case, camelCase, kebab-case, snake_case, and alternate case. 100% private.')

@section('content')

<style>
    .saas-case-wrapper {
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

    .case-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .case-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.09) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .case-container {
        max-width: 1140px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .case-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .case-hero-badge {
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

    .case-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .case-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* STUDIO WORKSPACE GRID */
    .case-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* GLASS CARDS */
    .case-glass-card {
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

    .case-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .case-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .case-card-icon {
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

    .case-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* EDITORS */
    .case-textarea {
        width: 100%;
        min-height: 340px;
        height: 340px;
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

    .case-textarea:focus {
        outline: none;
        border-color: var(--neon-blue);
        background: rgba(8, 12, 24, 0.98);
        box-shadow: 0 0 18px rgba(0, 212, 255, 0.2);
    }

    .case-output-viewer {
        width: 100%;
        min-height: 340px;
        height: 340px;
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

    /* CASE TRANSFORMERS GRID */
    .transformers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 10px;
        margin-bottom: 2rem;
    }

    .case-pill-btn {
        padding: 0.65rem 0.85rem;
        border-radius: 12px;
        background: rgba(8, 12, 24, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
        font-size: 0.8rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .case-pill-btn:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
        transform: translateY(-2px);
    }

    .case-btn-primary {
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

    .case-btn-glass {
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

    .case-btn-glass:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    /* FEATURES GRID */
    .case-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .case-feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .case-feature-card:hover {
        border-color: var(--neon-cyan);
        transform: translateY(-4px);
    }

    .case-feature-icon {
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
    .case-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .case-toast {
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
        .case-studio-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-case-wrapper">
    <div class="case-bg-grid"></div>
    <div class="case-ambient-glow"></div>

    <div class="case-container">
        
        <!-- HERO HEADER -->
        <div class="case-hero-box">
            <div class="case-hero-badge">
                <i class="fas fa-wand-magic me-1"></i> CASE ALCHEMY STUDIO
            </div>
            <h1 class="case-hero-title">
                Smart Text <span class="grad-text">Case Converter</span>
            </h1>
            <p class="case-hero-sub">
                Convert raw text instantly across 8 developer case modes: UPPERCASE, lowercase, Title Case, camelCase, kebab-case, snake_case, and more.
            </p>
        </div>

        <!-- STUDIO WORKSPACE GRID -->
        <div class="case-studio-grid">
            
            <!-- LEFT PANEL: INPUT SOURCE -->
            <div class="case-glass-card">
                <div class="case-card-head">
                    <div class="case-head-left">
                        <div class="case-card-icon"><i class="fas fa-file-alt"></i></div>
                        <h2 class="case-card-title">Input Source Text</h2>
                    </div>
                    <div style="display:flex; gap:6px;">
                        <button type="button" class="case-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="loadSampleText()">Sample</button>
                        <button type="button" class="case-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="clearInput()">Clear</button>
                    </div>
                </div>

                <textarea id="inputText" class="case-textarea" placeholder="Paste or type text here to transform..." oninput="updateStats()"></textarea>
                <div style="font-size:0.75rem; color:var(--text-muted); text-align:right; margin-top:6px; font-family:'Fira Code';" id="textStats">0 chars | 0 words</div>
            </div>

            <!-- RIGHT PANEL: ALCHEMIZED RESULT -->
            <div class="case-glass-card">
                <div class="case-card-head">
                    <div class="case-head-left">
                        <div class="case-card-icon"><i class="fas fa-magic"></i></div>
                        <h2 class="case-card-title">Alchemized Output</h2>
                    </div>
                    <div style="display:flex; gap:6px;">
                        <button type="button" class="case-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="copyOutput()"><i class="fas fa-copy"></i> Copy</button>
                        <button type="button" class="case-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="downloadResult()"><i class="fas fa-download"></i> .txt</button>
                    </div>
                </div>

                <pre id="outputBox" class="case-output-viewer"><span style="color:var(--text-muted);">Ready — enter text and click a case transformation below...</span></pre>
            </div>

        </div>

        <!-- CASE TRANSFORMERS GRID -->
        <div class="transformers-grid">
            <button type="button" class="case-pill-btn" onclick="transformCase('upper')">UPPERCASE</button>
            <button type="button" class="case-pill-btn" onclick="transformCase('lower')">lowercase</button>
            <button type="button" class="case-pill-btn" onclick="transformCase('title')">Title Case</button>
            <button type="button" class="case-pill-btn" onclick="transformCase('sentence')">Sentence case</button>
            <button type="button" class="case-pill-btn" onclick="transformCase('camel')">camelCase</button>
            <button type="button" class="case-pill-btn" onclick="transformCase('kebab')">kebab-case</button>
            <button type="button" class="case-pill-btn" onclick="transformCase('snake')">snake_case</button>
            <button type="button" class="case-pill-btn" onclick="transformCase('alternate')">aLtErNaTe cAsE</button>
        </div>

        <!-- FEATURES GRID -->
        <div class="case-features-grid">
            <div class="case-feature-card">
                <div class="case-feature-icon"><i class="fas fa-bolt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Sub-1ms Latency</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Real-time RegExp string transformations execute 100% locally in browser RAM.</p>
            </div>

            <div class="case-feature-card">
                <div class="case-feature-icon"><i class="fas fa-code"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Developer Essential</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Native support for code-friendly `camelCase`, `kebab-case`, and `snake_case` formats.</p>
            </div>

            <div class="case-feature-card">
                <div class="case-feature-icon"><i class="fas fa-user-shield"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Zero Cloud Logging</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Your confidential text never touches remote server endpoints or database logs.</p>
            </div>
        </div>

    </div>
</div>

<div class="case-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    const inputText = document.getElementById('inputText');
    const outputBox = document.getElementById('outputBox');
    const textStats = document.getElementById('textStats');

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'case-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-blue);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function updateStats() {
        const str = inputText.value;
        const chars = str.length;
        const words = str.trim() ? str.trim().split(/\s+/).length : 0;
        textStats.textContent = `${chars} chars | ${words} words`;
    }

    function transformCase(mode) {
        const str = inputText.value;
        if (!str.trim()) {
            outputBox.innerHTML = '<span style="color:#fca5a5;">Please enter text to transform.</span>';
            return showToast('Input is empty.');
        }

        let res = '';
        switch(mode) {
            case 'upper':
                res = str.toUpperCase();
                showToast('Converted to UPPERCASE!');
                break;
            case 'lower':
                res = str.toLowerCase();
                showToast('Converted to lowercase!');
                break;
            case 'title':
                res = str.replace(/\w\S*/g, txt => txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase());
                showToast('Converted to Title Case!');
                break;
            case 'sentence':
                res = str.replace(/([^.!?]*[.!?])/g, s => {
                    const trimmed = s.trim();
                    if (!trimmed) return s;
                    return trimmed.charAt(0).toUpperCase() + trimmed.slice(1).toLowerCase();
                });
                showToast('Converted to Sentence case!');
                break;
            case 'camel':
                res = str.replace(/(?:^\w|[A-Z]|\b\w)/g, (word, index) => {
                    return index === 0 ? word.toLowerCase() : word.toUpperCase();
                }).replace(/\s+/g, '');
                showToast('Converted to camelCase!');
                break;
            case 'kebab':
                res = str.trim().toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');
                showToast('Converted to kebab-case!');
                break;
            case 'snake':
                res = str.trim().toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
                showToast('Converted to snake_case!');
                break;
            case 'alternate':
                let upper = true;
                res = '';
                for (let i = 0; i < str.length; i++) {
                    const ch = str[i];
                    if (/[a-zA-Z]/.test(ch)) {
                        res += upper ? ch.toUpperCase() : ch.toLowerCase();
                        upper = !upper;
                    } else {
                        res += ch;
                    }
                }
                showToast('Converted to aLtErNaTe cAsE!');
                break;
        }

        outputBox.textContent = res;
    }

    function loadSampleText() {
        inputText.value = "case alchemy studio is live!\nconverts developer text into camelCase, kebab-case, snake_case & TITLE CASE instantly.";
        updateStats();
        transformCase('title');
    }

    function clearInput() {
        inputText.value = '';
        outputBox.innerHTML = '<span style="color:var(--text-muted);">Ready — enter text and click a case transformation below...</span>';
        updateStats();
        showToast('Input cleared');
    }

    function copyOutput() {
        const text = outputBox.textContent;
        if (!text || text.includes('Ready — enter text')) return showToast('No output to copy.');
        navigator.clipboard.writeText(text).then(() => showToast('Result copied to clipboard!'));
    }

    function downloadResult() {
        const text = outputBox.textContent;
        if (!text || text.includes('Ready — enter text')) return showToast('No result to download.');
        const blob = new Blob([text], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'case-alchemy-result.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('.txt file downloaded!');
    }
</script>
@endsection