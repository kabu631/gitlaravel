<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    protected $fillable = ['page', 'heading', 'subheading', 'body', 'meta_description', 'extra'];

    protected $casts = ['extra' => 'array'];

    public static function forPage(string $page): ?self
    {
        return static::where('page', $page)->first();
    }
}
