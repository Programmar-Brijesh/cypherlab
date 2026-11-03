@extends('layouts.app')

@section('title', 'Terms of Service - ' . config('site.name'))
@section('meta_description', 'Read the official Terms of Service for using ' . config('site.name') . ' free online developer tools and technical content.')

@push('schema')
{!! \App\Services\SeoService::generateWebSiteSchema() !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Terms of Service' => route('terms'),
]) !!}
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-info text-decoration-none"><i class="fas fa-home me-1"></i>Home</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">Terms of Service</li>
        </ol>
    </nav>

    <!-- Header Hero -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-10">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-info bg-opacity-10 border border-info border-opacity-25 text-info mb-3">
                <i class="fas fa-gavel"></i> Terms & Service Agreement
            </div>
            <h1 class="display-4 fw-bold text-white mb-3">Terms of Service</h1>
            <p class="lead text-white-50 mb-0">
                Effective date: <strong>{{ date('F j, Y') }}</strong>. Please read these terms carefully before utilizing developer tools, software utilities, or content on <strong>{{ config('site.name') }}</strong>.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Acceptance of Terms -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <h2 class="h4 fw-bold text-white mb-3">1. Acceptance of Terms</h2>
                <p class="text-white-50 mb-0">
                    By accessing or using <strong>{{ config('site.name') }}</strong>, you agree to be bound by these Terms of Service and our <a href="{{ route('privacy') }}" class="text-info text-decoration-none fw-semibold">Privacy Policy</a>. If you do not agree to all terms, you may not access or use our services.
                </p>
            </div>

            <!-- Use of Tools & Services -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <h2 class="h4 fw-bold text-white mb-3">2. Use of Developer Tools & Services</h2>
                <p class="text-white-50">
                    <strong>{{ config('site.name') }}</strong> provides free client-side developer utilities (including QR generation, image compression, JSON formatting, password generation, Base64 conversion, and PDF manipulation).
                </p>
                <ul class="text-white-50 mb-0">
                    <li class="mb-2"><strong class="text-white">Permitted Use:</strong> You may use all tools for personal, academic, open-source, or commercial projects without licensing fees.</li>
                    <li class="mb-2"><strong class="text-white">Prohibited Activities:</strong> You agree not to attempt to breach site security, launch automated denial-of-service (DDoS) attacks, scrape or reverse engineer backend endpoints maliciously, or transmit malware.</li>
                </ul>
            </div>

            <!-- Disclaimer of Warranties -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <h2 class="h4 fw-bold text-white mb-3">3. Disclaimer of Warranties</h2>
                <p class="text-white-50 mb-0">
                    All tools, code samples, guides, and services are provided <strong>"AS IS"</strong> and <strong>"AS AVAILABLE"</strong> without warranties of any kind, whether express or implied. While we engineer every tool for precision and reliability, <strong>{{ config('site.name') }}</strong> does not guarantee uninterrupted operation or zero error rates for converted files or processed strings.
                </p>
            </div>

            <!-- Limitation of Liability -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <h2 class="h4 fw-bold text-white mb-3">4. Limitation of Liability</h2>
                <p class="text-white-50 mb-0">
                    In no event shall <strong>{{ config('site.name') }}</strong>, its developer <strong>{{ config('site.developer.name') }}</strong>, or affiliates be liable for any direct, indirect, incidental, or consequential damages resulting from your reliance on generated outputs or inability to access services.
                </p>
            </div>

            <!-- Contact Information -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow">
                <h2 class="h4 fw-bold text-white mb-3">5. Contact Information</h2>
                <p class="text-white-50 mb-3">
                    If you have any questions regarding these Terms of Service, please reach out through our official contact channels.
                </p>
                <a href="{{ route('contact') }}" class="btn btn-info rounded-pill"><i class="fas fa-envelope me-2"></i>Contact Us</a>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="col-lg-4">
            <div class="card bg-dark border-secondary p-4 rounded-4 shadow sticky-top" style="top: 100px;">
                <h3 class="h5 fw-bold text-white mb-3"><i class="fas fa-info-circle text-info me-2"></i>Key Summary</h3>
                <hr class="border-secondary mb-3">
                <p class="text-white-50 small mb-3">
                    <strong class="text-white">100% Free Access:</strong> Commercial and personal use allowed.
                </p>
                <p class="text-white-50 small mb-3">
                    <strong class="text-white">Fair Use:</strong> No malicious automated abuse or scraping.
                </p>
                <p class="text-white-50 small mb-4">
                    <strong class="text-white">Author:</strong> {{ config('site.developer.name') }} ({{ config('site.developer.alias') }}).
                </p>
                <a href="{{ route('privacy') }}" class="btn btn-outline-info w-100 rounded-pill btn-sm mb-2">
                    <i class="fas fa-shield-alt me-1"></i> View Privacy Policy
                </a>
                <a href="{{ route('contact') }}" class="btn btn-outline-light w-100 rounded-pill btn-sm">
                    <i class="fas fa-headset me-1"></i> Contact Support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
