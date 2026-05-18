<?php

namespace App\Models;

use App\Traits\BacksUpImages;
use Illuminate\Database\Eloquent\Model;

class GadgetImage extends Model
{
    use BacksUpImages;

    protected $fillable = ['gadget_id', 'image', 'alt_text', 'order'];

    protected array $imageBackupFields = ['image'];

    public function gadget() { return $this->belongsTo(Gadget::class); }
}
