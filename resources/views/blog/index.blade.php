@extends('layouts.app')

@section('title', 'Our Blog | Insights & Tutorials')
@section('meta_description', 'Discover expert insights, tutorials, and guides on web development, design, and technology.')

@section('content')

<!-- Hero Section (using your existing theme classes) -->
<div class="hero-section">
    <div class="container">
        <div class="hero-content text-center position-relative z-1">
            <div class="hero-badge">
                <i class="fas fa-pen-fancy"></i> FRESH INSIGHTS WEEKLY
            </div>
            <h1 class="hero-title">
                Stories That<br>
                <span class="gradient-text" style="background: linear-gradient(135deg, #00d4ff, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Inspire & Educate
                </span>
            </h1>
            <p class="hero-subtitle">
                Expert-written articles on development, design, and tech trends.<br>
                Level up your skills with practical tutorials and deep dives.
            </p>

            <div class="search-wrapper">
                <form action="{{ route('blog.search') }}" method="GET" class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" name="q" class="search-input" placeholder="Search articles (React, Laravel, CSS...)" required>
                </form>
                <p class="search-hint">
                    <i class="fas fa-lightbulb"></i> Try "JavaScript", "performance", or "beginners"
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Bar (using your existing stats-grid classes) -->
<div class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number">{{ number_format($totalPosts) }}+</div>
                <div class="stat-label">Published Articles</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($totalCategories) }}+</div>
                <div class="stat-label">Topic Categories</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ number_format($totalViews) }}+</div>
                <div class="stat-label">Monthly Readers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">Weekly</div>
                <div class="stat-label">Fresh Content</div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Posts (using your tool-card classes for a premium look) -->
<div class="container">
    <div class="section-header">
        <div class="section-title-wrapper">
            <span class="section-icon">✦</span>
            <h2 class="section-title">Editor's Picks</h2>
        </div>
        <span class="section-badge"><i class="fas fa-star"></i> Must Read</span>
    </div>

    <div class="row g-4">
        @foreach($featuredPosts as $post)
        <div class="col-md-4">
            <a href="{{ route('blog.show', $post->slug) }}" class="tool-card d-block">
                <div class="tool-box">
                    <div class="tool-icon" style="width:auto; height:auto; background:none; border:none;">
                        <img src="{{ asset('uploads/blog/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width:100%; height:180px; object-fit:cover; border-radius:12px;">
                    </div>
                    <div class="tool-content">
                        <span class="category-badge" style="background:rgba(0,212,255,0.1); color:var(--neon-blue); padding:2px 12px; border-radius:50px; font-size:0.7rem; margin-bottom:10px; display:inline-block;">{{ $post->category->name }}</span>
                        <h3 class="tool-title">{{ $post->title }}</h3>
                        <p class="tool-desc">{{ Str::limit($post->excerpt, 100) }}</p>
                        <div class="post-meta text-muted" style="font-size:0.75rem;">
                            <span><i class="far fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}</span>
                            <span class="ms-3"><i class="far fa-clock"></i> {{ $post->read_time }} min</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- Latest Articles & Sidebar -->
<div class="content-section" style="margin: 4rem 0;">
    <div class="container">
        <div class="row">
            <!-- Recent Posts - 2 cards per row on desktop, single on mobile -->
            <div class="col-lg-8">
                <div class="section-header">
                    <div class="section-title-wrapper">
                        <span class="section-icon">◆</span>
                        <h2 class="section-title">Latest Articles</h2>
                    </div>
                </div>

                <!-- 2-column grid for recent posts -->
                <div class="recent-posts-grid" style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
                    @foreach($recentPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="tool-card d-block h-100">
                        <div class="tool-box h-100">
                            <div class="tool-icon" style="width:100%; height:auto; background:none; border:none;">
                                <img src="{{ asset('uploads/blog/' . $post->featured_image) }}" alt="{{ $post->title }}" style="width:100%; height:180px; object-fit:cover; border-radius:12px;">
                            </div>
                            <div class="tool-content">
                                <span class="category-tag" style="background:rgba(0,212,255,0.1); color:var(--neon-blue); padding:2px 10px; border-radius:50px; font-size:0.7rem; margin-bottom:8px; display:inline-block;">{{ $post->category->name }}</span>
                                <h3 class="tool-title" style="font-size:1.1rem;">{{ $post->title }}</h3>
                                <p class="tool-desc" style="font-size:0.8rem;">{{ Str::limit($post->excerpt, 80) }}</p>
                                <div class="post-meta" style="font-size:0.7rem;">
                                    <span><i class="far fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}</span>
                                    <span class="ms-3"><i class="far fa-eye"></i> {{ number_format($post->views) }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Trending Posts -->
                <div class="sidebar-widget" style="background:var(--glass-bg); backdrop-filter:blur(10px); border-radius:20px; padding:1.5rem; border:1px solid rgba(0,212,255,0.1); margin-bottom:2rem;">
                    <h4 class="widget-header" style="color:var(--neon-blue); display:flex; align-items:center; gap:10px;">
                        <i class="fas fa-fire"></i> Trending Posts
                    </h4>
                    @foreach($popularPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="popular-post d-flex align-items-center gap-3 py-2 border-bottom" style="border-color:rgba(255,255,255,0.05); color:var(--text-secondary); transition:0.3s;">
                        <span style="font-weight:800; color:var(--neon-blue); min-width:25px;">{{ $loop->iteration }}</span>
                        <div>
                            <h5 style="font-size:0.85rem; color:var(--text-primary); margin:0;">{{ Str::limit($post->title, 50) }}</h5>
                            <small><i class="far fa-calendar"></i> {{ $post->created_at->format('M d, Y') }}</small>
                            <small style="color:var(--text-muted);">{{ number_format($post->views) }} views</small>
                        </div>
                    </a>
                    @endforeach
                </div>

                <!-- Categories -->
                <div class="sidebar-widget" style="background:var(--glass-bg); backdrop-filter:blur(10px); border-radius:20px; padding:1.5rem; border:1px solid rgba(0,212,255,0.1); margin-bottom:2rem;">
                    <h4 class="widget-header" style="color:var(--neon-blue); display:flex; align-items:center; gap:10px;">
                        <i class="fas fa-folder"></i> Categories
                    </h4>
                    <div class="categories-list">
                        @foreach($categories as $category)
                        <a href="{{ route('blog.category', $category->slug) }}" class="category-item d-flex justify-content-between align-items-center py-2 px-3 rounded-3" style="color:var(--text-secondary); transition:0.3s;">
                            <span>{{ $category->name }}</span>
                            <span class="badge" style="background:rgba(0,212,255,0.1); color:var(--neon-blue);">{{ $category->posts_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Tags -->
                <div class="sidebar-widget" style="background:var(--glass-bg); backdrop-filter:blur(10px); border-radius:20px; padding:1.5rem; border:1px solid rgba(0,212,255,0.1);">
                    <h4 class="widget-header" style="color:var(--neon-blue); display:flex; align-items:center; gap:10px;">
                        <i class="fas fa-tags"></i> Popular Tags
                    </h4>
                    <div class="tags-cloud d-flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                        <a href="{{ route('blog.tag', $tag->slug) }}" class="tag-item" style="background:rgba(0,212,255,0.08); color:var(--text-secondary); padding:4px 12px; border-radius:50px; font-size:0.75rem; transition:0.3s;">
                            {{ $tag->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter CTA (using your existing cta-section) -->
<div class="cta-section">
    <div class="container">
        <div class="cta-wrapper">
            <div class="cta-content">
                <h3>Never miss an article</h3>
                <p>Get weekly insights, tutorials, and tech tips delivered straight to your inbox.</p>
                <form class="newsletter-form d-flex justify-content-center gap-3 flex-wrap">
                    <input type="email" class="search-input" placeholder="Enter your email address" required style="max-width:350px;">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick responsive fix for recent posts grid -->
<style>
    @media (max-width: 992px) {
        .recent-posts-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }

    @media (max-width: 768px) {
        .recent-posts-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@endsection