<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use App\Enums\Currency;
use App\Models\CurrencyRate;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
        $user = User::factory()->create();
        $this->actingAs($user);

        Service::factory()->count(2)->create();

        CurrencyRate::insert([
            ['currency' => 'USD', 'rate' => 1.1],
            ['currency' => 'PLN', 'rate' => 4.5],
            ['currency' => 'EUR', 'rate' => 1],
        ]);

    }

    public function test_index_displays_paginated_products(): void
    {
        Product::factory()->count(15)->create();

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertViewHas('products');

        $products = $response->viewData('products');
        $this->assertEquals(10, $products->count());
    }

    public function test_show_displays_product_with_prices(): void
    {
        $product = Product::factory()->create([
            'price' => 100.0,
        ]);

        $services = Service::factory()->count(2)->create();
        $product->services()->attach($services->pluck('id')->toArray(), [
            'days_to_complete' => 5,
            'cost' => 20,
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertViewHasAll(['product', 'prices']);

        $prices = $response->viewData('prices');

        $this->assertEquals(100.0, $prices[Currency::EUR->value]);
        $this->assertEquals(100.0 * 1.1, $prices[Currency::USD->value] ?? null);

        $response->assertSeeText($product->name);
    }
}
