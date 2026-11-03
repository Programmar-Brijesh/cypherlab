@extends('layouts.app')

@section('title', 'PDF Merge Studio – Combine Multiple PDFs | CypherLabs')
@section('meta_description', 'Merge multiple PDF files into one clean document. Drag & drop, reorder pages, custom output filename, and 100% private processing.')

@section('content')

<style>
    .saas-merge-wrapper {
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

    .merge-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .merge-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.09) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .merge-container {
        max-width: 1040px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .merge-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .merge-hero-badge {
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

    .merge-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .merge-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* GLASS CARD WORKSPACE */
    .merge-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        margin-bottom: 2.5rem;
    }

    .merge-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .merge-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* UPLOAD DROPZONE */
    .merge-dropzone {
        border: 2px dashed rgba(0, 212, 255, 0.35);
        border-radius: 18px;
        padding: 3rem 1.5rem;
        text-align: center;
        background: rgba(8, 12, 24, 0.7);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .merge-dropzone:hover, .merge-dropzone.dragover {
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

    /* FILE LIST ITEMS */
    .file-item-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-left: 4px solid var(--neon-blue);
        border-radius: 14px;
        padding: 0.85rem 1.2rem;
        margin-bottom: 8px;
        transition: all 0.2s ease;
    }

    .file-item-card:hover {
        border-color: var(--neon-cyan);
    }

    .file-item-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .drag-handle {
        color: var(--text-muted);
        cursor: grab;
        font-size: 1.1rem;
    }

    .status-pill {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
    }

    .status-uploading { background: rgba(0, 212, 255, 0.12); color: var(--neon-blue); }
    .status-uploaded { background: rgba(34, 197, 94, 0.12); color: #22c55e; }
    .status-failed { background: rgba(239, 68, 68, 0.12); color: #fca5a5; }

    /* MERGED RESULT BOX */
    .merged-result-box {
        background: rgba(8, 12, 24, 0.95);
        border: 1px solid rgba(0, 212, 255, 0.3);
        border-radius: 18px;
        padding: 1.75rem;
        text-align: center;
        margin-top: 1.5rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
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
        margin: 1rem 0;
        text-align: center;
    }

    /* BUTTONS */
    .merge-btn-primary {
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

    .merge-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .merge-btn-primary:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

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

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-top: 1.5rem;
    }

    .feature-item-box {
        background: rgba(8, 12, 24, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 1.25rem;
    }

    /* TOAST */
    .merge-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .merge-toast {
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

<div class="saas-merge-wrapper">
    <div class="merge-bg-grid"></div>
    <div class="merge-ambient-glow"></div>

    <div class="merge-container">
        
        <!-- HERO HEADER -->
        <div class="merge-hero-box">
            <div class="merge-hero-badge">
                <i class="fas fa-object-group me-1"></i> PDF MERGE STUDIO
            </div>
            <h1 class="merge-hero-title">
                Combine Multiple PDFs into <span class="grad-text">One Document</span>
            </h1>
            <p class="merge-hero-sub">
                Drag & drop PDF files, reorder pages with grip handles, and merge documents in sub-seconds with zero data retention.
            </p>
        </div>

        <!-- GLASS CARD WORKSPACE -->
        <div class="merge-glass-card">
            <div class="merge-card-head">
                <h2 class="merge-card-title"><i class="fas fa-layer-group" style="color:var(--neon-blue);"></i> Document Merge Stage</h2>
            </div>

            <!-- UPLOAD DROPZONE -->
            <div id="uploadZone" class="merge-dropzone">
                <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                <div style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">Drop PDF files here or click to browse</div>
                <div style="font-size:0.82rem; color:var(--text-muted); font-family:'Fira Code';">Select multiple files (max 50MB per file)</div>
                <input type="file" id="fileInput" multiple accept=".pdf" style="display:none">
            </div>

            <!-- FILE QUEUE LIST -->
            <div id="fileListContainer" style="display:none; margin-bottom:1.5rem;">
                <div style="font-size:0.8rem; font-weight:700; color:#cbd5e1; font-family:'Fira Code'; margin-bottom:0.5rem;">
                    Merge Queue (Drag handle to reorder)
                </div>
                <div id="fileList"></div>
            </div>

            <!-- MERGE ACTION & PROGRESS -->
            <div style="text-align:center;">
                <button type="button" id="mergeBtn" class="merge-btn-primary" disabled>
                    <i class="fas fa-object-group"></i> Merge PDFs Now
                </button>
            </div>

            <div id="progressContainer" style="display:none; margin-top:1.5rem;">
                <div style="width:100%; height:6px; background:rgba(255,255,255,0.08); border-radius:10px; overflow:hidden;">
                    <div id="progressFill" style="height:100%; width:0%; background:linear-gradient(90deg, #00d4ff, #06f7e3); transition:width 0.3s ease;"></div>
                </div>
                <div id="progressText" style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; text-align:center; margin-top:6px;">Processing merge...</div>
            </div>

            <!-- MERGED PREVIEW BOX -->
            <div id="mergedPreview" class="merged-result-box" style="display:none;">
                <i class="fas fa-file-pdf" style="font-size:2.5rem; color:var(--neon-cyan); margin-bottom:0.5rem;"></i>
                <div id="previewMeta" style="font-size:0.95rem; font-weight:700; color:#ffffff;">Merged PDF Ready</div>
                <div>
                    <input type="text" id="renameInput" class="rename-input" value="merged-cypherlabs.pdf">
                </div>
                <button type="button" id="downloadMergedBtn" class="merge-btn-primary">
                    <i class="fas fa-download"></i> Download Merged PDF
                </button>
            </div>

        </div>

        <!-- CONTENT SECTION 1: HOW TO USE -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:0.5rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-map-signs" style="color:var(--neon-blue);"></i> How to Merge PDF Files in 3 Steps
            </h3>
            <p style="color:var(--text-muted); font-size:0.9rem;">Follow these simple steps to merge multiple PDF files into a single structured document:</p>

            <div class="step-grid">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Select & Upload PDFs</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Drag and drop your PDF files into the upload box or click to select files from your desktop or device.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">2</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Reorder & Organize</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Use the vertical grip handles to drag and arrange files in the exact sequence you want them to appear.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">3</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Merge & Download</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Click "Merge PDFs Now", customize your file name, and download your consolidated PDF instantly.</p>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION 2: WHY CHOOSE -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:0.5rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-rocket" style="color:var(--neon-cyan);"></i> Why Choose CypherLabs PDF Merge Studio?
            </h3>

            <div class="features-grid">
                <div class="feature-item-box">
                    <i class="fas fa-shield-alt" style="color:var(--neon-blue); font-size:1.3rem; margin-bottom:0.5rem;"></i>
                    <h4 style="font-size:0.95rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">100% Privacy Guaranteed</h4>
                    <p style="color:var(--text-muted); font-size:0.82rem; margin:0;">Your documents are processed securely and automatically deleted immediately after download.</p>
                </div>
                <div class="feature-item-box">
                    <i class="fas fa-bolt" style="color:var(--neon-cyan); font-size:1.3rem; margin-bottom:0.5rem;"></i>
                    <h4 style="font-size:0.95rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">Sub-Second Speed</h4>
                    <p style="color:var(--text-muted); font-size:0.82rem; margin:0;">High-performance PDF assembly engine handles large files without lag or memory loss.</p>
                </div>
                <div class="feature-item-box">
                    <i class="fas fa-layer-group" style="color:var(--neon-purple); font-size:1.3rem; margin-bottom:0.5rem;"></i>
                    <h4 style="font-size:0.95rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">Zero Quality Loss</h4>
                    <p style="color:var(--text-muted); font-size:0.82rem; margin:0;">Original document formatting, vector fonts, image resolutions, and tables remain perfectly intact.</p>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION 3: FAQS -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:1rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-question-circle" style="color:var(--neon-blue);"></i> PDF Merge FAQs
            </h3>

            <details style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">How many PDF files can I merge at once?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">You can upload and merge as many PDF files as you need. There is no hard file count limit per merge session.</p>
            </details>

            <details style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Will merging PDFs affect the quality of my images?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">No. CypherLabs PDF Merge preserves the exact resolution, embedded fonts, graphics, and structure of your original PDFs.</p>
            </details>

            <details style="padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Is it safe to merge confidential documents?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">Yes! All document handling is executed in encrypted runtime streams with strict zero permanent cloud logging.</p>
            </details>
        </div>

    </div>
</div>

<div class="merge-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    let uploadedFiles = [];
    let mergeInProgress = false;
    let mergedFileBlob = null;

    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileListContainer = document.getElementById('fileListContainer');
    const fileListEl = document.getElementById('fileList');
    const mergeBtn = document.getElementById('mergeBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const mergedPreview = document.getElementById('mergedPreview');
    const previewMeta = document.getElementById('previewMeta');
    const renameInput = document.getElementById('renameInput');
    const downloadMergedBtn = document.getElementById('downloadMergedBtn');

    function showToast(msg, isError = false) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'merge-toast';
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

    function renderFileList() {
        if (uploadedFiles.length === 0) {
            fileListContainer.style.display = 'none';
            mergeBtn.disabled = true;
            return;
        }

        fileListContainer.style.display = 'block';
        fileListEl.innerHTML = '';

        uploadedFiles.forEach((file, idx) => {
            let statusBadge = '';
            if (file.status === 'uploading') {
                statusBadge = '<span class="status-pill status-uploading"><i class="fas fa-spinner fa-spin me-1"></i> Uploading</span>';
            } else if (file.status === 'uploaded') {
                statusBadge = '<span class="status-pill status-uploaded"><i class="fas fa-check me-1"></i> Ready</span>';
            } else {
                statusBadge = '<span class="status-pill status-failed"><i class="fas fa-times me-1"></i> Failed</span>';
            }

            const card = document.createElement('div');
            card.className = 'file-item-card';
            card.setAttribute('data-idx', idx);
            card.setAttribute('draggable', 'true');
            card.innerHTML = `
                <div class="file-item-left">
                    <i class="fas fa-grip-vertical drag-handle"></i>
                    <i class="fas fa-file-pdf" style="color:var(--neon-blue);"></i>
                    <span style="font-weight:600; font-size:0.85rem; color:#ffffff;">${file.name}</span>
                    <span style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code';">(${formatBytes(file.size)})</span>
                    ${statusBadge}
                </div>
                <i class="fas fa-trash-alt" style="color:#fca5a5; cursor:pointer; font-size:0.9rem;" onclick="removeFile(${idx})"></i>
            `;
            fileListEl.appendChild(card);
        });

        const allUploaded = uploadedFiles.every(f => f.status === 'uploaded');
        mergeBtn.disabled = !allUploaded || uploadedFiles.length < 2;
        attachDragDropReorder();
    }

    function removeFile(idx) {
        uploadedFiles.splice(idx, 1);
        renderFileList();
        if (mergedFileBlob) {
            mergedPreview.style.display = 'none';
            mergedFileBlob = null;
        }
    }

    function attachDragDropReorder() {
        const items = document.querySelectorAll('.file-item-card');
        let dragSrc = null;
        items.forEach(item => {
            item.addEventListener('dragstart', (e) => {
                dragSrc = item;
                e.dataTransfer.effectAllowed = 'move';
            });
            item.addEventListener('dragover', (e) => e.preventDefault());
            item.addEventListener('drop', (e) => {
                e.preventDefault();
                if (!dragSrc || dragSrc === item) return;
                const fromIdx = parseInt(dragSrc.getAttribute('data-idx'));
                const toIdx = parseInt(item.getAttribute('data-idx'));
                if (isNaN(fromIdx) || isNaN(toIdx)) return;
                const moved = uploadedFiles.splice(fromIdx, 1)[0];
                uploadedFiles.splice(toIdx, 0, moved);
                renderFileList();
            });
        });
    }

    async function uploadFiles(files) {
        const formData = new FormData();
        const newFiles = [];
        for (let file of files) {
            if (file.type !== 'application/pdf') {
                showToast(`Skipping ${file.name}: not a PDF`, true);
                continue;
            }
            if (file.size > 50 * 1024 * 1024) {
                showToast(`${file.name} exceeds 50MB limit`, true);
                continue;
            }
            const tempId = 'temp_' + Date.now() + '_' + Math.random();
            newFiles.push({ id: tempId, name: file.name, size: file.size, status: 'uploading' });
            formData.append('files[]', file);
        }

        if (newFiles.length === 0) return;

        uploadedFiles.push(...newFiles);
        renderFileList();

        try {
            const res = await fetch('{{ route("pdf.merge.upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error || 'Upload failed');

            for (let i = 0; i < data.files.length; i++) {
                const serverFile = data.files[i];
                const localIdx = uploadedFiles.findIndex(f => f.name === serverFile.name && f.status === 'uploading');
                if (localIdx !== -1) {
                    uploadedFiles[localIdx] = { ...uploadedFiles[localIdx], id: serverFile.id, status: 'uploaded' };
                }
            }
            renderFileList();
            showToast(`${data.files.length} PDF file(s) ready for merge`);
        } catch (err) {
            newFiles.forEach(f => {
                const idx = uploadedFiles.findIndex(uf => uf.id === f.id);
                if (idx !== -1) uploadedFiles[idx].status = 'failed';
            });
            renderFileList();
            showToast('Upload failed: ' + err.message, true);
        }
    }

    mergeBtn.addEventListener('click', async () => {
        if (uploadedFiles.filter(f => f.status === 'uploaded').length < 2 || mergeInProgress) return;
        mergeInProgress = true;
        mergeBtn.disabled = true;
        progressContainer.style.display = 'block';
        progressFill.style.width = '30%';
        progressText.innerText = 'Initializing document merge engine...';

        const order = uploadedFiles.filter(f => f.status === 'uploaded').map(f => f.id);
        const formData = new FormData();
        formData.append('order', JSON.stringify(order));

        try {
            progressFill.style.width = '65%';
            progressText.innerText = 'Merging pages & compiling output PDF...';
            const res = await fetch('{{ route("pdf.merge.process") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if (!res.ok) throw new Error('Merge execution error');

            const blob = await res.blob();
            mergedFileBlob = blob;

            previewMeta.innerHTML = `Merged PDF Ready <span style="font-size:0.8rem; color:var(--neon-blue); font-family:'Fira Code';">(${formatBytes(blob.size)})</span>`;
            mergedPreview.style.display = 'block';

            progressFill.style.width = '100%';
            progressText.innerText = 'Merge complete!';
            showToast('PDF merge successful!');
        } catch (err) {
            showToast('Merge failed: ' + err.message, true);
        } finally {
            setTimeout(() => {
                progressContainer.style.display = 'none';
                progressFill.style.width = '0%';
                mergeInProgress = false;
                renderFileList();
            }, 800);
        }
    });

    downloadMergedBtn.addEventListener('click', () => {
        if (!mergedFileBlob) return;
        let filename = renameInput.value.trim() || 'merged-cypherlabs.pdf';
        if (!filename.endsWith('.pdf')) filename += '.pdf';
        const url = URL.createObjectURL(mergedFileBlob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast(`Downloaded as ${filename}`);
    });

    uploadZone.addEventListener('click', () => fileInput.click());
    uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        uploadFiles(Array.from(e.dataTransfer.files));
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            uploadFiles(Array.from(fileInput.files));
            fileInput.value = '';
        }
    });
</script>
@endsection