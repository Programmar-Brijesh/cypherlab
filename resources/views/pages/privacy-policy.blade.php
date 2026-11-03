@extends('layouts.app')

@section('title', 'Privacy Policy - ' . config('site.name') . ' (Client-Side & Data Security)')
@section('meta_description', 'Read the official Privacy Policy of ' . config('site.name') . '. Learn how our 100% client-side developer utilities ensure total privacy, zero server data retention, and strict security.')

@push('schema')
{!! \App\Services\SeoService::generateWebSiteSchema() !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Privacy Policy' => route('privacy'),
]) !!}
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-info text-decoration-none"><i class="fas fa-home me-1"></i>Home</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">Privacy Policy</li>
        </ol>
    </nav>

    <!-- Header Hero -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-10">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-info bg-opacity-10 border border-info border-opacity-25 text-info mb-3">
                <i class="fas fa-shield-alt"></i> Privacy & Transparency Guarantee
            </div>
            <h1 class="display-4 fw-bold text-white mb-3">Privacy Policy</h1>
            <p class="lead text-white-50 mb-0">
                Last updated: <strong>{{ date('F j, Y') }}</strong>. At <strong>{{ config('site.name') }}</strong>, operated by <strong>{{ config('site.developer.name') }}</strong> ({{ config('site.developer.alias') }}), your data privacy and digital autonomy are fundamental engineering principles.
            </p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Client-Side Architecture Guarantee -->
            <div class="card bg-dark border-info border-opacity-25 p-4 p-md-5 rounded-4 shadow mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info">
                        <i class="fas fa-microchip fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold text-white mb-1">1. Client-Side Processing Architecture</h2>
                        <span class="badge bg-success bg-gradient">Zero Server Uploads for Core Tools</span>
                    </div>
                </div>
                <p class="text-white-50">
                    The core philosophy behind <strong>{{ config('site.name') }}</strong> is client-side execution. Utilities such as our <strong>QR Code Generator</strong>, <strong>Image Compressor</strong>, <strong>JSON Alchemist</strong>, <strong>Password Vault</strong>, <strong>Base64 Vanguard</strong>, <strong>Case Alchemy</strong>, and <strong>URI Envoy</strong> run 100% locally in your web browser using HTML5 Web APIs (Canvas, Web Crypto API, JavaScript V8 Engine).
                </p>
                <div class="alert alert-dark border-secondary text-white-50 rounded-3 mb-0">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <strong>What this means:</strong> Your passwords, uploaded images, raw JSON payloads, sensitive text strings, and Base64 tokens are <strong>never sent to our servers</strong> or saved in database storage.
                </div>
            </div>

            <!-- PDF Tools Privacy -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary">
                        <i class="fas fa-file-pdf fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold text-white mb-1">2. Document & PDF Tools Privacy</h2>
                        <span class="badge bg-secondary">Temporary Server Processing</span>
                    </div>
                </div>
                <p class="text-white-50">
                    For server-assisted tools (such as complex PDF merging, splitting, compression, or conversion), files are processed in encrypted temporary storage isolated per request session:
                </p>
                <ul class="text-white-50 mb-0">
                    <li class="mb-2"><strong class="text-white">Ephemeral Files:</strong> Uploaded documents are automatically deleted from server memory immediately after processing or download completion.</li>
                    <li class="mb-2"><strong class="text-white">No Inspection:</strong> We do not inspect, catalog, mine, or share document content processed through PDF tools.</li>
                    <li><strong class="text-white">HTTPS Encryption:</strong> All data transmissions are encrypted using standard TLS/SSL (HTTPS) protocols.</li>
                </ul>
            </div>

            <!-- Information We Collect -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-warning">
                        <i class="fas fa-database fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold text-white mb-1">3. Information We Collect</h2>
                    </div>
                </div>
                <h3 class="h6 text-info mb-2">A. Account & Auth Data (Optional Sign-In)</h3>
                <p class="text-white-50 mb-3">
                    If you register an account or sign in using Google OAuth, we collect basic profile details: name, email address, profile picture URL, and encrypted credentials required for authentication session maintenance.
                </p>

                <h3 class="h6 text-info mb-2">B. Server Log Data & Technical Analytics</h3>
                <p class="text-white-50 mb-0">
                    Like standard Web platforms, our servers log non-personally identifiable technical diagnostic info (IP address, browser user-agent, HTTP request status, timestamp, and referring URL) strictly for server load monitoring, DDoS prevention, and bug debugging.
                </p>
            </div>

            <!-- Cookies & Storage -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info">
                        <i class="fas fa-cookie-bite fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold text-white mb-1">4. Cookies & Local Storage</h2>
                    </div>
                </div>
                <p class="text-white-50">
                    We use cookies and browser local storage strictly for functional purposes:
                </p>
                <ul class="text-white-50 mb-0">
                    <li class="mb-2"><strong class="text-white">Session Cookies:</strong> Essential cookies required to maintain user authentication sessions and prevent CSRF vulnerabilities.</li>
                    <li class="mb-2"><strong class="text-white">Local Storage:</strong> Storing local UI preferences (e.g. active theme, editor preferences) directly in your browser.</li>
                    <li><strong class="text-white">Third-Party Scripts:</strong> Standard CDNs (e.g. FontAwesome, Google Fonts) may collect standard request headers when serving asset libraries.</li>
                </ul>
            </div>

            <!-- GDPR & CCPA Rights -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="p-3 bg-success bg-opacity-10 rounded-3 text-success">
                        <i class="fas fa-user-shield fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="h4 fw-bold text-white mb-1">5. Your Data Protection Rights (GDPR & CCPA)</h2>
                    </div>
                </div>
                <p class="text-white-50">
                    Regardless of location, users enjoy rights regarding personal information stored in account profiles:
                </p>
                <div class="row g-3 text-white-50">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <strong class="text-white d-block mb-1"><i class="fas fa-eye text-info me-1"></i> Access & Export</strong>
                            Request a copy of any personal profile data associated with your registered email.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-dark border border-secondary">
                            <strong class="text-white d-block mb-1"><i class="fas fa-trash-alt text-danger me-1"></i> Right to Deletion</strong>
                            Request immediate account termination and total erasure of stored profile info.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Policy Updates -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow">
                <h2 class="h4 fw-bold text-white mb-3">6. Questions & Contact</h2>
                <p class="text-white-50 mb-3">
                    If you have questions regarding this Privacy Policy or data security practices, please contact the site author directly:
                </p>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <a href="{{ route('contact') }}" class="btn btn-info rounded-pill"><i class="fas fa-envelope me-2"></i>Contact Us Form</a>
                    <a href="mailto:{{ config('site.contact.email') }}" class="btn btn-outline-light rounded-pill"><i class="fas fa-paper-plane me-2"></i>{{ config('site.contact.email') }}</a>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="col-lg-4">
            <div class="card bg-dark border-info border-opacity-25 p-4 rounded-4 shadow mb-4 sticky-top" style="top: 100px;">
                <h3 class="h5 fw-bold text-white mb-3"><i class="fas fa-lock text-info me-2"></i>Privacy Summary</h3>
                <hr class="border-secondary mb-3">
                <div class="d-flex align-items-start gap-3 mb-3">
                    <i class="fas fa-shield-alt text-success fs-5 mt-1"></i>
                    <div>
                        <strong class="text-white d-block">100% Client-Side</strong>
                        <small class="text-white-50">Browser-based data processing for maximum privacy.</small>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-3">
                    <i class="fas fa-ban text-danger fs-5 mt-1"></i>
                    <div>
                        <strong class="text-white d-block">No Data Mining</strong>
                        <small class="text-white-50">We never sell, rent, or monetize your inputs or text.</small>
                    </div>
                </div>
                <div class="d-flex align-items-start gap-3 mb-4">
                    <i class="fas fa-user-check text-info fs-5 mt-1"></i>
                    <div>
                        <strong class="text-white d-block">Architect</strong>
                        <small class="text-white-50">{{ config('site.developer.name') }} ({{ config('site.developer.alias') }})</small>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-outline-info w-100 rounded-pill btn-sm">
                    <i class="fas fa-headset me-1"></i> Have Questions? Contact Us
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
