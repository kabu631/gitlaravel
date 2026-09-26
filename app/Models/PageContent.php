<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
    use HasSeo;

    protected $fillable = ['page', 'heading', 'subheading', 'body', 'meta_description', 'extra'];

    protected $casts = ['extra' => 'array'];

    public static function forPage(string $page): ?self
    {
        return static::where('page', $page)->first();
    }
}
