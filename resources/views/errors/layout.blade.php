<!DOCTYPE html>
{{-- Self-contained on purpose: these pages must render when the database or the app itself is down. --}}
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title') | Git Infosys</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <style>
        :root { --bg: #fff; --fg: #232f3f; --muted: #3c5673; --line: #dfe5ec; --accent: #a85a00; }
        @media (prefers-color-scheme: dark) { :root { --bg: #0e141c; --fg: #f3f6f9; --muted: #a9bacd; --line: #2b3a4d; --accent: #ff991b; } }
        * { box-sizing: border-box; margin: 0; }
        body { min-height: 100vh; display: flex; align-items: center; background: var(--bg); color: var(--fg); font: 16px/1.6 system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; }
        main { width: 100%; max-width: 40rem; margin: 0 auto; padding: 3rem 1.25rem; }
        p.code { color: var(--accent); font-size: .875rem; font-weight: 500; }
        h1 { margin-top: .5rem; font-size: clamp(1.75rem, 5vw, 2.25rem); line-height: 1.15; letter-spacing: -.02em; }
        p.text { margin-top: .75rem; color: var(--muted); font-size: 1.0625rem; }
        nav { margin-top: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--line); display: flex; gap: 1.25rem; flex-wrap: wrap; }
        a { color: var(--fg); font-weight: 500; text-underline-offset: 4px; }
    </style>
</head>
<body>
    <main>
        <p class="code">Error @yield('code')</p>
        <h1>@yield('title')</h1>
        <p class="text">@yield('message')</p>
        <nav>@yield('links')<a href="/">Home</a></nav>
    </main>
</body>
</html>
