<?php
$routes = [
    '/',
    '/gadgets',
    '/reviews',
    '/news',
    '/guides',
    '/compare',
    '/price-tracker',
    '/tech-lab',
    '/pc-builder',
    '/about',
    '/contact',
    '/services',
    '/terms-and-conditions',
    '/privacy-policy',
];

$allPassed = true;
foreach ($routes as $r) {
    $ctx = stream_context_create(['http' => ['ignore_errors' => true, 'timeout' => 5]]);
    $fp = @fopen('http://127.0.0.1:8000' . $r, 'r', false, $ctx);
    $status = $http_response_header[0] ?? 'NO RESPONSE';
    $is200 = str_contains($status, '200');
    echo ($is200 ? "✅ " : "❌ ") . "[$status] $r\n";
    if (!$is200) $allPassed = false;
    if ($fp) fclose($fp);
}

exit($allPassed ? 0 : 1);
