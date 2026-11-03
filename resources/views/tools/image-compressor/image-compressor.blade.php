@extends('layouts.app')

@section('title', 'Free Online Image Compressor – Reduce JPG, PNG & WebP Size | CypherLab')
@section('meta_description', 'Compress JPEG, PNG & WebP images online without losing quality. Up to 85% file size reduction. 100% free & private client-side tool by CypherLab.')

@push('schema')
{!! \App\Services\SeoService::generateSoftwareSchema('Visual Forge - Free Online Image Compressor', 'Compress JPEG, PNG & WebP images with up to 85% file size reduction directly in your browser.', route('tools.image-compressor')) !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Tools' => route('home'),
    'Image Compressor' => route('tools.image-compressor'),
]) !!}
{!! \App\Services\SeoService::generateFaqSchema([
    'Does image compression lower visual quality?' => 'Visual Forge uses smart lossy/lossless canvas compression to maximize file size savings while keeping image quality virtually indistinguishable to the human eye.',
    'Is my image uploaded to any server?' => 'No! All compression runs 100% locally in your browser using HTML5 Canvas APIs. Your photos never leave your device.',
    'What image formats are supported?' => 'JPEG, PNG, WebP, and GIF images are fully supported for compression.'
]) !!}
@endpush

@section('content')

<style>
    .saas-img-wrapper {
        --neon-purple: #a855f7;
        --neon-blue: #00d4ff;
        --neon-cyan: #06f7e3;
        --bg-dark: #070913;
        --bg-card: rgba(13, 18, 33, 0.85);
        --border-glass: rgba(168, 85, 247, 0.2);
        --border-glass-hover: rgba(168, 85, 247, 0.45);
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

    .img-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(168, 85, 247, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(168, 85, 247, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .img-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(168, 85, 247, 0.1) 0%, rgba(0, 212, 255, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .img-container {
        max-width: 1120px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .img-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .img-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 14px;
        border-radius: 30px;
        background: rgba(168, 85, 247, 0.1);
        border: 1px solid rgba(168, 85, 247, 0.35);
        color: var(--neon-purple);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-family: 'Fira Code', monospace;
        margin-bottom: 1rem;
    }

    .img-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .img-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* STUDIO WORKSPACE GRID */
    .img-studio-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 3rem;
    }

    /* GLASS CARDS */
    .img-glass-card {
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

    .img-card-head {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .img-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: radial-gradient(circle at 30% 30%, rgba(168, 85, 247, 0.25), rgba(0, 212, 255, 0.1));
        border: 1px solid rgba(168, 85, 247, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-purple);
        font-size: 1.1rem;
    }

    .img-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
    }

    /* UPLOAD DROP ZONE */
    .img-drop-zone {
        border: 2px dashed rgba(168, 85, 247, 0.35);
        border-radius: 16px;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        background: rgba(10, 14, 26, 0.6);
        transition: all 0.3s ease;
        margin-bottom: 1.25rem;
    }

    .img-drop-zone:hover, .img-drop-zone.dragover {
        border-color: var(--neon-purple);
        background: rgba(168, 85, 247, 0.08);
        box-shadow: inset 0 0 25px rgba(168, 85, 247, 0.15);
    }

    .drop-icon-circle {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: rgba(168, 85, 247, 0.15);
        color: var(--neon-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        margin: 0 auto 0.85rem;
    }

    /* INPUTS & CONTROLS */
    .img-field-group {
        margin-bottom: 1.25rem;
    }

    .img-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.78rem;
        font-weight: 600;
        color: #cbd5e1;
        margin-bottom: 0.45rem;
    }

    .img-input {
        width: 100%;
        background: rgba(8, 12, 24, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: #ffffff;
        font-size: 0.88rem;
        font-family: 'Fira Code', monospace;
        transition: all 0.25s ease;
    }

    .img-input:focus {
        outline: none;
        border-color: var(--neon-purple);
        background: rgba(8, 12, 24, 0.95);
        box-shadow: 0 0 16px rgba(168, 85, 247, 0.2);
    }

    .range-slider-custom {
        width: 100%;
        accent-color: var(--neon-purple);
        cursor: pointer;
    }

    .pill-val-badge {
        background: rgba(168, 85, 247, 0.15);
        border: 1px solid rgba(168, 85, 247, 0.3);
        color: var(--neon-purple);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
    }

    /* PREVIEW STAGE GRID */
    .side-preview-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
        flex: 1;
    }

    .preview-box-card {
        background: rgba(8, 12, 24, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 1rem;
        display: flex;
        flex-direction: column;
    }

    .preview-head-tag {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        font-family: 'Fira Code', monospace;
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }

    .preview-img-frame {
        flex: 1;
        min-height: 180px;
        max-height: 220px;
        border-radius: 10px;
        background: rgba(4, 8, 18, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px dashed rgba(255, 255, 255, 0.08);
    }

    .preview-img-frame img {
        max-width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 8px;
    }

    .savings-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        background: rgba(34, 197, 94, 0.15);
        border: 1px solid rgba(34, 197, 94, 0.35);
        color: #4ade80;
        font-size: 0.78rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
    }

    /* BUTTONS */
    .img-btn-primary {
        width: 100%;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #a855f7 0%, #00d4ff 100%);
        border: none;
        color: #ffffff;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(168, 85, 247, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .img-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(168, 85, 247, 0.5);
        color: #ffffff;
    }

    .img-btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .img-btn-glass {
        width: 100%;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #ffffff;
        font-size: 0.88rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .img-btn-glass:hover:not(:disabled) {
        background: rgba(168, 85, 247, 0.15);
        border-color: var(--neon-purple);
        color: var(--neon-purple);
    }

    .img-btn-glass:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    /* FEATURES GRID */
    .img-features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .img-feature-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .img-feature-card:hover {
        border-color: var(--neon-blue);
        transform: translateY(-4px);
    }

    .img-feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(168, 85, 247, 0.12);
        color: var(--neon-purple);
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    /* TOAST */
    .img-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .img-toast {
        background: #0f172a;
        border: 1px solid var(--neon-purple);
        border-left: 4px solid var(--neon-purple);
        color: #ffffff;
        padding: 0.85rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: 'Fira Code', monospace;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    @media (max-width: 850px) {
        .img-studio-grid { grid-template-columns: 1fr; }
        .side-preview-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-img-wrapper">
    <div class="img-bg-grid"></div>
    <div class="img-ambient-glow"></div>

    <div class="img-container">
        
        <!-- HERO HEADER -->
        <div class="img-hero-box">
            <div class="img-hero-badge">
                <i class="fas fa-compress-arrows-alt"></i> VISUAL FORGE STUDIO
            </div>
            <h1 class="img-hero-title">
                Ultra-Fast Client-Side <span class="grad-text">Image Optimizer</span>
            </h1>
            <p class="img-hero-sub">
                Reduce JPEG, PNG, and WebP file sizes up to 85% with bicubic resampling, custom KB file size targets, and instant browser processing.
            </p>
        </div>

        <!-- STUDIO WORKSPACE -->
        <div class="img-studio-grid">
            
            <!-- LEFT PANEL: CONTROLS & UPLOAD -->
            <div class="img-glass-card">
                <div class="img-card-head">
                    <div class="img-card-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                    <h2 class="img-card-title">Upload & Parameters</h2>
                </div>

                <!-- DRAG DROP DROPZONE -->
                <div class="img-drop-zone" id="uploadZone" onclick="document.getElementById('fileInput').click()">
                    <div class="drop-icon-circle"><i class="fas fa-image"></i></div>
                    <div style="font-weight: 700; color:#ffffff; margin-bottom: 0.35rem; font-size: 0.95rem;">
                        Drop image here or click to browse
                    </div>
                    <div style="color: var(--text-muted); font-size: 0.78rem;">
                        Supports PNG, JPEG, WebP (Up to 10MB)
                    </div>
                    <input type="file" id="fileInput" accept="image/jpeg,image/png,image/webp" style="display:none;">
                </div>

                <!-- QUALITY SLIDER -->
                <div class="img-field-group">
                    <div class="img-label">
                        <span>Compression Quality</span>
                        <span class="pill-val-badge" id="qualityDisplay">80%</span>
                    </div>
                    <input type="range" id="qualitySlider" class="range-slider-custom" min="10" max="100" value="80">
                </div>

                <!-- PIXEL RESIZER TOGGLE -->
                <div class="img-field-group">
                    <div class="img-label">
                        <span><i class="fas fa-expand-arrows-alt me-1"></i> Pixel Resizer (Width x Height)</span>
                        <span id="resizeToggleBtn" style="color:var(--neon-purple); cursor:pointer; font-family:'Fira Code';">Enable</span>
                    </div>
                    <div id="resizeControls" style="display:none; margin-top: 0.6rem;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.5rem;">
                            <input type="number" id="resizeWidth" class="img-input" placeholder="Width (px)" step="1" min="1">
                            <input type="number" id="resizeHeight" class="img-input" placeholder="Height (px)" step="1" min="1">
                        </div>
                        <label style="font-size:0.75rem; color:var(--text-muted); cursor:pointer;">
                            <input type="checkbox" id="keepAspectRatio" checked> Keep original aspect ratio
                        </label>
                    </div>
                </div>

                <!-- TARGET FILE SIZE TOGGLE -->
                <div class="img-field-group">
                    <div class="img-label">
                        <span><i class="fas fa-bullseye me-1"></i> Target File Size (KB)</span>
                        <span id="targetSizeToggle" style="color:var(--neon-purple); cursor:pointer; font-family:'Fira Code';">Enable</span>
                    </div>
                    <div id="targetSizeControls" style="display:none; margin-top: 0.6rem;">
                        <input type="number" id="targetSizeKb" class="img-input" placeholder="e.g. 100 KB" min="5" step="5">
                        <div style="font-size:0.72rem; color:var(--text-muted); margin-top:4px;">Quality will auto-adjust to target target weight.</div>
                    </div>
                </div>

                <!-- OUTPUT FORMAT SELECTOR -->
                <div class="img-field-group mb-4">
                    <div class="img-label"><span>Output Format</span></div>
                    <select id="formatSelect" class="img-input">
                        <option value="image/jpeg">JPEG Image (.jpg)</option>
                        <option value="image/png">PNG Image (.png)</option>
                        <option value="image/webp" selected>WebP Next-Gen (.webp)</option>
                    </select>
                </div>

                <button type="button" id="compressBtn" class="img-btn-primary" disabled>
                    <i class="fas fa-compress-alt"></i> Optimize & Compress Image
                </button>
            </div>

            <!-- RIGHT PANEL: PREVIEW & ANALYTICS STAGE -->
            <div class="img-glass-card">
                <div class="img-card-head">
                    <div class="img-card-icon"><i class="fas fa-eye"></i></div>
                    <h2 class="img-card-title">Comparison Preview</h2>
                </div>

                <div class="side-preview-grid">
                    <!-- ORIGINAL IMAGE -->
                    <div class="preview-box-card">
                        <div class="preview-head-tag">
                            <span>Original</span>
                            <span id="originalSize" style="color:var(--neon-blue);">—</span>
                        </div>
                        <div class="preview-img-frame" id="originalPreview">
                            <span style="color:var(--text-muted); font-size:0.8rem;">No file selected</span>
                        </div>
                    </div>

                    <!-- COMPRESSED IMAGE -->
                    <div class="preview-box-card">
                        <div class="preview-head-tag">
                            <span>Compressed</span>
                            <span id="compressedSize" style="color:#4ade80;">—</span>
                        </div>
                        <div class="preview-img-frame" id="compressedPreview">
                            <span style="color:var(--text-muted); font-size:0.8rem;">Awaiting optimization</span>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-3">
                    <span class="savings-badge-pill" id="savingsBadge" style="display:none;">
                        <i class="fas fa-arrow-down"></i> <span id="savingsText">0%</span> Reduced
                    </span>
                </div>

                <button type="button" id="downloadBtn" class="img-btn-glass" disabled>
                    <i class="fas fa-download"></i> Download Optimized Result
                </button>
            </div>

        </div>

        <!-- FEATURES GRID -->
        <div class="img-features-grid">
            <div class="img-feature-card">
                <div class="img-feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Zero Cloud Uploads</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Images processed 100% inside your local browser HTML5 Canvas.</p>
            </div>

            <div class="img-feature-card">
                <div class="img-feature-icon"><i class="fas fa-bolt"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Up to 85% Reduction</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Smart lossy WebP and JPEG compression algorithms.</p>
            </div>

            <div class="img-feature-card">
                <div class="img-feature-icon"><i class="fas fa-bullseye"></i></div>
                <h4 style="color:#ffffff; font-weight:700; font-size:1rem; margin-bottom:0.4rem;">Binary Target Finder</h4>
                <p style="color:var(--text-muted); font-size:0.84rem; margin:0;">Binary search algorithm automatically finds optimal quality for exact target KB.</p>
            </div>
        </div>

    </div>
</div>

<div class="img-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const qualitySlider = document.getElementById('qualitySlider');
    const qualityDisplay = document.getElementById('qualityDisplay');
    const formatSelect = document.getElementById('formatSelect');
    const compressBtn = document.getElementById('compressBtn');
    const downloadBtn = document.getElementById('downloadBtn');
    const originalPreview = document.getElementById('originalPreview');
    const compressedPreview = document.getElementById('compressedPreview');
    const originalSizeEl = document.getElementById('originalSize');
    const compressedSizeEl = document.getElementById('compressedSize');
    const savingsBadge = document.getElementById('savingsBadge');
    const savingsText = document.getElementById('savingsText');

    const resizeControls = document.getElementById('resizeControls');
    const resizeToggleBtn = document.getElementById('resizeToggleBtn');
    const resizeWidth = document.getElementById('resizeWidth');
    const resizeHeight = document.getElementById('resizeHeight');
    const keepAspectRatio = document.getElementById('keepAspectRatio');
    const targetSizeControls = document.getElementById('targetSizeControls');
    const targetSizeToggle = document.getElementById('targetSizeToggle');
    const targetSizeKb = document.getElementById('targetSizeKb');

    let originalFile = null;
    let compressedBlob = null;
    let originalImageElement = null;

    function showToast(msg) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'img-toast';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-purple);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function formatBytes(bytes) {
        if (!+bytes) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    let resizeEnabled = false;
    resizeToggleBtn.addEventListener('click', () => {
        resizeEnabled = !resizeEnabled;
        resizeControls.style.display = resizeEnabled ? 'block' : 'none';
        resizeToggleBtn.textContent = resizeEnabled ? 'Disable' : 'Enable';
    });

    let targetSizeEnabled = false;
    targetSizeToggle.addEventListener('click', () => {
        targetSizeEnabled = !targetSizeEnabled;
        targetSizeControls.style.display = targetSizeEnabled ? 'block' : 'none';
        targetSizeToggle.textContent = targetSizeEnabled ? 'Disable' : 'Enable';
        qualitySlider.disabled = targetSizeEnabled;
    });

    qualitySlider.addEventListener('input', () => {
        qualityDisplay.textContent = qualitySlider.value + '%';
    });

    function updateHeightOnWidth() {
        if (!keepAspectRatio.checked || !originalImageElement) return;
        const origW = originalImageElement.width;
        const origH = originalImageElement.height;
        const newW = parseInt(resizeWidth.value);
        if (!isNaN(newW) && newW > 0) {
            resizeHeight.value = Math.round(newW * (origH / origW));
        }
    }
    function updateWidthOnHeight() {
        if (!keepAspectRatio.checked || !originalImageElement) return;
        const origW = originalImageElement.width;
        const origH = originalImageElement.height;
        const newH = parseInt(resizeHeight.value);
        if (!isNaN(newH) && newH > 0) {
            resizeWidth.value = Math.round(newH * (origW / origH));
        }
    }
    resizeWidth.addEventListener('input', updateHeightOnWidth);
    resizeHeight.addEventListener('input', updateWidthOnHeight);

    uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
    });
    fileInput.addEventListener('change', (e) => { if (e.target.files.length) handleFile(e.target.files[0]); });

    function handleFile(file) {
        const allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!allowed.includes(file.type)) {
            return showToast('Unsupported format. Please select JPEG, PNG, or WebP.');
        }
        if (file.size > 10 * 1024 * 1024) {
            return showToast('File too large (Max 10MB limit).');
        }
        originalFile = file;
        originalSizeEl.textContent = formatBytes(file.size);
        compressBtn.disabled = false;

        const reader = new FileReader();
        reader.onload = (e) => {
            originalPreview.innerHTML = `<img src="${e.target.result}">`;
            const img = new Image();
            img.onload = () => {
                originalImageElement = img;
                if (!resizeWidth.value) resizeWidth.value = img.width;
                if (!resizeHeight.value) resizeHeight.value = img.height;
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);

        compressedPreview.innerHTML = '<span style="color:var(--text-muted); font-size:0.8rem;">Awaiting optimization</span>';
        compressedSizeEl.textContent = '—';
        savingsBadge.style.display = 'none';
        downloadBtn.disabled = true;
        compressedBlob = null;
    }

    async function compressImage(quality, outputFormat, width = null, height = null) {
        return new Promise((resolve, reject) => {
            const img = new Image();
            const reader = new FileReader();
            reader.onload = (e) => { img.src = e.target.result; };
            reader.readAsDataURL(originalFile);
            img.onload = () => {
                let canvas = document.createElement('canvas');
                let ctx = canvas.getContext('2d');
                let outWidth = width || img.width;
                let outHeight = height || img.height;
                canvas.width = outWidth;
                canvas.height = outHeight;
                ctx.drawImage(img, 0, 0, outWidth, outHeight);
                canvas.toBlob((blob) => {
                    if (!blob) reject('Compression error');
                    else resolve(blob);
                }, outputFormat, quality);
            };
            img.onerror = () => reject('Image load failed');
        });
    }

    async function compressToTarget(targetKb, outputFormat, width, height) {
        let low = 0.1, high = 1.0, bestBlob = null, bestQuality = 0.8;
        for (let i = 0; i < 12; i++) {
            const mid = (low + high) / 2;
            const blob = await compressImage(mid, outputFormat, width, height);
            const sizeKb = blob.size / 1024;
            if (sizeKb <= targetKb) {
                bestBlob = blob;
                bestQuality = mid;
                low = mid;
            } else {
                high = mid;
            }
            if (Math.abs(sizeKb - targetKb) < 5) break;
        }
        if (!bestBlob) bestBlob = await compressImage(bestQuality, outputFormat, width, height);
        return bestBlob;
    }

    compressBtn.addEventListener('click', async () => {
        if (!originalFile) return;
        compressBtn.disabled = true;
        compressBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Optimizing...';

        try {
            let outputFormat = formatSelect.value;
            let quality = qualitySlider.value / 100;
            let width = null, height = null;

            if (resizeEnabled) {
                width = parseInt(resizeWidth.value);
                height = parseInt(resizeHeight.value);
                if (isNaN(width) || width <= 0) width = null;
                if (isNaN(height) || height <= 0) height = null;
            }

            let resultBlob;
            if (targetSizeEnabled) {
                let targetKb = parseFloat(targetSizeKb.value);
                if (isNaN(targetKb) || targetKb < 5) {
                    showToast('Please enter a valid target size (min 5 KB).');
                    compressBtn.disabled = false;
                    compressBtn.innerHTML = '<i class="fas fa-compress-alt"></i> Optimize & Compress Image';
                    return;
                }
                resultBlob = await compressToTarget(targetKb, outputFormat, width, height);
            } else {
                resultBlob = await compressImage(quality, outputFormat, width, height);
            }

            compressedBlob = resultBlob;
            compressedSizeEl.textContent = formatBytes(resultBlob.size);
            const savings = ((originalFile.size - resultBlob.size) / originalFile.size * 100).toFixed(1);
            savingsText.textContent = savings + '%';
            savingsBadge.style.display = 'inline-flex';

            const url = URL.createObjectURL(resultBlob);
            compressedPreview.innerHTML = `<img src="${url}">`;
            downloadBtn.disabled = false;
            showToast(`Optimization complete! ${savings}% smaller.`);
        } catch (err) {
            showToast('Compression failed.');
        } finally {
            compressBtn.disabled = false;
            compressBtn.innerHTML = '<i class="fas fa-compress-alt"></i> Optimize & Compress Image';
        }
    });

    downloadBtn.addEventListener('click', () => {
        if (!compressedBlob) return;
        const a = document.createElement('a');
        const url = URL.createObjectURL(compressedBlob);
        a.href = url;
        const ext = formatSelect.value.split('/')[1];
        a.download = `cypherlabs-optimized.${ext}`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
</script>
@endsection