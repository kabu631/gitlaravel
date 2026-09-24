<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use BacksUpImages;

    protected array $imageBackupFields = ['photo'];

    protected $fillable = ['name', 'role', 'bio', 'photo', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
