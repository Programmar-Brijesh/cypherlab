@extends('layouts.app')

@section('title', 'Image to PDF Studio – Convert JPG, PNG, WebP to PDF | CypherLabs')
@section('meta_description', 'Convert JPG, PNG, and WebP images to PDF. Fully customizable page sizes, orientation, margins, rotation, and drag & drop reordering.')

@section('content')

<style>
    .saas-img-wrapper {
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

    /* BACKGROUND ANIMATIONS */
    .img-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
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
        background: radial-gradient(circle, rgba(0, 212, 255, 0.09) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
        animation: pulseGlow 8s ease-in-out infinite alternate;
    }

    @keyframes pulseGlow {
        0% { transform: translateX(-50%) scale(1); opacity: 0.7; }
        100% { transform: translateX(-50%) scale(1.15); opacity: 1; }
    }

    .floating-particle {
        position: absolute;
        border-radius: 50%;
        background: rgba(0, 212, 255, 0.25);
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.5);
        pointer-events: none;
        animation: floatParticle 12s linear infinite;
    }

    @keyframes floatParticle {
        0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
        20% { opacity: 0.8; }
        80% { opacity: 0.8; }
        100% { transform: translateY(-10vh) rotate(360deg); opacity: 0; }
    }

    .img-container {
        max-width: 1080px;
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

    /* GLASS CARD WORKSPACE */
    .img-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        margin-bottom: 2.5rem;
    }

    .img-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .img-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* UPLOAD DROPZONE */
    .img-dropzone {
        border: 2px dashed rgba(0, 212, 255, 0.35);
        border-radius: 18px;
        padding: 3rem 1.5rem;
        text-align: center;
        background: rgba(8, 12, 24, 0.7);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .img-dropzone:hover, .img-dropzone.dragover {
        border-color: var(--neon-blue);
        background: rgba(0, 212, 255, 0.06);
        box-shadow: 0 0 25px rgba(0, 212, 255, 0.15);
    }

    .dropzone-icon {
        font-size: 3rem;
        color: var(--neon-blue);
        margin-bottom: 1rem;
        filter: drop-shadow(0 0 10px var(--neon-blue));
    }

    /* CUSTOMIZATION SETTINGS BAR */
    .settings-bar {
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        align-items: center;
    }

    .setting-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .setting-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
        font-family: 'Fira Code', monospace;
        text-transform: uppercase;
    }

    .setting-select {
        background: rgba(13, 18, 33, 0.95);
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 10px;
        padding: 0.5rem 0.85rem;
        color: var(--neon-cyan);
        font-family: 'Fira Code', monospace;
        font-size: 0.85rem;
    }

    /* EDITABLE IMAGE GRID */
    .editable-image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 1.2rem;
        margin-bottom: 1.5rem;
    }

    .editable-card {
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.25s ease;
        position: relative;
    }

    .editable-card:hover {
        border-color: var(--neon-blue);
        transform: translateY(-2px);
    }

    .editable-card img {
        width: 100%;
        height: 130px;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .editable-card-info {
        padding: 0.5rem;
        font-size: 0.72rem;
        color: var(--text-muted);
        text-align: center;
        font-family: 'Fira Code', monospace;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .editable-card-actions {
        display: flex;
        justify-content: center;
        gap: 8px;
        padding: 0.5rem;
        background: rgba(0, 0, 0, 0.3);
    }

    .action-btn-mini {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(0, 212, 255, 0.1);
        border: 1px solid rgba(0, 212, 255, 0.3);
        color: var(--neon-blue);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .action-btn-mini:hover {
        background: rgba(0, 212, 255, 0.25);
        color: #ffffff;
    }

    .action-btn-danger {
        border-color: rgba(239, 68, 68, 0.4);
        color: #fca5a5;
    }

    .action-btn-danger:hover {
        background: rgba(239, 68, 68, 0.25);
        color: #ffffff;
    }

    .rename-input {
        background: rgba(13, 18, 33, 0.9);
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 10px;
        padding: 0.6rem 1rem;
        color: #ffffff;
        font-family: 'Fira Code', monospace;
        font-size: 0.9rem;
        width: 280px;
        max-width: 100%;
        margin-bottom: 1rem;
        text-align: center;
    }

    /* BUTTONS */
    .img-btn-primary {
        padding: 0.85rem 2rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #00d4ff 0%, #06f7e3 100%);
        border: none;
        color: #070913;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .img-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .img-btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }

    /* CONTENT SECTIONS */
    .content-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .step-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .step-box {
        background: rgba(8, 12, 24, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(0, 212, 255, 0.15);
        border: 1px solid var(--neon-blue);
        color: var(--neon-blue);
        font-weight: 800;
        font-family: 'Fira Code', monospace;
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
        border: 1px solid var(--neon-blue);
        border-left: 4px solid var(--neon-blue);
        color: #ffffff;
        padding: 0.85rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-family: 'Fira Code', monospace;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
</style>

<div class="saas-img-wrapper">
    <div class="img-bg-grid"></div>
    <div class="img-ambient-glow"></div>

    <!-- FLOATING ANIMATED PARTICLES -->
    <div class="floating-particle" style="width:6px; height:6px; left:15%; animation-delay:0s;"></div>
    <div class="floating-particle" style="width:10px; height:10px; left:35%; animation-delay:3s;"></div>
    <div class="floating-particle" style="width:8px; height:8px; left:65%; animation-delay:6s;"></div>
    <div class="floating-particle" style="width:12px; height:12px; left:85%; animation-delay:1.5s;"></div>

    <div class="img-container">
        
        <!-- HERO HEADER -->
        <div class="img-hero-box">
            <div class="img-hero-badge">
                <i class="fas fa-images me-1"></i> IMAGE TO PDF STUDIO
            </div>
            <h1 class="img-hero-title">
                Convert Images to <span class="grad-text">PDF Studio</span>
            </h1>
            <p class="img-hero-sub">
                Convert JPG, PNG, and WebP images to structured PDF documents with custom page sizes, orientations, margins, rotation, and drag-and-drop reordering.
            </p>
        </div>

        <!-- GLASS CARD WORKSPACE -->
        <div class="img-glass-card">
            <div class="img-card-head">
                <h2 class="img-card-title"><i class="fas fa-photo-video" style="color:var(--neon-blue);"></i> Image Editing & Customization Stage</h2>
            </div>

            <!-- UPLOAD DROPZONE -->
            <div id="uploadZone" class="img-dropzone">
                <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                <div style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">Drop images here or click to browse</div>
                <div style="font-size:0.82rem; color:var(--text-muted); font-family:'Fira Code';">Supports JPG, PNG, WebP (Max 25 images, 20MB each)</div>
                <input type="file" id="fileInput" accept=".jpg,.jpeg,.png,.webp" multiple style="display:none">
            </div>

            <!-- CUSTOMIZATION SETTINGS BAR -->
            <div id="settings" class="settings-bar" style="display:none;">
                <div class="setting-group">
                    <span class="setting-label">Page Size</span>
                    <select id="pageSize" class="setting-select">
                        <option value="A4">A4 Standard</option>
                        <option value="LETTER">US Letter</option>
                        <option value="LEGAL">Legal</option>
                        <option value="FIT">Fit to Image</option>
                    </select>
                </div>

                <div class="setting-group">
                    <span class="setting-label">Orientation</span>
                    <select id="orientation" class="setting-select">
                        <option value="P">Portrait</option>
                        <option value="L">Landscape</option>
                        <option value="auto">Auto (per image)</option>
                    </select>
                </div>

                <div class="setting-group">
                    <span class="setting-label">Page Margins</span>
                    <select id="pageMargin" class="setting-select">
                        <option value="none">No Margin (Full Bleed)</option>
                        <option value="small">Small Margin (10px)</option>
                        <option value="medium">Medium Margin (20px)</option>
                    </select>
                </div>
            </div>

            <!-- EDITABLE IMAGE GRID -->
            <div id="imageGrid" class="editable-image-grid" style="display:none;"></div>

            <!-- ACTION BUTTON -->
            <div id="actionContainer" style="display:none; text-align:center;">
                <button type="button" id="convertBtn" class="img-btn-primary">
                    <i class="fas fa-file-pdf"></i> Generate PDF Document
                </button>
            </div>

            <!-- PROGRESS BAR -->
            <div id="progressContainer" style="display:none; margin-top:1.5rem;">
                <div style="width:100%; height:6px; background:rgba(255,255,255,0.08); border-radius:10px; overflow:hidden;">
                    <div id="progressFill" style="height:100%; width:0%; background:linear-gradient(90deg, #00d4ff, #06f7e3); transition:width 0.3s ease;"></div>
                </div>
                <div id="progressText" style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; text-align:center; margin-top:6px;">Building PDF pages...</div>
            </div>

            <!-- RESULT PANEL -->
            <div id="resultPanel" style="display:none; margin-top:1.5rem;">
                <div style="background:rgba(8,12,24,0.95); border:1px solid rgba(0,212,255,0.3); border-radius:18px; padding:1.5rem; text-align:center;">
                    <h4 style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:1rem;"><i class="fas fa-check-circle me-2" style="color:#22c55e;"></i> PDF Document Ready</h4>
                    
                    <div style="display:flex; justify-content:center; gap:1.5rem; flex-wrap:wrap; margin-bottom:1.25rem;">
                        <div style="background:rgba(8,12,24,0.9); border:1px solid rgba(255,255,255,0.08); border-radius:12px; padding:0.85rem 1.25rem;">
                            <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code';">Images Processed</div>
                            <div id="imageCountDisplay" style="font-weight:700; color:#ffffff; font-family:'Fira Code';"></div>
                        </div>
                        <div style="background:rgba(8,12,24,0.9); border:1px solid rgba(255,255,255,0.08); border-radius:12px; padding:0.85rem 1.25rem;">
                            <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code';">PDF File Size</div>
                            <div id="pdfSizeDisplay" style="font-weight:700; color:var(--neon-cyan); font-family:'Fira Code';"></div>
                        </div>
                    </div>

                    <div style="margin-top:1rem;">
                        <input type="text" id="renameInput" class="rename-input" value="images-compiled.pdf">
                    </div>
                    <button type="button" id="downloadBtn" class="img-btn-primary">
                        <i class="fas fa-download"></i> Download PDF Document
                    </button>
                </div>
            </div>

        </div>

        <!-- CONTENT SECTION 1: HOW TO USE -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:0.5rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-map-signs" style="color:var(--neon-blue);"></i> How to Convert Images to PDF in 3 Steps
            </h3>
            <p style="color:var(--text-muted); font-size:0.9rem;">Follow these simple steps to compile your photos into a clean PDF document:</p>

            <div class="step-grid">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Select & Upload Images</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Drag and drop JPG, PNG, or WebP images into the stage or click to browse from your device.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">2</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Customize & Reorder</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Adjust page size, orientation, and margins. Drag image cards to rearrange page sequence or rotate 90°.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">3</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Generate & Download</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Click "Generate PDF Document", customize your filename, and download your consolidated PDF instantly.</p>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION 2: FAQS -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:1rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-question-circle" style="color:var(--neon-blue);"></i> Image to PDF Conversion FAQs
            </h3>

            <details style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Can I mix portrait and landscape images in the same PDF?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">Yes! Select "Auto (per image)" under Orientation to automatically adapt each page layout to image dimensions.</p>
            </details>

            <details style="padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Does converting compress or degrade image quality?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">No. Original pixel dimensions and resolutions are preserved inside the PDF container without lossy compression.</p>
            </details>
        </div>

    </div>
</div>

<div class="img-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    let imageFiles = [];
    let convertInProgress = false;
    let sessionId = null;
    let dragIndex = null;

    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const settings = document.getElementById('settings');
    const imageGrid = document.getElementById('imageGrid');
    const actionContainer = document.getElementById('actionContainer');
    const convertBtn = document.getElementById('convertBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const resultPanel = document.getElementById('resultPanel');
    const imageCountDisplay = document.getElementById('imageCountDisplay');
    const pdfSizeDisplay = document.getElementById('pdfSizeDisplay');
    const renameInput = document.getElementById('renameInput');
    const downloadBtn = document.getElementById('downloadBtn');

    function showToast(msg, isError = false) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'img-toast';
        if (isError) toast.style.borderLeftColor = '#ff2e9e';
        toast.innerHTML = `<i class="fas fa-check-circle me-1" style="color:var(--neon-blue);"></i> ${msg}`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 2500);
    }

    function formatBytes(bytes) {
        if (!bytes) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function addImages(files) {
        let added = 0;
        for (const file of files) {
            if (imageFiles.length >= 25) {
                showToast('Max 25 images limit reached', true);
                break;
            }
            if (file.size > 20 * 1024 * 1024) {
                showToast(`${file.name} exceeds 20MB limit`, true);
                continue;
            }
            if (!file.type.match(/^image\/(jpeg|png|webp)$/)) {
                showToast(`${file.name} unsupported format`, true);
                continue;
            }

            imageFiles.push({
                id: Date.now() + '_' + Math.random().toString(36).substr(2, 6),
                file: file,
                name: file.name,
                size: file.size,
                rotation: 0
            });
            added++;
        }

        if (added > 0) {
            renderImageGrid();
            showToast(`Added ${added} image(s)`);
        }
    }

    function renderImageGrid() {
        if (imageFiles.length === 0) {
            imageGrid.style.display = 'none';
            settings.style.display = 'none';
            actionContainer.style.display = 'none';
            return;
        }

        imageGrid.innerHTML = '';
        imageFiles.forEach((img, idx) => {
            const card = document.createElement('div');
            card.className = 'editable-card';
            card.draggable = true;

            card.addEventListener('dragstart', (e) => {
                dragIndex = idx;
                e.dataTransfer.effectAllowed = 'move';
            });

            card.addEventListener('dragover', (e) => e.preventDefault());
            card.addEventListener('drop', (e) => {
                e.preventDefault();
                if (dragIndex === null || dragIndex === idx) return;
                const moved = imageFiles.splice(dragIndex, 1)[0];
                imageFiles.splice(idx, 0, moved);
                renderImageGrid();
            });

            const imgEl = document.createElement('img');
            imgEl.src = URL.createObjectURL(img.file);
            imgEl.style.transform = `rotate(${img.rotation}deg)`;

            const info = document.createElement('div');
            info.className = 'editable-card-info';
            info.innerText = `${img.name} (${formatBytes(img.size)})`;

            const actions = document.createElement('div');
            actions.className = 'editable-card-actions';

            const rotateBtn = document.createElement('button');
            rotateBtn.className = 'action-btn-mini';
            rotateBtn.innerHTML = '<i class="fas fa-redo-alt"></i>';
            rotateBtn.title = 'Rotate 90°';
            rotateBtn.onclick = () => {
                img.rotation = (img.rotation + 90) % 360;
                renderImageGrid();
            };

            const removeBtn = document.createElement('button');
            removeBtn.className = 'action-btn-mini action-btn-danger';
            removeBtn.innerHTML = '<i class="fas fa-trash-alt"></i>';
            removeBtn.title = 'Remove Image';
            removeBtn.onclick = () => {
                imageFiles.splice(idx, 1);
                URL.revokeObjectURL(imgEl.src);
                renderImageGrid();
            };

            actions.appendChild(rotateBtn);
            actions.appendChild(removeBtn);

            card.appendChild(imgEl);
            card.appendChild(info);
            card.appendChild(actions);
            imageGrid.appendChild(card);
        });

        imageGrid.style.display = 'grid';
        settings.style.display = 'flex';
        actionContainer.style.display = 'block';
    }

    async function uploadAllImages() {
        const uploadedIds = [];
        for (const img of imageFiles) {
            const formData = new FormData();
            formData.append('image', img.file);
            formData.append('rotation', img.rotation);

            try {
                const res = await fetch('{{ route("image.topdf.upload") }}', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const data = await res.json();
                if (!data.success) throw new Error(data.error);
                uploadedIds.push({ id: data.id, rotation: img.rotation });
            } catch (err) {
                showToast('Upload failed: ' + err.message, true);
                return null;
            }
        }
        return uploadedIds;
    }

    convertBtn.addEventListener('click', async () => {
        if (imageFiles.length === 0 || convertInProgress) return;
        convertInProgress = true;
        convertBtn.disabled = true;
        progressContainer.style.display = 'block';
        progressFill.style.width = '30%';
        progressText.innerText = 'Uploading image assets...';

        const uploadedIds = await uploadAllImages();
        if (!uploadedIds) {
            convertInProgress = false;
            convertBtn.disabled = false;
            progressContainer.style.display = 'none';
            return;
        }

        const payload = {
            images: uploadedIds,
            pageSize: document.getElementById('pageSize').value,
            orientation: document.getElementById('orientation').value
        };

        try {
            progressFill.style.width = '70%';
            progressText.innerText = 'Compiling PDF document...';
            const res = await fetch('{{ route("image.topdf.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            sessionId = data.sessionId;
            imageCountDisplay.innerText = data.imageCount + ' images';
            pdfSizeDisplay.innerText = formatBytes(data.pdfSize);
            resultPanel.style.display = 'block';
            progressFill.style.width = '100%';
            progressText.innerText = 'PDF creation complete!';
            showToast('PDF document generated successfully!');
        } catch (err) {
            showToast('Conversion failed: ' + err.message, true);
        } finally {
            setTimeout(() => {
                progressContainer.style.display = 'none';
                progressFill.style.width = '0%';
                convertInProgress = false;
                convertBtn.disabled = false;
            }, 800);
        }
    });

    downloadBtn.addEventListener('click', async () => {
        if (!sessionId) return;
        let newName = renameInput.value.trim() || 'images-compiled.pdf';
        if (!newName.endsWith('.pdf')) newName += '.pdf';

        try {
            const formData = new FormData();
            formData.append('sessionId', sessionId);
            formData.append('rename', newName);
            const res = await fetch('{{ route("image.topdf.download") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (!res.ok) throw new Error('Download failed');
            const blob = await res.blob();
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = newName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast(`Downloaded ${newName}`);
        } catch (err) {
            showToast('Download failed: ' + err.message, true);
        }
    });

    uploadZone.addEventListener('click', () => fileInput.click());
    uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) addImages(Array.from(e.dataTransfer.files));
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            addImages(Array.from(fileInput.files));
            fileInput.value = '';
        }
    });
</script>
@endsection