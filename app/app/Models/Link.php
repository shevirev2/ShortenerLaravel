<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Link extends Model
{
    protected $fillable = ['slug', 'target_url', 'is_active'];

    public function hits()
    {
        return $this->hasMany(LinkHit::class);
    }
}
