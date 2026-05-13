<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GadgetImage extends Model
{
    protected $fillable = ['gadget_id', 'image', 'alt_text', 'order'];

    public function gadget() { return $this->belongsTo(Gadget::class); }
}
