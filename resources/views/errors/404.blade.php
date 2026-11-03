@extends('layouts.app')

@section('title', '404 Page Not Found - ' . config('site.name'))
@section('meta_description', 'The page you are looking for does not exist or has been moved. Explore ' . config('site.name') . ' developer tools and blog tutorials.')
@section('robots', 'noindex, follow')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h1 class="display-1 fw-bold text-gradient-primary mb-3">404</h1>
            <h2 class="h3 fw-bold mb-3 text-white">Resource Not Found</h2>
            <p class="text-white-50 mb-4">
                The page or tool URL you were looking for doesn't exist, was renamed, or is temporarily unavailable.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill px-4">
                    <i class="fas fa-home me-2"></i> Explore Tools
                </a>
                <a href="{{ route('blog.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4">
                    <i class="fas fa-newspaper me-2"></i> Read Blog
                </a>
            </div>

            <div class="card bg-dark border-secondary text-start p-4 rounded-4 shadow">
                <h3 class="h5 text-info mb-3"><i class="fas fa-compass me-2"></i> Popular Developer Tools</h3>
                <ul class="list-unstyled mb-0 text-white-50 row">
                    <li class="col-6 mb-2"><a href="{{ route('tools.qr-generator') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-qrcode me-2 text-primary"></i> QR Generator</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('tools.image-compressor') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-file-image me-2 text-success"></i> Image Compressor</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('tools.json-alchemist') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-code me-2 text-warning"></i> JSON Alchemist</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('tools.password-generator') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-key me-2 text-info"></i> Password Vault</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('pdf.tools') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-file-pdf me-2 text-danger"></i> PDF Tools Hub</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-user-code me-2 text-secondary"></i> About Developer</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
