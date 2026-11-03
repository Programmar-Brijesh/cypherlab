@extends('layouts.app')

@section('title', 'Create Blog Post – CypherLabs')
@section('meta_description', 'Write a new blog post. Publish or save as draft. Simple, elegant editor.')

@section('content')

<style>
    /* ============================================
       BLOG CREATE – PREMIUM THEME (SCOPED)
       ============================================ */
    .blog-create-premium-wrapper {
        --neon-blue: #00d4ff;
        --neon-cyan: #2affdf;
        --neon-pink: #ff2e9e;
        --accent-gold: #ffcc44;
        --bg-glass: rgba(10, 14, 26, 0.75);
        --border-glass: rgba(0, 212, 255, 0.18);
        --text-primary: #edf2ff;
        --text-secondary: #9aa4bf;
        --text-muted: #6c7a9e;
        --gradient-primary: linear-gradient(135deg, #00d4ff, #2affdf);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

        background: #070b14;
        background-image:
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 60px 60px;
        padding: 2rem 0 4rem;
        min-height: 80vh;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--text-primary);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .blog-create-premium-wrapper .container {
        position: relative;
        z-index: 2;
    }

    /* Ambient glow */
    .blog-ambient {
        position: fixed;
        top: -20%;
        left: 50%;
        transform: translateX(-50%);
        width: 800px;
        height: 800px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.04) 0%, transparent 70%);
        pointer-events: none;
        z-index: 0;
    }

    /* Hero */
    .blog-hero {
        text-align: center;
        margin-bottom: 2rem;
    }

    .blog-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(0, 212, 255, 0.1);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(0, 212, 255, 0.25);
        padding: 0.35rem 1.2rem;
        border-radius: 40px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.08em;
        color: var(--neon-blue);
        text-transform: uppercase;
        font-family: 'SF Mono', 'Fira Code', monospace;
        margin-bottom: 1rem;
    }

    .blog-title {
        font-size: clamp(2rem, 6vw, 3rem);
        font-weight: 800;
        line-height: 1.2;
        background: linear-gradient(135deg, #ffffff 0%, var(--neon-blue) 40%, var(--neon-cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .blog-subtitle {
        font-size: 1rem;
        color: var(--text-secondary);
        max-width: 500px;
        margin: 0 auto;
    }

    /* Glass card */
    .glass-card {
        background: var(--bg-glass);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 28px;
        padding: 2rem 2rem;
        border: 1px solid var(--border-glass);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.04);
        transition: var(--transition-smooth);
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }

    .glass-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 28px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.25), rgba(42, 255, 223, 0.08));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 2rem;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(42, 255, 223, 0.1));
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--neon-blue);
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.15);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        background: linear-gradient(to right, #ffffff, var(--neon-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Form */
    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 1.6rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-secondary);
        margin-left: 0.2rem;
    }

    .form-control {
        width: 100%;
        background: rgba(5, 7, 15, 0.8);
        border: 1px solid rgba(0, 212, 255, 0.18);
        border-radius: 16px;
        padding: 0.9rem 1.1rem;
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(4px);
        box-sizing: border-box;
    }

    textarea.form-control {
        resize: vertical;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--neon-blue);
        box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.25), inset 0 0 0 1px rgba(0, 212, 255, 0.1);
        background: rgba(5, 7, 15, 0.95);
    }

    .form-control::placeholder {
        color: var(--text-muted);
        opacity: 0.6;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%236c7a9e' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    /* File input styling */
    input[type="file"].form-control {
        padding: 0.7rem 1rem;
        background: rgba(5, 7, 15, 0.8);
        border: 1px dashed rgba(0, 212, 255, 0.25);
        cursor: pointer;
    }

    input[type="file"].form-control:hover {
        border-color: var(--neon-blue);
    }

    /* Radio group */
    .radio-group {
        display: flex;
        gap: 2rem;
        align-items: center;
    }

    .radio-option {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .radio-option input[type="radio"] {
        accent-color: var(--neon-blue);
        transform: scale(1.1);
    }

    /* Buttons */
    .btn-glow {
        background: var(--gradient-primary);
        color: #070b14;
        border: none;
        border-radius: 60px;
        padding: 0.95rem 2rem;
        font-size: 1rem;
        font-weight: 700;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        transition: var(--transition-smooth);
        box-shadow: 0 6px 18px rgba(0, 212, 255, 0.35);
        width: 100%;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-glow::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s;
    }

    .btn-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 212, 255, 0.5);
    }

    .btn-glow:hover::after {
        left: 100%;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border-glass);
        color: var(--text-primary);
        box-shadow: none;
    }

    .btn-secondary:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.1);
    }

    .floating-dots {
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 0;
        pointer-events: none;
        overflow: hidden;
    }

    .dot {
        position: absolute;
        border-radius: 50%;
        background: var(--neon-blue);
        opacity: 0.04;
        animation: float 8s infinite ease-in-out;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(5deg);
        }
    }

    .dot:nth-child(1) {
        width: 200px;
        height: 200px;
        top: 5%;
        left: 5%;
    }

    .dot:nth-child(2) {
        width: 300px;
        height: 300px;
        bottom: 10%;
        right: 3%;
        background: var(--neon-cyan);
        opacity: 0.03;
        animation-duration: 12s;
    }

    .dot:nth-child(3) {
        width: 150px;
        height: 150px;
        top: 60%;
        left: 60%;
        background: var(--accent-gold);
        opacity: 0.03;
        animation-duration: 7s;
        animation-delay: 2s;
    }

    .mb-0 {
        margin-bottom: 0 !important;
    }

    .mt-3 {
        margin-top: 1rem;
    }
</style>

<div class="blog-create-premium-wrapper">
    <div class="blog-ambient"></div>
    <div class="floating-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <div class="container">
        <div class="blog-hero">
            <div class="blog-badge">
                <i class="fas fa-feather-alt"></i> NEW POST
            </div>
            <h1 class="blog-title">
                Craft Your Story
            </h1>
            <p class="blog-subtitle">Share your thoughts, publish instantly or save as draft for later.</p>
        </div>

        <div class="glass-card">
            <div class="section-header">
                <div class="section-icon"><i class="fas fa-pen-fancy"></i></div>
                <h2 class="section-title">Create Blog Post</h2>
            </div>

            <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label" for="title"><i class="fas fa-heading"></i> Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter captivating title" value="{{ old('title') }}" required>
                    </div>

                    <!-- Excerpt -->
                    <div class="form-group">
                        <label class="form-label" for="excerpt"><i class="fas fa-align-left"></i> Short Excerpt</label>
                        <textarea name="excerpt" id="excerpt" class="form-control" rows="2" placeholder="Brief summary (optional)">{{ old('excerpt') }}</textarea>
                    </div>

                    <!-- Content -->
                    <div class="form-group">
                        <label class="form-label" for="content"><i class="fas fa-file-alt"></i> Content</label>
                        <textarea name="content" id="content" class="form-control" rows="12" placeholder="Write your blog content here..." required>{{ old('content') }}</textarea>
                    </div>

                    <!-- Category & Tags (side by side on larger screens) -->
                    <div style="display: grid; grid-template-columns: 1fr; gap: 1.6rem; @media (min-width: 600px) { grid-template-columns: 1fr 1fr; }">
                        <div class="form-group">
                            <label class="form-label" for="category"><i class="fas fa-folder"></i> Category</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">Uncategorized</option>
                                <option value="tech" {{ old('category') == 'tech' ? 'selected' : '' }}>Technology</option>
                                <option value="design" {{ old('category') == 'design' ? 'selected' : '' }}>Design</option>
                                <option value="lifestyle" {{ old('category') == 'lifestyle' ? 'selected' : '' }}>Lifestyle</option>
                                <option value="business" {{ old('category') == 'business' ? 'selected' : '' }}>Business</option>
                                <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="tags"><i class="fas fa-tags"></i> Tags (comma-separated)</label>
                            <input type="text" name="tags" id="tags" class="form-control" placeholder="e.g. laravel, vue, api" value="{{ old('tags') }}">
                        </div>
                    </div>

                    <!-- Featured Image -->
                    <div class="form-group">
                        <label class="form-label" for="featured_image"><i class="fas fa-image"></i> Featured Image</label>
                        <input type="file" name="featured_image" id="featured_image" class="form-control" accept="image/*">
                        <small style="color: var(--text-muted);">Optional – a striking image for your post.</small>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-globe"></i> Status</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="status" value="published" {{ old('status', 'published') == 'published' ? 'checked' : '' }}>
                                <span style="color: var(--neon-cyan);">Publish</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="status" value="draft" {{ old('status') == 'draft' ? 'checked' : '' }}>
                                <span style="color: var(--text-muted);">Save as Draft</span>
                            </label>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <button type="submit" class="btn-glow" style="flex: 1;">
                            <i class="fas fa-paper-plane"></i> Publish Post
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn-glow btn-secondary" style="flex: 1;">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection