<?php

namespace App\Support;

use Illuminate\Http\Request;

class PerPage
{
    public const OPTIONS = [10, 25, 50, 100];

    /** Rows-per-page requested via ?per_page=, limited to the allowed options. */
    public static function resolve(Request $request, int $default): int
    {
        $requested = $request->integer('per_page');

        return in_array($requested, [...self::OPTIONS, $default], true) ? $requested : $default;
    }
}
