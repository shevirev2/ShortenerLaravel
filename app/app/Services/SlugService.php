<?php

namespace App\Services;

use App\Models\Link;

class SlugService
{
    /**
     * Generate a unique random slug.
     *
     * @param int $length
     * @return string
     */
    public static function generate($length = 6)
    {
        do {
            // lowercase letters + numbers only
            $slug = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, $length);
        } while (Link::where('slug', $slug)->exists());

        return $slug;
    }
}