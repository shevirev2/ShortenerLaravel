<?php

namespace App\Listeners;

use App\Events\LinkHitRecorded;
use App\Models\Link;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class ClearLinkStatsCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //

    }

    

    public function handle(LinkHitRecorded $event)
    {
        $link = Link::find($event->linkId);
        Cache::forget("link_stats_{$link->slug}");
    }
}
