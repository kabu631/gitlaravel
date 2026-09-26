<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use BacksUpImages, HasSeo;

    protected array $imageBackupFields = ['cover_image'];

    protected $fillable = [
        'user_id', 'title', 'slug', 'excerpt', 'content', 'cover_image', 'category', 'tags',
        'meta_description', 'is_published', 'is_featured', 'published_at', 'views_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
        'tags'         => 'array',
    ];

    protected $appends = ['reading_time'];

    protected static function booted(): void
    {
        static::creating(function (self $m) {
            $m->slug = $m->slug ?: Str::slug($m->title);
            $base = $m->slug;
            for ($i = 2; static::where('slug', $m->slug)->exists(); $i++) {
                $m->slug = "{$base}-{$i}";
            }
            $m->user_id ??= auth()->id();
        });

        static::saving(function (self $m) {
            if ($m->is_published && ! $m->published_at) {
                $m->published_at = now();
            }
        });
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getReadingTimeAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->content)) / 200));
    }
}
