<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    use BacksUpImages, HasSeo;

    protected $fillable = ['name', 'slug', 'logo'];

    protected array $imageBackupFields = ['logo'];

    protected static function booted(): void
    {
        static::creating(fn($m) => $m->slug ??= Str::slug($m->name));
    }

    public function gadgets()
    {
        return $this->hasMany(Gadget::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
