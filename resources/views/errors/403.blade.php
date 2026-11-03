@extends('layouts.app')

@section('title', '403 Access Forbidden - ' . config('site.name'))
@section('meta_description', 'You do not have permission to access this resource or page.')
@section('robots', 'noindex, follow')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-4">
                <span class="badge bg-warning bg-opacity-20 text-warning border border-warning px-3 py-2 rounded-pill fs-6">
                    <i class="fas fa-lock me-1"></i> HTTP 403
                </span>
            </div>
            <h1 class="display-1 fw-bold text-gradient-primary mb-3">403</h1>
            <h2 class="h3 fw-bold mb-3 text-white">Access Forbidden</h2>
            <p class="text-white-50 mb-4">
                You do not have the required permissions to access this page or area. If you believe this is an error, please log in with an authorized account.
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                    <i class="fas fa-home me-2"></i> Go to Homepage
                </a>
                <a href="{{ route('login.page') }}" class="btn btn-outline-info btn-lg rounded-pill px-4">
                    <i class="fas fa-sign-in-alt me-2"></i> User Login
                </a>
            </div>

            <div class="card bg-dark border-secondary text-start p-4 rounded-4 shadow">
                <h3 class="h5 text-info mb-3"><i class="fas fa-compass me-2"></i> Quick Links</h3>
                <ul class="list-unstyled mb-0 text-white-50 row">
                    <li class="col-6 mb-2"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-tools me-2 text-primary"></i> Developer Tools</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('blog.index') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-book me-2 text-success"></i> Blog Tutorials</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('about') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-info-circle me-2 text-warning"></i> About Platform</a></li>
                    <li class="col-6 mb-2"><a href="{{ route('login.page') }}" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-user me-2 text-info"></i> Account Login</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
