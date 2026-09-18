<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Path
    |--------------------------------------------------------------------------
    |
    | The URI segment the Filament admin panel is served from. Keep this hard
    | to guess in production. Read through config (not env() directly) so the
    | value survives `php artisan config:cache`.
    |
    */

    'path' => env('FILAMENT_PATH', 'secure-admin'),

];
