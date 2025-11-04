<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Link;
use Illuminate\Support\Facades\Queue;
use App\Jobs\LinkHitJob;
use PHPUnit\Framework\Attributes\Test;

/**
 * Checks 302 redirect.
 * Ensures queue job is dispatched asynchronously.
 * Handles inactive link (410) and non-existent link (404). 
 */

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_redirects_to_target_url_and_dispatches_job()
    {
        Queue::fake();

        $link = Link::create([
            'slug' => 'myslug',
            'target_url' => 'https://example.com',
            'is_active' => true
        ]);

        $response = $this->get('/r/myslug');

        $response->assertRedirect('https://example.com');

        Queue::assertPushed(LinkHitJob::class, function($job) use ($link) {
            return $job->link->id === $link->id;
        });
    }

    #[Test]
    public function it_returns_410_if_link_is_inactive()
    {
        $link = Link::create([
            'slug' => 'inactive',
            'target_url' => 'https://example.com',
            'is_active' => false
        ]);

        $response = $this->get('/r/inactive');

        $response->assertStatus(410);
    }

    #[Test]
    public function it_returns_404_if_link_not_found()
    {
        $response = $this->get('/r/unknownslug');
        $response->assertStatus(404);
    }
}
