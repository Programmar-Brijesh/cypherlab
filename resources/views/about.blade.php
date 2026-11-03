@extends('layouts.app')

@section('title', 'About ' . config('site.name') . ' - Developed by ' . config('site.developer.name') . ' (' . config('site.developer.alias') . ')')
@section('meta_description', 'Learn about ' . config('site.name') . ', a suite of free client-side developer utilities and technical guides architected by ' . config('site.developer.name') . ' (aka ' . config('site.developer.alias') . ').')

@push('schema')
{!! \App\Services\SeoService::generateWebSiteSchema() !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'About ' . config('site.name') => route('about'),
]) !!}
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-info text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">About</li>
        </ol>
    </nav>

    <!-- Header Hero -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-8">
            <h1 class="display-4 fw-bold text-white mb-3">About {{ config('site.name') }}</h1>
            <p class="lead text-white-50">
                {{ config('site.name') }} is a high-performance, privacy-first platform offering client-side developer utilities, PDF processing tools, and comprehensive technical guides. Built with standard-compliant software engineering practices for speed, security, and developer productivity.
            </p>
        </div>
    </div>

    <!-- Developer Section -->
    <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-5">
        <div class="row align-items-center">
            <div class="col-md-3 text-center mb-4 mb-md-0">
                <div class="mx-auto rounded-circle bg-primary bg-gradient d-flex align-items-center justify-content-center text-white display-3 fw-bold shadow" style="width: 120px; height: 120px;">
                    {{ strtoupper(substr(config('site.developer.name'), 0, 1) . substr(strstr(config('site.developer.name'), ' '), 1, 1)) }}
                </div>
            </div>
            <div class="col-md-9">
                <span class="badge bg-primary px-3 py-2 rounded-pill mb-2">Creator & Lead Architect</span>
                <h2 class="h3 fw-bold text-white mb-2">{{ config('site.developer.name') }}</h2>
                <h3 class="h6 text-info mb-3">Known online as <strong>{{ config('site.developer.alias') }}</strong></h3>
                <p class="text-white-50 mb-3">
                    {{ config('site.developer.bio') }} {{ config('site.name') }} was engineered to give developers, designers, and privacy-conscious users instantaneous tools without server data retention or complex signups.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('home') }}" class="btn btn-outline-info rounded-pill btn-sm"><i class="fas fa-tools me-1"></i> Explore Developer Tools</a>
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-light rounded-pill btn-sm"><i class="fas fa-book me-1"></i> Technical Tutorials</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Mission & Principles -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100 p-4 rounded-4 shadow-sm">
                <div class="text-primary display-6 mb-3"><i class="fas fa-shield-alt"></i></div>
                <h3 class="h5 fw-bold text-white">100% Client-Side Privacy</h3>
                <p class="text-white-50 small mb-0">
                    Your code snippets, images, passwords, and data stay on your device. Client-side tools execute locally using modern Web APIs without storing data on remote servers.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100 p-4 rounded-4 shadow-sm">
                <div class="text-success display-6 mb-3"><i class="fas fa-bolt"></i></div>
                <h3 class="h5 fw-bold text-white">Ultra-Fast Performance</h3>
                <p class="text-white-50 small mb-0">
                    Lightweight architecture with zero bloat guarantees instant load times, smooth client interaction, and efficient PDF processing.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100 p-4 rounded-4 shadow-sm">
                <div class="text-warning display-6 mb-3"><i class="fas fa-code"></i></div>
                <h3 class="h5 fw-bold text-white">Developer-Centric Engineering</h3>
                <p class="text-white-50 small mb-0">
                    Designed by developers for developers. Clean interfaces, standard outputs, and robust tools tailored for day-to-day software development workflows.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
