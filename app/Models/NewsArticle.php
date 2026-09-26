<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    use BacksUpImages, HasSeo;

    protected array $imageBackupFields = ['thumbnail'];

    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'category', 'thumbnail',
        'is_published', 'views_count', 'meta_description',
        'react_happy', 'react_sad', 'react_love', 'react_like', 'react_funny', 'react_angry',
    ];

    protected $casts = ['is_published' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function($m) {
            $m->slug ??= Str::slug($m->title);
            if (!$m->user_id) {
                $m->user_id = auth()->id() ?? User::where('is_admin', true)->value('id') ?? User::value('id') ?? 1;
            }
        });
    }

    public function author() { return $this->belongsTo(User::class, 'user_id'); }
}
