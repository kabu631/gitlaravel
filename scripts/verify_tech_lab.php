<?php

$urls = [
    'http://127.0.0.1:8000/tech-lab',
    'http://127.0.0.1:8000/',
    'http://127.0.0.1:8000/products',
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "$url => HTTP $status (" . strlen($response) . " bytes)\n";
    if ($status !== 200) {
        exit(1);
    }
}

echo "ALL VERIFIED!\n";
