<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#7c3aed">

        <!-- Default SEO fallbacks (overridden per-page by @inertiaHead) -->
        <meta name="description" content="Nepal's trusted tech review &amp; gadget price comparison platform. Discover smartphones, laptops, and accessories with honest reviews and the best prices.">
        <meta name="robots" content="index, follow">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:type" content="website">
        <meta property="og:image" content="{{ asset('images/og-default.jpg') }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:site" content="@gitinfosys">

        <title inertia>{{ config('app.name', 'Git Infosys') }}</title>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Theme: apply before render to avoid flash -->
        <script>
            (function() {
                var t = localStorage.getItem('theme');
                if (t !== 'light') { document.documentElement.classList.add('dark'); }
            })();
        </script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
