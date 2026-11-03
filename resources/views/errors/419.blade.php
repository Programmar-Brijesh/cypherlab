@extends('layouts.app')

@section('title', '419 Page Expired - ' . config('site.name'))
@section('meta_description', 'Your session or CSRF token has expired. Please refresh the page and try submitting again.')
@section('robots', 'noindex, follow')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-4">
                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning px-3 py-2 rounded-pill fs-6">
                    <i class="fas fa-history me-1"></i> HTTP 419
                </span>
            </div>
            <h1 class="display-1 fw-bold text-gradient-primary mb-3">419</h1>
            <h2 class="h3 fw-bold mb-3 text-white">Page & Session Expired</h2>
            <p class="text-white-50 mb-4">
                Your form session or CSRF security token has expired due to inactivity. Please refresh the page and re-submit your request.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
                <a href="javascript:history.back()" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Go Back & Retry
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    <i class="fas fa-home me-2"></i> Homepage
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
