@extends('layouts.app')

@section('title', 'Free Online JSON Formatter, Validator & Minifier | CypherLab')
@section('meta_description', 'Format, beautify, validate, and minify JSON data online with instant syntax highlighting and error detection. 100% private client-side tool by CypherLab.')

@push('schema')
{!! \App\Services\SeoService::generateSoftwareSchema('JSON Alchemist - Online JSON Formatter & Validator', 'Format, beautify, validate, and minify JSON data instantly with syntax highlighting.', route('tools.json-alchemist')) !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Tools' => route('home'),
    'JSON Formatter' => route('tools.json-alchemist'),
]) !!}
{!! \App\Services\SeoService::generateFaqSchema([
    'How do I validate JSON for syntax errors?' => 'Paste your raw JSON string into JSON Alchemist. The tool automatically parses the input and highlights syntax errors with line-by-line line numbers.',
    'Is my sensitive API data private?' => 'Yes! JSON Alchemist processes all input 100% client-side in Javascript without sending payloads to external servers.'
]) !!}
@endpush

@section('content')

<style>
    .saas-json-wrapper {
        --neon-cyan: #06f7e3;
        --neon-blue: #00d4ff;
        --neon-purple: #a855f7;
        --neon-pink: #ff2e9e;
        --accent-gold: #ffcc44;
        --bg-dark: #070913;
        --bg-card: rgba(13, 18, 33, 0.85);
        --border-glass: rgba(6, 247, 227, 0.18);
        --border-glass-hover: rgba(6, 247, 227, 0.4);
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

    .json-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(6, 247, 227, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(6, 247, 227, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .json-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(6, 247, 227, 0.08) 0%, rgba(0, 212, 255, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .json-container {
        max-width: 1140px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .json-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .json-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 14px;
        border-radius: 30px;
        background: rgba(6, 247, 227, 0.08);
        border: 1px solid rgba(6, 247, 227, 0.3);
        color: var(--neon-cyan);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-family: 'Fira Code', monospace;
        margin-bottom: 1rem;
    }

    .json-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .json-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* STUDIO WORKSPACE GRID */
    .json-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    /* GLASS CARDS */
    .json-glass-card {
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

    .json-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .json-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .json-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: radial-gradient(circle at 30% 30%, rgba(6, 247, 227, 0.2), rgba(0, 212, 255, 0.1));
        border: 1px solid rgba(6, 247, 227, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-cyan);
        font-size: 1rem;
        font-family: 'Fira Code', monospace;
        font-weight: 700;
    }

    .json-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* CODE EDITORS */
    .json-editor-textarea {
        width: 100%;
        min-height: 420px;
        height: 420px;
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

    .json-editor-textarea:focus {
        outline: none;
        border-color: var(--neon-cyan);
        background: rgba(8, 12, 24, 0.98);
        box-shadow: 0 0 18px rgba(6, 247, 227, 0.2);
    }

    .json-output-viewer {
        width: 100%;
        min-height: 420px;
        height: 420px;
        overflow: auto;
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        color: #ffffff;
        font-family: 'Fira Code', 'SF Mono', monospace;
        font-size: 0.88rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* SYNTAX COLOR HIGHLIGHTS */
    .json-output-viewer .key { color: var(--neon-blue); font-weight: 600; }
    .json-output-viewer .string { color: var(--neon-cyan); }
    .json-output-viewer .number { color: var(--accent-gold); font-weight: 600; }
    .json-output-viewer .boolean { color: var(--neon-pink); font-weight: 700; }
    .json-output-viewer .null { color: var(--neon-pink); font-style: italic; }

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
        background: rgba(6, 247, 227, 0.12);
        border: 1px solid rgba(6, 247, 227, 0.35);
        color: var(--neon-cyan);
    }

    .badge-invalid {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        color: #fca5a5;
    }

    /* ACTION BAR */
    .json-action-bar {
        display: flex;
        justify-content: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .json-btn-primary {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #06f7e3 0%, #00d4ff 100%);
        border: none;
        color: #070913;
        font-size: 0.88rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(6, 247, 227, 0.3);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .json-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(6, 247, 227, 0.45);
        color: #070913;
    }

    .json-btn-glass {
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

    .json-btn-glass:hover {
        background: rgba(6, 247, 227, 0.12);
        border-color: var(--neon-cyan);
        color: var(--neon-cyan);
    }

    /* FEATURES GRID */
    .json-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .json-feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .json-feature-card:hover {
        border-color: var(--neon-blue);
        transform: translateY(-4px);
    }

    .json-feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(6, 247, 227, 0.12);
        color: var(--neon-cyan);
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    /* TOAST */
    .json-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .json-toast {
        background: #0f172a;
        border: 1px solid var(--neon-cyan);
        border-left: 4px solid var(--neon-cyan);
        color: #ffffff;
        padding: 0.85rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: 'Fira Code', monospace;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    @media (max-width: 850px) {
        .json-studio-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-json-wrapper">
    <div class="json-bg-grid"></div>
    <div class="json-ambient-glow"></div>

    <div class="json-container">
        
        <!-- HERO HEADER -->
        <div class="json-hero-box">
            <div class="json-hero-badge">
                <i class="fab fa-js me-1"></i> JSON ALCHEMIST STUDIO
            </div>
            <h1 class="json-hero-title">
                Real-Time <span class="grad-text">JSON Formatter & Validator</span>
            </h1>
            <p class="json-hero-sub">
                Beautify, minify, validate, and syntax highlight complex JSON structures instantly. 100% private client-side execution — zero server logging.
            </p>
        </div>

        <!-- STUDIO WORKSPACE GRID -->
        <div class="json-studio-grid">
            
            <!-- LEFT PANEL: INPUT EDITOR -->
            <div class="json-glass-card">
                <div class="json-card-head">
                    <div class="json-head-left">
                        <div class="json-card-icon">{ }</div>
                        <h2 class="json-card-title">Input Payload</h2>
                    </div>
                    <div style="display:flex; gap:6px;">
                        <button type="button" class="json-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="loadSampleJSON()">Sample</button>
                        <button type="button" class="json-btn-glass" style="padding:0.35rem 0.75rem; font-size:0.75rem;" onclick="clearInput()">Clear</button>
                    </div>
                </div>

                <textarea id="jsonInput" class="json-editor-textarea" placeholder='Paste your JSON data here...&#10;e.g. {"project":"CypherLabs","status":"active","tools":4}'></textarea>
            </div>

            <!-- RIGHT PANEL: FORMATTED OUTPUT -->
            <div class="json-glass-card">
                <div class="json-card-head">
                    <div class="json-head-left">
                        <div class="json-card-icon"><i class="fas fa-code"></i></div>
                        <h2 class="json-card-title">Formatted Output</h2>
                    </div>
                    <span id="statusBadge" class="status-badge-pill badge-valid" style="display:none;">
                        <i class="fas fa-check-circle me-1"></i> VALID JSON
                    </span>
                </div>

                <pre id="jsonOutput" class="json-output-viewer"><span style="color:var(--text-muted);">Your formatted JSON output will appear here...</span></pre>
            </div>

        </div>

        <!-- ACTION BUTTONS BAR -->
        <div class="json-action-bar">
            <button type="button" class="json-btn-primary" onclick="beautifyJSON()">
                <i class="fas fa-magic"></i> Beautify JSON
            </button>
            <button type="button" class="json-btn-primary" onclick="minifyJSON()" style="background: linear-gradient(135deg, #a855f7 0%, #00d4ff 100%);">
                <i class="fas fa-compress-alt"></i> Minify JSON
            </button>
            <button type="button" class="json-btn-glass" onclick="validateJSON()">
                <i class="fas fa-check-circle"></i> Validate Only
            </button>
            <button type="button" class="json-btn-glass" onclick="copyOutput()">
                <i class="fas fa-copy"></i> Copy Output
            </button>
            <button type="button" class="json-btn-glass" onclick="downloadJSON()">
                <i class="fas fa-download"></i> Download .json
            </button>
        </div>

        <!-- FEATURES GRID -->
        <div class="json-features-grid">
            <div class="json-feature-card">
                <div class="json-feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Zero Cloud Uploads</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">All JSON data remains inside your browser V8 JavaScript memory.</p>
            </div>

            <div class="json-feature-card">
                <div class="json-feature-icon"><i class="fas fa-bolt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Instant Highlighting</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Dual-tone neon colors for keys, strings, numbers, and boolean values.</p>
            </div>

            <div class="json-feature-card">
                <div class="json-feature-icon"><i class="fas fa-file-code"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Export & Minify</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Compress payloads for API transport or export clean `.json` files.</p>
            </div>
        </div>

    </div>
</div>

<div class="json-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    const inputEl = document.getElementById('jsonInput');
    const outputEl = document.getElementById('jsonOutput');
    const statusBadge = document.getElementById('statusBadge');

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'json-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-cyan);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function syntaxHighlight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(
            /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
            function (match) {
                let cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            }
        );
    }

    function processJSON(action) {
        const raw = inputEl.value.trim();
        if (!raw) {
            outputEl.innerHTML = '<span style="color:var(--text-muted);">Please paste or enter some JSON data...</span>';
            statusBadge.style.display = 'none';
            return false;
        }
        let parsed;
        try {
            parsed = JSON.parse(raw);
        } catch (e) {
            outputEl.innerHTML = `<span style="color:#fca5a5;"><i class="fas fa-exclamation-triangle me-1"></i> Invalid JSON Syntax:\n${e.message}</span>`;
            statusBadge.innerHTML = '<i class="fas fa-times-circle me-1"></i> INVALID JSON';
            statusBadge.className = 'status-badge-pill badge-invalid';
            statusBadge.style.display = 'inline-flex';
            return false;
        }

        if (action === 'beautify') {
            const formatted = JSON.stringify(parsed, null, 2);
            outputEl.innerHTML = syntaxHighlight(formatted);
            showToast('JSON formatted & beautified successfully!');
        } else if (action === 'minify') {
            const minified = JSON.stringify(parsed);
            outputEl.innerHTML = syntaxHighlight(minified);
            showToast('JSON minified for payload transport!');
        }

        statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> VALID JSON';
        statusBadge.className = 'status-badge-pill badge-valid';
        statusBadge.style.display = 'inline-flex';
        return true;
    }

    function beautifyJSON() { processJSON('beautify'); }
    function minifyJSON() { processJSON('minify'); }

    function validateJSON() {
        const raw = inputEl.value.trim();
        if (!raw) return showToast('Please enter some JSON to validate.');
        try {
            JSON.parse(raw);
            statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> VALID JSON';
            statusBadge.className = 'status-badge-pill badge-valid';
            statusBadge.style.display = 'inline-flex';
            showToast('JSON syntax is 100% valid!');
            beautifyJSON();
        } catch (e) {
            statusBadge.innerHTML = '<i class="fas fa-times-circle me-1"></i> INVALID JSON';
            statusBadge.className = 'status-badge-pill badge-invalid';
            statusBadge.style.display = 'inline-flex';
            outputEl.innerHTML = `<span style="color:#fca5a5;"><i class="fas fa-exclamation-triangle me-1"></i> Invalid JSON Syntax:\n${e.message}</span>`;
            showToast('Invalid JSON syntax error!');
        }
    }

    function loadSampleJSON() {
        const sample = {
            "application": "CypherLabs Developer Portal",
            "version": "2.4.0",
            "active": true,
            "modules": ["QR Architect", "Visual Forge", "JSON Alchemist"],
            "config": {
                "encryption": "AES-256-GCM",
                "maxPayloadMb": 10
            }
        };
        inputEl.value = JSON.stringify(sample, null, 2);
        beautifyJSON();
    }

    function clearInput() {
        inputEl.value = '';
        outputEl.innerHTML = '<span style="color:var(--text-muted);">Your formatted JSON output will appear here...</span>';
        statusBadge.style.display = 'none';
        showToast('Editor cleared');
    }

    function copyOutput() {
        const text = outputEl.innerText;
        if (!text.trim() || text.includes('appear here')) return showToast('No formatted output to copy.');
        navigator.clipboard.writeText(text).then(() => showToast('Formatted JSON copied to clipboard!'));
    }

    function downloadJSON() {
        const text = outputEl.innerText;
        if (!text.trim() || text.includes('appear here')) return showToast('No formatted output to download.');
        const blob = new Blob([text], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'cypherlabs-formatted.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('.json file downloaded!');
    }
</script>
@endsection