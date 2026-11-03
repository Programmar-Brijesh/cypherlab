@extends('layouts.app')

@section('title', 'Free Online QR Code Generator – Custom Color & High-Res Export | CypherLab')
@section('meta_description', 'Generate custom high-resolution QR codes online with live color customization, error correction, and instant PNG export. 100% free & private client-side tool by CypherLab.')

@push('schema')
{!! \App\Services\SeoService::generateSoftwareSchema('QR Architect - Free Online QR Code Generator', 'Generate custom high-resolution QR codes with live color customization, error correction, and instant PNG export.', route('tools.qr-generator')) !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Tools' => route('home'),
    'QR Code Generator' => route('tools.qr-generator'),
]) !!}
{!! \App\Services\SeoService::generateFaqSchema([
    'Is this QR Code Generator free?' => 'Yes, CypherLab QR Architect is 100% free with unlimited generation and zero restrictions.',
    'Are generated QR codes permanent?' => 'Yes! The generated QR codes encode static text or URLs directly into the pattern and will never expire.',
    'Is my data stored on any server?' => 'No, all QR codes are rendered 100% client-side in your browser using the JavaScript Web Crypto and Canvas APIs.'
]) !!}
@endpush

@section('content')

<style>
    /* ============================================
       QR ARCHITECT – ULTRA-PREMIUM SAAS STUDIO
       ============================================ */
    .saas-qr-wrapper {
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

    .qr-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .qr-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.1) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .qr-container {
        max-width: 1120px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .qr-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .qr-hero-badge {
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

    .qr-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .qr-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* STUDIO WORKSPACE GRID */
    .qr-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* STUDIO CARDS */
    .qr-glass-card {
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

    .qr-card-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .qr-card-icon {
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

    .qr-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* INPUTS & CONTROLS */
    .qr-field-group {
        margin-bottom: 1.25rem;
    }

    .qr-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 0.45rem;
    }

    .qr-input,
    textarea.qr-input {
        width: 100%;
        background: rgba(8, 12, 24, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: #ffffff;
        font-size: 0.9rem;
        font-family: 'Fira Code', monospace;
        transition: all 0.25s ease;
    }

    .qr-input:focus {
        outline: none;
        border-color: var(--neon-blue);
        background: rgba(8, 12, 24, 0.95);
        box-shadow: 0 0 16px rgba(0, 212, 255, 0.2);
    }

    /* PRESET PILLS */
    .preset-pills-row {
        display: flex;
        gap: 6px;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .preset-pill {
        padding: 3px 10px;
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: var(--text-muted);
        font-size: 0.72rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .preset-pill:hover, .preset-pill.active {
        background: rgba(0, 212, 255, 0.15);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    /* COLOR PICKERS GRID */
    .qr-colors-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .color-picker-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(8, 12, 24, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 0.5rem 0.85rem;
    }

    .color-input-native {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: transparent;
        cursor: pointer;
    }

    .color-val-code {
        font-size: 0.82rem;
        font-family: 'Fira Code', monospace;
        color: #ffffff;
    }

    /* PREVIEW STAGE */
    .qr-stage-wrapper {
        flex: 1;
        min-height: 340px;
        border-radius: 16px;
        background: radial-gradient(circle at 50% 50%, rgba(0, 212, 255, 0.08) 0%, rgba(8, 12, 24, 0.95) 70%);
        border: 1px dashed rgba(0, 212, 255, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .qr-stage-wrapper::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(0, 212, 255, 0.06) 50%, transparent 100%);
        animation: scannerBeam 3.5s ease-in-out infinite;
        pointer-events: none;
    }

    @keyframes scannerBeam {
        0%, 100% { transform: translateY(-100%); opacity: 0; }
        50% { transform: translateY(100%); opacity: 1; }
    }

    #qrOutputBox {
        position: relative;
        z-index: 2;
        padding: 16px;
        border-radius: 18px;
        background: rgba(4, 8, 18, 0.7);
        backdrop-filter: blur(12px);
        box-shadow: 0 10px 35px rgba(0, 0, 0, 0.6), 0 0 30px rgba(0, 212, 255, 0.15);
        transition: transform 0.3s ease;
    }

    #qrOutputBox:hover {
        transform: scale(1.02);
    }

    /* BUTTONS */
    .qr-btn-primary {
        width: 100%;
        padding: 0.8rem 1.5rem;
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

    .qr-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #ffffff;
    }

    .qr-actions-row {
        display: flex;
        gap: 10px;
    }

    .qr-btn-action {
        flex: 1;
        padding: 0.75rem 1rem;
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

    .qr-btn-action:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    /* FEATURES GRID */
    .qr-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .qr-feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .qr-feature-card:hover {
        border-color: var(--neon-purple);
        transform: translateY(-4px);
    }

    .qr-feature-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: rgba(0, 212, 255, 0.1);
        color: var(--neon-blue);
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    /* TOAST NOTIFICATION */
    .qr-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .qr-toast {
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
        .qr-studio-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-qr-wrapper">
    <div class="qr-bg-grid"></div>
    <div class="qr-ambient-glow"></div>

    <div class="qr-container">
        
        <!-- HERO HEADER -->
        <div class="qr-hero-box">
            <div class="qr-hero-badge">
                <i class="fas fa-qrcode"></i> VECTOR QR ARCHITECT SUITE
            </div>
            <h1 class="qr-hero-title">
                Craft High-Performance <span class="grad-text">Custom QR Codes</span>
            </h1>
            <p class="qr-hero-sub">
                Generate high-resolution customizable vector QR codes instantly. 100% private, client-side execution with real-time color styling and high-res PNG export.
            </p>
        </div>

        <!-- STUDIO WORKSPACE -->
        <div class="qr-studio-grid">
            
            <!-- LEFT PANEL: STUDIO SETTINGS -->
            <div class="qr-glass-card">
                <div class="qr-card-head">
                    <div class="qr-card-icon"><i class="fas fa-sliders-h"></i></div>
                    <h2 class="qr-card-title">Studio Parameters</h2>
                </div>

                <div class="preset-pills-row">
                    <span class="preset-pill active" onclick="setPreset('https://cypherlabs.in', this)">URL</span>
                    <span class="preset-pill" onclick="setPreset('WIFI:S:MyNetwork;P:SecretPassword;;', this)">Wi-Fi</span>
                    <span class="preset-pill" onclick="setPreset('BEGIN:VCARD\nVERSION:3.0\nN:Doe;John\nTEL:+123456789\nEND:VCARD', this)">vCard</span>
                    <span class="preset-pill" onclick="setPreset('mailto:contact@cypherlabs.in', this)">Email</span>
                </div>

                <div class="qr-field-group">
                    <div class="qr-label">
                        <span>QR Payload Content</span>
                        <span id="charCounter" style="color:var(--neon-blue); font-family:'Fira Code';">21 chars</span>
                    </div>
                    <textarea id="qrText" class="qr-input" rows="4" placeholder="Enter URL, text, or Wi-Fi string..." oninput="updateCharCount()">https://cypherlabs.in</textarea>
                </div>

                <div class="qr-field-group">
                    <div class="qr-label"><span>Resolution (Pixels)</span></div>
                    <select id="qrSize" class="qr-input" onchange="generateQR()">
                        <option value="200">200 x 200 (Compact)</option>
                        <option value="300" selected>300 x 300 (Standard)</option>
                        <option value="400">400 x 400 (HD)</option>
                        <option value="600">600 x 600 (Print Ultra HD)</option>
                    </select>
                </div>

                <div class="qr-colors-grid">
                    <div>
                        <div class="qr-label"><span>Foreground Color</span></div>
                        <div class="color-picker-wrap">
                            <input type="color" id="qrDark" class="color-input-native" value="#00d4ff" onchange="updateHexLabel('dark'); generateQR();">
                            <span id="hexDark" class="color-val-code">#00d4ff</span>
                        </div>
                    </div>

                    <div>
                        <div class="qr-label"><span>Background Color</span></div>
                        <div class="color-picker-wrap">
                            <input type="color" id="qrLight" class="color-input-native" value="#070913" onchange="updateHexLabel('light'); generateQR();">
                            <span id="hexLight" class="color-val-code">#070913</span>
                        </div>
                    </div>
                </div>

                <div class="qr-field-group mb-4">
                    <div class="qr-label"><span>Error Correction Level</span></div>
                    <select id="qrCorrection" class="qr-input" onchange="generateQR()">
                        <option value="L">Low (7% recovery)</option>
                        <option value="M" selected>Medium (15% recovery)</option>
                        <option value="Q">Quartile (25% recovery)</option>
                        <option value="H">High (30% recovery)</option>
                    </select>
                </div>

                <button type="button" class="qr-btn-primary" onclick="generateQR()">
                    <i class="fas fa-bolt"></i> Generate QR Code
                </button>
            </div>

            <!-- RIGHT PANEL: LIVE PREVIEW STAGE -->
            <div class="qr-glass-card">
                <div class="qr-card-head">
                    <div class="qr-card-icon"><i class="fas fa-eye"></i></div>
                    <h2 class="qr-card-title">Live Stage Preview</h2>
                </div>

                <div class="qr-stage-wrapper">
                    <div id="qrOutputBox"></div>
                </div>

                <div class="qr-actions-row">
                    <button class="qr-btn-action" onclick="downloadPNG()">
                        <i class="fas fa-download"></i> PNG Export
                    </button>
                    <button class="qr-btn-action" onclick="copyQRImage()">
                        <i class="fas fa-copy"></i> Copy Image
                    </button>
                </div>
            </div>

        </div>

        <!-- FEATURES GRID -->
        <div class="qr-features-grid">
            <div class="qr-feature-card">
                <div class="qr-feature-icon"><i class="fas fa-bolt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Sub-5ms Execution</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Generates vector matrix instantly inside your browser memory.</p>
            </div>

            <div class="qr-feature-card">
                <div class="qr-feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">100% Client-Side Privacy</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Zero server storage or analytics logging. Your URLs remain private.</p>
            </div>

            <div class="qr-feature-card">
                <div class="qr-feature-icon"><i class="fas fa-palette"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Custom Hex Palette</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Customize foreground and background neon contrast hex values.</p>
            </div>
        </div>

    </div>
</div>

<div class="qr-toast-container" id="toastContainer"></div>

<!-- SEO Content & Technical Guide Section -->
<div class="container py-5">
    <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow text-white">
        <h2 class="h3 fw-bold text-gradient-primary mb-3">Free Online Vector QR Code Generator (QR Architect)</h2>
        <p class="text-white-50 mb-4">
            CypherLab QR Architect is a high-performance vector QR code generator engineered for developers, marketers, and business professionals. Create custom, scannable QR codes instantly directly in your browser without data tracking, server latency, or expiration limits.
        </p>

        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <h3 class="h5 font-semibold text-info mb-2"><i class="fas fa-list-ol me-2"></i> How to Generate a QR Code</h3>
                <ol class="text-white-50 ps-3">
                    <li class="mb-2">Enter your website URL, Wi-Fi credentials, contact details, or text into the input field.</li>
                    <li class="mb-2">Customize the output resolution (from 250px up to 1000px high-res).</li>
                    <li class="mb-2">Select custom dark/light color palette options to match your brand design.</li>
                    <li class="mb-2">Choose your desired Reed-Solomon Error Correction Level (Low, Medium, Quartile, High).</li>
                    <li class="mb-2">Click <strong>Download PNG</strong> or copy the generated image directly to your clipboard.</li>
                </ol>
            </div>
            <div class="col-md-6">
                <h3 class="h5 font-semibold text-success mb-2"><i class="fas fa-star me-2"></i> Key Features</h3>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong>100% Private Client-Side Generation:</strong> Data stays local on your machine.</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong>High-Resolution PNG Export:</strong> Ready for print, packaging, business cards, and websites.</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong>Permanent Static QR Codes:</strong> No redirects, no third-party server dependencies, and no expiration dates.</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> <strong>Advanced Error Correction:</strong> Keeps QR codes scannable even if partially damaged or covered by logos.</li>
                </ul>
            </div>
        </div>

        <!-- FAQ Section -->
        <h3 class="h4 fw-bold text-white mb-3"><i class="fas fa-question-circle text-warning me-2"></i> Frequently Asked Questions</h3>
        <div class="accordion accordion-flush mb-4" id="qrFaqAccordion">
            <div class="accordion-item bg-transparent text-white border-secondary">
                <h4 class="accordion-header">
                    <button class="accordion-button collapsed bg-transparent text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Is this QR Code Generator free to use for commercial projects?
                    </button>
                </h4>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#qrFaqAccordion">
                    <div class="accordion-body text-white-50">
                        Yes! CypherLab QR Architect is completely free for personal and commercial applications. You can use generated QR codes on products, marketing flyers, menus, websites, and print ads without licensing fees or attribution requirements.
                    </div>
                </div>
            </div>
            <div class="accordion-item bg-transparent text-white border-secondary">
                <h4 class="accordion-header">
                    <button class="accordion-button collapsed bg-transparent text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Do generated static QR codes ever expire?
                    </button>
                </h4>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#qrFaqAccordion">
                    <div class="accordion-body text-white-50">
                        No. Static QR codes encode the destination payload (such as a URL or plain text) directly into the pixel pattern. Because there are no intermediate redirect servers involved, the QR code remains active permanently.
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Tools -->
        <div class="pt-3 border-top border-secondary">
            <h4 class="h6 text-muted mb-3">RELATED DEVELOPER UTILITIES</h4>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('tools.image-compressor') }}" class="btn btn-sm btn-outline-secondary text-white-50">Image Compressor</a>
                <a href="{{ route('tools.json-alchemist') }}" class="btn btn-sm btn-outline-secondary text-white-50">JSON Alchemist</a>
                <a href="{{ route('tools.base64-vanguard') }}" class="btn btn-sm btn-outline-secondary text-white-50">Base64 Encoder</a>
                <a href="{{ route('tools.uri-envoy') }}" class="btn btn-sm btn-outline-secondary text-white-50">URI Envoy</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/qrcode.min.js') }}"></script>
<script>
    if (typeof QRCode === 'undefined') {
        document.write('<script src="https://cdn.jsdelivr.net/npm/qrcodejs2@0.0.2/qrcode.min.js"><\/script>');
    }
</script>
<script>
    let qrInstance = null;

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = 'qr-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-blue);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function updateCharCount() {
        const input = document.getElementById('qrText');
        const counter = document.getElementById('charCounter');
        if (input && counter) {
            counter.textContent = `${input.value.length} chars`;
        }
    }

    function updateHexLabel(type) {
        if (type === 'dark') {
            const dark = document.getElementById('qrDark');
            const label = document.getElementById('hexDark');
            if (dark && label) label.textContent = dark.value;
        } else {
            const light = document.getElementById('qrLight');
            const label = document.getElementById('hexLight');
            if (light && label) label.textContent = light.value;
        }
    }

    function setPreset(text, element) {
        document.querySelectorAll('.preset-pill').forEach(el => el.classList.remove('active'));
        if (element) {
            element.classList.add('active');
        }
        const qrInput = document.getElementById('qrText');
        if (qrInput) {
            qrInput.value = text;
        }
        updateCharCount();
        generateQR();
    }

    function generateQR() {
        const qrInput = document.getElementById('qrText');
        const container = document.getElementById('qrOutputBox');
        if (!container) return;

        const text = qrInput ? qrInput.value.trim() : 'https://cypherlabs.in';
        if (!text) {
            showToast('Please enter text or URL for QR code.');
            return;
        }

        const sizeEl = document.getElementById('qrSize');
        const darkEl = document.getElementById('qrDark');
        const lightEl = document.getElementById('qrLight');
        const correctionEl = document.getElementById('qrCorrection');

        const size = sizeEl ? parseInt(sizeEl.value) || 300 : 300;
        const dark = darkEl ? darkEl.value : '#00d4ff';
        const light = lightEl ? lightEl.value : '#070913';
        const correction = correctionEl ? correctionEl.value : 'M';

        if (typeof QRCode === 'undefined') {
            showToast('Loading QR engine, retrying...');
            setTimeout(generateQR, 300);
            return;
        }

        container.innerHTML = '';

        try {
            const correctLevel = (QRCode.CorrectLevel && QRCode.CorrectLevel[correction] !== undefined)
                ? QRCode.CorrectLevel[correction]
                : 0;

            qrInstance = new QRCode(container, {
                text: text,
                width: size,
                height: size,
                colorDark: dark,
                colorLight: light,
                correctLevel: correctLevel
            });

            showToast('QR code generated successfully!');
        } catch (e) {
            console.error('QR generation error:', e);
            showToast('Error generating QR code. Content may be too long.');
        }
    }

    function downloadPNG() {
        const canvas = document.querySelector('#qrOutputBox canvas');
        const img = document.querySelector('#qrOutputBox img');

        let dataUrl = '';
        if (canvas && canvas.toDataURL) {
            try {
                dataUrl = canvas.toDataURL('image/png');
            } catch (e) {
                // Ignore canvas security errors if any
            }
        }
        if (!dataUrl && img && img.src && !img.src.startsWith('data:image/svg')) {
            dataUrl = img.src;
        }

        if (!dataUrl) {
            return showToast('Generate a QR code first.');
        }

        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = 'CypherLabs-qr.png';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        showToast('PNG image downloaded successfully!');
    }

    async function copyQRImage() {
        const canvas = document.querySelector('#qrOutputBox canvas');
        const img = document.querySelector('#qrOutputBox img');

        if (!canvas && !img) {
            return showToast('No QR code to copy.');
        }

        try {
            if (canvas && canvas.toBlob) {
                canvas.toBlob(async (blob) => {
                    if (blob && navigator.clipboard && navigator.clipboard.write) {
                        try {
                            await navigator.clipboard.write([
                                new ClipboardItem({ 'image/png': blob })
                            ]);
                            showToast('QR code copied to clipboard!');
                            return;
                        } catch (err) {
                            downloadPNG();
                        }
                    } else {
                        downloadPNG();
                    }
                }, 'image/png');
                return;
            }

            if (img && img.src) {
                const response = await fetch(img.src);
                const blob = await response.blob();
                if (navigator.clipboard && navigator.clipboard.write) {
                    await navigator.clipboard.write([
                        new ClipboardItem({ [blob.type || 'image/png']: blob })
                    ]);
                    showToast('QR code copied to clipboard!');
                    return;
                }
            }
            downloadPNG();
        } catch (err) {
            downloadPNG();
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        updateCharCount();
        generateQR();
    });
</script>
@endsection