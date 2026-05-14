<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#7c3aed">

        <!-- SEO Meta Tags (Server Rendered for Bots) -->
        @php
            $seo = $page['props']['seo'] ?? [];
            $title = !empty($seo['title']) ? $seo['title'] . ' | Git Infosys' : config('app.name', 'Git Infosys');
            $desc = $seo['description'] ?? "Nepal's trusted tech review & gadget price comparison platform. Discover smartphones, laptops, and accessories with honest reviews and the best prices.";
            $image = $seo['image'] ?? asset('images/og-default.jpg');
            $url = $seo['canonical'] ?? request()->url();
            $type = $seo['type'] ?? 'website';
            $noindex = $seo['noindex'] ?? false;
        @endphp

        <title inertia>{{ $title }}</title>
        <meta inertia head-key="description" name="description" content="{{ $desc }}">
        <meta inertia head-key="robots" name="robots" content="{{ $noindex ? 'noindex,nofollow' : 'index, follow' }}">
        <link inertia head-key="canonical" rel="canonical" href="{{ $url }}">

        <meta inertia head-key="og:site_name" property="og:site_name" content="{{ config('app.name') }}">
        <meta inertia head-key="og:type" property="og:type" content="{{ $type }}">
        <meta inertia head-key="og:title" property="og:title" content="{{ $title }}">
        <meta inertia head-key="og:description" property="og:description" content="{{ $desc }}">
        <meta inertia head-key="og:image" property="og:image" content="{{ $image }}">
        <meta inertia head-key="og:url" property="og:url" content="{{ $url }}">

        <meta inertia head-key="twitter:card" name="twitter:card" content="summary_large_image">
        <meta inertia head-key="twitter:site" name="twitter:site" content="@gitinfosys">
        <meta inertia head-key="twitter:title" name="twitter:title" content="{{ $title }}">
        <meta inertia head-key="twitter:description" name="twitter:description" content="{{ $desc }}">
        <meta inertia head-key="twitter:image" name="twitter:image" content="{{ $image }}">

        @if(!empty($seo['published_at']))
            <meta inertia head-key="article:published_time" property="article:published_time" content="{{ $seo['published_at'] }}">
        @endif
        @if(!empty($seo['modified_at']))
            <meta inertia head-key="article:modified_time" property="article:modified_time" content="{{ $seo['modified_at'] }}">
        @endif
        
        @if(!empty($seo['json_ld']))
            <script inertia head-key="json-ld" type="application/ld+json">
                {!! json_encode($seo['json_ld'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
            </script>
        @endif

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
