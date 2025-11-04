<?php

namespace App\Jobs;

use App\Models\Link;
use App\Models\LinkHit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LinkHitJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $linkId, $ip, $userAgent;

    public function __construct(int $linkId, string $ip, string $userAgent)
    {
        $this->linkId = $linkId;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    public function handle()
    {
        $link = Link::find($this->linkId); // ✅ always exists fresh

        if (! $link) {
            Log::warning("LinkHitJob: Link {$this->linkId} not found.");
            return;
        }

        LinkHit::create([
            'link_id' => $link->id,
            'ip' => $this->ip,
            'user_agent' => $this->userAgent,
        ]);

        Cache::forget("stats_{$link->slug}");
    }
}
