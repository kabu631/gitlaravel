<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserComment extends Model
{
    protected $fillable = ['gadget_id', 'user_id', 'rating', 'comment'];

    public function gadget() { return $this->belongsTo(Gadget::class); }
    public function user()   { return $this->belongsTo(User::class); }
}
