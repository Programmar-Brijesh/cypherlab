@extends('layouts.app')

@section('title', 'CypherLab - Free Online Developer Tools & Technical Guides')
@section('meta_description', 'Free client-side developer tools suite: QR generator, image compressor, JSON formatter, password vault, Base64 encoder, PDF tools & tech tutorials by Brijesh Verma (Programmer Brijesh).')

@push('schema')
{!! \App\Services\SeoService::generateWebSiteSchema() !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
]) !!}
@endpush

@section('content')
<style>
    :root {
        --bg-base: #030712;
        --bg-card: rgba(15, 23, 42, 0.75);
        --bg-card-hover: rgba(30, 41, 59, 0.88);
        --border-glass: rgba(0, 212, 255, 0.15);
        --border-glass-hover: rgba(0, 212, 255, 0.45);
        --neon-blue: #00d4ff;
        --neon-purple: #a855f7;
        --neon-cyan: #06f7e3;
        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --text-muted: #64748b;
        --radius-lg: 16px;
        --radius-xl: 24px;
    }

    body {
        background-color: var(--bg-base) !important;
    }

    .saas-tools-wrapper {
        color: var(--text-primary);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        padding-bottom: 4rem;
    }

    /* HERO SECTION */
    .saas-hero {
        position: relative;
        padding: 3rem 1.5rem 2.5rem;
        text-align: center;
        background: radial-gradient(circle at 50% 0%, rgba(0, 212, 255, 0.14) 0%, rgba(168, 85, 247, 0.06) 50%, transparent 80%);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-glass);
        margin-bottom: 2.5rem;
        overflow: hidden;
        backdrop-filter: blur(16px);
    }

    .saas-hero-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0, 212, 255, 0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.035) 1px, transparent 1px);
        background-size: 36px 36px;
        z-index: 0;
        pointer-events: none;
    }

    .saas-hero-content {
        position: relative;
        z-index: 2;
        max-width: 840px;
        margin: 0 auto;
    }

    .saas-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 30px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.25);
        color: var(--neon-blue);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1.25rem;
        font-family: 'Fira Code', monospace;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--neon-blue);
        box-shadow: 0 0 10px var(--neon-blue);
        animation: pulseDot 2s ease-in-out infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    .saas-hero-title {
        font-size: 3rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1px;
        margin-bottom: 1rem;
        color: #ffffff;
    }

    .grad-text {
        background: linear-gradient(135deg, #00d4ff 0%, #a855f7 50%, #06f7e3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .saas-hero-sub {
        color: var(--text-secondary);
        font-size: 1.05rem;
        max-width: 620px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }

    /* SEARCH & FILTER CONTROLS */
    .saas-search-wrapper {
        max-width: 620px;
        margin: 0 auto 1.5rem;
        position: relative;
    }

    .saas-search-input {
        width: 100%;
        padding: 16px 20px 16px 50px;
        border-radius: 14px;
        background: rgba(10, 15, 30, 0.9);
        border: 1px solid var(--border-glass);
        color: #ffffff;
        font-size: 0.95rem;
        backdrop-filter: blur(12px);
        transition: all 0.3s ease;
    }

    .saas-search-input:focus {
        outline: none;
        border-color: var(--neon-blue);
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.25);
    }

    .saas-search-wrapper i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1.05rem;
    }

    .filter-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }

    .tool-pill {
        padding: 6px 14px;
        border-radius: 20px;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--text-secondary);
        font-size: 0.82rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
        font-family: 'Fira Code', monospace;
    }

    .tool-pill:hover, .tool-pill.active {
        background: rgba(0, 212, 255, 0.15);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.2);
    }

    /* STATS BAR */
    .saas-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        background: rgba(10, 15, 30, 0.7);
        border: 1px solid var(--border-glass);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.5rem;
        margin-bottom: 3rem;
        backdrop-filter: blur(12px);
    }

    .stat-box {
        text-align: center;
    }

    .stat-val {
        font-size: 1.4rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 0.2rem;
        font-family: 'Fira Code', monospace;
    }

    .stat-lbl {
        font-size: 0.78rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* SECTION TITLE */
    .section-head-wrap {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 0.75rem;
    }

    .section-head-title {
        font-size: 1.35rem;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-head-title i {
        color: var(--neon-blue);
    }

    /* FEATURED TOOLS GRID */
    .featured-tools-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .featured-tool-card {
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.85) 0%, rgba(10, 15, 30, 0.95) 100%);
        border: 1px solid var(--border-glass);
        border-top: 2px solid var(--neon-blue);
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        text-decoration: none !important;
        overflow: hidden;
    }

    .featured-tool-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--neon-blue), var(--neon-purple), transparent);
        opacity: 0;
        transition: opacity 0.35s ease;
    }

    .featured-tool-card:hover {
        transform: translateY(-8px) scale(1.01);
        border-color: var(--border-glass-hover);
        box-shadow: 0 16px 36px rgba(0, 212, 255, 0.18), inset 0 0 20px rgba(0, 212, 255, 0.04);
    }

    .featured-tool-card:hover::before {
        opacity: 1;
    }

    .tool-icon-box {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        background: radial-gradient(circle at 30% 30%, rgba(0, 212, 255, 0.25), rgba(168, 85, 247, 0.15));
        border: 1px solid rgba(0, 212, 255, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 1.5rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 0 20px rgba(0, 212, 255, 0.2);
        transition: all 0.35s ease;
    }

    .featured-tool-card:hover .tool-icon-box {
        transform: scale(1.12) rotate(-4deg);
        border-color: var(--neon-blue);
        box-shadow: 0 0 25px rgba(0, 212, 255, 0.4);
    }

    .tool-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .tool-card-desc {
        color: var(--text-secondary);
        font-size: 0.88rem;
        line-height: 1.6;
        margin-bottom: 1.25rem;
        flex: 1;
    }

    .tool-sub-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 1.25rem;
    }

    .sub-tag {
        font-size: 0.72rem;
        padding: 3px 8px;
        border-radius: 4px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.2);
        color: var(--neon-blue);
        font-family: 'Fira Code', monospace;
    }

    .tool-card-action {
        color: var(--neon-blue);
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        font-family: 'Fira Code', monospace;
    }

    .tool-card-action i {
        transition: transform 0.25s ease;
    }

    .featured-tool-card:hover .tool-card-action {
        color: #ffffff;
        text-shadow: 0 0 10px var(--neon-blue);
    }

    .featured-tool-card:hover .tool-card-action i {
        transform: translateX(5px);
        color: var(--neon-blue);
    }

    /* ALL TOOLS GRID */
    .tools-catalog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3.5rem;
    }

    .saas-tool-card {
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.75) 0%, rgba(10, 15, 30, 0.85) 100%);
        border: 1px solid var(--border-glass);
        border-radius: var(--radius-lg);
        padding: 1.35rem;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        text-decoration: none !important;
        overflow: hidden;
    }

    .saas-tool-card:hover {
        transform: translateY(-5px);
        border-color: var(--border-glass-hover);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.14);
        background: rgba(20, 30, 55, 0.85);
    }

    .mini-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: radial-gradient(circle at 30% 30%, rgba(0, 212, 255, 0.2), rgba(168, 85, 247, 0.1));
        border: 1px solid rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 1.2rem;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }

    .saas-tool-card:hover .mini-icon {
        transform: scale(1.1) rotate(-3deg);
        border-color: var(--neon-blue);
    }

    .mini-badge {
        position: absolute;
        top: 14px;
        right: 14px;
        padding: 3px 8px;
        border-radius: 4px;
        background: rgba(0, 212, 255, 0.1);
        border: 1px solid rgba(0, 212, 255, 0.25);
        color: var(--neon-blue);
        font-size: 0.68rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
    }

    /* FEATURES GRID */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3.5rem;
    }

    .feature-box {
        background: rgba(10, 15, 30, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        text-align: center;
    }

    .feature-box i {
        font-size: 1.8rem;
        color: var(--neon-blue);
        margin-bottom: 0.85rem;
    }

    .feature-box h4 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.4rem;
    }

    .feature-box p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin: 0;
    }

    /* RESPONSIVE */
    @media (max-width: 991px) {
        .saas-hero-title { font-size: 2.3rem; }
        .saas-stats-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 576px) {
        .saas-hero { padding: 2rem 1rem 1.75rem; }
        .saas-hero-title { font-size: 1.8rem; }
        .saas-stats-grid { grid-template-columns: 1fr; }
        .tools-catalog-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-tools-wrapper">

    <!-- HERO SECTION -->
    <section class="saas-hero">
        <div class="saas-hero-grid"></div>
        <div class="saas-hero-content">
            <div class="saas-badge">
                <span class="pulse-dot"></span> CYPHERLABS UTILITY SUITE
            </div>
            <h1 class="saas-hero-title">
                Empower Your Workflow with <span class="grad-text">Developer Utilities</span>
            </h1>
            <p class="saas-hero-sub">
                Lightning-fast, 100% client-side privacy-first web tools. No signups, no rate limits, completely free.
            </p>

            <div class="saas-search-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" class="saas-search-input" id="toolSearchInput" placeholder="Search utilities (QR, image compression, JSON, PDF, Base64...)" onkeyup="filterSaaSTools()">
            </div>

            <div class="filter-pills">
                <span class="tool-pill active" onclick="filterCategory('all')">All Utilities</span>
                <span class="tool-pill" onclick="filterCategory('developer')">Developer</span>
                <span class="tool-pill" onclick="filterCategory('media')">Media & Image</span>
                <span class="tool-pill" onclick="filterCategory('security')">Security</span>
                <span class="tool-pill" onclick="filterCategory('pdf')">PDF Suite</span>
            </div>
        </div>
    </section>

    <!-- METRIC STRIP -->
    <div class="saas-stats-grid">
        <div class="stat-box">
            <div class="stat-val"><i class="fas fa-bolt text-cyan me-1"></i> &lt; 10ms</div>
            <div class="stat-lbl">Client Processing</div>
        </div>
        <div class="stat-box">
            <div class="stat-val"><i class="fas fa-shield-alt text-cyan me-1"></i> 100%</div>
            <div class="stat-lbl">Privacy First</div>
        </div>
        <div class="stat-box">
            <div class="stat-val"><i class="fas fa-infinity text-cyan me-1"></i> Unlimited</div>
            <div class="stat-lbl">Free Access</div>
        </div>
        <div class="stat-box">
            <div class="stat-val"><i class="fas fa-server text-cyan me-1"></i> 99.99%</div>
            <div class="stat-lbl">Uptime Status</div>
        </div>
    </div>

    <!-- FEATURED TRENDING TOOLS -->
    <div class="section-head-wrap">
        <div class="section-head-title">
            <i class="fas fa-fire text-cyan"></i> Trending Utilities
        </div>
    </div>

    <div class="featured-tools-grid">
        <a href="{{ route('tools.qr-generator') }}" class="featured-tool-card" data-category="developer media" data-tags="qr code generator vector SVG logo">
            <div class="tool-icon-box"><i class="fas fa-qrcode"></i></div>
            <h3 class="tool-card-title">QR Architect</h3>
            <p class="tool-card-desc">Create high-definition customizable QR codes with custom colors, logo embedding, and SVG/PNG vector exports.</p>
            <div class="tool-sub-tags">
                <span class="sub-tag"><i class="fas fa-check me-1"></i>SVG Export</span>
                <span class="sub-tag"><i class="fas fa-check me-1"></i>Logo Embed</span>
                <span class="sub-tag"><i class="fas fa-check me-1"></i>Vector HD</span>
            </div>
            <div class="tool-card-action">Launch QR Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.image-compressor') }}" class="featured-tool-card" data-category="media" data-tags="image compressor optimizer webp png jpg">
            <div class="tool-icon-box"><i class="fas fa-compress-alt"></i></div>
            <h3 class="tool-card-title">Visual Forge</h3>
            <p class="tool-card-desc">Intelligent client-side image compression reducing payload size up to 85% while maintaining crisp fidelity.</p>
            <div class="tool-sub-tags">
                <span class="sub-tag"><i class="fas fa-check me-1"></i>WebP / PNG</span>
                <span class="sub-tag"><i class="fas fa-check me-1"></i>85% Smaller</span>
                <span class="sub-tag"><i class="fas fa-check me-1"></i>Bulk Compress</span>
            </div>
            <div class="tool-card-action">Compress Image <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.json-alchemist') }}" class="featured-tool-card" data-category="developer" data-tags="json formatter beautifier minifier validator">
            <div class="tool-icon-box"><i class="fab fa-js"></i></div>
            <h3 class="tool-card-title">JSON Alchemist</h3>
            <p class="tool-card-desc">Beautify, validate, format, and minify complex JSON data structures with real-time syntax highlighting.</p>
            <div class="tool-sub-tags">
                <span class="sub-tag"><i class="fas fa-check me-1"></i>Beautify</span>
                <span class="sub-tag"><i class="fas fa-check me-1"></i>Validate Schema</span>
                <span class="sub-tag"><i class="fas fa-check me-1"></i>Minify</span>
            </div>
            <div class="tool-card-action">Format JSON <i class="fas fa-arrow-right"></i></div>
        </a>
    </div>

    <!-- ALL UTILITIES CATALOG -->
    <div class="section-head-wrap">
        <div class="section-head-title">
            <i class="fas fa-th-large text-cyan"></i> Complete Utility Suite
        </div>
    </div>

    <div class="tools-catalog-grid" id="toolsCatalogGrid">
        <a href="{{ route('tools.qr-generator') }}" class="saas-tool-card" data-category="developer media" data-tags="qr generator code logo">
            <span class="mini-badge">QR</span>
            <div class="mini-icon"><i class="fas fa-qrcode"></i></div>
            <h3 class="tool-card-title">QR Architect</h3>
            <p class="tool-card-desc">Custom QR generator with SVG export</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.image-compressor') }}" class="saas-tool-card" data-category="media" data-tags="image compressor webp">
            <span class="mini-badge">MEDIA</span>
            <div class="mini-icon"><i class="fas fa-file-image"></i></div>
            <h3 class="tool-card-title">Visual Forge</h3>
            <p class="tool-card-desc">Compress WebP, PNG, JPG images</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.json-alchemist') }}" class="saas-tool-card" data-category="developer" data-tags="json formatter beautifier">
            <span class="mini-badge">DEV</span>
            <div class="mini-icon"><i class="fas fa-code"></i></div>
            <h3 class="tool-card-title">JSON Alchemist</h3>
            <p class="tool-card-desc">Format & validate JSON schemas</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.password-generator') }}" class="saas-tool-card" data-category="security" data-tags="password generator vault cipher">
            <span class="mini-badge">SECURITY</span>
            <div class="mini-icon"><i class="fas fa-lock"></i></div>
            <h3 class="tool-card-title">Vault Key</h3>
            <p class="tool-card-desc">Generate cryptographically secure passwords</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.base64-vanguard') }}" class="saas-tool-card" data-category="developer" data-tags="base64 encoder decoder string">
            <span class="mini-badge">DEV</span>
            <div class="mini-icon"><i class="fas fa-exchange-alt"></i></div>
            <h3 class="tool-card-title">Base64 Vanguard</h3>
            <p class="tool-card-desc">Encode & decode strings or files</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.case-alchemy') }}" class="saas-tool-card" data-category="developer" data-tags="case converter uppercase camelcase">
            <span class="mini-badge">TEXT</span>
            <div class="mini-icon"><i class="fas fa-text-height"></i></div>
            <h3 class="tool-card-title">Case Alchemy</h3>
            <p class="tool-card-desc">Transform uppercase, camelCase, snake_case</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.chromatic-studio') }}" class="saas-tool-card" data-category="developer media" data-tags="color converter hex rgb hsl">
            <span class="mini-badge">COLOR</span>
            <div class="mini-icon"><i class="fas fa-palette"></i></div>
            <h3 class="tool-card-title">Chromatic Studio</h3>
            <p class="tool-card-desc">Convert HEX, RGB, HSL color palettes</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="{{ route('tools.uri-envoy') }}" class="saas-tool-card" data-category="developer" data-tags="url encoder uri envoy">
            <span class="mini-badge">URL</span>
            <div class="mini-icon"><i class="fas fa-link"></i></div>
            <h3 class="tool-card-title">URI Envoy</h3>
            <p class="tool-card-desc">Encode & decode URLs safely</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>

        <a href="/" class="saas-tool-card" data-category="pdf" data-tags="pdf tools merge split compress document">
            <span class="mini-badge">PDF</span>
            <div class="mini-icon"><i class="fas fa-file-pdf"></i></div>
            <h3 class="tool-card-title">PDF Werkstatt</h3>
            <p class="tool-card-desc">Merge, split & compress PDF files</p>
            <div class="tool-card-action">Open Tool <i class="fas fa-arrow-right"></i></div>
        </a>
    </div>

    <!-- WHY CYPHERLABS FEATURES -->
    <div class="section-head-wrap">
        <div class="section-head-title">
            <i class="fas fa-shield-virus text-cyan"></i> Engineered for Privacy & Speed
        </div>
    </div>

    <div class="features-grid">
        <div class="feature-box">
            <i class="fas fa-bolt"></i>
            <h4>Sub-10ms Latency</h4>
            <p>Runs locally in your web browser with zero server roundtrips.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-user-shield"></i>
            <h4>100% Private</h4>
            <p>Your files and data never leave your browser. Zero tracking.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-infinity"></i>
            <h4>Zero Limits</h4>
            <p>No daily usage caps, subscription popups, or hidden fees.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-mobile-alt"></i>
            <h4>Cross-Platform</h4>
            <p>Fully responsive on Mobile, Tablet, Desktop, and PWA environments.</p>
        </div>
    </div>

</div>

<script>
function filterSaaSTools() {
    const input = document.getElementById('toolSearchInput').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.saas-tool-card, .featured-tool-card');

    cards.forEach(card => {
        const title = card.querySelector('.tool-card-title')?.innerText.toLowerCase() || '';
        const desc = card.querySelector('.tool-card-desc')?.innerText.toLowerCase() || '';
        const tags = card.getAttribute('data-tags') || '';

        if (input === '' || title.includes(input) || desc.includes(input) || tags.includes(input)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function filterCategory(cat) {
    const pills = document.querySelectorAll('.tool-pill');
    pills.forEach(p => p.classList.remove('active'));
    
    event.currentTarget.classList.add('active');

    const cards = document.querySelectorAll('.saas-tool-card, .featured-tool-card');
    cards.forEach(card => {
        const categories = card.getAttribute('data-category') || '';
        if (cat === 'all' || categories.includes(cat)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endsection