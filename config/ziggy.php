<?php

// Routes the browser never needs. Leaving them out keeps every page's inline route list
// small and stops the admin panel's path from being printed into public HTML.
return [
    'except' => [
        'filament.*',
        'livewire.*',
        'sanctum.*',
        'ignition.*',
        'storage.*',
        'debugbar.*',
        'sitemap',
        'robots',
    ],
];
