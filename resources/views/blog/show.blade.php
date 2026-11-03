@extends('layouts.app')

@section('title', $post->title . ' – CypherLabs')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? $post->content), 160))
@section('main_class', 'w-100 p-0 m-0')

@section('content')

<style>
    /* ============================================
       ULTIMATE FULL-WIDTH RESPONSIVE BLOG EXPERIENCE
       ============================================ */

    :root {
        --blog-bg: #070913;
        --blog-card-bg: rgba(13, 18, 33, 0.85);
        --blog-glass-bg: rgba(13, 18, 33, 0.75);
        --blog-border: rgba(0, 212, 255, 0.15);
        --blog-border-hover: rgba(0, 212, 255, 0.4);
        
        --text-main: #F8FAFC;
        --text-muted: #94A3B8;
        --text-subtle: #64748B;
        
        --neon-blue: #00D4FF;
        --neon-cyan: #06F7E3;
        --neon-pink: #FF2E9E;
        --accent-glow: rgba(0, 212, 255, 0.35);
        
        --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        --font-mono: 'Fira Code', 'JetBrains Mono', Consolas, monospace;
        
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 18px;
        --radius-xl: 24px;
    }

    /* Core Layout Strategy - Full Width Body up to 1400px */
    .blog-modern-layout {
        background-color: var(--blog-bg);
        color: var(--text-main);
        font-family: var(--font-sans);
        position: relative;
        min-height: 100vh;
        width: 100%;
        overflow-x: hidden;
        padding: 2.5rem 0 5rem;
        background-image: 
            radial-gradient(circle at 15% 30%, rgba(0, 212, 255, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 85% 60%, rgba(255, 46, 158, 0.04) 0%, transparent 50%);
    }

    /* 1400px centered container for the blog */
    .blog-page-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1.25rem;
    }
    @media (min-width: 576px) { .blog-page-container { padding: 0 1.5rem; } }
    @media (min-width: 992px) { .blog-page-container { padding: 0 2rem; } }
    @media (min-width: 1400px) { .blog-page-container { padding: 0 2.5rem; } }

    /* Two-column grid: article + sidebar */
    .blog-page-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 992px) {
        .blog-page-grid {
            grid-template-columns: minmax(0, 1fr) 320px;
            gap: 2.5rem;
        }
    }
    @media (min-width: 1200px) {
        .blog-page-grid {
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 3rem;
        }
    }

    /* Sidebar Sticky */
    .sidebar-scroll-area {
        position: sticky;
        top: 90px;
        height: fit-content;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Reading Progress Bar */
    .reading-progress-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: transparent;
        z-index: 1000;
    }
    .reading-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, var(--neon-blue), var(--neon-cyan), var(--neon-pink));
        width: 0%;
        transition: width 0.1s ease-out;
        box-shadow: 0 0 10px var(--neon-cyan);
    }

    /* Hero Section */
    .hero-header {
        margin-bottom: 2.5rem;
        animation: fadeUp 0.8s ease-out forwards;
        width: 100%;
    }

    .hero-category {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.3);
        color: var(--neon-blue);
        padding: 0.4rem 1rem;
        border-radius: 30px;
        margin-bottom: 1.25rem;
        font-family: var(--font-mono);
    }

    .hero-title {
        font-size: clamp(2.2rem, 5vw, 3.8rem);
        font-weight: 800;
        line-height: 1.16;
        letter-spacing: -0.02em;
        margin-bottom: 1.25rem;
        color: #ffffff;
        width: 100%;
    }

    .hero-meta-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--blog-border);
        font-family: var(--font-mono);
    }

    .hero-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .hero-meta-item i { color: var(--neon-blue); }

    .hero-image-wrapper {
        width: 100%;
        border-radius: var(--radius-lg);
        overflow: hidden;
        border: 1px solid var(--blog-border);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.5);
        margin-bottom: 2.5rem;
    }
    .hero-image-wrapper img {
        width: 100%;
        height: auto;
        max-height: 600px;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .hero-image-wrapper:hover img {
        transform: scale(1.02);
    }

    /* ============================================
       STRICT CODE ISOLATION & FULL-WIDTH OVERRIDES FOR POST CONTENT
       ============================================ */
    .blog-content-isolated {
        isolation: isolate;
        contain: layout style;
        font-size: 1.1rem;
        line-height: 1.85;
        color: var(--text-muted);
        width: 100% !important;
        max-width: 100% !important;
        word-break: break-word;
    }

    /* Force all inner divs, cards, containers, sections inside post content to expand to 100% full width */
    .blog-content-isolated,
    .blog-content-isolated *,
    .blog-content-isolated [class*="container"],
    .blog-content-isolated [class*="wrapper"],
    .blog-content-isolated [class*="card"],
    .blog-content-isolated [class*="box"] {
        max-width: 100% !important;
        box-sizing: border-box !important;
    }

    .blog-content-isolated div,
    .blog-content-isolated section,
    .blog-content-isolated article {
        width: 100% !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .blog-content-isolated p {
        margin-bottom: 1.5rem;
        color: #E2E8F0;
        width: 100% !important;
    }

    .blog-content-isolated h1,
    .blog-content-isolated h2, 
    .blog-content-isolated h3, 
    .blog-content-isolated h4,
    .blog-content-isolated h5,
    .blog-content-isolated h6 {
        color: #ffffff !important;
        font-weight: 800 !important;
        margin-top: 2.5rem !important;
        margin-bottom: 1rem !important;
        line-height: 1.3 !important;
        letter-spacing: -0.01em !important;
        width: 100% !important;
    }

    .blog-content-isolated h2 { font-size: 1.85rem !important; border-bottom: 1px solid var(--blog-border) !important; padding-bottom: 0.5rem !important; }
    .blog-content-isolated h3 { font-size: 1.45rem !important; }
    .blog-content-isolated h4 { font-size: 1.25rem !important; }

    .blog-content-isolated a {
        color: var(--neon-blue) !important;
        text-decoration: none !important;
        border-bottom: 1px solid transparent !important;
        transition: var(--transition-smooth) !important;
    }
    .blog-content-isolated a:hover {
        border-bottom-color: var(--neon-blue) !important;
        text-shadow: 0 0 8px var(--accent-glow) !important;
    }

    .blog-content-isolated ul, 
    .blog-content-isolated ol {
        margin-bottom: 1.5rem !important;
        padding-left: 1.5rem !important;
        color: #E2E8F0 !important;
    }
    .blog-content-isolated li { margin-bottom: 0.5rem !important; }

    .blog-content-isolated blockquote {
        margin: 2rem 0 !important;
        padding: 1.5rem 2rem !important;
        background: rgba(0, 212, 255, 0.05) !important;
        border-left: 4px solid var(--neon-blue) !important;
        border-radius: 0 var(--radius-md) var(--radius-md) 0 !important;
        font-style: italic !important;
        color: #F1F5F9 !important;
        font-size: 1.15rem !important;
        width: 100% !important;
    }

    .blog-content-isolated img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: var(--radius-md) !important;
        border: 1px solid var(--blog-border) !important;
        margin: 2rem 0 !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
    }

    /* Isolated Code Blocks */
    .blog-content-isolated pre {
        background: #0B0E1B !important;
        border: 1px solid var(--blog-border) !important;
        border-radius: var(--radius-md) !important;
        padding: 1.25rem !important;
        overflow-x: auto !important;
        margin: 1.5rem 0 !important;
        font-family: var(--font-mono) !important;
        font-size: 0.9rem !important;
        color: #E2E8F0 !important;
        width: 100% !important;
    }
    .blog-content-isolated code {
        font-family: var(--font-mono) !important;
        background: rgba(0, 212, 255, 0.1) !important;
        padding: 0.2rem 0.4rem !important;
        border-radius: 4px !important;
        font-size: 0.85em !important;
        color: var(--neon-cyan) !important;
    }
    .blog-content-isolated pre code {
        background: transparent !important;
        padding: 0 !important;
        color: inherit !important;
    }

    .blog-content-isolated hr {
        border: 0 !important;
        height: 1px !important;
        background: linear-gradient(90deg, transparent, var(--blog-border), transparent) !important;
        margin: 3rem 0 !important;
    }

    .blog-content-isolated table {
        width: 100% !important;
        margin: 2rem 0 !important;
        border-collapse: collapse !important;
        overflow-x: auto !important;
        display: block !important;
    }
    .blog-content-isolated th, .blog-content-isolated td {
        border: 1px solid var(--blog-border) !important;
        padding: 1rem !important;
        text-align: left !important;
    }
    .blog-content-isolated th {
        background: rgba(0, 212, 255, 0.08) !important;
        color: #ffffff !important;
        font-weight: 700 !important;
    }

    /* Tags & Navigation */
    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid var(--blog-border);
    }
    .premium-tag {
        font-size: 0.8rem;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        background: var(--blog-card-bg);
        border: 1px solid var(--blog-border);
        color: var(--text-muted);
        text-decoration: none;
        font-family: var(--font-mono);
        transition: var(--transition-smooth);
    }
    .premium-tag:hover {
        background: rgba(0, 212, 255, 0.12);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
    }

    .article-nav {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-top: 3rem;
    }
    .nav-card {
        display: flex;
        flex-direction: column;
        padding: 1.5rem;
        background: var(--blog-card-bg);
        border: 1px solid var(--blog-border);
        border-radius: var(--radius-md);
        text-decoration: none;
        transition: var(--transition-smooth);
    }
    .nav-card:hover {
        border-color: var(--blog-border-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
    }
    .nav-card.prev-card { align-items: flex-start; text-align: left; }
    .nav-card.next-card { align-items: flex-end; text-align: right; }
    
    .nav-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--neon-blue);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: var(--font-mono);
    }
    .nav-title {
        font-weight: 700;
        color: #ffffff;
        font-size: 1rem;
        line-height: 1.4;
    }

    /* Related Articles */
    .related-section {
        margin-top: 4rem;
        padding-top: 2.5rem;
        border-top: 1px solid var(--blog-border);
    }
    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .related-card {
        background: var(--blog-card-bg);
        border: 1px solid var(--blog-border);
        border-radius: var(--radius-md);
        overflow: hidden;
        transition: var(--transition-smooth);
        display: flex;
        flex-direction: column;
        text-decoration: none;
    }
    .related-card:hover {
        transform: translateY(-4px);
        border-color: var(--neon-blue);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
    }
    .related-card-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        border-bottom: 1px solid var(--blog-border);
    }
    .related-card-body {
        padding: 1.2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .related-card-category {
        font-size: 0.7rem;
        color: var(--neon-blue);
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-family: var(--font-mono);
    }
    .related-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.4;
        margin-bottom: 0.5rem;
    }
    .related-card-excerpt {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
        flex: 1;
    }
    .related-card-link {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--neon-cyan);
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: auto;
        font-family: var(--font-mono);
    }

    /* Right Sidebar Widgets */
    .sidebar-widget {
        background: var(--blog-glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--blog-border);
        border-radius: var(--radius-md);
        padding: 1.25rem 1.5rem;
    }

    /* AI Button */
    .btn-ai-create {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 0.9rem;
        border-radius: var(--radius-md);
        background: linear-gradient(135deg, rgba(255, 46, 158, 0.15), rgba(0, 212, 255, 0.15));
        border: 1px solid rgba(0, 212, 255, 0.4);
        color: #ffffff;
        font-weight: 700;
        font-family: var(--font-mono);
        text-decoration: none;
        transition: var(--transition-smooth);
    }
    .btn-ai-create:hover {
        background: linear-gradient(135deg, rgba(255, 46, 158, 0.25), rgba(0, 212, 255, 0.25));
        border-color: var(--neon-blue);
        box-shadow: 0 0 25px rgba(0, 212, 255, 0.3);
        color: #ffffff;
    }

    /* Reading Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .stat-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .stat-label { font-size: 0.7rem; color: var(--text-subtle); text-transform: uppercase; font-family: var(--font-mono); }
    .stat-value { font-size: 0.9rem; font-weight: 700; color: #ffffff; }

    /* TOC */
    .toc-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 1rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: var(--font-mono);
    }
    .toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .toc-list li {
        margin-bottom: 0.4rem;
    }
    .toc-list a {
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.85rem;
        transition: var(--transition-smooth);
        display: block;
        padding: 4px 0;
        border-left: 2px solid transparent;
        padding-left: 10px;
    }
    .toc-list a:hover, .toc-list a.active {
        color: var(--neon-blue);
        border-left-color: var(--neon-blue);
        background: rgba(0, 212, 255, 0.05);
    }
    .toc-list li.h3 a { padding-left: 20px; font-size: 0.8rem; }

    /* Author Card */
    .author-mini-profile {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .author-avatar {
        width: 46px;
        height: 46px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--neon-blue);
    }
    .author-info h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
    }
    .author-info p {
        margin: 0;
        font-size: 0.8rem;
        color: var(--text-subtle);
    }
    .author-bio {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 1rem;
    }
    .social-links {
        display: flex;
        gap: 0.5rem;
    }
    .social-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-muted);
        text-decoration: none;
        font-size: 0.85rem;
        transition: var(--transition-smooth);
    }
    .social-btn:hover {
        background: var(--neon-blue);
        color: #070913;
    }

    /* Newsletter */
    .newsletter-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #ffffff;
    }
    .newsletter-desc {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }
    .newsletter-input {
        width: 100%;
        background: rgba(8, 12, 24, 0.9);
        border: 1px solid var(--blog-border);
        color: #ffffff;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        margin-bottom: 0.5rem;
        font-size: 0.85rem;
        font-family: var(--font-mono);
    }
    .newsletter-input:focus {
        outline: none;
        border-color: var(--neon-blue);
    }
    .newsletter-btn {
        width: 100%;
        background: linear-gradient(135deg, var(--neon-blue), var(--neon-cyan));
        color: #070913;
        border: none;
        padding: 0.75rem;
        border-radius: var(--radius-sm);
        font-weight: 700;
        font-family: var(--font-mono);
        cursor: pointer;
        transition: var(--transition-smooth);
    }
    .newsletter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 212, 255, 0.3);
    }

    /* Floating Action Buttons */
    .fab-container {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        display: flex;
        flex-direction: column;
        gap: 10px;
        z-index: 50;
    }
    .fab-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: var(--blog-glass-bg);
        backdrop-filter: blur(8px);
        border: 1px solid var(--blog-border);
        color: var(--neon-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-smooth);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
    }
    .fab-btn:hover {
        background: var(--neon-blue);
        color: #070913;
        transform: translateY(-2px);
    }

    /* Animations */
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="blog-modern-layout">
    <div class="reading-progress-container">
        <div class="reading-progress-bar" id="progressBar"></div>
    </div>

    <div class="fab-container">
        <button class="fab-btn" onclick="scrollToTop()" title="Back to Top"><i class="fas fa-arrow-up"></i></button>
        <button class="fab-btn" onclick="copyLink()" title="Copy Link"><i class="fas fa-link"></i></button>
        <button class="fab-btn" onclick="bookmarkPage()" title="Bookmark"><i class="far fa-bookmark"></i></button>
    </div>

    <!-- 1400px CENTERED BLOG CONTAINER -->
    <div class="blog-page-container">
        <div class="blog-page-grid">
            
            <!-- MAIN ARTICLE COLUMN -->
            <div style="min-width:0; width:100%;">
                    
                    <div class="hero-header">
                        @if($post->category)
                            <span class="hero-category"><i class="fas fa-folder-open"></i> {{ $post->category->name }}</span>
                        @endif
                        
                        <h1 class="hero-title">{{ $post->title }}</h1>
                        
                        <div class="hero-meta-row">
                            <div class="hero-meta-item">
                                <i class="far fa-user-circle"></i>
                                <span>{{ $post->author->name ?? 'CypherLabs Team' }}</span>
                            </div>
                            <div class="hero-meta-item">
                                <i class="far fa-calendar-alt"></i>
                                <span>{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="hero-meta-item">
                                <i class="far fa-clock"></i>
                                <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                            </div>
                            <div class="hero-meta-item">
                                <i class="far fa-eye"></i>
                                <span>{{ $post->views ?? 0 }} views</span>
                            </div>
                        </div>

                        @if($post->featured_image)
                        <div class="hero-image-wrapper">
                            <img src="{{ asset('uploads/blog/' . $post->featured_image) }}" alt="{{ $post->title }}">
                        </div>
                        @endif
                    </div>

                    <!-- SANITIZED & ISOLATED POST CONTENT (PREVENTS INNER HTML/CSS/JS LEAKAGE AND COMPRESSION) -->
                    @php
                        $cleanPostContent = $post->content;
                        // Strip <style> tags to avoid overriding host page styles or enforcing narrow width
                        $cleanPostContent = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $cleanPostContent);
                        // Strip <script> tags for security and layout stability
                        $cleanPostContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $cleanPostContent);
                        // Remove inline width/max-width/margin constraints on root divs
                        $cleanPostContent = preg_replace('/style=["\'][^"\']*(max-width|margin:\s*0\s+auto)[^"\']*["\']/i', '', $cleanPostContent);
                    @endphp

                    <article class="blog-content-isolated js-toc-content">
                        {!! $cleanPostContent !!}
                    </article>

                    @if($post->tags && $post->tags->count())
                    <div class="tags-container">
                        @foreach($post->tags as $tag)
                        <a href="{{ route('blog.index', ['tag' => $tag->slug]) }}" class="premium-tag">#{{ $tag->name }}</a>
                        @endforeach
                    </div>
                    @endif

                    <div class="article-nav">
                        @if($previousPost)
                        <a href="{{ route('blog.show', $previousPost->slug ?? $previousPost->id) }}" class="nav-card prev-card">
                            <span class="nav-label"><i class="fas fa-arrow-left"></i> Previous Article</span>
                            <span class="nav-title">{{ \Illuminate\Support\Str::limit($previousPost->title, 50) }}</span>
                        </a>
                        @else
                        <div></div>
                        @endif

                        @if($nextPost)
                        <a href="{{ route('blog.show', $nextPost->slug ?? $nextPost->id) }}" class="nav-card next-card">
                            <span class="nav-label">Next Article <i class="fas fa-arrow-right"></i></span>
                            <span class="nav-title">{{ \Illuminate\Support\Str::limit($nextPost->title, 50) }}</span>
                        </a>
                        @endif
                    </div>

                    @if($relatedPosts->count() > 0)
                    <div class="related-section">
                        <h3 class="section-title">
                            Continue Reading
                            <a href="{{ route('blog.index') }}" class="premium-tag" style="font-size:0.75rem; font-weight:600;">View All</a>
                        </h3>
                        <div class="related-grid">
                            @foreach($relatedPosts as $related)
                            <a href="{{ route('blog.show', $related->slug ?? $related->id) }}" class="related-card">
                                @if($related->featured_image)
                                <img src="{{ asset('uploads/' . $related->featured_image) }}" alt="{{ $related->title }}" class="related-card-img">
                                @else
                                <div class="related-card-img" style="background: #0B0E1B; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-newspaper" style="font-size:2rem; color:var(--text-subtle);"></i>
                                </div>
                                @endif
                                <div class="related-card-body">
                                    @if($related->category)
                                    <span class="related-card-category">{{ $related->category }}</span>
                                    @endif
                                    <h4 class="related-card-title">{{ \Illuminate\Support\Str::limit($related->title, 50) }}</h4>
                                    <p class="related-card-excerpt">{{ \Illuminate\Support\Str::limit(strip_tags($related->excerpt ?? $related->content), 80) }}</p>
                                    <span class="related-card-link">Read Article <i class="fas fa-arrow-right"></i></span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

            </div>

            <!-- RIGHT SIDEBAR COLUMN -->
            <div style="min-width:0;">
                <aside class="sidebar-scroll-area">
                    
                    <a href="{{ route('admin.blog.create') }}" class="btn-ai-create">
                        <i class="fas fa-sparkles" style="color:var(--neon-pink);"></i> Create Blog Post by AI
                    </a>

                    <div class="sidebar-widget">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <span class="stat-label">Words</span>
                                <span class="stat-value">{{ str_word_count(strip_tags($post->content)) }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Reading Time</span>
                                <span class="stat-value">{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Published</span>
                                <span class="stat-value">{{ $post->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Updated</span>
                                <span class="stat-value">{{ $post->updated_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="sidebar-widget" id="toc-widget" style="display: none;">
                        <h4 class="toc-title"><i class="fas fa-list-ul"></i> Table of Contents</h4>
                        <ul class="toc-list" id="toc-list">
                        </ul>
                    </div>

                    <div class="sidebar-widget">
                        <div class="author-mini-profile">
                            @if(isset($post->author->avatar))
                            <img src="{{ asset('uploads/' . $post->author->avatar) }}" alt="{{ $post->author->name }}" class="author-avatar">
                            @else
                            <div class="author-avatar" style="background:#0B0E1B; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-user" style="color:var(--neon-blue);"></i>
                            </div>
                            @endif
                            <div class="author-info">
                                <h5>{{ $post->author->name ?? 'CypherLabs Team' }}</h5>
                                <p>Author</p>
                            </div>
                        </div>
                        <p class="author-bio">{{ $post->author->bio ?? 'Crafting premium tools and resources for modern developers worldwide.' }}</p>
                        <div class="social-links">
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="social-btn" title="Share on Twitter"><i class="fab fa-x-twitter"></i></a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($post->title) }}" target="_blank" class="social-btn" title="Share on LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="social-btn" title="Share on Facebook"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>

                    <div class="sidebar-widget">
                        <h4 class="toc-title"><i class="fas fa-tags"></i> Popular Tags</h4>
                        <div style="display:flex; flex-wrap:wrap; gap:8px;">
                            @php
                            $popularTags = $popularTags ?? ['laravel', 'vue.js', 'api', 'design', 'cypherlabs', 'tutorial'];
                            @endphp
                            @foreach($popularTags as $tag)
                            <a href="{{ route('blog.index', ['tag' => $tag]) }}" class="premium-tag" style="padding:0.2rem 0.8rem;">#{{ $tag }}</a>
                            @endforeach
                        </div>
                    </div>

                    <div class="sidebar-widget">
                        <h4 class="newsletter-title">Stay Updated</h4>
                        <p class="newsletter-desc">Get the latest articles and resources delivered to your inbox weekly.</p>
                        <form onsubmit="event.preventDefault(); alert('Subscribed!');">
                            <input type="email" placeholder="Email address" required class="newsletter-input">
                            <button type="submit" class="newsletter-btn">Subscribe</button>
                        </form>
                    </div>

                </aside>
            </div>

        </div><!-- /.blog-page-grid -->
    </div><!-- /.blog-page-container -->
</div><!-- /.blog-modern-layout -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Reading Progress Bar ---
    const progressBar = document.getElementById('progressBar');
    
    const updateProgress = () => {
        const scrollTotal = document.documentElement.scrollHeight - window.innerHeight;
        const scrollPos = window.scrollY;
        let progress = (scrollPos / scrollTotal) * 100;
        if (progressBar) {
            progressBar.style.width = Math.min(Math.max(progress, 0), 100) + '%';
        }
    };
    window.addEventListener('scroll', updateProgress);

    // --- 2. Auto Table of Contents Generation ---
    const articleContent = document.querySelector('.js-toc-content');
    const headings = articleContent ? articleContent.querySelectorAll('h2, h3') : [];
    const tocWidget = document.getElementById('toc-widget');
    const tocList = document.getElementById('toc-list');
    
    if (headings.length > 0) {
        tocWidget.style.display = 'block';
        let tocLinks = [];
        
        headings.forEach((heading, index) => {
            if (!heading.id) {
                heading.id = 'heading-' + index + '-' + heading.innerText.toLowerCase().replace(/[^a-z0-9]+/g, '-');
            }
            
            let li = document.createElement('li');
            li.className = heading.tagName.toLowerCase();
            
            let a = document.createElement('a');
            a.href = '#' + heading.id;
            a.innerText = heading.innerText;
            
            a.addEventListener('click', (e) => {
                e.preventDefault();
                let targetOffset = heading.getBoundingClientRect().top + window.scrollY - 90;
                window.scrollTo({ top: targetOffset, behavior: 'smooth' });
            });
            
            li.appendChild(a);
            tocList.appendChild(li);
            tocLinks.push({ link: a, element: heading });
        });

        // Highlight Active TOC Item
        const headingObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    tocLinks.forEach(item => item.link.classList.remove('active'));
                    const activeItem = tocLinks.find(item => item.element.id === entry.target.id);
                    if (activeItem) activeItem.link.classList.add('active');
                }
            });
        }, { rootMargin: '0px 0px -60% 0px', threshold: 0.1 });

        headings.forEach(heading => headingObserver.observe(heading));
    }
});

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert("Link copied to clipboard!");
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}

function bookmarkPage() {
    alert("Press " + (navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + "+D to bookmark this page.");
}
</script>

@endsection