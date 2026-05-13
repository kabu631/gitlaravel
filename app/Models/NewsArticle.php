<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'category', 'thumbnail',
        'is_published', 'views_count', 'meta_description',
        'react_happy', 'react_sad', 'react_love', 'react_like', 'react_funny', 'react_angry',
    ];

    protected $casts = ['is_published' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(fn($m) => $m->slug ??= Str::slug($m->title));
    }

    public function author() { return $this->belongsTo(User::class, 'user_id'); }
}
