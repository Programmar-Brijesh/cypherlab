@extends('layouts.app')

@section('title', 'OCR PDF Reader Studio – Extract Text from PDFs & Images | CypherLabs')
@section('meta_description', 'Extract 95%+ accurate text from PDF documents and scanned images online. Multi-language support, page-by-page extraction, copy text & download TXT.')

@section('content')

<style>
    .saas-ocr-wrapper {
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
    .ocr-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .ocr-ambient-glow {
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

    .ocr-container {
        max-width: 1040px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .ocr-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .ocr-hero-badge {
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

    .ocr-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .ocr-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* GLASS CARD WORKSPACE */
    .ocr-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        margin-bottom: 2.5rem;
    }

    .ocr-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .ocr-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* UPLOAD DROPZONE */
    .ocr-dropzone {
        border: 2px dashed rgba(0, 212, 255, 0.35);
        border-radius: 18px;
        padding: 3rem 1.5rem;
        text-align: center;
        background: rgba(8, 12, 24, 0.7);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .ocr-dropzone:hover, .ocr-dropzone.dragover {
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

    /* SETTINGS BAR */
    .settings-bar {
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
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

    /* OUTPUT TEXTAREA */
    .text-output-box {
        width: 100%;
        background: rgba(8, 12, 24, 0.95);
        border: 1px solid rgba(0, 212, 255, 0.25);
        border-radius: 14px;
        padding: 1.25rem;
        color: #f1f5f9;
        font-family: 'Fira Code', monospace;
        font-size: 0.88rem;
        line-height: 1.6;
        resize: vertical;
        min-height: 220px;
        max-height: 450px;
        margin-bottom: 1.25rem;
    }

    /* CONFIDENCE BAR */
    .confidence-meter-container {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: 'Fira Code', monospace;
        font-size: 0.8rem;
    }

    .confidence-bar {
        width: 140px;
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .confidence-fill {
        height: 100%;
        background: linear-gradient(90deg, #00d4ff, #06f7e3);
        transition: width 0.4s ease;
    }

    /* BUTTONS */
    .ocr-btn-primary {
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

    .ocr-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .ocr-btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }

    .ocr-btn-glass {
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #f1f5f9;
        font-size: 0.82rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .ocr-btn-glass:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
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

    /* TOAST */
    .ocr-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .ocr-toast {
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

<div class="saas-ocr-wrapper">
    <div class="ocr-bg-grid"></div>
    <div class="ocr-ambient-glow"></div>

    <!-- FLOATING ANIMATED PARTICLES -->
    <div class="floating-particle" style="width:6px; height:6px; left:15%; animation-delay:0s;"></div>
    <div class="floating-particle" style="width:10px; height:10px; left:35%; animation-delay:3s;"></div>
    <div class="floating-particle" style="width:8px; height:8px; left:65%; animation-delay:6s;"></div>
    <div class="floating-particle" style="width:12px; height:12px; left:85%; animation-delay:1.5s;"></div>

    <div class="ocr-container">
        
        <!-- HERO HEADER -->
        <div class="ocr-hero-box">
            <div class="ocr-hero-badge">
                <i class="fas fa-search me-1"></i> OCR READER STUDIO
            </div>
            <h1 class="ocr-hero-title">
                Extract Text from <span class="grad-text">PDFs & Scans</span>
            </h1>
            <p class="ocr-hero-sub">
                Extract 95%+ accurate searchable text from PDF documents and scanned images with zero software installation required.
            </p>
        </div>

        <!-- GLASS CARD WORKSPACE -->
        <div class="ocr-glass-card">
            <div class="ocr-card-head">
                <h2 class="ocr-card-title"><i class="fas fa-eye" style="color:var(--neon-blue);"></i> OCR Text Extraction Stage</h2>
            </div>

            <!-- UPLOAD DROPZONE -->
            <div id="uploadZone" class="ocr-dropzone">
                <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                <div style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">Drop scanned PDF or Image file here or click to browse</div>
                <div style="font-size:0.82rem; color:var(--text-muted); font-family:'Fira Code';">Supports PDF, JPG, PNG, WebP (Max 10MB)</div>
                <input type="file" id="fileInput" accept=".pdf,.jpg,.jpeg,.png,.webp" style="display:none">
            </div>

            <!-- FILE INFO CARD -->
            <div id="fileInfo" style="display:none; margin-bottom:1.5rem;">
                <div style="background:rgba(8,12,24,0.9); border:1px solid rgba(0,212,255,0.3); border-radius:14px; padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                    <div><i class="fas fa-file-alt me-2" style="color:var(--neon-blue);"></i> <span id="fileName" style="font-weight:700; color:#ffffff;"></span></div>
                    <div style="font-family:'Fira Code'; font-size:0.85rem; color:var(--neon-cyan);"><i class="fas fa-copy me-1"></i> <span id="filePages"></span> page(s)</div>
                    <div style="font-family:'Fira Code'; font-size:0.85rem; color:var(--text-muted);"><i class="fas fa-hdd me-1"></i> <span id="fileSize"></span></div>
                </div>
            </div>

            <!-- SETTINGS BAR -->
            <div id="settings" class="settings-bar" style="display:none;">
                <span style="font-size:0.8rem; font-weight:700; color:var(--text-muted); font-family:'Fira Code'; text-transform:uppercase;">Document Language:</span>
                <select id="language" class="setting-select">
                    <option value="eng">English</option>
                    <option value="hin">Hindi (हिन्दी)</option>
                    <option value="spa">Spanish (Español)</option>
                    <option value="fre">French (Français)</option>
                    <option value="ger">German (Deutsch)</option>
                    <option value="ara">Arabic (العربية)</option>
                    <option value="chi_sim">Chinese (Simplified)</option>
                    <option value="jpn">Japanese (日本語)</option>
                    <option value="urd">Urdu (اردو)</option>
                </select>
            </div>

            <!-- ACTION BUTTON -->
            <div style="text-align:center;">
                <button type="button" id="ocrBtn" class="ocr-btn-primary" disabled>
                    <i class="fas fa-search"></i> Extract Text Now
                </button>
            </div>

            <!-- PROGRESS BAR -->
            <div id="progressContainer" style="display:none; margin-top:1.5rem;">
                <div style="width:100%; height:6px; background:rgba(255,255,255,0.08); border-radius:10px; overflow:hidden;">
                    <div id="progressFill" style="height:100%; width:0%; background:linear-gradient(90deg, #00d4ff, #06f7e3); transition:width 0.3s ease;"></div>
                </div>
                <div id="progressText" style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; text-align:center; margin-top:6px;">Processing OCR extraction...</div>
            </div>

            <!-- RESULT PANEL -->
            <div id="resultPanel" style="display:none; margin-top:1.5rem;">
                <div style="background:rgba(8,12,24,0.95); border:1px solid rgba(0,212,255,0.3); border-radius:18px; padding:1.5rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1rem;">
                        <h4 style="font-size:1.1rem; font-weight:700; color:#ffffff; margin:0;"><i class="fas fa-align-left me-2" style="color:var(--neon-blue);"></i> Extracted Text Content</h4>
                        
                        <div class="confidence-meter-container">
                            <span id="confidenceDisplay" style="color:var(--neon-cyan);">Accuracy: 95%</span>
                            <div class="confidence-bar">
                                <div id="confidenceBar" class="confidence-fill" style="width:95%;"></div>
                            </div>
                        </div>
                    </div>

                    <textarea id="textOutput" class="text-output-box" readonly></textarea>

                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <button type="button" id="copyBtn" class="ocr-btn-glass"><i class="fas fa-copy"></i> Copy Text</button>
                        <button type="button" id="downloadTxtBtn" class="ocr-btn-glass"><i class="fas fa-download"></i> Download .TXT</button>
                    </div>
                </div>
            </div>

        </div>

        <!-- CONTENT SECTION 1: HOW TO USE -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:0.5rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-map-signs" style="color:var(--neon-blue);"></i> How to Extract Text from PDFs & Images in 3 Steps
            </h3>
            <p style="color:var(--text-muted); font-size:0.9rem;">Follow these simple steps to perform high-precision OCR text extraction:</p>

            <div class="step-grid">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Upload Document or Image</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Drag and drop your PDF file or scanned image (JPG, PNG, WebP) into the extraction stage.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">2</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Select Language & Process</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Select document language and click "Extract Text Now" to parse characters with 95%+ accuracy.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">3</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Copy or Download TXT</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Review extracted text, copy to clipboard with 1-click, or download formatted `.txt` files.</p>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION 2: FAQS -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:1rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-question-circle" style="color:var(--neon-blue);"></i> OCR Extraction FAQs
            </h3>

            <details style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">How high is the OCR accuracy rate?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">Native PDF parsing achieves 98-100% precision. Scanned documents & images achieve 95%+ text accuracy depending on scan clarity.</p>
            </details>

            <details style="padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Are non-English languages supported?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">Yes! We support English, Hindi, Spanish, French, German, Arabic, Japanese, Chinese, and Urdu.</p>
            </details>
        </div>

    </div>
</div>

<div class="ocr-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    let uploadedFile = null;
    let ocrInProgress = false;
    let extractedText = '';

    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileInfoDiv = document.getElementById('fileInfo');
    const fileNameSpan = document.getElementById('fileName');
    const fileSizeSpan = document.getElementById('fileSize');
    const filePagesSpan = document.getElementById('filePages');
    const settings = document.getElementById('settings');
    const ocrBtn = document.getElementById('ocrBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const resultPanel = document.getElementById('resultPanel');
    const textOutput = document.getElementById('textOutput');
    const confidenceDisplay = document.getElementById('confidenceDisplay');
    const confidenceBar = document.getElementById('confidenceBar');
    const copyBtn = document.getElementById('copyBtn');
    const downloadTxtBtn = document.getElementById('downloadTxtBtn');

    function showToast(msg, isError = false) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'ocr-toast';
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

    async function uploadFile(file) {
        if (!file.name.match(/\.(pdf|jpe?g|png|webp)$/i)) {
            showToast('Please select PDF, JPG, PNG or WebP', true);
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            showToast('File exceeds 10MB limit', true);
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('{{ route("ocr.upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            uploadedFile = { id: data.id, name: data.name, size: data.size, pages: data.pages || 1 };
            fileNameSpan.innerText = data.name;
            fileSizeSpan.innerText = formatBytes(data.size);
            filePagesSpan.innerText = data.pages || 1;
            fileInfoDiv.style.display = 'block';
            settings.style.display = 'flex';
            ocrBtn.disabled = false;
            resultPanel.style.display = 'none';
            showToast('File uploaded successfully!');
        } catch (err) {
            showToast('Upload failed: ' + err.message, true);
        }
    }

    ocrBtn.addEventListener('click', async () => {
        if (!uploadedFile || ocrInProgress) return;
        ocrInProgress = true;
        ocrBtn.disabled = true;
        progressContainer.style.display = 'block';
        progressFill.style.width = '30%';
        progressText.innerText = 'Initializing OCR text engine...';

        try {
            progressFill.style.width = '70%';
            progressText.innerText = 'Extracting document text...';
            const res = await fetch('{{ route("ocr.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: uploadedFile.id,
                    language: document.getElementById('language').value
                })
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            extractedText = data.text;
            const confidence = data.confidence || 95;

            textOutput.value = extractedText;
            confidenceDisplay.innerText = `Accuracy: ${confidence}%`;
            confidenceBar.style.width = confidence + '%';

            resultPanel.style.display = 'block';
            progressFill.style.width = '100%';
            progressText.innerText = 'Extraction complete!';
            showToast('Text extracted successfully!');
        } catch (err) {
            showToast('OCR failed: ' + err.message, true);
        } finally {
            setTimeout(() => {
                progressContainer.style.display = 'none';
                progressFill.style.width = '0%';
                ocrInProgress = false;
                ocrBtn.disabled = false;
            }, 800);
        }
    });

    copyBtn.addEventListener('click', () => {
        if (!extractedText) return;
        navigator.clipboard.writeText(extractedText).then(() => showToast('Text copied to clipboard!'));
    });

    downloadTxtBtn.addEventListener('click', () => {
        if (!extractedText) return;
        const blob = new Blob([extractedText], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = (uploadedFile?.name || 'ocr-extracted').replace(/\.[^.]+$/, '') + '.txt';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('Downloaded .TXT file');
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