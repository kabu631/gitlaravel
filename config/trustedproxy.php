<?php

return [
    // Your load balancer's or CDN's IP addresses, comma separated, or * when every request comes through it
    // (for example behind Cloudflare). Needed so visitor IPs and https are read correctly. Empty = trust nobody.
    'proxies' => env('TRUSTED_PROXIES'),
];
