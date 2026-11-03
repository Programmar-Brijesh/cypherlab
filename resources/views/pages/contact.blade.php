@extends('layouts.app')

@section('title', 'Contact Us - ' . config('site.name') . ' Developer & Support Team')
@section('meta_description', 'Get in touch with ' . config('site.name') . ' developer ' . config('site.developer.name') . ' (' . config('site.developer.alias') . '). Report bugs, request tools, or send technical inquiries.')

@push('schema')
{!! \App\Services\SeoService::generateWebSiteSchema() !!}
{!! \App\Services\SeoService::generateBreadcrumbSchema([
    'Home' => route('home'),
    'Contact Us' => route('contact'),
]) !!}
@endpush

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-info text-decoration-none"><i class="fas fa-home me-1"></i>Home</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">Contact Us</li>
        </ol>
    </nav>

    <!-- Header Hero -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-8">
            <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-info bg-opacity-10 border border-info border-opacity-25 text-info mb-3">
                <i class="fas fa-headset"></i> We'd Love to Hear From You
            </div>
            <h1 class="display-4 fw-bold text-white mb-3">Contact {{ config('site.name') }}</h1>
            <p class="lead text-white-50">
                Have feedback, feature requests, or encountered a bug in one of our client-side tools? Reach out directly to author <strong>{{ config('site.developer.name') }}</strong> ({{ config('site.developer.alias') }}) or send a message below.
            </p>
        </div>
    </div>

    <!-- Alert Banners -->
    @if(session('success'))
        <div class="alert alert-success bg-success bg-opacity-10 border border-success text-success p-4 rounded-4 shadow mb-4 d-flex align-items-center gap-3">
            <i class="fas fa-check-circle fa-2x"></i>
            <div>
                <h2 class="h5 fw-bold mb-1">Message Sent Successfully!</h2>
                <p class="mb-0 small text-white-50">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger bg-danger bg-opacity-10 border border-danger text-danger p-4 rounded-4 shadow mb-4">
            <h2 class="h6 fw-bold mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following issues:</h2>
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <!-- Form Column -->
        <div class="col-lg-7">
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow">
                <h2 class="h3 fw-bold text-white mb-2">Send a Message</h2>
                <p class="text-white-50 small mb-4">Fill out the form below. We typically respond within 24 to 48 business hours.</p>

                <form action="{{ route('contact.submit') }}" method="POST" id="contact-form">
                    @csrf

                    <!-- Honeypot Field (Spam prevention) -->
                    <div style="display: none;" aria-hidden="true">
                        <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="row g-3">
                        <!-- Full Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label text-white fw-medium">Your Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-info"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" id="name" class="form-control bg-dark border-secondary text-white @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Alex Rivera" required>
                            </div>
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="col-md-6">
                            <label for="email" class="form-label text-white fw-medium">Email Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-secondary text-info"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-dark border-secondary text-white @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="name@example.com" required>
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div class="col-12">
                            <label for="subject" class="form-label text-white fw-medium">Subject / Inquiry Type <span class="text-danger">*</span></label>
                            <select name="subject" id="subject" class="form-select bg-dark border-secondary text-white @error('subject') is-invalid @enderror" required>
                                <option value="" disabled {{ old('subject') ? '' : 'selected' }}>Select a subject...</option>
                                <option value="Feature Request & Tool Suggestion" {{ old('subject') == 'Feature Request & Tool Suggestion' ? 'selected' : '' }}>Feature Request / Tool Suggestion</option>
                                <option value="Bug Report" {{ old('subject') == 'Bug Report' ? 'selected' : '' }}>Bug Report / Tool Issue</option>
                                <option value="API & Developer Inquiry" {{ old('subject') == 'API & Developer Inquiry' ? 'selected' : '' }}>API & Developer Partnership</option>
                                <option value="General Question" {{ old('subject') == 'General Question' ? 'selected' : '' }}>General Question</option>
                            </select>
                            @error('subject')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message -->
                        <div class="col-12">
                            <label for="message" class="form-label text-white fw-medium">Your Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="5" class="form-control bg-dark border-secondary text-white @error('message') is-invalid @enderror" placeholder="Describe your query, suggestion, or bug reproduction steps in detail..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-info btn-lg rounded-pill w-100 fw-bold shadow">
                                <i class="fas fa-paper-plane me-2"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Info Sidebar -->
        <div class="col-lg-5">
            <!-- Info Card -->
            <div class="card bg-dark border-secondary p-4 p-md-5 rounded-4 shadow mb-4">
                <h2 class="h4 fw-bold text-white mb-4"><i class="fas fa-address-card text-info me-2"></i>Contact Details</h2>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-3 bg-info bg-opacity-10 rounded-3 text-info">
                        <i class="fas fa-at fa-lg"></i>
                    </div>
                    <div>
                        <strong class="text-white d-block mb-1">Direct Email</strong>
                        <a href="mailto:{{ config('site.contact.email') }}" class="text-info text-decoration-none fw-semibold">
                            {{ config('site.contact.email') }}
                        </a>
                        <small class="d-block text-white-50 mt-1">Best for technical inquiries & partnerships.</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-3 bg-primary bg-opacity-10 rounded-3 text-primary">
                        <i class="fas fa-user-code fa-lg"></i>
                    </div>
                    <div>
                        <strong class="text-white d-block mb-1">Lead Developer & Author</strong>
                        <span class="text-white">{{ config('site.developer.name') }}</span>
                        <span class="badge bg-secondary ms-1">{{ config('site.developer.alias') }}</span>
                        <small class="d-block text-white-50 mt-1">Full-Stack Software Engineer & Platform Architect.</small>
                    </div>
                </div>

                <div class="d-flex align-items-start gap-3 mb-4">
                    <div class="p-3 bg-warning bg-opacity-10 rounded-3 text-warning">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <div>
                        <strong class="text-white d-block mb-1">Response Guarantee</strong>
                        <span class="text-white-50">24 – 48 Business Hours</span>
                    </div>
                </div>

                <hr class="border-secondary my-4">

                <div class="d-flex gap-2">
                    <a href="{{ route('about') }}" class="btn btn-outline-light rounded-pill btn-sm w-100">
                        <i class="fas fa-info-circle me-1"></i> About {{ config('site.name') }}
                    </a>
                    <a href="{{ route('privacy') }}" class="btn btn-outline-info rounded-pill btn-sm w-100">
                        <i class="fas fa-shield-alt me-1"></i> Privacy Policy
                    </a>
                </div>
            </div>

            <!-- Quick FAQ -->
            <div class="card bg-dark border-secondary p-4 rounded-4 shadow">
                <h3 class="h5 fw-bold text-white mb-3"><i class="fas fa-question-circle text-info me-2"></i>Frequently Asked</h3>
                <div class="accordion accordion-flush bg-transparent" id="contactFaq">
                    <div class="accordion-item bg-transparent text-white border-secondary">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent text-white shadow-none small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Are CypherLab tools free for commercial use?
                            </button>
                        </h4>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#contactFaq">
                            <div class="accordion-body text-white-50 small">
                                Yes! All client-side tools and PDF utilities are 100% free for both personal and commercial projects.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item bg-transparent text-white border-0">
                        <h4 class="accordion-header">
                            <button class="accordion-button collapsed bg-transparent text-white shadow-none small fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                How do I submit a new tool idea?
                            </button>
                        </h4>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#contactFaq">
                            <div class="accordion-body text-white-50 small">
                                Select "Feature Request & Tool Suggestion" in the subject dropdown above and describe your proposed tool!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
