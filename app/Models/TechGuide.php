<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TechGuide extends Model
{
    use BacksUpImages, HasSeo;

    protected $fillable = ['user_id', 'title', 'slug', 'type', 'content', 'thumbnail', 'is_published'];

    protected array $imageBackupFields = ['thumbnail'];

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
