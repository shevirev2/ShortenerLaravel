<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Link;


/**
 * 
 * Ensures uniqueness when DB has a conflict.
 * Checks only lowercase letters and digits are used.
 * 
 */

class SlugServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function generated_slug_is_unique()
    {
        Link::create(['slug' => 'abcdef', 'target_url' => 'https://example.com']);

        $slug = SlugService::generate(6);

        $this->assertNotEquals('abcdef', $slug);
        $this->assertMatchesRegularExpression('/^[a-z0-9]{6}$/', $slug);
    }

    #[Test]
    public function slug_contains_only_allowed_characters()
    {
        $slug = SlugService::generate(8);
        $this->assertMatchesRegularExpression('/^[a-z0-9]{8}$/', $slug);
    }
}
