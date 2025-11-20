<?php

namespace Tests\Feature;

use Database\Seeders\ShopSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ShopSeeder::class);
    }

    public function test_products_endpoint_returns_paginated_payload(): void
    {
        $response = $this->getJson('/api/products');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'sku', 'name', 'slug', 'price'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_can_view_single_product(): void
    {
        $slug = 'tomoe-oversize-tee-sakura-blade';

        $response = $this->getJson("/api/products/{$slug}");

        $response
            ->assertOk()
            ->assertJsonPath('data.slug', $slug);
    }
}

