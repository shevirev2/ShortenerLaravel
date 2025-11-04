<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkHit extends Model
{
    protected $fillable = ['link_id', 'ip', 'user_agent'];

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
