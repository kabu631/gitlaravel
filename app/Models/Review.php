<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Review extends Model
{
    use HasSeo;

    protected $fillable = [
        'gadget_id', 'user_id', 'title', 'slug', 'content', 'rating',
        'pros', 'cons', 'verdict', 'is_published',
        'react_happy', 'react_sad', 'react_love', 'react_like', 'react_funny', 'react_angry',
    ];

    protected $casts = ['is_published' => 'boolean', 'rating' => 'decimal:1'];

    protected static function booted(): void
    {
        static::creating(function($m) {
            $m->slug ??= Str::slug($m->title);
            if (!$m->user_id && auth()->check()) $m->user_id = auth()->id();
        });
    }

    public function gadget() { return $this->belongsTo(Gadget::class); }
    public function author() { return $this->belongsTo(User::class, 'user_id'); }

    public function getProsList(): array
    {
        return array_filter(array_map('trim', explode("\n", $this->pros ?? '')));
    }

    public function getConsList(): array
    {
        return array_filter(array_map('trim', explode("\n", $this->cons ?? '')));
    }
}
