@extends('layouts.app')

@section('title', 'Free Online PDF Tools Hub – Merge, Split, Compress & Convert PDF | CypherLab')
@section('meta_description', 'Complete PDF toolkit online: merge PDFs, split pages, compress file size, convert Word to PDF, OCR text extraction & watermarking. 100% free by CypherLab.')

@push('schema')
{!! \App\Services\SeoService::generateSoftwareSchema('CypherLab PDF Tools Hub', 'Complete PDF toolkit: merge, split, compress, convert Word/Images, OCR, and watermark PDFs.', route('pdf.tools')) !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'PDF Tools' => route('pdf.tools'),
]) !!}
{!! \App\Services\SeoService::generateFaqSchema([
    'Are these PDF tools free?' => 'Yes, all CypherLab PDF utilities are completely free with no user registration or daily limits.',
    'Is PDF processing secure?' => 'Yes! Files are processed securely in temporary isolated memory and automatically deleted after processing.'
]) !!}
@endpush

@section('content')

<style>
    .saas-pdf-wrapper {
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

    .pdf-bg-grid {
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 50px 50px;
        pointer-events: none;
    }

    .pdf-ambient-glow {
        position: absolute;
        top: -150px;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.09) 0%, rgba(168, 85, 247, 0.04) 50%, transparent 70%);
        pointer-events: none;
    }

    .pdf-container {
        max-width: 1180px;
        margin: 0 auto;
        position: relative;
        z-index: 2;
    }

    /* HERO HEADER */
    .pdf-hero-box {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .pdf-hero-badge {
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

    .pdf-hero-title {
        font-size: clamp(2.2rem, 5vw, 3.4rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1.15;
        margin-bottom: 0.75rem;
        letter-spacing: -0.02em;
    }

    .pdf-hero-sub {
        color: var(--text-muted);
        font-size: 0.95rem;
        max-width: 650px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* STATS BAR */
    .pdf-stats-row {
        display: flex;
        justify-content: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }

    .pdf-stat-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 1rem;
        border-radius: 30px;
        background: rgba(8, 12, 24, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.8rem;
        font-weight: 600;
        color: #e2e8f0;
        font-family: 'Fira Code', monospace;
    }

    /* TOOL GRID */
    .pdf-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3.5rem;
    }

    .pdf-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 1.75rem;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.4);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        position: relative;
    }

    .pdf-card:hover {
        transform: translateY(-4px);
        border-color: var(--neon-blue);
        box-shadow: 0 25px 50px rgba(0, 212, 255, 0.15);
    }

    .pdf-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .pdf-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: radial-gradient(circle at 30% 30%, rgba(0, 212, 255, 0.2), rgba(168, 85, 247, 0.1));
        border: 1px solid rgba(0, 212, 255, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 1.25rem;
    }

    .status-pill {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        text-transform: uppercase;
    }

    .status-active {
        background: rgba(34, 197, 94, 0.12);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .status-maint {
        background: rgba(239, 68, 68, 0.12);
        color: #fca5a5;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .pdf-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 0.5rem;
    }

    .pdf-card-desc {
        color: var(--text-muted);
        font-size: 0.88rem;
        line-height: 1.55;
        margin-bottom: 1.25rem;
        flex: 1;
    }

    .pdf-features-list {
        list-style: none;
        padding: 0;
        margin: 0 0 1.5rem 0;
    }

    .pdf-features-list li {
        font-size: 0.8rem;
        color: #cbd5e1;
        margin-bottom: 0.45rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pdf-features-list li i {
        color: var(--neon-cyan);
        font-size: 0.75rem;
    }

    .pdf-btn-launch {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #00d4ff 0%, #06f7e3 100%);
        color: #070913;
        font-size: 0.85rem;
        font-weight: 700;
        font-family: 'Fira Code', monospace;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
    }

    .pdf-btn-launch:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.45);
        color: #070913;
    }

    .pdf-btn-disabled {
        pointer-events: none;
        opacity: 0.5;
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-muted);
        box-shadow: none;
    }

    /* FAQ CARD */
    .pdf-faq-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 3rem;
    }

    .pdf-faq-item {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 1rem 0;
    }

    .pdf-faq-item:last-child {
        border-bottom: none;
    }

    .pdf-faq-item summary {
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
        cursor: pointer;
        outline: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pdf-faq-item p {
        color: var(--text-muted);
        font-size: 0.88rem;
        margin-top: 0.5rem;
        line-height: 1.6;
    }

    @media (max-width: 850px) {
        .pdf-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="saas-pdf-wrapper">
    <div class="pdf-bg-grid"></div>
    <div class="pdf-ambient-glow"></div>

    <div class="pdf-container">
        
        <!-- HERO HEADER -->
        <div class="pdf-hero-box">
            <div class="pdf-hero-badge">
                <i class="fas fa-file-pdf me-1"></i> CYPHERLABS PDF SUITE
            </div>
            <h1 class="pdf-hero-title">
                All-in-One Professional <span class="grad-text">PDF Toolkit</span>
            </h1>
            <p class="pdf-hero-sub">
                Merge, split, compress, convert, OCR, and watermark PDF documents with sub-second performance and 100% client-side privacy.
            </p>

            <div class="pdf-stats-row">
                <div class="pdf-stat-pill"><i class="fas fa-shield-alt" style="color:var(--neon-blue);"></i> 100% Client Security</div>
                <div class="pdf-stat-pill"><i class="fas fa-hdd" style="color:var(--neon-cyan);"></i> Up to 50MB Files</div>
                <div class="pdf-stat-pill"><i class="fas fa-user-check" style="color:var(--neon-purple);"></i> Zero Registration</div>
                <div class="pdf-stat-pill"><i class="fas fa-history" style="color:var(--accent-gold);"></i> Auto-Purge Storage</div>
            </div>
        </div>

        <!-- TOOLS GRID -->
        <div class="pdf-grid">
            
            <!-- 1. PDF MERGE -->
            <div class="pdf-card">
                <div class="pdf-card-top">
                    <div class="pdf-card-icon"><i class="fas fa-object-group"></i></div>
                    <span class="status-pill status-active">ACTIVE READY</span>
                </div>
                <h3 class="pdf-card-title">PDF Merge Studio</h3>
                <p class="pdf-card-desc">Combine multiple PDF documents into a single unified file with drag & drop page reordering.</p>
                <ul class="pdf-features-list">
                    <li><i class="fas fa-check-circle"></i> Drag & drop multi-file upload</li>
                    <li><i class="fas fa-check-circle"></i> Custom page reordering & removal</li>
                    <li><i class="fas fa-check-circle"></i> Instant merged preview</li>
                </ul>
                <a href="{{ route('pdf.merge') }}" class="pdf-btn-launch">Launch PDF Merge <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- 2. PDF SPLIT -->
            <div class="pdf-card">
                <div class="pdf-card-top">
                    <div class="pdf-card-icon"><i class="fas fa-cut"></i></div>
                    <span class="status-pill status-active">ACTIVE READY</span>
                </div>
                <h3 class="pdf-card-title">PDF Split Studio</h3>
                <p class="pdf-card-desc">Extract specific pages or split PDFs by page ranges and intervals into downloadable ZIP archives.</p>
                <ul class="pdf-features-list">
                    <li><i class="fas fa-check-circle"></i> Split by custom page ranges</li>
                    <li><i class="fas fa-check-circle"></i> Extract individual pages</li>
                    <li><i class="fas fa-check-circle"></i> One-click ZIP batch export</li>
                </ul>
                <a href="{{ route('pdf.split') }}" class="pdf-btn-launch">Launch PDF Split <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- 3. WORD TO PDF -->
            <div class="pdf-card">
                <div class="pdf-card-top">
                    <div class="pdf-card-icon"><i class="fas fa-file-word"></i></div>
                    <span class="status-pill status-active">ACTIVE READY</span>
                </div>
                <h3 class="pdf-card-title">Word to PDF</h3>
                <p class="pdf-card-desc">Convert DOC and DOCX documents into clean, high-precision PDF files with formatting preserved.</p>
                <ul class="pdf-features-list">
                    <li><i class="fas fa-check-circle"></i> Preserves exact fonts & tables</li>
                    <li><i class="fas fa-check-circle"></i> Multi-page DOCX support</li>
                    <li><i class="fas fa-check-circle"></i> Sub-second conversion engine</li>
                </ul>
                <a href="{{ route('word.to-pdf') }}" class="pdf-btn-launch">Launch Word to PDF <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- 4. IMAGE TO PDF -->
            <div class="pdf-card">
                <div class="pdf-card-top">
                    <div class="pdf-card-icon"><i class="fas fa-images"></i></div>
                    <span class="status-pill status-active">ACTIVE READY</span>
                </div>
                <h3 class="pdf-card-title">Image to PDF</h3>
                <p class="pdf-card-desc">Convert JPG, PNG, and WebP images into a single structured PDF document with orientation control.</p>
                <ul class="pdf-features-list">
                    <li><i class="fas fa-check-circle"></i> Multi-image batch conversion</li>
                    <li><i class="fas fa-check-circle"></i> Portrait / Landscape orientation</li>
                    <li><i class="fas fa-check-circle"></i> Adjustable page margins</li>
                </ul>
                <a href="{{ route('image.to-pdf') }}" class="pdf-btn-launch">Launch Image to PDF <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- 5. OCR READER -->
            <div class="pdf-card">
                <div class="pdf-card-top">
                    <div class="pdf-card-icon"><i class="fas fa-eye"></i></div>
                    <span class="status-pill status-active">ACTIVE READY</span>
                </div>
                <h3 class="pdf-card-title">OCR PDF Reader</h3>
                <p class="pdf-card-desc">Extract searchable, editable text from scanned documents and images using browser Tesseract OCR.</p>
                <ul class="pdf-features-list">
                    <li><i class="fas fa-check-circle"></i> Multi-language OCR engine</li>
                    <li><i class="fas fa-check-circle"></i> Page-by-page text extraction</li>
                    <li><i class="fas fa-check-circle"></i> 1-Click copy & text export</li>
                </ul>
                <a href="{{ route('pdf.ocr') }}" class="pdf-btn-launch">Launch OCR Reader <i class="fas fa-arrow-right"></i></a>
            </div>

            <!-- 6. PDF COMPRESSOR -->
            <div class="pdf-card">
                <div class="pdf-card-top">
                    <div class="pdf-card-icon"><i class="fas fa-compress-alt"></i></div>
                    <span class="status-pill status-maint">MAINTENANCE</span>
                </div>
                <h3 class="pdf-card-title">PDF Compressor</h3>
                <p class="pdf-card-desc">Reduce PDF file size while preserving high visual resolution for fast email attachments.</p>
                <ul class="pdf-features-list">
                    <li><i class="fas fa-check-circle"></i> Low / Medium / High compression</li>
                    <li><i class="fas fa-check-circle"></i> Size savings comparison</li>
                </ul>
                <a href="{{ route('pdf.compress') }}" class="pdf-btn-launch pdf-btn-disabled">Under Maintenance</a>
            </div>

        </div>

        <!-- FAQ SECTION -->
        <div class="pdf-faq-card">
            <h3 style="font-size:1.25rem; font-weight:800; color:#ffffff; margin-bottom:1rem; display:flex; align-items:center; gap:10px;">
                <i class="fas fa-question-circle" style="color:var(--neon-blue);"></i> Frequently Asked Questions
            </h3>

            <details class="pdf-faq-item">
                <summary>Are my PDF documents stored or logged?</summary>
                <p>No. Processing happens inside browser memory or ephemeral runtime tasks. All temporary files auto-purge immediately.</p>
            </details>

            <details class="pdf-faq-item">
                <summary>What is the maximum file size limit?</summary>
                <p>You can upload and process PDF documents up to 50MB per file with zero speed throttling.</p>
            </details>

            <details class="pdf-faq-item">
                <summary>Do I need to create an account to use PDF tools?</summary>
                <p>No account or registration is required. All tools in the CypherLabs PDF Suite are 100% free.</p>
            </details>
        </div>

    </div>
</div>

@endsection