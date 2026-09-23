<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'admin@gitinfosys.com')->first();
\Illuminate\Support\Facades\Auth::login($user);

$req = \Illuminate\Http\Request::create('/secure-admin/brands', 'GET');
$req->setLaravelSession($app['session.store']);
$response = $app->handle($req);
$html = $response->getContent();

if (preg_match('/<th[^>]*class="[^"]*fi-ta-selection-header-cell[^"]*"[^>]*>(.*?)<\/th>/is', $html, $th)) {
    echo "Header selection cell:\n" . $th[0] . PHP_EOL;
}
if (preg_match('/<td[^>]*class="[^"]*fi-ta-selection-cell[^"]*"[^>]*>(.*?)<\/td>/is', $html, $td)) {
    echo "\nRow selection cell:\n" . $td[0] . PHP_EOL;
}
