@extends('layouts.app')

@section('title', 'PDF Watermark - Add Text/Image Watermark to PDF | CypherLabs')
@section('meta_description', 'Add text or image watermarks to PDF files. Customize opacity, rotation, position & page range. Free online tool.')

@section('content')
<style>
    .compress-page {
        background: var(--bg-deep);
        padding: 2rem 0;
    }

    .tool-box {
        background: var(--glass-bg);
        backdrop-filter: blur(10px);
        border-radius: 28px;
        padding: 2rem;
        border: 1px solid rgba(0, 212, 255, 0.15);
    }

    .section-title-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        font-size: 1.4rem;
        color: var(--neon-blue);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #fff, var(--neon-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .upload-zone {
        border: 2px dashed rgba(0, 212, 255, 0.4);
        border-radius: 24px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        background: rgba(10, 14, 26, 0.5);
        margin-bottom: 1.5rem;
        transition: 0.2s;
    }

    .upload-zone.dragover {
        border-color: var(--neon-blue);
        background: rgba(0, 212, 255, 0.05);
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--neon-blue);
        margin-bottom: 1rem;
    }

    .file-info-card {
        background: rgba(10, 14, 26, 0.4);
        border-radius: 20px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .settings-panel {
        background: rgba(10, 14, 26, 0.4);
        border: 1px solid rgba(0, 212, 255, 0.15);
        border-radius: 20px;
        padding: 1.5rem;
        margin: 1.5rem 0;
    }

    .setting-row {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .setting-label {
        color: white;
        min-width: 80px;
        font-size: 0.85rem;
    }

    .input-field {
        background: rgba(10, 14, 26, 0.9);
        border: 1px solid var(--neon-blue);
        border-radius: 12px;
        padding: 0.5rem 1rem;
        color: white;
    }

    .input-field:focus {
        outline: none;
        border-color: #2affdf;
    }

    .range-slider {
        width: 200px;
        accent-color: var(--neon-blue);
    }

    .radio-group {
        display: flex;
        gap: 0.5rem;
    }

    .radio-btn {
        background: rgba(0, 212, 255, 0.1);
        border: 1px solid rgba(0, 212, 255, 0.3);
        padding: 0.4rem 1rem;
        border-radius: 40px;
        cursor: pointer;
        color: white;
        font-size: 0.8rem;
        transition: 0.2s;
    }

    .radio-btn.active {
        background: linear-gradient(135deg, #00d4ff, #2affdf);
        color: #0a0e1a;
        border-color: transparent;
    }

    .btn-primary {
        background: linear-gradient(135deg, #00d4ff, #2affdf);
        color: #0a0e1a;
        border: none;
        padding: 0.8rem 1.8rem;
        border-radius: 50px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-secondary {
        background: transparent;
        border: 1px solid var(--neon-blue);
        color: var(--neon-blue);
        padding: 0.5rem 1rem;
        border-radius: 40px;
        cursor: pointer;
        transition: 0.2s;
    }

    .progress-bar {
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        overflow: hidden;
        margin: 1rem 0;
    }

    .progress-fill {
        width: 0%;
        height: 100%;
        background: linear-gradient(90deg, var(--neon-blue), var(--neon-cyan));
        transition: width 0.3s;
    }

    .result-box {
        margin-top: 1.5rem;
        background: rgba(10, 14, 26, 0.4);
        border-radius: 20px;
        padding: 1.2rem;
    }

    .size-comparison {
        display: flex;
        justify-content: space-around;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 1rem 0;
    }

    .size-card {
        text-align: center;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 16px;
        flex: 1;
    }

    .preview-box {
        background: rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        margin: 1rem 0;
        max-height: 300px;
        overflow: auto;
    }

    .preview-image {
        max-width: 100%;
        max-height: 250px;
        border-radius: 8px;
    }

    .color-input {
        width: 40px;
        height: 40px;
        border: 2px solid var(--neon-blue);
        border-radius: 8px;
        cursor: pointer;
        padding: 0;
    }

    .rename-input {
        background: rgba(10, 14, 26, 0.9);
        border: 1px solid var(--neon-blue);
        border-radius: 12px;
        padding: 0.5rem;
        color: white;
        width: 250px;
    }

    .toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: #0a0e1a;
        border-left: 4px solid var(--neon-blue);
        padding: 0.8rem 1.2rem;
        border-radius: 12px;
        z-index: 1000;
        animation: slideIn 0.3s;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @media (max-width: 768px) {
        .tool-box {
            padding: 1.2rem;
        }

        .setting-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .rename-input {
            width: 100%;
        }
    }
</style>

<div class="compress-page">
    <div class="container">
        <div class="tool-box">
            <div class="section-title-wrapper">
                <span class="section-icon">💧</span>
                <h2 class="section-title">PDF Watermark</h2>
            </div>

            <div id="uploadZone" class="upload-zone">
                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                <p class="upload-text">Drop PDF here or click to select</p>
                <p class="upload-hint">Single PDF, max 20MB</p>
                <input type="file" id="fileInput" accept=".pdf" style="display:none">
            </div>

            <div id="fileInfo" style="display: none;">
                <div class="file-info-card">
                    <span><i class="fas fa-file-pdf"></i> <span id="fileName"></span></span>
                    <span><i class="fas fa-file-alt"></i> <span id="filePages"></span> pages</span>
                    <span><i class="fas fa-weight-hanging"></i> <span id="fileSize"></span></span>
                </div>
            </div>

            <div id="settingsPanel" style="display: none;">
                <div class="settings-panel">
                    <h4 style="color: white; margin-bottom: 1rem;">Watermark Settings</h4>

                    <!-- Watermark Type -->
                    <div class="setting-row">
                        <span class="setting-label">Type:</span>
                        <div class="radio-group" id="typeGroup">
                            <button class="radio-btn active" data-type="text">📝 Text</button>
                            <button class="radio-btn" data-type="image">🖼️ Image</button>
                        </div>
                    </div>

                    <!-- Text Watermark Settings -->
                    <div id="textSettings">
                        <div class="setting-row">
                            <span class="setting-label">Text:</span>
                            <input type="text" id="watermarkText" class="input-field" value="CONFIDENTIAL" style="width: 250px;">
                        </div>
                        <div class="setting-row">
                            <span class="setting-label">Font Size:</span>
                            <input type="range" id="fontSize" class="range-slider" min="10" max="120" value="60">
                            <span id="fontSizeVal" style="color: white;">60px</span>
                        </div>
                        <div class="setting-row">
                            <span class="setting-label">Color:</span>
                            <input type="color" id="textColor" class="color-input" value="#ff0000">
                        </div>
                    </div>

                    <!-- Image Watermark Settings -->
                    <div id="imageSettings" style="display: none;">
                        <div class="setting-row">
                            <span class="setting-label">Image:</span>
                            <input type="file" id="watermarkImage" accept=".png,.jpg,.jpeg" style="color: white;">
                        </div>
                        <div class="setting-row">
                            <span class="setting-label">Size:</span>
                            <input type="range" id="imageSize" class="range-slider" min="50" max="500" value="200">
                            <span id="imageSizeVal" style="color: white;">200px</span>
                        </div>
                    </div>

                    <!-- Common Settings -->
                    <div class="setting-row">
                        <span class="setting-label">Opacity:</span>
                        <input type="range" id="opacity" class="range-slider" min="5" max="100" value="30">
                        <span id="opacityVal" style="color: white;">30%</span>
                    </div>
                    <div class="setting-row">
                        <span class="setting-label">Rotation:</span>
                        <input type="range" id="rotation" class="range-slider" min="-90" max="90" value="45">
                        <span id="rotationVal" style="color: white;">45°</span>
                    </div>
                    <div class="setting-row">
                        <span class="setting-label">Position:</span>
                        <div class="radio-group" id="positionGroup">
                            <button class="radio-btn active" data-pos="center">Center</button>
                            <button class="radio-btn" data-pos="tile">Tile</button>
                            <button class="radio-btn" data-pos="top-left">Top-Left</button>
                            <button class="radio-btn" data-pos="top-right">Top-Right</button>
                            <button class="radio-btn" data-pos="bottom-left">Bottom-Left</button>
                            <button class="radio-btn" data-pos="bottom-right">Bottom-Right</button>
                        </div>
                    </div>
                    <div class="setting-row">
                        <span class="setting-label">Pages:</span>
                        <input type="text" id="pageRange" class="input-field" placeholder="e.g., 1-5, 1,3,5 or all" value="all" style="width: 200px;">
                        <span style="color: var(--text-muted); font-size: 0.7rem;">(leave "all" for all pages)</span>
                    </div>
                </div>

                <div class="text-center" style="margin-top: 1rem;">
                    <button id="applyBtn" class="btn-primary">
                        <i class="fas fa-check"></i> Apply Watermark
                    </button>
                </div>
            </div>

            <div id="progressContainer" style="display: none;">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                <p id="progressText" style="font-size:0.75rem;">Processing...</p>
            </div>

            <div id="resultPanel" style="display: none;">
                <div class="result-box">
                    <h4>Watermark Applied!</h4>
                    <div class="size-comparison">
                        <div class="size-card">
                            <i class="fas fa-file-pdf"></i> Original<br>
                            <strong id="originalSizeDisplay"></strong>
                        </div>
                        <div class="size-card">
                            <i class="fas fa-file-pdf"></i> Watermarked<br>
                            <strong id="watermarkedSizeDisplay"></strong>
                        </div>
                        <div class="size-card">
                            <i class="fas fa-check-circle"></i> Status<br>
                            <strong>Done</strong>
                        </div>
                    </div>
                    <div class="text-center" style="margin-top: 1rem;">
                        <input type="text" id="renameInput" class="rename-input" value="watermarked.pdf" placeholder="Filename">
                        <button id="downloadBtn" class="btn-primary" style="margin-left: 0.5rem;">
                            <i class="fas fa-download"></i> Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let uploadedFile = null;
    let watermarkImageFile = null;
    let sessionId = null;
    let inProgress = false;

    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const fileInfoDiv = document.getElementById('fileInfo');
    const fileNameSpan = document.getElementById('fileName');
    const filePagesSpan = document.getElementById('filePages');
    const fileSizeSpan = document.getElementById('fileSize');
    const settingsPanel = document.getElementById('settingsPanel');
    const applyBtn = document.getElementById('applyBtn');
    const progressContainer = document.getElementById('progressContainer');
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    const resultPanel = document.getElementById('resultPanel');
    const originalSizeDisplay = document.getElementById('originalSizeDisplay');
    const watermarkedSizeDisplay = document.getElementById('watermarkedSizeDisplay');
    const renameInput = document.getElementById('renameInput');
    const downloadBtn = document.getElementById('downloadBtn');

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

    // Type toggle
    document.querySelectorAll('#typeGroup .radio-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#typeGroup .radio-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const type = btn.dataset.type;
            document.getElementById('textSettings').style.display = type === 'text' ? 'block' : 'none';
            document.getElementById('imageSettings').style.display = type === 'image' ? 'block' : 'none';
        });
    });

    // Position toggle
    document.querySelectorAll('#positionGroup .radio-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('#positionGroup .radio-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });

    // Sliders
    document.getElementById('fontSize').addEventListener('input', function() {
        document.getElementById('fontSizeVal').textContent = this.value + 'px';
    });
    document.getElementById('imageSize').addEventListener('input', function() {
        document.getElementById('imageSizeVal').textContent = this.value + 'px';
    });
    document.getElementById('opacity').addEventListener('input', function() {
        document.getElementById('opacityVal').textContent = this.value + '%';
    });
    document.getElementById('rotation').addEventListener('input', function() {
        document.getElementById('rotationVal').textContent = this.value + '°';
    });

    // Watermark image upload
    document.getElementById('watermarkImage').addEventListener('change', function() {
        if (this.files.length > 0) {
            watermarkImageFile = this.files[0];
        }
    });

    async function uploadFile(file) {
        if (file.type !== 'application/pdf') {
            showToast('Please select a PDF file', true);
            return false;
        }
        if (file.size > 20 * 1024 * 1024) {
            showToast('File exceeds 20MB limit', true);
            return false;
        }

        const formData = new FormData();
        formData.append('file', file);

        try {
            const res = await fetch('{{ route("pdf.watermark.upload") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            uploadedFile = {
                id: data.id,
                name: data.name,
                size: data.size,
                pages: data.pages
            };
            fileNameSpan.innerText = data.name;
            filePagesSpan.innerText = data.pages;
            fileSizeSpan.innerText = formatBytes(data.size);
            fileInfoDiv.style.display = 'block';
            settingsPanel.style.display = 'block';
            resultPanel.style.display = 'none';
            sessionId = null;
            return true;
        } catch (err) {
            showToast('Upload failed: ' + err.message, true);
            return false;
        }
    }

    function getSettings() {
        const activeType = document.querySelector('#typeGroup .radio-btn.active').dataset.type;
        const activePos = document.querySelector('#positionGroup .radio-btn.active').dataset.pos;

        const settings = {
            id: uploadedFile.id,
            type: activeType,
            opacity: parseInt(document.getElementById('opacity').value),
            rotation: parseInt(document.getElementById('rotation').value),
            position: activePos,
            pageRange: document.getElementById('pageRange').value.trim() || 'all',
        };

        if (activeType === 'text') {
            settings.text = document.getElementById('watermarkText').value || 'WATERMARK';
            settings.fontSize = parseInt(document.getElementById('fontSize').value);
            settings.color = document.getElementById('textColor').value;
        }

        return settings;
    }

    async function processWatermark() {
        if (!uploadedFile || inProgress) return;
        inProgress = true;
        applyBtn.disabled = true;
        progressContainer.style.display = 'block';
        progressFill.style.width = '20%';
        progressText.innerText = 'Applying watermark...';

        const formData = new FormData();
        formData.append('settings', JSON.stringify(getSettings()));

        if (watermarkImageFile) {
            formData.append('watermark_image', watermarkImageFile);
        }

        try {
            progressFill.style.width = '60%';
            const res = await fetch('{{ route("pdf.watermark.process") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await res.json();
            if (!data.success) throw new Error(data.error);

            sessionId = data.sessionId;
            originalSizeDisplay.innerText = formatBytes(data.originalSize);
            watermarkedSizeDisplay.innerText = formatBytes(data.watermarkedSize);
            resultPanel.style.display = 'block';

            progressFill.style.width = '100%';
            progressText.innerText = 'Complete!';
            showToast('Watermark applied successfully!');
        } catch (err) {
            showToast('Watermark failed: ' + err.message, true);
        } finally {
            setTimeout(() => {
                progressContainer.style.display = 'none';
                progressFill.style.width = '0%';
                inProgress = false;
                applyBtn.disabled = false;
            }, 1000);
        }
    }

    applyBtn.addEventListener('click', () => processWatermark());

    downloadBtn.addEventListener('click', async () => {
        if (!sessionId) return;
        let newName = renameInput.value.trim();
        if (!newName) newName = 'watermarked.pdf';
        if (!newName.endsWith('.pdf')) newName += '.pdf';

        try {
            const formData = new FormData();
            formData.append('sessionId', sessionId);
            formData.append('rename', newName);
            const res = await fetch('{{ route("pdf.watermark.download") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
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
    uploadZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadZone.classList.add('dragover');
    });
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