@extends('layouts.app')

@section('title', ($post->seo_title ?: $post->title) . ' | CypherLab')
@section('meta_description', $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->html), 160))
@section('canonical', $post->canonical_url ?: route('blog.show', $post->slug))
@section('og_image', $post->og_image ?: ($post->featured_image_url ?: asset('assets/images/og-default.png')))
@section('main_class', 'w-100 p-0 m-0')

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "{{ $post->schema_type ?? 'Article' }}",
  "headline": "{{ e($post->seo_title ?: $post->title) }}",
  "description": "{{ e($post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->html), 160)) }}",
  "image": "{{ $post->og_image ?: ($post->featured_image_url ?: asset('assets/images/og-default.png')) }}",
  "datePublished": "{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}",
  "dateModified": "{{ ($post->updated_at ?? $post->published_at ?? $post->created_at)->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "{{ $post->author->name ?? 'Brijesh Verma' }}",
    "alternateName": "Programmer Brijesh",
    "url": "{{ route('about') }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "CypherLab",
    "url": "{{ route('home') }}"
  },
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ route('blog.show', $post->slug) }}"
  }
}
</script>
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Blog' => route('blog.index'),
    $post->title => route('blog.show', $post->slug),
]) !!}
@endpush

@section('content')

<!-- READING PROGRESS BAR -->
<div id="wx-progress-bar"></div>

@if($post->css)
<style>
    {!! $post->css !!}
</style>
@endif

<style>
    :root {
        --wx-bg-base: #060812;
        --wx-bg-card: rgba(13, 18, 33, 0.75);
        --wx-border-color: rgba(0, 212, 255, 0.16);
        --wx-border-glow: rgba(0, 212, 255, 0.35);
        --wx-neon-blue: #00D4FF;
        --wx-neon-cyan: #06F7E3;
        --wx-violet: #8B5CF6;
        --wx-text-bright: #FFFFFF;
        --wx-text-body: #CBD5E1;
        --wx-text-muted: #94A3B8;
    }

    /* TOP PROGRESS BAR */
    #wx-progress-bar {
        position: fixed;
        top: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, var(--wx-neon-blue), var(--wx-neon-cyan), var(--wx-violet));
        width: 0%;
        z-index: 10000;
        transition: width 0.1s ease-out;
        box-shadow: 0 0 10px rgba(0, 212, 255, 0.8);
    }

    /* MAIN WRAPPER WITH AMBIENT GLOW */
    .wx-post-wrapper {
        background: var(--wx-bg-base);
        background-image: 
            radial-gradient(circle at 50% -10%, rgba(0, 212, 255, 0.12), rgba(138, 92, 246, 0.06) 45%, transparent 80%),
            radial-gradient(circle at 90% 20%, rgba(6, 247, 227, 0.04), transparent 50%);
        color: var(--wx-text-body);
        min-height: 100vh;
        width: 100%;
        padding-top: 28px;
        padding-bottom: 60px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        overflow-x: hidden;
    }

    /* COMPACT HEADER SECTION */
    .wx-post-header {
        max-width: 960px;
        margin: 0 auto 24px;
        text-align: center;
        padding: 0 16px;
        position: relative;
    }

    .wx-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--wx-neon-blue);
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.25);
        padding: 6px 16px;
        border-radius: 30px;
        text-decoration: none;
        font-size: 12.5px;
        font-weight: 600;
        margin-bottom: 16px;
        backdrop-filter: blur(8px);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .wx-back-btn:hover {
        background: rgba(0, 212, 255, 0.2);
        color: #ffffff;
        transform: translateX(-3px);
        box-shadow: 0 4px 15px rgba(0, 212, 255, 0.25);
    }

    .wx-category-pill {
        display: inline-block;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(138, 92, 246, 0.15));
        border: 1px solid var(--wx-border-color);
        color: var(--wx-neon-cyan);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        padding: 3px 12px;
        border-radius: 20px;
        margin-bottom: 12px;
    }

    .wx-post-title {
        font-size: clamp(22px, 3.4vw, 36px);
        font-weight: 800;
        line-height: 1.3;
        letter-spacing: -0.015em;
        margin-bottom: 14px;
        color: var(--wx-text-bright);
        text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
    }

    .wx-post-excerpt {
        font-size: 1.05rem;
        color: var(--wx-text-muted);
        max-width: 760px;
        margin: 0 auto 18px;
        line-height: 1.55;
    }

    .wx-post-meta {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        color: var(--wx-text-muted);
        font-size: 12.5px;
        font-family: 'Fira Code', monospace;
        flex-wrap: wrap;
    }

    .wx-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(13, 18, 33, 0.6);
        border: 1px solid var(--wx-border-color);
        padding: 4px 12px;
        border-radius: 20px;
    }

    .wx-meta-item i {
        color: var(--wx-neon-blue);
    }

    /* COMPACT HERO IMAGE */
    .wx-featured-hero-container {
        max-width: 1160px;
        margin: 0 auto 30px;
        padding: 0 16px;
    }

    .wx-featured-hero-img {
        width: 100%;
        max-height: 380px;
        object-fit: cover;
        border-radius: 16px;
        border: 1px solid var(--wx-border-glow);
        box-shadow: 0 15px 40px -10px rgba(0, 0, 0, 0.65), 0 0 20px rgba(0, 212, 255, 0.08);
        display: block;
    }

    /* COMPACT RESPONSIVE GRID */
    .wx-post-container {
        max-width: 1160px;
        margin: 0 auto;
        padding: 0 16px;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 280px;
        gap: 32px;
        align-items: start;
    }

    @media (max-width: 992px) {
        .wx-post-container {
            grid-template-columns: 1fr;
            gap: 32px;
        }
    }

    /* MAIN CONTENT BODY */
    .wx-post-main {
        width: 100%;
        min-width: 0;
    }

    .wx-post-body {
        line-height: 1.78;
        font-size: 1.02rem;
        color: var(--wx-text-body);
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Safe Resets & Typography Overrides */
    .wx-post-body * {
        box-sizing: border-box;
    }

    .wx-post-body img,
    .wx-post-body iframe,
    .wx-post-body video,
    .wx-post-body object {
        max-width: 100% !important;
        height: auto !important;
    }

    .wx-post-body img {
        border-radius: 12px;
        margin: 1.5rem auto;
        display: block;
        border: 1px solid var(--wx-border-color);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .wx-post-body img:hover {
        transform: scale(1.008);
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.55), 0 0 15px rgba(0, 212, 255, 0.12);
    }

    .wx-post-body h1,
    .wx-post-body h2,
    .wx-post-body h3,
    .wx-post-body h4,
    .wx-post-body h5,
    .wx-post-body h6 {
        color: var(--wx-text-bright);
        font-weight: 700;
        line-height: 1.35;
        margin-top: 1.8rem;
        margin-bottom: 0.85rem;
        letter-spacing: -0.012em;
        scroll-margin-top: 80px;
    }

    .wx-post-body h1 { font-size: clamp(22px, 2.8vw, 30px); border-bottom: 1px solid var(--wx-border-color); padding-bottom: 8px; }
    .wx-post-body h2 { font-size: clamp(19px, 2.2vw, 25px); }
    .wx-post-body h3 { font-size: clamp(17px, 1.8vw, 21px); }
    .wx-post-body h4 { font-size: 1.12rem; }

    .wx-post-body p {
        margin-bottom: 1.15rem;
    }

    /* Lead Paragraph */
    .wx-post-body > p:first-of-type {
        font-size: 1.1rem;
        color: #E2E8F0;
        line-height: 1.8;
    }

    .wx-post-body ul,
    .wx-post-body ol {
        margin-bottom: 1.25rem;
        padding-left: 1.6rem;
    }

    .wx-post-body li {
        margin-bottom: 0.45rem;
    }

    .wx-post-body ul li::marker {
        color: var(--wx-neon-blue);
    }

    .wx-post-body a {
        color: var(--wx-neon-blue);
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .wx-post-body a:hover {
        color: var(--wx-neon-cyan);
        text-shadow: 0 0 6px rgba(6, 247, 227, 0.35);
    }

    /* BLOCKQUOTES & QUOTE CARDS */
    .wx-post-body blockquote,
    .wx-quote-card {
        position: relative;
        border-left: 3px solid var(--wx-neon-blue);
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.05), rgba(138, 92, 246, 0.03));
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.35rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #F1F5F9;
    }

    .wx-quote-card cite {
        display: block;
        font-size: 13px;
        color: var(--wx-neon-blue);
        margin-top: 8px;
        font-style: normal;
        font-weight: 600;
    }

    /* CALLOUT ALERT BOXES */
    .wx-alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin: 1.5rem 0;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
    }

    .wx-alert-info {
        background: rgba(0, 212, 255, 0.08);
        border-color: rgba(0, 212, 255, 0.3);
        color: #E2E8F0;
    }

    .wx-alert-warning {
        background: rgba(234, 179, 8, 0.08);
        border-color: rgba(234, 179, 8, 0.3);
        color: #FEF08A;
    }

    .wx-alert-success {
        background: rgba(34, 197, 94, 0.08);
        border-color: rgba(34, 197, 94, 0.3);
        color: #DCFCE7;
    }

    .wx-alert-title {
        font-weight: 700;
        font-size: 14px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* KEY TAKEAWAYS CARD */
    .wx-key-takeaways {
        background: rgba(13, 18, 33, 0.85);
        border: 1px solid var(--wx-border-glow);
        border-radius: 14px;
        padding: 18px 22px;
        margin: 1.8rem 0;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    .wx-key-takeaways h4 {
        color: var(--wx-neon-blue);
        margin-top: 0;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* FAQ ACCORDION BLOCK */
    .wx-faq-block {
        background: rgba(13, 18, 33, 0.5);
        border: 1px solid var(--wx-border-color);
        border-radius: 14px;
        padding: 20px;
        margin: 1.8rem 0;
    }

    .wx-faq-block h3 {
        margin-top: 0;
        margin-bottom: 14px;
        color: var(--wx-neon-cyan);
    }

    .wx-faq-block details {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--wx-border-color);
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 10px;
        cursor: pointer;
    }

    .wx-faq-block summary {
        font-weight: 600;
        color: #ffffff;
    }

    .wx-faq-block details p {
        margin-top: 10px;
        margin-bottom: 0;
        font-size: 13.5px;
        color: var(--wx-text-muted);
    }

    /* CALL TO ACTION (CTA) BOX */
    .wx-cta-box {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.12), rgba(138, 92, 246, 0.12));
        border: 1px solid var(--wx-border-glow);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        margin: 2.2rem 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .wx-cta-box h3 {
        margin-top: 0;
        color: #ffffff;
    }

    .wx-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--wx-neon-blue);
        color: #060812 !important;
        font-weight: 700;
        padding: 10px 24px;
        border-radius: 30px;
        text-decoration: none !important;
        margin-top: 12px;
        transition: all 0.3s ease;
    }

    .wx-cta-btn:hover {
        background: var(--wx-neon-cyan);
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0, 212, 255, 0.4);
    }

    /* STEPS WORKFLOW TIMELINE */
    .wx-steps-timeline {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin: 1.8rem 0;
    }

    .wx-step-item {
        display: flex;
        gap: 16px;
        background: rgba(13, 18, 33, 0.6);
        border: 1px solid var(--wx-border-color);
        border-radius: 12px;
        padding: 16px;
        backdrop-filter: blur(8px);
    }

    .wx-step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--wx-neon-blue);
        color: #060812;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.4);
    }

    .wx-step-content h4 {
        margin-top: 0;
        margin-bottom: 4px;
        font-size: 16px;
        color: #ffffff;
    }

    .wx-step-content p {
        margin: 0;
        font-size: 14px;
        color: var(--wx-text-muted);
    }

    /* PROS & CONS GRID */
    .wx-pros-cons-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin: 1.8rem 0;
    }

    @media (max-width: 768px) {
        .wx-pros-cons-grid {
            grid-template-columns: 1fr;
        }
    }

    .wx-pros-box,
    .wx-cons-box {
        background: rgba(13, 18, 33, 0.65);
        border-radius: 12px;
        padding: 18px;
        border: 1px solid var(--wx-border-color);
    }

    .wx-pros-box h4,
    .wx-cons-box h4 {
        margin-top: 0;
        margin-bottom: 12px;
        font-size: 15px;
    }

    .wx-pros-box ul,
    .wx-cons-box ul {
        margin: 0;
        padding-left: 20px;
    }

    /* AUTHOR NOTE */
    .wx-author-note {
        background: linear-gradient(135deg, rgba(138, 92, 246, 0.12), rgba(0, 212, 255, 0.08));
        border: 1px solid rgba(138, 92, 246, 0.35);
        border-radius: 12px;
        padding: 16px 20px;
        margin: 1.8rem 0;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    }

    .wx-author-note-badge {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--wx-violet);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .wx-author-note p {
        margin: 0;
        color: #F1F5F9;
        font-style: italic;
    }

    /* STATS GRID */
    .wx-stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin: 1.8rem 0;
    }

    @media (max-width: 576px) {
        .wx-stats-grid {
            grid-template-columns: 1fr;
        }
    }

    .wx-stat-card {
        background: rgba(13, 18, 33, 0.8);
        border: 1px solid var(--wx-border-glow);
        border-radius: 12px;
        padding: 18px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .wx-stat-number {
        font-size: 24px;
        font-weight: 800;
        color: var(--wx-neon-blue);
    }

    .wx-stat-label {
        font-size: 12px;
        color: var(--wx-text-muted);
        margin-top: 4px;
    }

    /* DOWNLOAD CARD */
    .wx-download-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(13, 18, 33, 0.7);
        border: 1px solid var(--wx-border-color);
        border-radius: 14px;
        padding: 16px 20px;
        margin: 1.8rem 0;
        gap: 14px;
    }

    @media (max-width: 576px) {
        .wx-download-card {
            flex-direction: column;
            text-align: center;
        }
    }

    .wx-download-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .wx-download-icon {
        font-size: 26px;
        color: var(--wx-neon-blue);
    }

    .wx-download-info h4 {
        margin: 0;
        font-size: 15px;
        color: #ffffff;
    }

    .wx-download-info p {
        margin: 2px 0 0;
        font-size: 12.5px;
        color: var(--wx-text-muted);
    }

    .wx-download-btn {
        background: rgba(0, 212, 255, 0.12);
        border: 1px solid var(--wx-neon-blue);
        color: var(--wx-neon-blue) !important;
        padding: 8px 18px;
        border-radius: 20px;
        text-decoration: none !important;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.25s ease;
        flex-shrink: 0;
    }

    .wx-download-btn:hover {
        background: var(--wx-neon-blue);
        color: #060812 !important;
    }

    /* RESPONSIVE VIDEO WRAPPER */
    .wx-video-wrapper {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 14px;
        margin: 1.8rem 0;
        border: 1px solid var(--wx-border-glow);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .wx-video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    /* GENERAL TOPIC TAGS */
    .wx-tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 1.6rem 0;
    }

    .wx-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.22);
        color: var(--wx-neon-cyan);
        font-size: 12px;
        font-weight: 600;
        font-family: 'Fira Code', monospace;
        padding: 4px 13px;
        border-radius: 20px;
        transition: all 0.25s ease;
    }

    .wx-tag-badge:hover {
        background: rgba(0, 212, 255, 0.2);
        color: #ffffff;
        border-color: var(--wx-neon-blue);
        box-shadow: 0 0 12px rgba(0, 212, 255, 0.3);
    }

    /* GENERAL LAYOUT SECTION STYLES */
    .wx-section-hero {
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(138, 92, 246, 0.1));
        border: 1px solid var(--wx-border-glow);
        border-radius: 18px;
        padding: 36px 28px;
        text-align: center;
        margin: 2.2rem 0;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.35);
    }

    .wx-section-hero h2 {
        font-size: clamp(22px, 3vw, 32px);
        margin-top: 12px;
        color: #ffffff;
    }

    .wx-section-feature-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin: 2.2rem 0;
    }

    @media (max-width: 768px) {
        .wx-section-feature-grid {
            grid-template-columns: 1fr;
        }
    }

    .wx-feature-card {
        background: rgba(13, 18, 33, 0.7);
        border: 1px solid var(--wx-border-color);
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    .wx-feature-icon {
        font-size: 26px;
        color: var(--wx-neon-blue);
        margin-bottom: 10px;
    }

    .wx-feature-card h4 {
        margin: 0 0 8px;
        font-size: 16px;
        color: #ffffff;
    }

    .wx-feature-card p {
        margin: 0;
        font-size: 13.5px;
        color: var(--wx-text-muted);
    }

    .wx-section-split {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        align-items: center;
        margin: 2.2rem 0;
        background: rgba(13, 18, 33, 0.6);
        border: 1px solid var(--wx-border-color);
        border-radius: 16px;
        padding: 24px;
    }

    @media (max-width: 768px) {
        .wx-section-split {
            grid-template-columns: 1fr;
        }
    }

    .wx-split-media img {
        width: 100%;
        border-radius: 12px;
        display: block;
        border: 1px solid var(--wx-border-color);
    }

    .wx-split-text h3 {
        margin-top: 0;
        color: #ffffff;
    }

    .wx-section-newsletter {
        background: linear-gradient(135deg, rgba(13, 18, 33, 0.95), rgba(6, 8, 18, 0.95));
        border: 1px solid var(--wx-border-glow);
        border-radius: 18px;
        padding: 30px;
        text-align: center;
        margin: 2.2rem 0;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .wx-newsletter-form {
        display: flex;
        gap: 10px;
        max-width: 460px;
        margin: 18px auto 0;
    }

    @media (max-width: 480px) {
        .wx-newsletter-form {
            flex-direction: column;
        }
    }

    .wx-newsletter-form input {
        flex: 1;
        background: rgba(0, 0, 0, 0.4);
        border: 1px solid var(--wx-border-color);
        border-radius: 30px;
        padding: 10px 18px;
        color: #ffffff;
        outline: none;
    }

    .wx-newsletter-form button {
        background: var(--wx-neon-blue);
        border: none;
        color: #060812;
        font-weight: 700;
        border-radius: 30px;
        padding: 10px 22px;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .wx-newsletter-form button:hover {
        background: var(--wx-neon-cyan);
    }

    .wx-section-divider {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 2.5rem 0;
        position: relative;
    }

    .wx-section-divider::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: 50%;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0, 212, 255, 0.4), transparent);
    }

    .wx-section-divider span {
        position: relative;
        background: #060812;
        padding: 0 16px;
        color: var(--wx-neon-blue);
        font-size: 14px;
    }

    .wx-section-pricing {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin: 2.2rem 0;
    }

    @media (max-width: 768px) {
        .wx-section-pricing {
            grid-template-columns: 1fr;
        }
    }

    .wx-pricing-card {
        background: rgba(13, 18, 33, 0.7);
        border: 1px solid var(--wx-border-color);
        border-radius: 16px;
        padding: 24px;
        position: relative;
    }

    .wx-pricing-card.featured {
        border-color: var(--wx-neon-blue);
        background: rgba(0, 212, 255, 0.05);
    }

    .wx-plan-badge {
        position: absolute;
        top: -10px;
        right: 20px;
        background: var(--wx-neon-blue);
        color: #060812;
        font-weight: 800;
        font-size: 10px;
        text-transform: uppercase;
        padding: 2px 10px;
        border-radius: 10px;
    }

    .wx-plan-name {
        font-weight: 700;
        font-size: 18px;
        color: #ffffff;
    }

    .wx-plan-price {
        font-size: 28px;
        font-weight: 800;
        color: var(--wx-neon-blue);
        margin: 10px 0;
    }

    .wx-plan-price span {
        font-size: 13px;
        color: var(--wx-text-muted);
        font-weight: normal;
    }

    .wx-plan-btn {
        display: block;
        text-align: center;
        padding: 10px 20px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 13.5px;
        text-decoration: none !important;
        margin-top: 18px;
        transition: all 0.25s ease;
    }

    .wx-plan-btn.filled {
        background: var(--wx-neon-blue);
        color: #060812 !important;
    }

    .wx-plan-btn.outline {
        border: 1px solid var(--wx-border-color);
        color: var(--wx-neon-blue) !important;
    }




    /* COMPACT CODE BLOCKS */
    .wx-code-wrapper {
        position: relative;
        margin: 1.5rem 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid var(--wx-border-color);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
    }

    .wx-code-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #0D1221;
        padding: 6px 14px;
        border-bottom: 1px solid var(--wx-border-color);
        font-family: 'Fira Code', monospace;
        font-size: 11.5px;
        color: var(--wx-text-muted);
    }

    .wx-code-dots {
        display: flex;
        gap: 5px;
    }

    .wx-code-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
    }

    .wx-dot-red { background: #FF5F56; }
    .wx-dot-yellow { background: #FFBD2E; }
    .wx-dot-green { background: #27C93F; }

    .wx-copy-btn {
        background: rgba(0, 212, 255, 0.1);
        border: 1px solid rgba(0, 212, 255, 0.22);
        color: var(--wx-neon-blue);
        border-radius: 5px;
        padding: 2px 8px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .wx-copy-btn:hover {
        background: rgba(0, 212, 255, 0.22);
        color: #ffffff;
    }

    .wx-post-body pre {
        background: #080B17 !important;
        padding: 1rem 1.25rem !important;
        overflow-x: auto !important;
        margin: 0 !important;
        font-family: 'Fira Code', monospace !important;
        font-size: 0.88rem !important;
        color: #E2E8F0 !important;
        line-height: 1.6 !important;
    }

    .wx-post-body code {
        font-family: 'Fira Code', monospace !important;
        background: rgba(0, 212, 255, 0.12) !important;
        padding: 0.15rem 0.4rem !important;
        border-radius: 4px !important;
        font-size: 0.86em !important;
        color: var(--wx-neon-cyan) !important;
    }

    .wx-post-body pre code {
        background: transparent !important;
        padding: 0 !important;
        color: inherit !important;
    }

    /* COMPACT TABLES */
    .wx-post-body table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        overflow-x: auto;
        display: block;
        border-radius: 10px;
        border: 1px solid var(--wx-border-color);
    }

    .wx-post-body th,
    .wx-post-body td {
        padding: 10px 14px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        text-align: left;
    }

    .wx-post-body th {
        background: rgba(0, 212, 255, 0.12);
        color: #ffffff;
        font-weight: 700;
    }

    .wx-post-body tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.02);
    }

    /* COMPACT SIDEBAR */
    .wx-post-sidebar {
        position: sticky;
        top: 80px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .wx-sidebar-card {
        background: var(--wx-bg-card);
        border: 1px solid var(--wx-border-color);
        border-radius: 14px;
        padding: 16px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
    }

    .wx-sidebar-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        color: var(--wx-neon-blue);
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    /* TABLE OF CONTENTS */
    .wx-toc-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
        max-height: 320px;
        overflow-y: auto;
    }

    .wx-toc-item a {
        color: var(--wx-text-muted);
        text-decoration: none;
        font-size: 13px;
        line-height: 1.35;
        display: block;
        padding: 3px 8px;
        border-radius: 5px;
        border-left: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .wx-toc-item.level-3 a {
        padding-left: 18px;
        font-size: 12px;
    }

    .wx-toc-item a:hover,
    .wx-toc-item.active a {
        color: var(--wx-neon-cyan);
        background: rgba(0, 212, 255, 0.08);
        border-left-color: var(--wx-neon-blue);
    }

    /* SHARE BUTTONS */
    .wx-share-btns {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
    }

    .wx-share-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--wx-border-color);
        border-radius: 8px;
        color: var(--wx-text-body);
        font-size: 15px;
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .wx-share-btn:hover {
        background: rgba(0, 212, 255, 0.15);
        color: #ffffff;
        border-color: var(--wx-neon-blue);
        transform: translateY(-2px);
    }

    /* COMPACT BOTTOM AUTHOR CARD */
    .wx-author-card {
        margin-top: 40px;
        background: linear-gradient(135deg, rgba(13, 18, 33, 0.9), rgba(6, 8, 18, 0.9));
        border: 1px solid var(--wx-border-glow);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 18px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
    }

    @media (max-width: 576px) {
        .wx-author-card {
            flex-direction: column;
            text-align: center;
        }
    }

    .wx-author-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--wx-neon-blue), var(--wx-violet));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 22px;
        font-weight: 700;
        flex-shrink: 0;
        box-shadow: 0 0 16px rgba(0, 212, 255, 0.35);
    }

    .wx-author-info h4 {
        color: #ffffff;
        font-size: 1.1rem;
        margin-bottom: 4px;
        font-weight: 700;
    }

    .wx-author-info p {
        color: var(--wx-text-muted);
        font-size: 0.9rem;
        margin: 0;
        line-height: 1.45;
    }

    /* TOAST NOTIFICATION */
    #wx-toast {
        position: fixed;
        bottom: 24px;
        right: 24px;
        background: #0B0E1B;
        border: 1px solid var(--wx-neon-blue);
        color: var(--wx-neon-cyan);
        padding: 10px 20px;
        border-radius: 30px;
        font-size: 13.5px;
        font-weight: 600;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5), 0 0 12px rgba(0, 212, 255, 0.25);
        opacity: 0;
        transform: translateY(16px);
        transition: all 0.25s ease;
        z-index: 10000;
        pointer-events: none;
    }

    #wx-toast.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div class="wx-post-wrapper">

    <!-- HEADER SECTION -->
    <div class="wx-post-header">
        <a href="{{ route('blog.index') }}" class="wx-back-btn">
            <i class="fas fa-arrow-left"></i> Back to Articles
        </a>
        
        @if($post->category)
        <div>
            <span class="wx-category-pill">{{ $post->category->name }}</span>
        </div>
        @endif

        <h1 class="wx-post-title">{{ $post->title }}</h1>

        @if($post->excerpt)
        <p class="wx-post-excerpt">{{ $post->excerpt }}</p>
        @endif

        <div class="wx-post-meta">
            <div class="wx-meta-item">
                <i class="far fa-user"></i>
                <span>{{ $post->author->name ?? 'CypherLabs Team' }}</span>
            </div>
            <div class="wx-meta-item">
                <i class="far fa-calendar-alt"></i>
                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}</span>
            </div>
            <div class="wx-meta-item">
                <i class="far fa-clock"></i>
                <span>{{ $post->reading_time ?? 5 }} min read</span>
            </div>
            <div class="wx-meta-item">
                <i class="far fa-eye"></i>
                <span>{{ number_format($post->views) }} views</span>
            </div>
        </div>
    </div>

    <!-- FEATURED HERO IMAGE -->
    @if($post->featured_image_url)
    <div class="wx-featured-hero-container">
        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="wx-featured-hero-img">
    </div>
    @endif

    <!-- GRID CONTAINER -->
    <div class="wx-post-container">
        
        <!-- MAIN CONTENT COLUMN -->
        <main class="wx-post-main">
            <article class="wx-post-body" id="wx-article-body">
                {!! $post->html !!}
            </article>

            <!-- AUTHOR BIO FOOTER CARD -->
            <div class="wx-author-card">
                <div class="wx-author-avatar">
                    {{ strtoupper(substr($post->author->name ?? 'C', 0, 1)) }}
                </div>
                <div class="wx-author-info">
                    <h4>Written by {{ $post->author->name ?? 'CypherLabs Team' }}</h4>
                    <p>Building high-performance web applications, developer tools, and modern web solutions at CypherLabs.</p>
                </div>
            </div>
        </main>

        <!-- STICKY SIDEBAR -->
        <aside class="wx-post-sidebar">
            
            <!-- TABLE OF CONTENTS CARD -->
            <div class="wx-sidebar-card" id="wx-toc-card" style="display: none;">
                <div class="wx-sidebar-title">
                    <i class="fas fa-list-ul"></i> Table of Contents
                </div>
                <ul class="wx-toc-list" id="wx-toc-list"></ul>
            </div>

            <!-- SHARE CARD -->
            <div class="wx-sidebar-card">
                <div class="wx-sidebar-title">
                    <i class="fas fa-share-alt"></i> Share Article
                </div>
                <div class="wx-share-btns">
                    <button class="wx-share-btn" onclick="copyArticleLink()" title="Copy Link">
                        <i class="fas fa-link"></i>
                    </button>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(request()->url()) }}" target="_blank" class="wx-share-btn" title="Share on Twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" target="_blank" class="wx-share-btn" title="Share on LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" class="wx-share-btn" title="Share on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- QUICK NAVIGATION CARD -->
            <div class="wx-sidebar-card">
                <div class="wx-sidebar-title">
                    <i class="fas fa-compass"></i> Explore More
                </div>
                <a href="{{ route('blog.index') }}" class="btn btn-outline-info w-100 btn-sm rounded-pill" style="border-color: var(--wx-border-color); color: var(--wx-neon-blue);">
                    <i class="fas fa-th-large me-1"></i> All Articles
                </a>
            </div>

        </aside>

    </div>
</div>

<!-- TOAST -->
<div id="wx-toast"><i class="fas fa-check-circle me-1"></i> Link copied to clipboard!</div>

@if($post->js)
<script>
    {!! $post->js !!}
</script>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. SCROLL READING PROGRESS BAR
        const progressBar = document.getElementById('wx-progress-bar');
        window.addEventListener('scroll', function () {
            const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
            if (totalHeight > 0) {
                const progress = (window.scrollY / totalHeight) * 100;
                progressBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
            }
        });

        // 2. AUTO GENERATE TABLE OF CONTENTS (TOC)
        const articleBody = document.getElementById('wx-article-body');
        const headings = articleBody.querySelectorAll('h2, h3');
        const tocCard = document.getElementById('wx-toc-card');
        const tocList = document.getElementById('wx-toc-list');

        if (headings.length > 0) {
            tocCard.style.display = 'block';
            
            headings.forEach((heading, index) => {
                if (!heading.id) {
                    heading.id = 'section-' + (index + 1);
                }

                const li = document.createElement('li');
                li.className = 'wx-toc-item ' + (heading.tagName.toLowerCase() === 'h3' ? 'level-3' : 'level-2');
                
                const a = document.createElement('a');
                a.href = '#' + heading.id;
                a.textContent = heading.textContent;
                a.addEventListener('click', function (e) {
                    e.preventDefault();
                    heading.scrollIntoView({ behavior: 'smooth' });
                });

                li.appendChild(a);
                tocList.appendChild(li);
            });

            // Scrollspy highlight for TOC
            window.addEventListener('scroll', function () {
                let current = '';
                headings.forEach(heading => {
                    const headingTop = heading.getBoundingClientRect().top;
                    if (headingTop <= 120) {
                        current = heading.id;
                    }
                });

                const tocItems = tocList.querySelectorAll('.wx-toc-item');
                tocItems.forEach(item => {
                    item.classList.remove('active');
                    const link = item.querySelector('a');
                    if (link && link.getAttribute('href') === '#' + current) {
                        item.classList.add('active');
                    }
                });
            });
        }

        // 3. CODE BLOCK FORMATTING & COPY BUTTON
        const preBlocks = articleBody.querySelectorAll('pre');
        preBlocks.forEach(pre => {
            if (pre.parentNode.classList.contains('wx-code-wrapper')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'wx-code-wrapper';

            const header = document.createElement('div');
            header.className = 'wx-code-header';
            header.innerHTML = `
                <div class="wx-code-dots">
                    <span class="wx-code-dot wx-dot-red"></span>
                    <span class="wx-code-dot wx-dot-yellow"></span>
                    <span class="wx-code-dot wx-dot-green"></span>
                </div>
                <button class="wx-copy-btn"><i class="far fa-copy"></i> Copy</button>
            `;

            pre.parentNode.insertBefore(wrapper, pre);
            wrapper.appendChild(header);
            wrapper.appendChild(pre);

            const copyBtn = header.querySelector('.wx-copy-btn');
            copyBtn.addEventListener('click', function () {
                const codeText = pre.textContent;
                navigator.clipboard.writeText(codeText).then(() => {
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    setTimeout(() => {
                        copyBtn.innerHTML = '<i class="far fa-copy"></i> Copy';
                    }, 2000);
                });
            });
        });
    });

    // 4. COPY ARTICLE LINK TOAST
    function copyArticleLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            const toast = document.getElementById('wx-toast');
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2500);
        });
    }
</script>

@endsection
