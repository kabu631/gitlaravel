<?php

namespace App\Models;

use App\Models\Concerns\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasSeo;

    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(fn($m) => $m->slug ??= Str::slug($m->name));
    }

    public function gadgets()
    {
        return $this->hasMany(Gadget::class);
    }

    public function brands()
    {
        return $this->belongsToMany(Brand::class);
    }
}
