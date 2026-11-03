@extends('layouts.app')

@section('title', 'Word to PDF Converter Studio – Convert DOCX to PDF | CypherLabs')
@section('meta_description', 'Convert Word (DOCX/DOC) documents to clean PDF files online with layout formatting preserved. 100% private & instant conversion.')

@section('content')

<style>
    .saas-word-wrapper {
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
    .word-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .word-ambient-glow {
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

    .word-container {
        max-width: 1040px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .word-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .word-hero-badge {
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

    .word-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .word-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* GLASS CARD WORKSPACE */
    .word-glass-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        margin-bottom: 2.5rem;
    }

    .word-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .word-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* UPLOAD DROPZONE */
    .word-dropzone {
        border: 2px dashed rgba(0, 212, 255, 0.35);
        border-radius: 18px;
        padding: 3rem 1.5rem;
        text-align: center;
        background: rgba(8, 12, 24, 0.7);
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .word-dropzone:hover, .word-dropzone.dragover {
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

    /* RESULT COMPARISON CARDS */
    .size-comparison-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .size-card {
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 1.2rem;
        text-align: center;
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
    .word-btn-primary {
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

    .word-btn-primary:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .word-btn-primary:disabled { opacity: 0.4; cursor: not-allowed; }

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
    .word-toast-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        z-index: 9999;
    }

    .word-toast {
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

<div class="saas-word-wrapper">
    <div class="word-bg-grid"></div>
    <div class="word-ambient-glow"></div>

    <!-- FLOATING ANIMATED PARTICLES -->
    <div class="floating-particle" style="width:6px; height:6px; left:15%; animation-delay:0s;"></div>
    <div class="floating-particle" style="width:10px; height:10px; left:35%; animation-delay:3s;"></div>
    <div class="floating-particle" style="width:8px; height:8px; left:65%; animation-delay:6s;"></div>
    <div class="floating-particle" style="width:12px; height:12px; left:85%; animation-delay:1.5s;"></div>

    <div class="word-container">
        
        <!-- HERO HEADER -->
        <div class="word-hero-box">
            <div class="word-hero-badge">
                <i class="fas fa-file-word me-1"></i> WORD TO PDF STUDIO
            </div>
            <h1 class="word-hero-title">
                Convert Word Documents to <span class="grad-text">PDF Studio</span>
            </h1>
            <p class="word-hero-sub">
                Convert DOC and DOCX files to high-precision PDF documents while keeping exact fonts, images, and layout formatting preserved.
            </p>
        </div>

        <!-- GLASS CARD WORKSPACE -->
        <div class="word-glass-card">
            <div class="word-card-head">
                <h2 class="word-card-title"><i class="fas fa-exchange-alt" style="color:var(--neon-blue);"></i> Document Conversion Stage</h2>
            </div>

            <!-- UPLOAD DROPZONE -->
            <div id="uploadZone" class="word-dropzone">
                <i class="fas fa-cloud-upload-alt dropzone-icon"></i>
                <div style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:0.3rem;">Drop Word file (DOCX / DOC) here or click to browse</div>
                <div style="font-size:0.82rem; color:var(--text-muted); font-family:'Fira Code';">Supports up to 20MB document file size</div>
                <input type="file" id="fileInput" accept=".docx,.doc" style="display:none">
            </div>

            <!-- FILE INFO CARD -->
            <div id="fileInfo" style="display:none; margin-bottom:1.5rem;">
                <div style="background:rgba(8,12,24,0.9); border:1px solid rgba(0,212,255,0.3); border-radius:14px; padding:1rem 1.25rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                    <div><i class="fas fa-file-word me-2" style="color:var(--neon-blue);"></i> <span id="fileName" style="font-weight:700; color:#ffffff;"></span></div>
                    <div style="font-family:'Fira Code'; font-size:0.85rem; color:var(--text-muted);"><i class="fas fa-hdd me-1"></i> <span id="fileSize"></span></div>
                </div>
            </div>

            <!-- CONVERT ACTION BUTTON -->
            <div id="conversionOptions" style="display:none; text-align:center;">
                <button type="button" id="convertBtn" class="word-btn-primary">
                    <i class="fas fa-exchange-alt"></i> Convert to PDF Now
                </button>
            </div>

            <!-- PROGRESS BAR -->
            <div id="progressContainer" style="display:none; margin-top:1.5rem;">
                <div style="width:100%; height:6px; background:rgba(255,255,255,0.08); border-radius:10px; overflow:hidden;">
                    <div id="progressFill" style="height:100%; width:0%; background:linear-gradient(90deg, #00d4ff, #06f7e3); transition:width 0.3s ease;"></div>
                </div>
                <div id="progressText" style="font-size:0.8rem; color:var(--text-muted); font-family:'Fira Code'; text-align:center; margin-top:6px;">Processing conversion...</div>
            </div>

            <!-- RESULT PANEL -->
            <div id="resultPanel" style="display:none; margin-top:1.5rem;">
                <div style="background:rgba(8,12,24,0.95); border:1px solid rgba(0,212,255,0.3); border-radius:18px; padding:1.5rem; text-align:center;">
                    <h4 style="font-size:1.1rem; font-weight:700; color:#ffffff; margin-bottom:1rem;"><i class="fas fa-check-circle me-2" style="color:#22c55e;"></i> Conversion Complete</h4>
                    
                    <div class="size-comparison-grid">
                        <div class="size-card">
                            <i class="fas fa-file-word" style="color:var(--neon-blue); font-size:1.5rem; margin-bottom:0.4rem;"></i>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code';">DOCX (Original)</div>
                            <div id="originalSizeDisplay" style="font-weight:700; color:#ffffff; font-family:'Fira Code';"></div>
                        </div>
                        <div class="size-card">
                            <i class="fas fa-file-pdf" style="color:var(--neon-cyan); font-size:1.5rem; margin-bottom:0.4rem;"></i>
                            <div style="font-size:0.75rem; color:var(--text-muted); font-family:'Fira Code';">PDF (Converted)</div>
                            <div id="pdfSizeDisplay" style="font-weight:700; color:var(--neon-cyan); font-family:'Fira Code';"></div>
                        </div>
                    </div>

                    <div style="margin-top:1rem;">
                        <input type="text" id="renameInput" class="rename-input" value="converted.pdf">
                    </div>
                    <button type="button" id="downloadBtn" class="word-btn-primary">
                        <i class="fas fa-download"></i> Download Converted PDF
                    </button>
                </div>
            </div>

        </div>

        <!-- CONTENT SECTION 1: HOW TO USE -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:0.5rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-map-signs" style="color:var(--neon-blue);"></i> How to Convert Word to PDF in 3 Steps
            </h3>
            <p style="color:var(--text-muted); font-size:0.9rem;">Follow these simple steps to transform your Word documents into professional PDFs:</p>

            <div class="step-grid">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Upload Word File</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Drag and drop your DOCX or DOC file into the conversion area or click to upload.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">2</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Click Convert to PDF</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Our sub-second rendering engine converts document pages while retaining formatting.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">3</div>
                    <h4 style="font-size:1rem; font-weight:700; color:#ffffff; margin-bottom:0.4rem;">Download Converted File</h4>
                    <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">Rename output if desired and download your PDF instantly to your desktop or device.</p>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION 2: FAQS -->
        <div class="content-card">
            <h3 style="font-size:1.3rem; font-weight:800; color:#ffffff; margin-bottom:1rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-question-circle" style="color:var(--neon-blue);"></i> Word to PDF Conversion FAQs
            </h3>

            <details style="border-bottom:1px solid rgba(255,255,255,0.08); padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Will tables and fonts look identical in the output PDF?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">Yes. CypherLabs Word to PDF engine parses embedded vector tables, text alignment, and styles to produce matching PDFs.</p>
            </details>

            <details style="padding:0.85rem 0;">
                <summary style="font-size:0.95rem; font-weight:700; color:#ffffff; cursor:pointer;">Is my Word document stored on cloud servers?</summary>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-top:0.4rem;">No. Files are processed in temporary isolated environments and deleted automatically right after download.</p>
            </details>
        </div>

    </div>
</div>

<div class="word-toast-container" id="toastContainer"></div>

@endsection

@section('scripts')
<script>
    let uploadedFile = null;
    let convertInProgress = false;
    let sessionId = null;

    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileInfoDiv = document.getElementById('fileInfo');
    const fileNameSpan = document.getElementById('fileName');
    const fileSizeSpan = document.getElementById('fileSize');
    const conversionOptionsDiv = document.getElementById('conversionOptions');
    const convertBtn = document.getElementById('convertBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const resultPanel = document.getElementById('resultPanel');
    const originalSizeDisplay = document.getElementById('originalSizeDisplay');
    const pdfSizeDisplay = document.getElementById('pdfSizeDisplay');
    const renameInput = document.getElementById('renameInput');
    const downloadBtn = document.getElementById('downloadBtn');

    function showToast(msg, isError = false) {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.className = 'word-toast';
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
        if (!file.name.match(/\.(docx?)$/i)) {
            showToast('Please select a Word document (DOCX/DOC)', true);
            return;
        }
        if (file.size > 20 * 1024 * 1024) {
            showToast('File exceeds 20MB limit', true);
            return;
        }

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('{{ route("word.topdf.upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            uploadedFile = { id: data.id, name: data.name, size: data.size };
            fileNameSpan.innerText = data.name;
            fileSizeSpan.innerText = formatBytes(data.size);
            fileInfoDiv.style.display = 'block';
            conversionOptionsDiv.style.display = 'block';
            resultPanel.style.display = 'none';
            showToast('Word document uploaded successfully!');
        } catch (err) {
            showToast('Upload failed: ' + err.message, true);
        }
    }

    convertBtn.addEventListener('click', async () => {
        if (!uploadedFile || convertInProgress) return;
        convertInProgress = true;
        convertBtn.disabled = true;
        progressContainer.style.display = 'block';
        progressFill.style.width = '30%';
        progressText.innerText = 'Initializing Word rendering engine...';

        try {
            progressFill.style.width = '70%';
            progressText.innerText = 'Converting document to PDF format...';
            const res = await fetch('{{ route("word.topdf.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: uploadedFile.id })
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            sessionId = data.sessionId;
            originalSizeDisplay.innerText = formatBytes(data.originalSize);
            pdfSizeDisplay.innerText = formatBytes(data.pdfSize);
            resultPanel.style.display = 'block';
            progressFill.style.width = '100%';
            progressText.innerText = 'Conversion complete!';
            showToast('Word document converted to PDF successfully!');
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
        let newName = renameInput.value.trim() || 'converted.pdf';
        if (!newName.endsWith('.pdf')) newName += '.pdf';

        try {
            const formData = new FormData();
            formData.append('sessionId', sessionId);
            formData.append('rename', newName);
            const res = await fetch('{{ route("word.topdf.download") }}', {
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