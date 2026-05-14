<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TechGuide extends Model
{
    protected $fillable = ['user_id', 'title', 'slug', 'content', 'thumbnail', 'is_published'];

    protected $casts = ['is_published' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function($m) {
            $m->slug ??= Str::slug($m->title);
            if (!$m->user_id && auth()->check()) $m->user_id = auth()->id();
        });
    }

    public function author() { return $this->belongsTo(User::class, 'user_id'); }
}
