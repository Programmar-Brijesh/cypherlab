@extends('layouts.app')

@section('title', 'PDF Split Studio – Extract Pages & Split PDFs | CypherLabs')
@section('meta_description', 'Split PDF documents by page range, extract specific pages, interval splitting, or single page extraction. Fast, secure & 100% private.')

@section('content')

<style>
    .saas-split-wrapper {
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
    .split-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .split-ambient-glow {
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

    .split-container {
        max-width: 1040px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .split-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .split-hero-badge {
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

    .split-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .split-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* GLASS CARD WORKSPACE */
    .split-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        margin-bottom: 2.5rem;
    }

    .split-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .split-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* UPLOAD DROPZONE */
    .split-dropzone {
        border: 2px dashed rgba(0, 212, 255, 0.35);
        border-radius: 18px;
        padding: 3rem 1.5rem;
        text-align: center;
        background: rgba(8, 12, 24, 0.7);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .split-dropzone:hover, .split-dropzone.dragover {
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

    /* METHOD SELECTOR TABS */
    .method-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 1.5rem;
    }

    .method-tab-btn {
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
    }

    .method-tab-btn.active {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    .split-input {
        width: 100%;
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 0.8rem 1rem;
        color: var(--neon-cyan);
        font-family: 'Fira Code', monospace;
        font-size: 0.88rem;
    }

    .split-input:focus {
        outline: none;
        border-color: var(--neon-blue);
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
    }

    /* BUTTONS */
    .split-btn-primary {
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

    .split-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .split-btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }

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
    .split-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .split-toast {
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

<div class="saas-split-wrapper">
    <div class="split-bg-grid"></div>
    <div class="split-ambient-glow"></div>

    <!-- FLOATING ANIMATED PARTICLES -->
    <div class="floating-particle" style="width:6px; height:6px; left:15%; animation-delay:0s;"></div>
    <div class="floating-particle" style="width:10px; height:10px; left:35%; animation-delay:3s;"></div>
    <div class="floating-particle" style="width:8px; height:8px; left:65%; animation-delay:6s;"></div>
    <div class="floating-particle" style="width:12px; height:12px; left:85%; animation-delay:1.5s;"></div>

    <div class="split-container">
        
        <!-- HERO HEADER -->
        <div class="split-hero-box">
            <div class="split-hero-badge">
                <i class="fas fa-cut me-1"></i> PDF SPLIT STUDIO
            </div>
            <h1 class="split-hero-title">
                Extract & Split PDF <span class="grad-text">Documents</span>
            </h1>
            <p class="split-hero-sub">
                Split PDF pages by custom range, extract specific pages, split at intervals, or export individual single-page PDFs instantly.
            </p>
        </div>

        <!-- GLASS CARD WORKSPACE -->
        <div class="split-glass-card">
            <div class="split-card-head">
                <h2 class="split-card-title"><i class="fas fa-sitemap" style="color:var(--neon-blue);"></i> PDF Extraction Stage</h2>
            </div>

            <!-- UPLOAD DROPZONE -->
            <div id="uploadZone" class="split-dropzone">
                <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                <div style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">Drop single PDF file here or click to browse</div>
                <div style="font-size:0.82rem; color:var(--text-muted); font-family:'Fira Code';">Supports up to 50MB file size</div>
                <input type="file" id="fileInput" accept=".pdf" style="display:none">
            </div>

            <!-- FILE INFO CARD -->
            <div id="fileInfo" style="display:none; margin-bottom:1.5rem;">
                <div style="background:rgba(8,12,24,0.9); border:1px solid rgba(0,212,255,0.3); border-radius:14px; padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                    <div><i class="fas fa-file-pdf me-2" style="color:var(--neon-blue);"></i> <span id="fileName" style="font-weight:700; color:#ffffff;"></span></div>
                    <div style="font-family:'Fira Code'; font-size:0.85rem; color:var(--neon-cyan);"><i class="fas fa-file-alt me-1"></i> <span id="filePages"></span> pages</div>
                    <div style="font-family:'Fira Code'; font-size:0.85rem; color:var(--text-muted);"><i class="fas fa-hdd me-1"></i> <span id="fileSize"></span></div>
                </div>
            </div>

            <!-- METHOD TABS -->
            <div id="methodSelector" class="method-tabs" style="display:none;">
                <button type="button" class="method-tab-btn active" data-method="range">📄 Page Range</button>
                <button type="button" class="method-tab-btn" data-method="extract">🔢 Extract Pages</button>
                <button type="button" class="method-tab-btn" data-method="interval">📑 Interval Split</button>
                <button type="button" class="method-tab-btn" data-method="every">📄 1 Page per PDF</button>
            </div>

            <!-- PARAM INPUTS -->
            <div id="paramRange" class="param-group" style="display:none; margin-bottom:1.5rem;">
                <label style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; margin-bottom:6px; display:block;">Page ranges (one per line, e.g. 1-5, 6-10)</label>
                <textarea id="rangeInput" class="split-input" rows="2" placeholder="1-5&#10;6-10"></textarea>
            </div>

            <div id="paramExtract" class="param-group" style="display:none; margin-bottom:1.5rem;">
                <label style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; margin-bottom:6px; display:block;">Comma-separated page numbers (e.g. 1, 3, 5)</label>
                <input type="text" id="extractInput" class="split-input" placeholder="1, 3, 5, 7">
            </div>

            <div id="paramInterval" class="param-group" style="display:none; margin-bottom:1.5rem;">
                <label style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; margin-bottom:6px; display:block;">Split every N pages</label>
                <input type="number" id="intervalInput" class="split-input" min="1" value="2">
            </div>

            <!-- ACTION BUTTON -->
            <div id="splitBtnContainer" style="display:none; text-align:center;">
                <button type="button" id="splitBtn" class="split-btn-primary">
                    <i class="fas fa-cut"></i> Split PDF Now
                </button>
            </div>

            <!-- PROGRESS BAR -->
            <div id="progressContainer" style="display:none; margin-top:1.5rem;">
                <div style="width:100%; height:6px; background:rgba(255,255,255,0.08); border-radius:10px; overflow:hidden;">
                    <div id="progressFill" style="height:100%; width:0%; background:linear-gradient(90deg, #00d4ff, #06f7e3); transition:width 0.3s ease;"></div>
                </div>
                <div id="progressText" style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; text-align:center; margin-top:6px;">Splitting pages...</div>
            </div>

            <!-- SPLIT RESULT FILE LIST -->
            <div id="splitResult" style="display:none; margin-top:1.5rem;">
                <div style="background:rgba(8,12,24,0.95); border:1px solid rgba(0,212,255,0.3); border-radius:18px; padding:1.5rem;">
                    <h4 style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:1rem;"><i class="fas fa-list me-2" style="color:var(--neon-blue);"></i> Generated PDF Files</h4>
                    <div id="filesList"></div>
                    <div style="text-align:center; margin-top:1.5rem;">
                        <button type="button" id="downloadAllZipBtn" class="split-btn-primary">
                            <i class="fas fa-file-archive me-1"></i> Download All as ZIP
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- CONTENT SECTION 1: HOW TO USE -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:0.5rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-map-signs" style="color:var(--neon-blue);"></i> How to Split PDF Documents in 3 Steps
            </h3>
            <p style="color:var(--text-muted); font-size:0.9rem;">Follow these simple steps to extract and split pages from your PDF file:</p>

            <div class="step-grid">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Upload Target PDF</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Drag and drop your PDF document into the upload area or click to select from your device.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">2</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Choose Split Mode</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Select Page Range (1-5), Extract Specific Pages (1,3,5), Interval Split (every 2 pages), or 1 Page Per PDF.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">3</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Export & Download ZIP</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Download individual split files or export all extracted PDFs in one compressed ZIP package.</p>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION 2: FAQS -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:1rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-question-circle" style="color:var(--neon-blue);"></i> PDF Split FAQs
            </h3>

            <details style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Can I split password-protected PDFs?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">PDF documents must be unlocked before splitting so that pages can be read and separated securely.</p>
            </details>

            <details style="padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">How does ZIP export work?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">Clicking "Download All as ZIP" packages all extracted PDF files into a single ZIP archive for fast 1-click downloading.</p>
            </details>
        </div>

    </div>
</div>

<div class="split-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    let uploadedFile = null;
    let splitSessionId = null;
    let splitFiles = [];
    let splitInProgress = false;
    let currentMethod = 'range';

    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileInfoDiv = document.getElementById('fileInfo');
    const fileNameSpan = document.getElementById('fileName');
    const filePagesSpan = document.getElementById('filePages');
    const fileSizeSpan = document.getElementById('fileSize');
    const methodSelector = document.getElementById('methodSelector');
    const paramRange = document.getElementById('paramRange');
    const paramExtract = document.getElementById('paramExtract');
    const paramInterval = document.getElementById('paramInterval');
    const splitBtnContainer = document.getElementById('splitBtnContainer');
    const splitBtn = document.getElementById('splitBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const splitResultDiv = document.getElementById('splitResult');
    const filesListDiv = document.getElementById('filesList');
    const downloadAllZipBtn = document.getElementById('downloadAllZipBtn');

    function showToast(msg, isError = false) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'split-toast';
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

    function showMethodUI() {
        paramRange.style.display = 'none';
        paramExtract.style.display = 'none';
        paramInterval.style.display = 'none';
        if (currentMethod === 'range') paramRange.style.display = 'block';
        else if (currentMethod === 'extract') paramExtract.style.display = 'block';
        else if (currentMethod === 'interval') paramInterval.style.display = 'block';
        splitBtn.disabled = false;
    }

    document.querySelectorAll('.method-tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.method-tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentMethod = btn.getAttribute('data-method');
            showMethodUI();
        });
    });

    async function uploadFile(file) {
        if (file.type !== 'application/pdf') {
            showToast('Please select a PDF file', true);
            return;
        }
        if (file.size > 50 * 1024 * 1024) {
            showToast('File exceeds 50MB limit', true);
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('{{ route("pdf.split.upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            uploadedFile = { id: data.id, name: data.name, size: data.size, pages: data.pages };
            fileNameSpan.innerText = data.name;
            filePagesSpan.innerText = data.pages;
            fileSizeSpan.innerText = formatBytes(data.size);
            fileInfoDiv.style.display = 'block';
            methodSelector.style.display = 'flex';
            splitBtnContainer.style.display = 'block';
            showMethodUI();

            splitResultDiv.style.display = 'none';
            filesListDiv.innerHTML = '';
            showToast('PDF file uploaded successfully!');
        } catch (err) {
            showToast('Upload failed: ' + err.message, true);
        }
    }

    splitBtn.addEventListener('click', async () => {
        if (!uploadedFile || splitInProgress) return;
        splitInProgress = true;
        splitBtn.disabled = true;
        progressContainer.style.display = 'block';
        progressFill.style.width = '30%';
        progressText.innerText = 'Initializing split engine...';

        let params = {};
        if (currentMethod === 'range') {
            const lines = document.getElementById('rangeInput').value.split(/\r?\n/).filter(l => l.trim());
            const ranges = lines.filter(l => l.match(/^\d+-\d+$/));
            if (!ranges.length) {
                showToast('Enter valid ranges (e.g. 1-5)', true);
                splitInProgress = false; splitBtn.disabled = false; progressContainer.style.display = 'none';
                return;
            }
            params = { ranges };
        } else if (currentMethod === 'extract') {
            const pages = document.getElementById('extractInput').value.split(/[ ,]+/).map(p => parseInt(p)).filter(p => !isNaN(p) && p > 0);
            if (!pages.length) {
                showToast('Enter valid page numbers', true);
                splitInProgress = false; splitBtn.disabled = false; progressContainer.style.display = 'none';
                return;
            }
            params = { pages };
        } else if (currentMethod === 'interval') {
            const interval = parseInt(document.getElementById('intervalInput').value);
            if (isNaN(interval) || interval < 1) {
                showToast('Enter a valid interval', true);
                splitInProgress = false; splitBtn.disabled = false; progressContainer.style.display = 'none';
                return;
            }
            params = { interval };
        }

        const formData = new FormData();
        formData.append('id', uploadedFile.id);
        formData.append('method', currentMethod);
        formData.append('params', JSON.stringify(params));

        try {
            progressFill.style.width = '70%';
            progressText.innerText = 'Extracting pages...';
            const res = await fetch('{{ route("pdf.split.process") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            splitSessionId = data.sessionId;
            splitFiles = data.files;

            filesListDiv.innerHTML = '';
            splitFiles.forEach((file) => {
                const row = document.createElement('div');
                row.style.cssText = "display:flex; justify-content:space-between; align-items:center; padding:0.65rem 0.85rem; border-radius:10px; background:rgba(8,12,24,0.7); border:1px solid rgba(255,255,255,0.06); margin-bottom:6px;";
                row.innerHTML = `
                    <div style="font-weight:600; font-size:0.85rem; color:#ffffff;"><i class="fas fa-file-pdf me-2" style="color:var(--neon-blue);"></i>${file.name}</div>
                    <div style="font-family:'Fira Code'; font-size:0.8rem; color:var(--text-muted);">${formatBytes(file.size)}</div>
                `;
                filesListDiv.appendChild(row);
            });

            splitResultDiv.style.display = 'block';
            progressFill.style.width = '100%';
            progressText.innerText = 'Split complete!';
            showToast(`Split successful! ${splitFiles.length} files generated.`);
        } catch (err) {
            showToast('Split failed: ' + err.message, true);
        } finally {
            setTimeout(() => {
                progressContainer.style.display = 'none';
                progressFill.style.width = '0%';
                splitInProgress = false;
                splitBtn.disabled = false;
            }, 800);
        }
    });

    downloadAllZipBtn.addEventListener('click', async () => {
        if (!splitSessionId) return;
        try {
            const formData = new FormData();
            formData.append('sessionId', splitSessionId);
            const res = await fetch('{{ route("pdf.split.download.zip") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (!res.ok) throw new Error('ZIP failed');
            const blob = await res.blob();
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'split_files.zip';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast('Downloaded split_files.zip');
        } catch (err) {
            showToast('ZIP download failed', true);
        }
    });

    uploadZone.addEventListener('click', () => fileInput.click());
    uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        if (e.dataTransfer.files.length) uploadFile(e.dataTransfer.files[0]);
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            uploadFile(fileInput.files[0]);
            fileInput.value = '';
        }
    });
</script>
@endsection