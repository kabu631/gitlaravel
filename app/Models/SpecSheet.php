<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecSheet extends Model
{
    protected $fillable = [
        'gadget_id', 'display', 'processor', 'ram', 'storage',
        'battery', 'camera', 'os', 'connectivity', 'weight', 'dimensions', 'extra_specs',
    ];

    protected $casts = ['extra_specs' => 'array'];

    public function gadget() { return $this->belongsTo(Gadget::class); }
}
