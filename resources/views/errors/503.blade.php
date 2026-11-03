@extends('layouts.app')

@section('title', '503 Service Maintenance - ' . config('site.name'))
@section('meta_description', 'CypherLab is currently undergoing scheduled system maintenance. We will be back online shortly.')
@section('robots', 'noindex, follow')

@section('content')
<div class="container py-5 text-center my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="mb-4">
                <span class="badge bg-info bg-opacity-20 text-info border border-info px-3 py-2 rounded-pill fs-6">
                    <i class="fas fa-tools me-1"></i> Maintenance Mode
                </span>
            </div>
            <h1 class="display-1 fw-bold text-gradient-primary mb-3">503</h1>
            <h2 class="h3 fw-bold mb-3 text-white">Under Scheduled Maintenance</h2>
            <p class="text-white-50 mb-4">
                {{ config('site.name') }} is currently undergoing scheduled system updates to improve performance and security. We will be back online shortly!
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap mb-5">
                <a href="javascript:location.reload()" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm">
                    <i class="fas fa-sync-alt me-2"></i> Check Status
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
