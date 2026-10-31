<?php

namespace Tests\Feature\Api\V1;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_strip_is_public(): void
    {
        $this->postJson('/api/v1/banners')->assertMethodNotAllowed();

        $this->getJson('/api/v1/banners')->assertOk();
    }

    public function test_public_unexpired_banners_surface_pinned_first(): void
    {
        $newer = Banner::create([
            'name' => 'ใหม่ล่าสุด',
            'type' => 'product',
            'image_url' => 'banners/new.jpg',
            'is_public' => true,
            'is_pinned' => false,
        ]);
        $pinned = Banner::create([
            'name' => 'ปักหมุดแนะนำ',
            'type' => 'promotion',
            'image_url' => 'banners/pinned.jpg',
            'is_public' => true,
            'is_pinned' => true,
        ]);

        // Hidden on every axis a banner can fall off the strip: not public,
        // already expired, and soft-deleted. None of these may surface.
        Banner::create([
            'name' => 'ซ่อนอยู่',
            'type' => 'product',
            'image_url' => 'banners/hidden.jpg',
            'is_public' => false,
        ]);
        Banner::create([
            'name' => 'หมดอายุ',
            'type' => 'product',
            'image_url' => 'banners/expired.jpg',
            'is_public' => true,
            'expired_at' => now()->subHour(),
        ]);
        Banner::create([
            'name' => 'ถังขยะ',
            'type' => 'product',
            'image_url' => 'banners/deleted.jpg',
            'is_public' => true,
        ])->delete();

        $response = $this->getJson('/api/v1/banners');

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $pinned->id)
            ->assertJsonPath('data.1.id', $newer->id)
            ->assertJsonPath('data.0.name', 'ปักหมุดแนะนำ')
            ->assertJsonPath('data.0.is_pinned', true);
    }

    public function test_the_strip_is_capped_at_five_cards(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            Banner::create([
                'name' => "แบนเนอร์ {$i}",
                'type' => 'product',
                'image_url' => "banners/{$i}.jpg",
                'is_public' => true,
            ]);
        }

        $this->getJson('/api/v1/banners')->assertOk()->assertJsonCount(5, 'data');
    }
}
