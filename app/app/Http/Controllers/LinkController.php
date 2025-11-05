<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\LinkHitJob;
use App\Models\Link;
use App\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LinkController extends Controller
{
    public function store(Request $request)
    {

        $request->validate(['target_url' => 'required|url']);

        $slug = $request->slug ?? SlugService::generate();

        $link = Link::create([
            'slug' => $slug,
            'target_url' => $request->target_url
        ]);

        return response()->json($link);
    }

    public function redirect($slug)
    {
        $link = Link::where('slug', $slug)->firstOrFail();

        if (!$link->is_active) {
            abort(410);
        }

        // Dispatch queue job
        LinkHitJob::dispatch($link->id, request()->ip(), request()->userAgent());

        return redirect()->away($link->target_url);
    }

    public function stats($slug)
    {
        $link = Link::where('slug', $slug)->firstOrFail();

        return Cache::remember("stats_{$slug}", 60, function() use ($link) {
            return [
                'target_url' => $link->target_url,
                'total_hits' => $link->hits()->count(),
                'last_hits' => $link->hits()->latest()->take(5)->get(['ip', 'created_at'])
            ];
        });
    }
}
