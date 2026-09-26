<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#f5a623">
        <link rel="icon" type="image/png" href="/favicon.png">
        <link rel="shortcut icon" href="/favicon.png">

        <!-- SEO Meta Tags (Server Rendered for Bots) -->
        @php
            $seo = $page['props']['seo'] ?? \App\Support\Seo::make();
            $settings = \App\Models\SiteSetting::getAllSettings();
        @endphp

        <title inertia>{{ $seo['full_title'] }}</title>
        <meta inertia head-key="description" name="description" content="{{ $seo['description'] ?? '' }}">
        @if(!empty($seo['keywords']))
            <meta inertia head-key="keywords" name="keywords" content="{{ $seo['keywords'] }}">
        @endif
        <meta inertia head-key="robots" name="robots" content="{{ $seo['robots'] }}">
        <link inertia head-key="canonical" rel="canonical" href="{{ $seo['canonical'] }}">

        @if(!empty($settings['seo_google_verification']))
            <meta name="google-site-verification" content="{{ $settings['seo_google_verification'] }}">
        @endif
        @if(!empty($settings['seo_bing_verification']))
            <meta name="msvalidate.01" content="{{ $settings['seo_bing_verification'] }}">
        @endif
        @if(!empty($settings['seo_facebook_app_id']))
            <meta property="fb:app_id" content="{{ $settings['seo_facebook_app_id'] }}">
        @endif

        <meta inertia head-key="og:site_name" property="og:site_name" content="{{ config('app.name') }}">
        <meta inertia head-key="og:type" property="og:type" content="{{ $seo['type'] }}">
        <meta inertia head-key="og:title" property="og:title" content="{{ $seo['og_title'] }}">
        <meta inertia head-key="og:description" property="og:description" content="{{ $seo['og_description'] ?? '' }}">
        <meta inertia head-key="og:image" property="og:image" content="{{ $seo['og_image'] }}">
        <meta inertia head-key="og:image:alt" property="og:image:alt" content="{{ $seo['image_alt'] ?? '' }}">
        <meta inertia head-key="og:url" property="og:url" content="{{ $seo['canonical'] }}">

        <meta inertia head-key="twitter:card" name="twitter:card" content="{{ $seo['twitter_card'] }}">
        @if(!empty($seo['twitter_site']))
            <meta inertia head-key="twitter:site" name="twitter:site" content="{{ $seo['twitter_site'] }}">
        @endif
        <meta inertia head-key="twitter:title" name="twitter:title" content="{{ $seo['twitter_title'] }}">
        <meta inertia head-key="twitter:description" name="twitter:description" content="{{ $seo['twitter_description'] ?? '' }}">
        <meta inertia head-key="twitter:image" name="twitter:image" content="{{ $seo['twitter_image'] }}">
        <meta inertia head-key="twitter:image:alt" name="twitter:image:alt" content="{{ $seo['image_alt'] ?? '' }}">

        @if(!empty($seo['published_at']))
            <meta inertia head-key="article:published_time" property="article:published_time" content="{{ $seo['published_at'] }}">
        @endif
        @if(!empty($seo['modified_at']))
            <meta inertia head-key="article:modified_time" property="article:modified_time" content="{{ $seo['modified_at'] }}">
        @endif
        
        @if(!empty($seo['json_ld']))
            <script inertia head-key="json-ld" type="application/ld+json">
                {!! json_encode($seo['json_ld'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}
            </script>
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
        <link rel="dns-prefetch" href="//fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Theme: Light mode by default; apply dark mode only if explicitly selected -->
        <script>
            (function() {
                try {
                    var t = localStorage.getItem('git_infosys_theme');
                    if (t === 'dark') {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (e) {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased overflow-x-clip">
        @inertia
    </body>
</html>
