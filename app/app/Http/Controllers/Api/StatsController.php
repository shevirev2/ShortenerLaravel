<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    public function show($slug)
    {
        $cacheKey = "link_stats_$slug";

        $stats = Cache::remember($cacheKey, 60, function () use ($slug) {
            $link = Link::where('slug', $slug)->firstOrFail();

            return [
                'target_url' => $link->target_url,
                'total_hits' => $link->hits()->count(),
                'last_hits' => $link->hits()
                    ->latest()
                    ->take(5)
                    ->get(['ip', 'created_at'])
                    ->map(fn ($hit) => [
                        'ip' => preg_replace('/(\d+\.\d+)\.\d+\.\d+/', '$1.*.*', $hit->ip),
                        'created_at' => $hit->created_at,
                    ])
            ];
        });

        return response()->json($stats);
    }

}
