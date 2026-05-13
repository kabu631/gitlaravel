<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo'];

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
