<footer class="bg-dark text-white mt-5">
    <div class="container py-4 text-center">
        <p class="mb-1">&copy; {{ now()->year }} <strong>{{ config('site.name') }}</strong>. All rights reserved.</p>
        <p class="small text-muted mb-2">
            Developed with <i class="fas fa-heart text-danger"></i> by 
            <a href="{{ route('about') }}" class="text-info text-decoration-none fw-semibold">
                {{ config('site.developer.name') }} ({{ config('site.developer.alias') }})
            </a>
        </p>
        <div>
            <a href="{{ route('about') }}" class="text-white-50 me-3 small">About</a>
            <a href="{{ route('home') }}" class="text-white-50 me-3 small">Tools</a>
            <a href="{{ route('blog.index') }}" class="text-white-50 me-3 small">Blog</a>
            <a href="{{ route('privacy') }}" class="text-white-50 me-3 small">Privacy Policy</a>
            <a href="{{ route('terms') }}" class="text-white-50 me-3 small">Terms of Service</a>
            <a href="{{ route('contact') }}" class="text-white-50 small">Contact</a>
        </div>
    </div>
</footer>