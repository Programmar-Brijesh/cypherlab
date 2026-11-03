<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1996681861403242"
     crossorigin="anonymous"></script>

     <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-95ZYXBPQF8"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-95ZYXBPQF8');
</script>
    
    <x-seo />

    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @stack('schema')
</head>
<body>

@include('components.header')

<main class="@yield('main_class', 'container py-4')">
    @yield('content')
</main>

@include('components.footer')

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

@yield('scripts')

</body>
</html>