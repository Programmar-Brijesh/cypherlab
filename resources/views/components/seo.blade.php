@props([
    'title' => null,
    'description' => null,
    'robots' => null,
    'canonical' => null,
    'image' => null,
    'type' => 'website',
])

@php
    $resolvedTitle = $title ?: trim($__env->yieldContent('title', config('site.name') . ' - ' . config('site.tagline')));
    $resolvedDescription = $description ?: trim($__env->yieldContent('meta_description', config('site.description')));
    $resolvedRobots = $robots ?: trim($__env->yieldContent('robots', 'index, follow'));
    $resolvedCanonical = $canonical ?: trim($__env->yieldContent('canonical', url()->current()));
    $resolvedImage = $image ?: trim($__env->yieldContent('og_image', asset(config('site.default_og_image'))));
@endphp

{!! \App\Services\SeoService::renderMeta([
    'title' => $resolvedTitle,
    'description' => $resolvedDescription,
    'robots' => $resolvedRobots,
    'canonical' => $resolvedCanonical,
    'image' => $resolvedImage,
    'type' => $type,
]) !!}
