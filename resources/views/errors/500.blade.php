@extends('layouts.app')

@section('title', '500 Internal Server Error - ' . config('site.name'))
@section('meta_description', 'An unexpected server error occurred. Please try again later or return to the homepage.')
@section('robots', 'noindex, follow')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-4">
                <span class="badge bg-danger bg-opacity-20 text-danger border border-danger px-3 py-2 rounded-pill fs-6">
                    <i class="fas fa-exclamation-triangle me-1"></i> HTTP 500
                </span>
            </div>
            <h1 class="display-1 fw-bold text-gradient-primary mb-3">500</h1>
            <h2 class="h3 fw-bold mb-3 text-white">Server Error</h2>
            <p class="text-white-50 mb-4">
                Something went wrong on our servers while processing your request. Our system logs have recorded this event. Please refresh or try again in a few moments.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                    <i class="fas fa-home me-2"></i> Return to Homepage
                </a>
                <a href="javascript:location.reload()" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    <i class="fas fa-redo me-2"></i> Refresh Page
                </a>
            </div>

            <div class="card bg-dark border-secondary text-start p-4 rounded-4 shadow">
                <h3 class="h5 text-info mb-3"><i class="fas fa-tools me-2"></i> Explore Popular Tools</h3>
                <ul class="list-unstyled mb-0 text-white-50 row">
                    <li class="col-6 mb-2"><a href="{{ route('tools.qr-generator') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-qrcode me-2 text-primary"></i> QR Generator</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('tools.image-compressor') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-file-image me-2 text-success"></i> Image Compressor</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('tools.json-alchemist') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-code me-2 text-warning"></i> JSON Alchemist</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('pdf.tools') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-file-pdf me-2 text-danger"></i> PDF Tools Hub</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
