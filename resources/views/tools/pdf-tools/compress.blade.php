@extends('layouts.app')

@section('title', 'PDF Compressor - Reduce PDF File Size | CypherLabs')
@section('meta_description', 'Compress PDF files with low/medium/high compression or target a specific file size. Preview before download.')

@section('content')
<style>
    /* Same cyber theme as other tools */
    .compress-page { background: var(--bg-deep); padding: 2rem 0; }
    .tool-box { background: var(--glass-bg); backdrop-filter: blur(10px); border-radius: 28px; padding: 2rem; border: 1px solid rgba(0,212,255,0.15); }
    .section-title-wrapper { display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; }
    .section-icon { font-size: 1.4rem; color: var(--neon-blue); }
    .section-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(135deg, #fff, var(--neon-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .upload-zone { border: 2px dashed rgba(0,212,255,0.4); border-radius: 24px; padding: 2rem; text-align: center; cursor: pointer; background: rgba(10,14,26,0.5); margin-bottom: 1.5rem; transition: 0.2s; }
    .upload-zone.dragover { border-color: var(--neon-blue); background: rgba(0,212,255,0.05); }
    .upload-icon { font-size: 3rem; color: var(--neon-blue); margin-bottom: 1rem; }
    .file-info-card { background: rgba(10,14,26,0.4); border-radius: 20px; padding: 1rem; margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
    .compression-options { margin: 1.5rem 0; }
    .option-group { margin-bottom: 1.5rem; }
    .preset-buttons { display: flex; gap: 1rem; flex-wrap: wrap; margin-top: 0.5rem; }
    .preset-btn { background: rgba(0,212,255,0.1); border: 1px solid rgba(0,212,255,0.3); padding: 0.5rem 1.2rem; border-radius: 40px; cursor: pointer; transition: 0.2s; }
    .preset-btn.active { background: linear-gradient(135deg, #00d4ff, #2affdf); color: #0a0e1a; border-color: transparent; }
    .quality-slider { width: 100%; margin-top: 0.5rem; accent-color: var(--neon-blue); }
    .btn-primary { background: linear-gradient(135deg, #00d4ff, #2affdf); color: #0a0e1a; border: none; padding: 0.8rem 1.8rem; border-radius: 50px; font-weight: 700; cursor: pointer; transition: 0.2s; }
    .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
    .btn-secondary { background: transparent; border: 1px solid var(--neon-blue); color: var(--neon-blue); padding: 0.5rem 1rem; border-radius: 40px; cursor: pointer; transition: 0.2s; }
    .progress-bar { width: 100%; height: 4px; background: rgba(255,255,255,0.1); border-radius: 4px; overflow: hidden; margin: 1rem 0; }
    .progress-fill { width: 0%; height: 100%; background: linear-gradient(90deg, var(--neon-blue), var(--neon-cyan)); transition: width 0.3s; }
    .result-box { margin-top: 1.5rem; background: rgba(10,14,26,0.4); border-radius: 20px; padding: 1.2rem; }
    .size-comparison { display: flex; justify-content: space-around; flex-wrap: wrap; gap: 1rem; margin: 1rem 0; }
    .size-card { text-align: center; padding: 1rem; background: rgba(0,0,0,0.3); border-radius: 16px; flex: 1; }
    .preview-image { max-width: 100%; max-height: 200px; border-radius: 12px; margin-top: 0.5rem; }
    .rename-input { background: rgba(10,14,26,0.9); border: 1px solid var(--neon-blue); border-radius: 12px; padding: 0.5rem; color: white; width: 250px; }
    .toast { position: fixed; bottom: 2rem; right: 2rem; background: #0a0e1a; border-left: 4px solid var(--neon-blue); padding: 0.8rem 1.2rem; border-radius: 12px; z-index: 1000; animation: slideIn 0.3s; }
    @keyframes slideIn { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    @media (max-width: 768px) { .tool-box { padding: 1.2rem; } .rename-input { width: 100%; } }
</style>

<div class="compress-page">
    <div class="container">
        <div class="tool-box">
            <div class="section-title-wrapper">
                <span class="section-icon">🗜️</span>
                <h2 class="section-title">PDF Compressor</h2>
            </div>

            <div id="uploadZone" class="upload-zone">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <p class="upload-text">Drop PDF here or click to select</p>
                <p class="upload-hint">Single PDF, max 50MB</p>
                <input type="file" id="fileInput" accept=".pdf" style="display:none">
            </div>

            <div id="fileInfo" style="display: none;">
                <div class="file-info-card">
                    <span><i class="fas fa-file-pdf"></i> <span id="fileName"></span></span>
                    <span><i class="fas fa-file-alt"></i> <span id="filePages"></span> pages</span>
                    <span><i class="fas fa-weight-hanging"></i> <span id="fileSize"></span></span>
                </div>
            </div>

            <div id="compressionOptions" style="display: none;">
                <div class="compression-options">
                    <div class="option-group">
                        <label class="upload-text">Compression Method</label>
                        <div class="preset-buttons">
                            <button class="preset-btn active" data-method="preset">Preset</button>
                            <button class="preset-btn" data-method="target">Target Size (KB)</button>
                        </div>
                    </div>

                    <div id="presetPanel">
                        <label class="upload-text">Compression Level</label>
                        <div class="preset-buttons">
                            <button class="preset-level" data-level="low">Low</button>
                            <button class="preset-level active" data-level="medium">Medium</button>
                            <button class="preset-level" data-level="high">High</button>
                        </div>
                        <label class="upload-text" style="margin-top: 1rem;">Custom Quality (slider)</label>
                        <input type="range" id="qualitySlider" class="quality-slider" min="10" max="100" value="70">
                        <div><span id="qualityValue">70</span>%</div>
                    </div>

                    <div id="targetPanel" style="display: none;">
                        <label class="upload-text">Desired file size (KB)</label>
                        <input type="number" id="targetKb" class="rename-input" placeholder="e.g., 500" min="10">
                    </div>
                </div>

                <div class="text-center">
                    <button id="compressBtn" class="btn-primary" disabled>
                        <i class="fas fa-compress-alt"></i> Compress PDF
                    </button>
                </div>
            </div>

            <div id="progressContainer" style="display: none;">
                <div class="progress-bar"><div class="progress-fill" id="progressFill"></div></div>
                <p id="progressText" style="font-size:0.75rem;">Processing...</p>
            </div>

            <div id="resultPanel" style="display: none;">
                <div class="result-box">
                    <h4>Compression Result</h4>
                    <div class="size-comparison">
                        <div class="size-card">
                            <i class="fas fa-file"></i> Original<br>
                            <strong id="originalSizeDisplay"></strong>
                        </div>
                        <div class="size-card">
                            <i class="fas fa-file-alt"></i> Compressed<br>
                            <strong id="compressedSizeDisplay"></strong>
                        </div>
                        <div class="size-card">
                            <i class="fas fa-chart-line"></i> Saved<br>
                            <strong id="savedPercentDisplay"></strong>
                        </div>
                    </div>
                    <div class="text-center">
                        <img id="previewImg" class="preview-image" src="" alt="Preview" style="display: none;">
                        <p id="noPreviewMsg" style="font-size:0.7rem; color:var(--text-muted);">Preview not available (Imagick required)</p>
                    </div>
                    <div class="text-center" style="margin-top: 1rem;">
                        <input type="text" id="renameInput" class="rename-input" value="compressed.pdf" placeholder="Filename">
                        <button id="downloadBtn" class="btn-primary" style="margin-left: 0.5rem;">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let uploadedFile = null;      // { id, name, size, pages, gsAvailable }
    let compressInProgress = false;
    let currentMethod = 'preset';
    let currentPreset = 'medium';
    let sessionId = null;

    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileInfoDiv = document.getElementById('fileInfo');
    const fileNameSpan = document.getElementById('fileName');
    const filePagesSpan = document.getElementById('filePages');
    const fileSizeSpan = document.getElementById('fileSize');
    const compressionOptionsDiv = document.getElementById('compressionOptions');
    const presetPanel = document.getElementById('presetPanel');
    const targetPanel = document.getElementById('targetPanel');
    const compressBtn = document.getElementById('compressBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const resultPanel = document.getElementById('resultPanel');
    const originalSizeDisplay = document.getElementById('originalSizeDisplay');
    const compressedSizeDisplay = document.getElementById('compressedSizeDisplay');
    const savedPercentDisplay = document.getElementById('savedPercentDisplay');
    const previewImg = document.getElementById('previewImg');
    const noPreviewMsg = document.getElementById('noPreviewMsg');
    const renameInput = document.getElementById('renameInput');
    const downloadBtn = document.getElementById('downloadBtn');
    const qualitySlider = document.getElementById('qualitySlider');
    const qualityValue = document.getElementById('qualityValue');
    const targetKbInput = document.getElementById('targetKb');

    function showToast(msg, isError = false) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        if (isError) toast.style.borderLeftColor = '#ff2e9e';
        toast.innerText = msg;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function formatBytes(bytes) {
        if (!bytes) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    qualitySlider.addEventListener('input', () => {
        qualityValue.innerText = qualitySlider.value;
    });

    // Method toggle
    document.querySelectorAll('[data-method]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('[data-method]').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentMethod = btn.getAttribute('data-method');
            if (currentMethod === 'preset') {
                presetPanel.style.display = 'block';
                targetPanel.style.display = 'none';
                compressBtn.disabled = false;
            } else {
                presetPanel.style.display = 'none';
                targetPanel.style.display = 'block';
                compressBtn.disabled = !targetKbInput.value;
            }
        });
    });

    // Preset level
    document.querySelectorAll('.preset-level').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.preset-level').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentPreset = btn.getAttribute('data-level');
        });
    });

    targetKbInput.addEventListener('input', () => {
        compressBtn.disabled = !targetKbInput.value;
    });

    async function uploadFile(file) {
        if (file.type !== 'application/pdf') {
            showToast('Please select a PDF file', true);
            return false;
        }
        if (file.size > 50 * 1024 * 1024) {
            showToast('File exceeds 50MB limit', true);
            return false;
        }

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('{{ route("pdf.compress.upload") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            uploadedFile = {
                id: data.id,
                name: data.name,
                size: data.size,
                pages: data.pages,
                gsAvailable: data.gsAvailable
            };
            fileNameSpan.innerText = data.name;
            filePagesSpan.innerText = data.pages;
            fileSizeSpan.innerText = formatBytes(data.size);
            fileInfoDiv.style.display = 'block';
            compressionOptionsDiv.style.display = 'block';
            compressBtn.disabled = false;
            if (!data.gsAvailable) {
                showToast('Ghostscript not available on server. Compression may fail.', true);
            }
            // reset previous result
            resultPanel.style.display = 'none';
            sessionId = null;
            return true;
        } catch (err) {
            showToast('Upload failed: ' + err.message, true);
            return false;
        }
    }

    compressBtn.addEventListener('click', async () => {
        if (!uploadedFile || compressInProgress) return;
        compressInProgress = true;
        compressBtn.disabled = true;
        progressContainer.style.display = 'block';
        progressFill.style.width = '20%';
        progressText.innerText = 'Preparing compression...';

        let payload = {
            id: uploadedFile.id,
            method: currentMethod
        };
        if (currentMethod === 'preset') {
            payload.preset = currentPreset;
            payload.quality = qualitySlider.value;
        } else {
            payload.targetSize = parseInt(targetKbInput.value);
        }

        try {
            progressFill.style.width = '40%';
            progressText.innerText = 'Compressing PDF...';
            const res = await fetch('{{ route("pdf.compress.process") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            sessionId = data.sessionId;
            originalSizeDisplay.innerText = formatBytes(data.originalSize);
            compressedSizeDisplay.innerText = formatBytes(data.compressedSize);
            savedPercentDisplay.innerText = data.savedPercent + '%';
            if (data.previewUrl) {
                previewImg.src = data.previewUrl;
                previewImg.style.display = 'inline';
                noPreviewMsg.style.display = 'none';
            } else {
                previewImg.style.display = 'none';
                noPreviewMsg.style.display = 'block';
            }
            resultPanel.style.display = 'block';
            progressFill.style.width = '100%';
            progressText.innerText = 'Compression complete!';
            showToast('PDF compressed successfully!');
        } catch (err) {
            showToast('Compression failed: ' + err.message, true);
            resultPanel.style.display = 'none';
        } finally {
            setTimeout(() => {
                progressContainer.style.display = 'none';
                progressFill.style.width = '0%';
                compressInProgress = false;
                compressBtn.disabled = false;
            }, 1000);
        }
    });

    downloadBtn.addEventListener('click', async () => {
        if (!sessionId) return;
        let newName = renameInput.value.trim();
        if (!newName) newName = 'compressed.pdf';
        if (!newName.endsWith('.pdf')) newName += '.pdf';

        try {
            const formData = new FormData();
            formData.append('sessionId', sessionId);
            formData.append('rename', newName);
            const res = await fetch('{{ route("pdf.compress.download") }}', {
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

    // Upload events
    uploadZone.addEventListener('click', () => fileInput.click());
    uploadZone.addEventListener('dragover', (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
    uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('dragover'));
    uploadZone.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadZone.classList.remove('dragover');
        const files = Array.from(e.dataTransfer.files);
        if (files.length) uploadFile(files[0]);
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length) {
            uploadFile(fileInput.files[0]);
            fileInput.value = '';
        }
    });
</script>
@endsection