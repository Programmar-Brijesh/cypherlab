@extends('layouts.app')

@section('title', 'CypherLabs Articles & Engineering Tutorials | Developer Knowledge Hub')
@section('meta_description', 'Discover premium developer insights, tutorials, architecture guides, and tech tips on CypherLabs.')

@section('content')
<style>
    :root {
        --bg-base: #030712;
        --bg-surface: #0b0f19;
        --bg-card: rgba(15, 23, 42, 0.72);
        --bg-card-hover: rgba(22, 33, 58, 0.9);
        --border-glass: rgba(0, 212, 255, 0.14);
        --border-glass-hover: rgba(0, 212, 255, 0.45);
        --neon-blue: #00d4ff;
        --neon-purple: #a855f7;
        --neon-cyan: #06f7e3;
        --neon-pink: #ff2e9e;
        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --text-muted: #64748b;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 22px;
    }

    body {
        background-color: var(--bg-base) !important;
    }

    .blog-page-wrapper {
        color: var(--text-primary);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        padding-bottom: 3.5rem;
    }

    /* ==========================================================================
       1. HERO SECTION (Compact, Modern, Grid Glow)
       ========================================================================== */
    .blog-hero {
        position: relative;
        padding: 2.25rem 1.5rem 2rem;
        text-align: center;
        background: radial-gradient(circle at 50% 0%, rgba(0, 212, 255, 0.12) 0%, rgba(168, 85, 247, 0.05) 50%, transparent 80%);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-glass);
        margin-bottom: 2.25rem;
        overflow: hidden;
        backdrop-filter: blur(16px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .blog-hero-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0, 212, 255, 0.035) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.035) 1px, transparent 1px);
        background-size: 32px 32px;
        z-index: 0;
        pointer-events: none;
    }

    .blog-hero-content {
        position: relative;
        z-index: 2;
        max-width: 780px;
        margin: 0 auto;
    }

    .blog-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 5px 14px;
        border-radius: 30px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.25);
        color: var(--neon-blue);
        font-size: 0.76rem;
        font-weight: 600;
        letter-spacing: 0.6px;
        margin-bottom: 1rem;
        font-family: 'Fira Code', monospace;
    }

    .pulse-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--neon-blue);
        box-shadow: 0 0 8px var(--neon-blue);
        animation: pulseDot 2s ease-in-out infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    .blog-hero-title {
        font-size: 2.35rem;
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -0.75px;
        margin-bottom: 0.75rem;
        color: #ffffff;
    }

    .grad-text {
        background: linear-gradient(135deg, #00d4ff 0%, #a855f7 50%, #06f7e3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .blog-hero-sub {
        color: var(--text-secondary);
        font-size: 1rem;
        max-width: 620px;
        margin: 0 auto 1.5rem;
        line-height: 1.55;
    }

    /* SEARCH FORM & CONTROLS */
    .blog-controls-wrapper {
        max-width: 620px;
        margin: 0 auto;
    }

    .blog-search-form {
        position: relative;
        display: flex;
        align-items: center;
        background: rgba(10, 15, 30, 0.85);
        border: 1px solid var(--border-glass);
        border-radius: var(--radius-md);
        padding: 4px 6px 4px 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
        transition: all 0.25s ease;
    }

    .blog-search-form:focus-within {
        border-color: var(--neon-blue);
        box-shadow: 0 0 18px rgba(0, 212, 255, 0.28);
    }

    .search-icon-left {
        color: var(--text-muted);
        font-size: 0.95rem;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .blog-search-form input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: #ffffff;
        font-size: 0.92rem;
        padding: 10px 0;
    }

    .blog-search-form input::placeholder {
        color: var(--text-muted);
        font-size: 0.88rem;
    }

    .search-clear-btn {
        background: transparent;
        border: none;
        color: var(--text-muted);
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 4px;
        flex-shrink: 0;
    }

    .search-clear-btn:hover {
        color: #ffffff;
        background: rgba(255, 255, 255, 0.1);
    }

    .search-submit-btn {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(168, 85, 247, 0.2));
        border: 1px solid rgba(0, 212, 255, 0.35);
        color: var(--neon-blue);
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .search-submit-btn:hover {
        background: var(--neon-blue);
        color: #030712;
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.4);
    }

    /* HORIZONTALLY SCROLLABLE CATEGORY PILLS */
    .category-scroll-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 1.15rem;
        overflow-x: auto;
        padding: 4px 6px 6px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .category-scroll-container::-webkit-scrollbar {
        display: none;
    }

    .cat-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        background: rgba(15, 23, 42, 0.65);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--text-secondary);
        font-size: 0.8rem;
        font-weight: 500;
        text-decoration: none !important;
        white-space: nowrap;
        transition: all 0.2s ease;
        font-family: 'Fira Code', monospace;
        flex-shrink: 0;
    }

    .cat-pill:hover, .cat-pill.active {
        background: rgba(0, 212, 255, 0.14);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
    }

    .pill-count {
        background: rgba(255, 255, 255, 0.08);
        padding: 1px 6px;
        border-radius: 10px;
        font-size: 0.68rem;
        color: var(--text-muted);
    }

    .cat-pill.active .pill-count,
    .cat-pill:hover .pill-count {
        background: rgba(0, 212, 255, 0.25);
        color: #ffffff;
    }

    /* ==========================================================================
       SECTION HEADERS
       ========================================================================== */
    .section-title-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        padding-bottom: 0.65rem;
    }

    .section-heading {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-heading i {
        color: var(--neon-blue);
    }

    .section-meta-text {
        font-size: 0.82rem;
        color: var(--text-muted);
        font-family: 'Fira Code', monospace;
    }

    /* ==========================================================================
       2. EDITORIAL FEATURED ARTICLE SHOWCASE (Large, Prominent)
       ========================================================================== */
    .featured-showcase {
        margin-bottom: 3rem;
    }

    .featured-editorial-card {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        background: rgba(13, 18, 33, 0.85);
        border: 1px solid var(--border-glass);
        border-radius: var(--radius-xl);
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        text-decoration: none !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    }

    .featured-editorial-card:hover {
        border-color: var(--border-glass-hover);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(0, 212, 255, 0.15);
        transform: translateY(-4px);
    }

    .featured-media-col {
        position: relative;
        min-height: 320px;
        background: #070c17;
        overflow: hidden;
    }

    .featured-media-col img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .featured-editorial-card:hover .featured-media-col img {
        transform: scale(1.05);
    }

    .featured-placeholder-art {
        width: 100%;
        height: 100%;
        min-height: 320px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 50% 50%, rgba(0, 212, 255, 0.15) 0%, rgba(168, 85, 247, 0.08) 60%, #070c17 100%);
        color: var(--neon-blue);
        gap: 12px;
    }

    .featured-placeholder-art i {
        font-size: 3.5rem;
        opacity: 0.85;
    }

    .featured-overlay-shade {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right, transparent 50%, rgba(13, 18, 33, 0.8) 100%);
        pointer-events: none;
    }

    .featured-cat-tag {
        position: absolute;
        top: 14px;
        left: 14px;
        padding: 4px 10px;
        border-radius: 6px;
        background: rgba(10, 15, 30, 0.9);
        border: 1px solid rgba(0, 212, 255, 0.4);
        color: var(--neon-blue);
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
        font-family: 'Fira Code', monospace;
        letter-spacing: 0.5px;
        z-index: 2;
    }

    .featured-editorial-col {
        padding: 2rem 2.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .featured-kicker {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--neon-cyan);
        font-family: 'Fira Code', monospace;
        margin-bottom: 0.75rem;
    }

    .featured-main-title {
        font-size: 1.55rem;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 0.85rem;
        color: #ffffff;
    }

    .featured-main-title a {
        color: #ffffff;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }

    .featured-editorial-card:hover .featured-main-title a {
        color: var(--neon-blue);
    }

    .featured-main-excerpt {
        color: var(--text-secondary);
        font-size: 0.92rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .featured-meta-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--text-muted);
        font-size: 0.8rem;
    }

    .featured-author-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .author-badge-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 0.75rem;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.3);
    }

    .featured-author-name {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.85rem;
    }

    .featured-date-stat {
        display: flex;
        align-items: center;
        gap: 12px;
        font-family: 'Fira Code', monospace;
        font-size: 0.76rem;
    }

    .featured-cta-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--neon-blue);
        font-size: 0.84rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        text-decoration: none !important;
        transition: gap 0.25s ease, color 0.25s ease;
    }

    .featured-editorial-card:hover .featured-cta-link {
        color: #ffffff;
        gap: 9px;
    }

    /* ==========================================================================
       3. LATEST ARTICLES GRID (Responsive 3 col / 2 col / 1 col)
       ========================================================================== */
    .blog-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 3.5rem;
    }

    .blog-card {
        background: rgba(13, 18, 33, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--radius-lg);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        text-decoration: none !important;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.3);
    }

    .blog-card:hover {
        transform: translateY(-5px);
        border-color: var(--border-glass-hover);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 212, 255, 0.12);
        background: rgba(18, 25, 45, 0.88);
    }

    .blog-card-media {
        position: relative;
        width: 100%;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        background: #070c17;
    }

    .blog-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.45s ease;
    }

    .blog-card:hover .blog-card-media img {
        transform: scale(1.06);
    }

    .blog-card-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 50% 50%, rgba(0, 212, 255, 0.1) 0%, rgba(168, 85, 247, 0.05) 50%, #070c17 100%);
        color: var(--neon-blue);
        font-size: 2.2rem;
    }

    .blog-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(3, 7, 18, 0.05) 0%, rgba(3, 7, 18, 0.7) 100%);
        pointer-events: none;
    }

    .blog-card-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 3px 8px;
        border-radius: 5px;
        background: rgba(10, 15, 30, 0.85);
        border: 1px solid rgba(0, 212, 255, 0.3);
        color: var(--neon-blue);
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        backdrop-filter: blur(8px);
        font-family: 'Fira Code', monospace;
    }

    .blog-card-time {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 3px 8px;
        border-radius: 5px;
        background: rgba(10, 15, 30, 0.85);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: var(--text-secondary);
        font-size: 0.65rem;
        font-weight: 600;
        backdrop-filter: blur(8px);
        font-family: 'Fira Code', monospace;
    }

    .blog-card-body {
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .blog-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        color: #ffffff;
    }

    .blog-card-title a {
        color: #ffffff;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }

    .blog-card:hover .blog-card-title a {
        color: var(--neon-blue);
    }

    .blog-card-excerpt {
        color: var(--text-secondary);
        font-size: 0.84rem;
        line-height: 1.55;
        margin-bottom: 1rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .blog-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--text-muted);
        font-size: 0.76rem;
        margin-top: auto;
        padding-top: 0.85rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .card-meta-left {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .card-author-pill {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .card-stat-pill {
        font-family: 'Fira Code', monospace;
        font-size: 0.74rem;
        color: var(--text-muted);
    }

    .card-action-link {
        color: var(--neon-blue);
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-decoration: none !important;
        transition: gap 0.2s ease, color 0.2s ease;
        flex-shrink: 0;
    }

    .blog-card:hover .card-action-link {
        color: #ffffff;
        gap: 7px;
    }

    /* ==========================================================================
       EMPTY STATE
       ========================================================================== */
    .empty-state-box {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-card);
        border: 1px dashed var(--border-glass);
        border-radius: var(--radius-xl);
        max-width: 680px;
        margin: 1rem auto 2.5rem;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 1.25rem;
        border-radius: 50%;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 2rem;
    }

    .empty-state-title {
        color: #ffffff;
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .empty-state-desc {
        color: var(--text-muted);
        font-size: 0.92rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
        max-width: 480px;
        margin-left: auto;
        margin-right: auto;
    }

    .empty-reset-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(168, 85, 247, 0.15));
        border: 1px solid var(--border-glass-hover);
        color: var(--neon-blue);
        border-radius: var(--radius-md);
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.25s ease;
        font-family: 'Fira Code', monospace;
    }

    .empty-reset-btn:hover {
        background: var(--neon-blue);
        color: #030712;
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.4);
    }

    /* ==========================================================================
       4. POPULAR TOPICS SECTION
       ========================================================================== */
    .topics-section {
        margin-bottom: 3rem;
    }

    .topics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 1rem;
    }

    .topic-card {
        background: var(--bg-card);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: var(--radius-lg);
        padding: 1.25rem 1.15rem;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .topic-card:hover {
        border-color: var(--neon-blue);
        transform: translateY(-4px);
        background: rgba(18, 25, 45, 0.9);
        box-shadow: 0 8px 20px rgba(0, 212, 255, 0.16);
    }

    .topic-card.active {
        border-color: var(--neon-blue);
        background: rgba(0, 212, 255, 0.12);
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.22);
    }

    .topic-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(10, 15, 30, 0.85);
        border: 1px solid rgba(0, 212, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--neon-blue);
        font-size: 1.35rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .topic-card:hover .topic-icon-wrap {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(168, 85, 247, 0.2));
        color: #ffffff;
        border-color: var(--neon-blue);
    }

    .topic-info {
        flex: 1;
        min-width: 0;
    }

    .topic-title {
        font-weight: 700;
        font-size: 0.92rem;
        color: #ffffff;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .topic-count {
        font-size: 0.74rem;
        color: var(--text-muted);
        font-family: 'Fira Code', monospace;
    }

    .topic-arrow {
        color: var(--text-muted);
        font-size: 0.75rem;
        transition: transform 0.25s ease, color 0.25s ease;
    }

    .topic-card:hover .topic-arrow {
        color: var(--neon-blue);
        transform: translateX(3px);
    }

    /* ==========================================================================
       PAGINATION STYLING
       ========================================================================== */
    .blog-pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
        margin-bottom: 3rem;
    }

    .blog-pagination-wrapper .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
        align-items: center;
        flex-wrap: wrap;
        justify-content: center;
    }

    .blog-pagination-wrapper .page-item .page-link {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--text-secondary);
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-family: 'Fira Code', monospace;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
    }

    .blog-pagination-wrapper .page-item.active .page-link {
        background: rgba(0, 212, 255, 0.16);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.25);
        font-weight: 700;
    }

    .blog-pagination-wrapper .page-item:not(.active):not(.disabled) .page-link:hover {
        background: rgba(30, 41, 59, 0.9);
        border-color: rgba(0, 212, 255, 0.4);
        color: #ffffff;
    }

    .blog-pagination-wrapper .page-item.disabled .page-link {
        background: rgba(15, 23, 42, 0.4);
        border-color: rgba(255, 255, 255, 0.04);
        color: var(--text-muted);
        cursor: not-allowed;
        opacity: 0.5;
    }

    /* ==========================================================================
       RESPONSIVE BREAKPOINTS (320px, 375px, 768px, 1024px, 1440px)
       ========================================================================== */
    @media (max-width: 1024px) {
        .blog-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }
        .featured-editorial-card {
            grid-template-columns: 1fr;
        }
        .featured-media-col {
            min-height: 240px;
        }
        .featured-editorial-col {
            padding: 1.5rem 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .blog-hero {
            padding: 1.75rem 1rem 1.5rem;
            margin-bottom: 1.75rem;
        }
        .blog-hero-title {
            font-size: 1.85rem;
        }
        .blog-hero-sub {
            font-size: 0.92rem;
            margin-bottom: 1.25rem;
        }
        .category-scroll-container {
            justify-content: flex-start;
            padding-left: 2px;
            padding-right: 2px;
        }
        .blog-grid {
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }
        .topics-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }

    @media (max-width: 480px) {
        .blog-hero {
            padding: 1.5rem 0.85rem 1.25rem;
        }
        .blog-hero-title {
            font-size: 1.5rem;
        }
        .featured-media-col {
            min-height: 200px;
        }
        .featured-main-title {
            font-size: 1.25rem;
        }
        .featured-meta-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .section-title-wrap {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
    }
</style>

@php
    $categoryIcons = [
        'laravel' => 'fab fa-laravel',
        'php' => 'fab fa-php',
        'javascript' => 'fab fa-js',
        'js' => 'fab fa-js',
        'vue' => 'fab fa-vuejs',
        'react' => 'fab fa-react',
        'python' => 'fab fa-python',
        'tech' => 'fas fa-microchip',
        'technology' => 'fas fa-laptop-code',
        'ai' => 'fas fa-brain',
        'artificial-intelligence' => 'fas fa-robot',
        'devops' => 'fas fa-server',
        'cloud' => 'fas fa-cloud',
        'database' => 'fas fa-database',
        'security' => 'fas fa-shield-halved',
        'lifestyle' => 'fas fa-mug-hot',
        'tutorials' => 'fas fa-book-open',
        'engineering' => 'fas fa-code-branch',
    ];
@endphp

<div class="blog-page-wrapper">

    <!-- 1. HERO SECTION -->
    <section class="blog-hero">
        <div class="blog-hero-grid"></div>
        <div class="blog-hero-content">
            <div class="blog-badge">
                <span class="pulse-dot"></span> CYPHERLABS KNOWLEDGE HUB
            </div>
            <h1 class="blog-hero-title">
                Engineering Insights & <span class="grad-text">Developer Guides</span>
            </h1>
            <p class="blog-hero-sub">
                Deep architectural breakdowns, modern engineering workflows, and hands-on developer tutorials.
            </p>

            <div class="blog-controls-wrapper">
                <form method="GET" action="{{ route('blog.index') }}" class="blog-search-form" id="blogSearchForm">
                    @if(!empty($categorySlug))
                        <input type="hidden" name="category" value="{{ $categorySlug }}">
                    @endif
                    <i class="fas fa-search search-icon-left"></i>
                    <input type="text"
                           name="search"
                           id="blogSearchInput"
                           value="{{ $search ?? '' }}"
                           placeholder="Search articles by title, topic, or keyword..."
                           autocomplete="off">
                    <button type="button"
                            class="search-clear-btn"
                            id="searchClearBtn"
                            title="Clear search"
                            style="display: {{ !empty($search) ? 'flex' : 'none' }};">
                        <i class="fas fa-times"></i>
                    </button>
                    <button type="submit" class="search-submit-btn" title="Search">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <!-- Horizontally Scrollable Category Filter Pills -->
                <div class="category-scroll-container">
                    <a href="{{ route('blog.index', array_filter(['search' => $search])) }}"
                       class="cat-pill {{ empty($categorySlug) ? 'active' : '' }}">
                        <span>All Posts</span>
                        <span class="pill-count">{{ $totalPublishedPosts ?? $categories->sum('builder_posts_count') }}</span>
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('blog.index', array_filter(['category' => $category->slug, 'search' => $search])) }}"
                           class="cat-pill {{ $categorySlug === $category->slug ? 'active' : '' }}">
                            <span>{{ $category->name }}</span>
                            <span class="pill-count">{{ $category->builder_posts_count }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- 2. EDITORIAL FEATURED ARTICLE SPOTLIGHT (Single Hero Card, No Duplication) -->
    @if($featuredPost)
    <section class="featured-showcase">
        <div class="section-title-wrap">
            <div class="section-heading">
                <i class="fas fa-bolt text-cyan"></i> Featured Editorial
            </div>
            <div class="section-meta-text">Handpicked by CypherLabs Engineers</div>
        </div>

        <article class="featured-editorial-card">
            <div class="featured-media-col">
                @if($featuredPost->featured_image_url)
                    <img src="{{ $featuredPost->featured_image_url }}" alt="{{ $featuredPost->title }}" loading="lazy">
                @else
                    <div class="featured-placeholder-art">
                        <i class="fas fa-terminal"></i>
                        <span style="font-family:'Fira Code', monospace; font-size: 0.8rem; letter-spacing: 1px;">CYPHERLABS ARCHITECTURE</span>
                    </div>
                @endif
                <div class="featured-overlay-shade"></div>
                <span class="featured-cat-tag">
                    {{ $featuredPost->category->name ?? ($featuredPost->schema_type ?? 'Engineering') }}
                </span>
            </div>

            <div class="featured-editorial-col">
                <div>
                    <div class="featured-kicker">
                        <i class="fas fa-star"></i> Spotlight Story
                    </div>
                    <h2 class="featured-main-title">
                        <a href="{{ route('article.show', $featuredPost->slug) }}">
                            {{ $featuredPost->title }}
                        </a>
                    </h2>
                    <p class="featured-main-excerpt">
                        {{ $featuredPost->excerpt ?: ($featuredPost->meta_description ?: 'Explore in-depth architectural patterns, modern framework techniques, and high-performance practices in this engineering deep dive.') }}
                    </p>
                </div>

                <div class="featured-meta-bar">
                    <div class="featured-author-info">
                        <div class="author-badge-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <div>
                            <div class="featured-author-name">{{ $featuredPost->author->name ?? 'CypherLabs Team' }}</div>
                            <div class="featured-date-stat">
                                <span><i class="far fa-calendar-alt me-1"></i>{{ ($featuredPost->published_at ?: $featuredPost->created_at)->format('M d, Y') }}</span>
                                <span><i class="far fa-clock me-1"></i>{{ $featuredPost->estimated_reading_time }} min read</span>
                                <span><i class="far fa-eye me-1"></i>{{ number_format($featuredPost->views) }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('article.show', $featuredPost->slug) }}" class="featured-cta-link">
                        <span>Read Full Article</span> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </article>
    </section>
    @endif

    <!-- 3. LATEST ARTICLES GRID -->
    <section class="latest-articles-section">
        <div class="section-title-wrap">
            <div class="section-heading">
                @if(!empty($selectedCategory))
                    <i class="fas fa-filter text-cyan"></i> Articles in {{ $selectedCategory->name }}
                @elseif(!empty($search))
                    <i class="fas fa-search text-cyan"></i> Search Results for "{{ $search }}"
                @else
                    <i class="fas fa-layer-group text-cyan"></i> Latest Articles
                @endif
            </div>
            <div class="section-meta-text">
                {{ $posts->total() }} {{ Str::plural('Article', $posts->total()) }}
            </div>
        </div>

        <div class="blog-grid" id="blogGrid">
            @forelse($posts as $post)
            <article class="blog-card"
                     data-title="{{ strtolower($post->title) }}"
                     data-excerpt="{{ strtolower($post->excerpt ?: ($post->meta_description ?: '')) }}">
                <div class="blog-card-media">
                    @if($post->featured_image_url)
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" loading="lazy">
                    @else
                        <div class="blog-card-placeholder">
                            <i class="fas fa-code"></i>
                        </div>
                    @endif
                    <div class="blog-card-overlay"></div>
                    <span class="blog-card-badge">
                        {{ $post->category->name ?? ($post->schema_type ?? 'Tutorial') }}
                    </span>
                    <span class="blog-card-time">
                        <i class="far fa-clock me-1"></i> {{ $post->estimated_reading_time }} min
                    </span>
                </div>

                <div class="blog-card-body">
                    <h3 class="blog-card-title">
                        <a href="{{ route('article.show', $post->slug) }}">
                            {{ Str::limit($post->title, 64) }}
                        </a>
                    </h3>
                    <p class="blog-card-excerpt">
                        {{ Str::limit($post->excerpt ?: ($post->meta_description ?: 'Learn essential principles, real-world implementations, and production insights.'), 95) }}
                    </p>

                    <div class="blog-card-footer">
                        <div class="card-meta-left">
                            <span class="card-author-pill">
                                <i class="fas fa-terminal me-1" style="font-size: 0.65rem; color: var(--neon-blue);"></i>
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                            </span>
                            <span class="card-stat-pill ms-2">
                                <i class="far fa-eye me-1"></i>{{ number_format($post->views) }}
                            </span>
                        </div>
                        <a href="{{ route('article.show', $post->slug) }}" class="card-action-link">
                            <span>Read</span> <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="empty-state-box" id="emptyStateBox">
                <div class="empty-state-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="empty-state-title">No Articles Found</h3>
                <p class="empty-state-desc">
                    @if(!empty($search) || !empty($categorySlug))
                        We couldn't find any articles matching your search query or filter. Try a different keyword or reset to browse all articles.
                    @else
                        We are currently crafting fresh engineering guides and developer tutorials. Check back soon!
                    @endif
                </p>
                @if(!empty($search) || !empty($categorySlug))
                    <a href="{{ route('blog.index') }}" class="empty-reset-btn">
                        <i class="fas fa-rotate-left"></i> View All Articles
                    </a>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($posts->hasPages())
        <div class="blog-pagination-wrapper">
            {{ $posts->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </section>

    <!-- 4. POPULAR TOPICS (Only Existing Database Categories) -->
    @if($categories->isNotEmpty())
    <section class="topics-section">
        <div class="section-title-wrap">
            <div class="section-heading">
                <i class="fas fa-cubes text-cyan"></i> Popular Topics
            </div>
            <div class="section-meta-text">Browse By Domain</div>
        </div>

        <div class="topics-grid">
            @foreach($categories as $cat)
                @php
                    $slugKey = strtolower($cat->slug);
                    $iconClass = $categoryIcons[$slugKey] ?? 'fas fa-folder-open';
                @endphp
                <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                   class="topic-card {{ $categorySlug === $cat->slug ? 'active' : '' }}"
                   title="View {{ $cat->name }} guides">
                    <div class="topic-icon-wrap">
                        <i class="{{ $iconClass }}"></i>
                    </div>
                    <div class="topic-info">
                        <div class="topic-title">{{ $cat->name }}</div>
                        <div class="topic-count">
                            {{ $cat->builder_posts_count }} {{ Str::plural('Guide', $cat->builder_posts_count) }}
                        </div>
                    </div>
                    <div class="topic-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('blogSearchInput');
    const searchClearBtn = document.getElementById('searchClearBtn');
    const blogCards = document.querySelectorAll('.blog-card');
    const emptyStateBox = document.getElementById('emptyStateBox');
    const blogGrid = document.getElementById('blogGrid');

    // Handle clear button visibility & action
    if (searchInput && searchClearBtn) {
        searchInput.addEventListener('input', function() {
            if (this.value.trim().length > 0) {
                searchClearBtn.style.display = 'flex';
            } else {
                searchClearBtn.style.display = 'none';
            }

            // Real-time instant filtering of currently loaded grid cards
            const query = this.value.trim().toLowerCase();
            let visibleCount = 0;

            blogCards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                const excerpt = card.getAttribute('data-excerpt') || '';
                if (query === '' || title.includes(query) || excerpt.includes(query)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // If empty dynamically during live typing, show/hide empty placeholder if needed
            if (blogCards.length > 0) {
                let liveEmpty = document.getElementById('liveEmptyState');
                if (visibleCount === 0 && query !== '') {
                    if (!liveEmpty) {
                        liveEmpty = document.createElement('div');
                        liveEmpty.id = 'liveEmptyState';
                        liveEmpty.className = 'empty-state-box';
                        liveEmpty.innerHTML = `
                            <div class="empty-state-icon"><i class="fas fa-search"></i></div>
                            <h3 class="empty-state-title">No Matching Articles</h3>
                            <p class="empty-state-desc">No articles match "<strong>${query.replace(/</g, '&lt;')}</strong>" in the current view.</p>
                            <button type="button" class="empty-reset-btn" id="liveClearBtn">
                                <i class="fas fa-times"></i> Clear Search
                            </button>
                        `;
                        blogGrid.appendChild(liveEmpty);
                        document.getElementById('liveClearBtn').addEventListener('click', function() {
                            searchInput.value = '';
                            searchClearBtn.style.display = 'none';
                            blogCards.forEach(c => c.style.display = 'flex');
                            liveEmpty.remove();
                            searchInput.focus();
                        });
                    }
                } else if (liveEmpty) {
                    liveEmpty.remove();
                }
            }
        });

        searchClearBtn.addEventListener('click', function() {
            searchInput.value = '';
            searchClearBtn.style.display = 'none';
            blogCards.forEach(c => c.style.display = 'flex');
            const liveEmpty = document.getElementById('liveEmptyState');
            if (liveEmpty) liveEmpty.remove();

            // If a server-side search query was in URL, submit form to reset cleanly
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('search')) {
                urlParams.delete('search');
                window.location.search = urlParams.toString();
            } else {
                searchInput.focus();
            }
        });
    }
});
</script>
@endsection
