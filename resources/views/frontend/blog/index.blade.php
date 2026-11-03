@extends('layouts.app')

@section('title', 'CypherLabs Articles & Engineering Tutorials')
@section('meta_description', 'Discover premium developer insights, tutorials, architecture guides, and tech tips.')

@section('content')
<style>
    :root {
        --bg-base: #030712;
        --bg-card: rgba(15, 23, 42, 0.7);
        --bg-card-hover: rgba(30, 41, 59, 0.85);
        --border-glass: rgba(0, 212, 255, 0.15);
        --border-glass-hover: rgba(0, 212, 255, 0.4);
        --neon-blue: #00d4ff;
        --neon-purple: #a855f7;
        --neon-cyan: #06f7e3;
        --neon-pink: #ff2e9e;
        --text-primary: #f8fafc;
        --text-secondary: #94a3b8;
        --text-muted: #64748b;
        --radius-lg: 16px;
        --radius-xl: 24px;
    }

    body {
        background-color: var(--bg-base) !important;
    }

    .blog-page-wrapper {
        color: var(--text-primary);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        padding-bottom: 3rem;
    }

    /* HERO SECTION */
    .blog-hero {
        position: relative;
        padding: 2.5rem 1.5rem 2rem;
        text-align: center;
        background: radial-gradient(circle at 50% 0%, rgba(0, 212, 255, 0.12) 0%, rgba(168, 85, 247, 0.05) 50%, transparent 80%);
        border-radius: var(--radius-xl);
        border: 1px solid var(--border-glass);
        margin-bottom: 2.5rem;
        overflow: hidden;
        backdrop-filter: blur(16px);
    }

    .blog-hero-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
        background-size: 32px 32px;
        z-index: 0;
        pointer-events: none;
    }

    .blog-hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        margin: 0 auto;
    }

    .blog-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 30px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.25);
        color: var(--neon-blue);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 1.25rem;
        font-family: 'Fira Code', monospace;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--neon-blue);
        box-shadow: 0 0 10px var(--neon-blue);
        animation: pulseDot 2s ease-in-out infinite;
    }

    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    .blog-hero-title {
        font-size: 2.75rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -1px;
        margin-bottom: 1rem;
        color: #ffffff;
    }

    .grad-text {
        background: linear-gradient(135deg, #00d4ff 0%, #a855f7 50%, #06f7e3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .blog-hero-sub {
        color: var(--text-secondary);
        font-size: 1.05rem;
        max-width: 600px;
        margin: 0 auto 1.75rem;
        line-height: 1.6;
    }

    /* SEARCH & FILTER CONTROLS */
    .blog-controls-wrapper {
        max-width: 650px;
        margin: 0 auto;
    }

    .blog-search-box {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .blog-search-box input {
        width: 100%;
        padding: 14px 20px 14px 48px;
        border-radius: 12px;
        background: rgba(10, 15, 30, 0.85);
        border: 1px solid var(--border-glass);
        color: #ffffff;
        font-size: 0.95rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .blog-search-box input:focus {
        outline: none;
        border-color: var(--neon-blue);
        box-shadow: 0 0 15px rgba(0, 212, 255, 0.25);
    }

    .blog-search-box i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1rem;
    }

    .category-pills {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }

    .cat-pill {
        padding: 6px 14px;
        border-radius: 20px;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: var(--text-secondary);
        font-size: 0.82rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.25s ease;
        font-family: 'Fira Code', monospace;
    }

    .cat-pill:hover, .cat-pill.active {
        background: rgba(0, 212, 255, 0.15);
        border-color: var(--neon-blue);
        color: var(--neon-blue);
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
    }

    /* UNIFIED COMPACT SAAS BLOG CARDS */
    .featured-grid, .blog-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 1.25rem;
        margin-bottom: 3rem;
    }

    .featured-card, .blog-card {
        background: rgba(13, 18, 33, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        text-decoration: none !important;
    }

    .featured-card:hover, .blog-card:hover {
        transform: translateY(-5px);
        border-color: rgba(0, 212, 255, 0.35);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 212, 255, 0.1);
        background: rgba(18, 25, 45, 0.88);
    }

    .featured-img-container, .blog-card-media {
        position: relative;
        height: 160px;
        overflow: hidden;
        background: #090d1a;
    }

    .featured-img-container img, .blog-card-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .featured-card:hover .featured-img-container img,
    .blog-card:hover .blog-card-media img {
        transform: scale(1.06);
    }

    .featured-overlay, .blog-card-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(3, 7, 18, 0.1) 0%, rgba(3, 7, 18, 0.75) 100%);
        pointer-events: none;
    }

    .featured-tag-badge, .blog-card-badge {
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

    .featured-time-badge, .blog-card-time {
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

    .featured-content, .blog-card-body {
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .featured-title, .blog-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        line-height: 1.35;
        margin-bottom: 0.5rem;
        color: #ffffff;
    }

    .featured-title a, .blog-card-title a {
        color: #ffffff;
        text-decoration: none !important;
        transition: color 0.2s ease;
    }

    .featured-card:hover .featured-title a,
    .blog-card:hover .blog-card-title a {
        color: var(--neon-blue);
    }

    .featured-excerpt, .blog-card-excerpt {
        color: var(--text-secondary);
        font-size: 0.83rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .author-row, .blog-card-author-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--text-muted);
        font-size: 0.76rem;
        margin-top: auto;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .author-left {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .mini-author-avatar {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--neon-blue), var(--neon-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 0.6rem;
    }

    .featured-read-btn, .blog-card-action-btn {
        color: var(--neon-blue);
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        display: flex;
        align-items: center;
        gap: 4px;
        text-decoration: none !important;
        transition: gap 0.2s ease, color 0.2s ease;
    }

    .featured-card:hover .featured-read-btn,
    .blog-card:hover .blog-card-action-btn {
        color: #ffffff;
        gap: 7px;
    }

    /* SECTION TITLES */
    .section-title-wrap {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 0.75rem;
    }

    .section-heading {
        font-size: 1.4rem;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-heading i {
        color: var(--neon-blue);
    }

    /* TOPICS GRID */
    .topics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .topic-card {
        background: var(--bg-card);
        border: 1px solid var(--border-glass);
        border-radius: var(--radius-lg);
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .topic-card:hover {
        border-color: var(--neon-blue);
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 212, 255, 0.15);
    }

    .topic-icon {
        font-size: 1.8rem;
        color: var(--neon-blue);
        margin-bottom: 0.5rem;
    }

    .topic-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: #ffffff;
        margin-bottom: 0.25rem;
    }

    .topic-count {
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    /* EMPTY STATE */
    .empty-blogs {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-card);
        border: 1px dashed var(--border-glass);
        border-radius: var(--radius-xl);
    }

    .empty-blogs i {
        font-size: 3rem;
        color: var(--text-muted);
        margin-bottom: 1rem;
    }

    /* RESPONSIVE BREAKPOINTS */
    @media (max-width: 991px) {
        .featured-card {
            grid-template-columns: 1fr;
        }
        .featured-img-container {
            min-height: 220px;
        }
        .blog-hero-title {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 576px) {
        .blog-hero {
            padding: 1.75rem 1rem 1.5rem;
        }
        .blog-hero-title {
            font-size: 1.8rem;
        }
        .blog-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="blog-page-wrapper">

    <!-- HERO BANNER -->
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
                Handcrafted tutorials, architecture deep dives, and web development strategies.
            </p>

            <div class="blog-controls-wrapper">
                <div class="blog-search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="blogSearchInput" placeholder="Search articles by title, topic or tech stack...">
                </div>

                <div class="category-pills">
                    <span class="cat-pill active" data-filter="all">All Posts</span>
                    <span class="cat-pill" data-filter="laravel">Laravel</span>
                    <span class="cat-pill" data-filter="php">PHP</span>
                    <span class="cat-pill" data-filter="javascript">JavaScript</span>
                    <span class="cat-pill" data-filter="ai">AI & ML</span>
                    <span class="cat-pill" data-filter="devops">DevOps</span>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURED ARTICLES SPOTLIGHT (TOP 3) -->
    @if($posts->isNotEmpty())
    @php($featuredPosts = $posts->take(3))
    <div class="section-title-wrap">
        <div class="section-heading">
            <i class="fas fa-star text-cyan"></i> Featured Articles
        </div>
    </div>

    <div class="featured-grid">
        @foreach($featuredPosts as $featured)
        <article class="featured-card" data-title="{{ strtolower($featured->title) }}">
            <div class="featured-img-container">
                @if($featured->featured_image_url)
                    <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}">
                @else
                    <div class="featured-placeholder" style="min-height: 160px;"><i class="fas fa-newspaper"></i></div>
                @endif
                <div class="featured-overlay"></div>
                <span class="featured-tag-badge">{{ $featured->schema_type ?? 'Featured' }}</span>
                <span class="featured-time-badge"><i class="far fa-clock me-1"></i> {{ $featured->reading_time ?? 5 }} MIN READ</span>
            </div>
            <div class="featured-content">
                <h3 class="featured-title">
                    <a href="{{ route('article.show', $featured->slug) }}">{{ Str::limit($featured->title, 55) }}</a>
                </h3>
                <p class="featured-excerpt">
                    {{ Str::limit($featured->meta_description ?? 'Discover deep technical insights and best practices in this comprehensive developer tutorial.', 90) }}
                </p>
                <div class="author-row mt-auto">
                    <div class="author-left">
                        <div class="mini-author-avatar"><i class="fas fa-terminal"></i></div>
                        <span>{{ $featured->published_at ? $featured->published_at->format('M d') : $featured->created_at->format('M d') }}</span>
                        <span class="ms-2 text-white-50" style="font-size: 0.78rem;"><i class="far fa-eye me-1"></i>{{ number_format($featured->views) }}</span>
                    </div>
                    <a href="{{ route('article.show', $featured->slug) }}" class="featured-read-btn">
                        <span>Read</span> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    @endif

    <!-- LATEST ARTICLES GRID -->
    <div class="section-title-wrap">
        <div class="section-heading">
            <i class="fas fa-layer-group text-cyan"></i> Latest Articles
        </div>
    </div>

    <div class="blog-grid" id="blogGrid">
        @forelse($posts as $post)
        <article class="blog-card" data-title="{{ strtolower($post->title) }}">
            <div class="blog-card-media">
                @if($post->featured_image_url)
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}">
                @else
                    <div class="featured-placeholder" style="min-height: 160px;"><i class="fas fa-code"></i></div>
                @endif
                <div class="blog-card-overlay"></div>
                <span class="blog-card-badge">{{ $post->schema_type ?? 'Tutorial' }}</span>
                <span class="blog-card-time"><i class="far fa-clock me-1"></i> {{ $post->reading_time ?? 5 }} min</span>
            </div>

            <div class="blog-card-body">
                <h3 class="blog-card-title">
                    <a href="{{ route('article.show', $post->slug) }}">{{ Str::limit($post->title, 60) }}</a>
                </h3>
                <p class="blog-card-excerpt">
                    {{ Str::limit($post->meta_description ?? 'Learn the best practices and latest trends in this detailed guide for modern developers.', 90) }}
                </p>

                <div class="blog-card-author-row mt-auto">
                    <div class="author-left">
                        <div class="mini-author-avatar"><i class="fas fa-code"></i></div>
                        <span>{{ $post->published_at ? $post->published_at->format('M d') : $post->created_at->format('M d') }}</span>
                        <span class="ms-2 text-white-50" style="font-size: 0.78rem;"><i class="far fa-eye me-1"></i>{{ number_format($post->views) }}</span>
                    </div>
                    <a href="{{ route('article.show', $post->slug) }}" class="blog-card-action-btn">
                        <span>Read</span> <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </article>
        @empty
        <div class="empty-blogs">
            <i class="far fa-folder-open"></i>
            <h3 style="color:#ffffff; margin-bottom: 0.5rem;">No Articles Found</h3>
            <p style="color:var(--text-muted); font-size: 0.9rem;">We are working on fresh developer content. Check back soon!</p>
        </div>
        @endforelse
    </div>

    <!-- POPULAR TOPICS -->
    <div class="section-title-wrap">
        <div class="section-heading">
            <i class="fas fa-cubes text-cyan"></i> Popular Topics
        </div>
    </div>

    <div class="topics-grid">
        <div class="topic-card">
            <div class="topic-icon"><i class="fab fa-laravel"></i></div>
            <div class="topic-title">Laravel</div>
            <div class="topic-count">30+ Guides</div>
        </div>
        <div class="topic-card">
            <div class="topic-icon"><i class="fab fa-php"></i></div>
            <div class="topic-title">PHP</div>
            <div class="topic-count">24+ Guides</div>
        </div>
        <div class="topic-card">
            <div class="topic-icon"><i class="fab fa-js"></i></div>
            <div class="topic-title">JavaScript</div>
            <div class="topic-count">28+ Guides</div>
        </div>
        <div class="topic-card">
            <div class="topic-icon"><i class="fas fa-robot"></i></div>
            <div class="topic-title">AI Engineering</div>
            <div class="topic-count">15+ Guides</div>
        </div>
        <div class="topic-card">
            <div class="topic-icon"><i class="fas fa-server"></i></div>
            <div class="topic-title">DevOps & Cloud</div>
            <div class="topic-count">18+ Guides</div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('blogSearchInput');
    const blogCards = document.querySelectorAll('.blog-card');
    const catPills = document.querySelectorAll('.cat-pill');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            blogCards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                if (title.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    catPills.forEach(pill => {
        pill.addEventListener('click', function() {
            catPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            blogCards.forEach(card => {
                const title = card.getAttribute('data-title') || '';
                if (filter === 'all' || title.includes(filter)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>
@endsection
