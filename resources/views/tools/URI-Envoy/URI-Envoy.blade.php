@extends('layouts.app')

@section('title', 'URI Envoy – Enterprise URL & URI Engineering Studio | CypherLabs')
@section('meta_description', 'Encode, decode, parse, validate, clean tracking parameters, batch process URLs, and generate API code snippets. 100% private & client-side.')

@section('content')

<style>
    .saas-uri-wrapper {
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

    .uri-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .uri-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.09) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .uri-container {
        max-width: 1180px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .uri-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .uri-hero-badge {
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

    .uri-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .uri-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* INPUT CARD */
    .uri-input-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
    }

    .uri-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .uri-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .uri-textarea {
        width: 100%;
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 14px;
        padding: 1rem 1.25rem;
        color: var(--neon-cyan);
        font-family: 'Fira Code', 'SF Mono', monospace;
        font-size: 0.88rem;
        line-height: 1.6;
        resize: vertical;
        transition: all 0.25s ease;
    }

    .uri-textarea:focus {
        outline: none;
        border-color: var(--neon-blue);
        box-shadow: 0 0 18px rgba(0, 212, 255, 0.2);
    }

    /* TABS */
    .uri-tabs-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 0.75rem;
    }

    .uri-tab-btn {
        padding: 0.6rem 1.2rem;
        border-radius: 30px;
        background: rgba(8, 12, 24, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-muted);
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .uri-tab-btn:hover, .uri-tab-btn.active {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    .uri-tab-pane {
        display: none;
    }

    .uri-tab-pane.active {
        display: block;
    }

    /* STUDIO GRID */
    .uri-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .uri-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
    }

    /* PARSED TABLE */
    .parsed-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.6rem 0.85rem;
        border-radius: 10px;
        background: rgba(8, 12, 24, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.06);
        margin-bottom: 6px;
        font-family: 'Fira Code', monospace;
        font-size: 0.8rem;
    }

    .parsed-key {
        color: var(--neon-blue);
        font-weight: 700;
    }

    .parsed-val {
        color: #e2e8f0;
        word-break: break-all;
    }

    /* BUTTONS */
    .uri-btn-primary {
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
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .uri-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .uri-btn-glass {
        padding: 0.55rem 1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        font-size: 0.8rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .uri-btn-glass:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    /* TOAST */
    .uri-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .uri-toast {
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
        .uri-studio-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-uri-wrapper">
    <div class="uri-bg-grid"></div>
    <div class="uri-ambient-glow"></div>

    <div class="uri-container">
        
        <!-- HERO HEADER -->
        <div class="uri-hero-box">
            <div class="uri-hero-badge">
                <i class="fas fa-link me-1"></i> URI ENVOY STUDIO
            </div>
            <h1 class="uri-hero-title">
                Enterprise URL & <span class="grad-text">URI Engineering Studio</span>
            </h1>
            <p class="uri-hero-sub">
                Encode, decode, parse parameters, validate security, sanitize UTM tracking, generate QR codes, and export API snippets instantly.
            </p>
        </div>

        <!-- MAIN INPUT CARD -->
        <div class="uri-input-card">
            <div class="uri-card-head">
                <h2 class="uri-card-title"><i class="fas fa-terminal" style="color:var(--neon-blue);"></i> Input Target URL / String</h2>
                <div style="display:flex; gap:8px;">
                    <button type="button" class="uri-btn-glass" onclick="loadSampleUrl()">Sample URL</button>
                    <button type="button" class="uri-btn-glass" onclick="clearInput()">Clear</button>
                </div>
            </div>

            <textarea id="urlInput" class="uri-textarea" rows="3" placeholder="Paste URL here (e.g. https://example.com/path?name=John+Doe&utm_source=google#section)..." oninput="processAll(this.value)"></textarea>
        </div>

        <!-- TABS BAR -->
        <div class="uri-tabs-bar">
            <button type="button" class="uri-tab-btn active" onclick="switchTab('encode')"><i class="fas fa-code"></i> Encode / Decode</button>
            <button type="button" class="uri-tab-btn" onclick="switchTab('parse')"><i class="fas fa-sitemap"></i> Inspector & Params</button>
            <button type="button" class="uri-tab-btn" onclick="switchTab('clean')"><i class="fas fa-shield-alt"></i> Security & Cleaner</button>
            <button type="button" class="uri-tab-btn" onclick="switchTab('snippets')"><i class="fas fa-laptop-code"></i> API Snippets & QR</button>
        </div>

        <!-- TAB 1: ENCODE / DECODE -->
        <div id="tab-encode" class="uri-tab-pane active">
            <div class="uri-studio-grid">
                
                <!-- LEFT: ENCODED -->
                <div class="uri-glass-card">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-lock" style="color:var(--neon-blue);"></i> Encoded Formats</h3>
                    </div>

                    <div style="margin-bottom:1.25rem;">
                        <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code'; margin-bottom:4px;">encodeURI()</div>
                        <textarea id="outEncodeUri" class="uri-textarea" rows="2" readonly></textarea>
                        <button type="button" class="uri-btn-glass" style="margin-top:6px;" onclick="copyFieldValue('outEncodeUri')"><i class="fas fa-copy"></i> Copy</button>
                    </div>

                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code'; margin-bottom:4px;">encodeURIComponent()</div>
                        <textarea id="outEncodeComp" class="uri-textarea" rows="2" readonly></textarea>
                        <button type="button" class="uri-btn-glass" style="margin-top:6px;" onclick="copyFieldValue('outEncodeComp')"><i class="fas fa-copy"></i> Copy</button>
                    </div>
                </div>

                <!-- RIGHT: DECODED -->
                <div class="uri-glass-card">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-unlock-alt" style="color:var(--neon-cyan);"></i> Decoded Formats</h3>
                    </div>

                    <div style="margin-bottom:1.25rem;">
                        <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code'; margin-bottom:4px;">decodeURI()</div>
                        <textarea id="outDecodeUri" class="uri-textarea" rows="2" readonly></textarea>
                        <button type="button" class="uri-btn-glass" style="margin-top:6px;" onclick="copyFieldValue('outDecodeUri')"><i class="fas fa-copy"></i> Copy</button>
                    </div>

                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code'; margin-bottom:4px;">decodeURIComponent()</div>
                        <textarea id="outDecodeComp" class="uri-textarea" rows="2" readonly></textarea>
                        <button type="button" class="uri-btn-glass" style="margin-top:6px;" onclick="copyFieldValue('outDecodeComp')"><i class="fas fa-copy"></i> Copy</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- TAB 2: INSPECTOR & QUERY PARAMS -->
        <div id="tab-parse" class="uri-tab-pane">
            <div class="uri-studio-grid">
                
                <!-- LEFT: STRUCTURE -->
                <div class="uri-glass-card">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-network-wired" style="color:var(--neon-blue);"></i> URL Structure Inspector</h3>
                    </div>
                    <div id="parsedStructure"></div>
                </div>

                <!-- RIGHT: PARAMS -->
                <div class="uri-glass-card">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-list-ol" style="color:var(--neon-cyan);"></i> Query Parameters</h3>
                    </div>
                    <div id="queryParamsList"></div>
                </div>

            </div>
        </div>

        <!-- TAB 3: SECURITY & CLEANER -->
        <div id="tab-clean" class="uri-tab-pane">
            <div class="uri-studio-grid">
                
                <!-- LEFT: SECURITY -->
                <div class="uri-glass-card">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-user-shield" style="color:var(--neon-blue);"></i> Security Audit</h3>
                    </div>
                    <div id="securityAuditBox" style="font-family:'Fira Code', monospace; font-size:0.85rem;"></div>
                </div>

                <!-- RIGHT: CLEANED -->
                <div class="uri-glass-card">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-broom" style="color:var(--neon-cyan);"></i> Sanitized Clean URL</h3>
                    </div>
                    <textarea id="cleanedUrlBox" class="uri-textarea" rows="3" readonly></textarea>
                    <div style="display:flex; gap:8px; margin-top:12px; flex-wrap:wrap;">
                        <button type="button" class="uri-btn-primary" onclick="stripTrackingParams()"><i class="fas fa-trash-alt"></i> Strip UTM & Tracking</button>
                        <button type="button" class="uri-btn-glass" onclick="copyFieldValue('cleanedUrlBox')"><i class="fas fa-copy"></i> Copy Cleaned URL</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- TAB 4: API SNIPPETS & QR -->
        <div id="tab-snippets" class="uri-tab-pane">
            <div class="uri-studio-grid">
                
                <!-- LEFT: SNIPPETS -->
                <div class="uri-glass-card">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-code-branch" style="color:var(--neon-blue);"></i> API Code Snippets</h3>
                    </div>

                    <div style="margin-bottom:1rem;">
                        <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code';">cURL</div>
                        <textarea id="curlBox" class="uri-textarea" rows="2" readonly></textarea>
                    </div>

                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code';">Fetch API (JS)</div>
                        <textarea id="fetchBox" class="uri-textarea" rows="3" readonly></textarea>
                    </div>
                </div>

                <!-- RIGHT: QR GENERATOR -->
                <div class="uri-glass-card" style="text-align:center;">
                    <div class="uri-card-head">
                        <h3 class="uri-card-title" style="font-size:1rem;"><i class="fas fa-qrcode" style="color:var(--neon-cyan);"></i> Instant QR Code</h3>
                    </div>
                    <div id="qrCodeContainer" style="display:flex; justify-content:center; margin:1rem 0;"></div>
                </div>

            </div>
        </div>

    </div>
</div>

<div class="uri-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const urlInput = document.getElementById('urlInput');

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'uri-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-blue);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function switchTab(tabKey) {
        document.querySelectorAll('.uri-tab-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.querySelectorAll('.uri-tab-pane').forEach(pane => pane.classList.remove('active'));
        document.getElementById(`tab-${tabKey}`).classList.add('active');
    }

    function loadSampleUrl() {
        const sample = "https://cypherlabs.io/api/v1/search?query=AI+Models&category=SaaS&utm_source=google&fbclid=12345#results";
        urlInput.value = sample;
        processAll(sample);
        showToast('Loaded sample URL');
    }

    function clearInput() {
        urlInput.value = '';
        processAll('');
        showToast('Input cleared');
    }

    function copyFieldValue(id) {
        const val = document.getElementById(id).value;
        if (!val) return showToast('Nothing to copy.');
        navigator.clipboard.writeText(val).then(() => showToast('Copied to clipboard!'));
    }

    function processAll(str) {
        if (!str.trim()) {
            document.getElementById('outEncodeUri').value = '';
            document.getElementById('outEncodeComp').value = '';
            document.getElementById('outDecodeUri').value = '';
            document.getElementById('outDecodeComp').value = '';
            document.getElementById('parsedStructure').innerHTML = '<div style="color:var(--text-muted); font-size:0.85rem;">Enter a URL to inspect...</div>';
            document.getElementById('queryParamsList').innerHTML = '<div style="color:var(--text-muted); font-size:0.85rem;">No parameters found...</div>';
            document.getElementById('securityAuditBox').innerHTML = '<div style="color:var(--text-muted);">Ready for validation...</div>';
            document.getElementById('cleanedUrlBox').value = '';
            document.getElementById('curlBox').value = '';
            document.getElementById('fetchBox').value = '';
            document.getElementById('qrCodeContainer').innerHTML = '';
            return;
        }

        // Encode / Decode
        try {
            document.getElementById('outEncodeUri').value = encodeURI(str);
            document.getElementById('outEncodeComp').value = encodeURIComponent(str);
        } catch(e) {
            document.getElementById('outEncodeUri').value = 'Encoding error';
        }

        try {
            document.getElementById('outDecodeUri').value = decodeURI(str);
            document.getElementById('outDecodeComp').value = decodeURIComponent(str);
        } catch(e) {
            document.getElementById('outDecodeUri').value = 'Decode error';
        }

        // URL Parsing
        try {
            const url = new URL(str);
            document.getElementById('parsedStructure').innerHTML = `
                <div class="parsed-row"><span class="parsed-key">Protocol</span><span class="parsed-val">${url.protocol}</span></div>
                <div class="parsed-row"><span class="parsed-key">Hostname</span><span class="parsed-val">${url.hostname}</span></div>
                <div class="parsed-row"><span class="parsed-key">Port</span><span class="parsed-val">${url.port || 'default'}</span></div>
                <div class="parsed-row"><span class="parsed-key">Path</span><span class="parsed-val">${url.pathname}</span></div>
                <div class="parsed-row"><span class="parsed-key">Search Query</span><span class="parsed-val">${url.search || 'none'}</span></div>
                <div class="parsed-row"><span class="parsed-key">Hash / Fragment</span><span class="parsed-val">${url.hash || 'none'}</span></div>
            `;

            // Query Params
            let paramsHtml = '';
            url.searchParams.forEach((val, key) => {
                paramsHtml += `<div class="parsed-row"><span class="parsed-key">${key}</span><span class="parsed-val">${val}</span></div>`;
            });
            document.getElementById('queryParamsList').innerHTML = paramsHtml || '<div style="color:var(--text-muted); font-size:0.85rem;">No query parameters</div>';

            // Security Audit
            document.getElementById('securityAuditBox').innerHTML = `
                <div style="color:#22c55e; margin-bottom:4px;"><i class="fas fa-check-circle me-1"></i> Valid URL Structure</div>
                <div style="color:var(--text-muted);">Protocol: ${url.protocol}</div>
            `;

            // Cleaned URL
            const cleaned = new URL(str);
            ['utm_source','utm_medium','utm_campaign','fbclid','gclid'].forEach(p => cleaned.searchParams.delete(p));
            document.getElementById('cleanedUrlBox').value = cleaned.toString();

            // Snippets
            document.getElementById('curlBox').value = `curl -X GET "${str}"`;
            document.getElementById('fetchBox').value = `fetch("${str}")\n  .then(res => res.json())\n  .then(data => console.log(data));`;

            // QR Code
            const qrContainer = document.getElementById('qrCodeContainer');
            qrContainer.innerHTML = '';
            if (typeof QRCode !== 'undefined') {
                new QRCode(qrContainer, { text: str, width: 140, height: 140 });
            }

        } catch(e) {
            document.getElementById('parsedStructure').innerHTML = '<div style="color:#fca5a5; font-size:0.85rem;"><i class="fas fa-exclamation-circle me-1"></i> Invalid URL string syntax</div>';
            document.getElementById('queryParamsList').innerHTML = '<div style="color:var(--text-muted); font-size:0.85rem;">-</div>';
            document.getElementById('cleanedUrlBox').value = str;
        }
    }

    function stripTrackingParams() {
        const str = urlInput.value;
        if (!str.trim()) return showToast('Input URL is empty.');
        try {
            const url = new URL(str);
            ['utm_source','utm_medium','utm_campaign','utm_term','utm_content','fbclid','gclid','ref'].forEach(p => url.searchParams.delete(p));
            urlInput.value = url.toString();
            processAll(url.toString());
            showToast('Tracking parameters stripped!');
        } catch(e) {
            showToast('Invalid URL format.');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        processAll(urlInput.value || 'https://cypherlabs.io/api/v1/search?query=AI+Models&category=SaaS&utm_source=google&fbclid=12345#results');
    });
</script>
@endsection