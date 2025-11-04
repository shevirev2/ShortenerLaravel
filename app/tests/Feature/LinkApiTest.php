<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests custom and auto-generated slugs.
 * Checks API key validation.
 * Checks URL validation.
 * Uses RefreshDatabase to reset DB for each test. 
 */

class LinkApiTest extends TestCase
{
    use RefreshDatabase;

    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->headers = ['X-Api-Key' => env('API_KEY')];
    }

    #[Test]
    public function it_creates_a_link_with_custom_slug()
    {
        $payload = [
            'target_url' => 'https://example.com',
            'slug' => 'customslug'
        ];

        $response = $this->withHeaders($this->headers)->postJson('/api/links', $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['slug' => 'customslug', 'target_url' => 'https://example.com']);

        $this->assertDatabaseHas('links', ['slug' => 'customslug']);
    }

    #[Test]
    public function it_creates_a_link_with_auto_generated_slug()
    {
        $payload = ['target_url' => 'https://example.com'];

        $response = $this->withHeaders($this->headers)->postJson('/api/links', $payload);

        $response->assertStatus(200)
                 ->assertJsonStructure(['id', 'slug', 'target_url']);

        $this->assertDatabaseHas('links', ['target_url' => 'https://example.com']);
    }

    #[Test]
    public function it_rejects_request_with_invalid_api_key()
    {
        $response = $this->withHeaders(['X-Api-Key' => 'wrongkey'])//'wrongkey'
                         ->postJson('/api/links', ['target_url' => 'https://example.com']);

        $response->assertStatus(401);
    }

    #[Test]
    public function it_validates_target_url()
    {
        $response = $this->withHeaders($this->headers)
                         ->postJson('/api/links', ['target_url' => 'invalid-url']);

        $response->assertStatus(422);
    }
}
