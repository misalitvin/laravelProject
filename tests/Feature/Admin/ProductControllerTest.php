<?php

namespace Tests\Feature\Admin;

use App\Models\Product;
use App\Models\Service;
use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    #[Test]
    public function it_shows_product_index()
    {
        Product::factory()->count(5)->create();

        $response = $this->get(route('admin.products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
        $response->assertViewHas('products');
    }

    #[Test]
    public function it_shows_create_product_form()
    {
        $response = $this->get(route('admin.products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
        $response->assertViewHas(['services', 'manufacturers']);
    }

    #[Test]
    public function it_stores_a_new_product_and_syncs_services()
    {
        $manufacturer = Manufacturer::factory()->create();
        $services = Service::factory()->count(2)->create();

        $servicesData = [];
        foreach ($services as $service) {
            $servicesData[$service->id] = [
                'selected' => true,
                'days_to_complete' => 5,
                'cost' => 100,
            ];
        }

        $data = [
            'name' => 'Test Product',
            'price' => 199.99,
            'manufacturer_id' => $manufacturer->id,
            'release_date' => now()->toDateString(),
            'description' => 'Updated description',
            'services' => $servicesData,
        ];

        $response = $this->post(route('admin.products.store'), $data);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['name' => 'Test Product']);

        foreach ($services as $service) {
            $this->assertDatabaseHas('product_service', [
                'service_id' => $service->id,
            ]);
        }
    }

    #[Test]
    public function it_shows_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('admin.products.show', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.show');
        $response->assertViewHas('product');
    }

    #[Test]
    public function it_shows_edit_product_form()
    {
        $product = Product::factory()->create();

        $response = $this->get(route('admin.products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
        $response->assertViewHas(['product', 'services', 'manufacturers']);
    }

    #[Test]
    public function it_updates_a_product_and_syncs_services()
    {
        $product = Product::factory()->create();
        $newService = Service::factory()->create();

        $servicesData = [
            $newService->id => [
                'selected' => true,
                'days_to_complete' => 3,
                'cost' => 50,
            ],
        ];

        $data = [
            'name' => 'Updated Product Name',
            'price' => 299.99,
            'release_date' => now()->toDateString(),
            'description' => 'Updated description',
            'manufacturer_id' => $product->manufacturer_id,
            'services' => $servicesData,
        ];

        $response = $this->put(route('admin.products.update', $product), $data);

        $response->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Product Name']);
        $this->assertDatabaseHas('product_service', [
            'product_id' => $product->id,
            'service_id' => $newService->id,
        ]);
    }

    #[Test]
    public function it_deletes_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    #[Test]
    public function it_validates_required_fields_when_creating_a_product()
    {
        $response = $this->post(route('admin.products.store'), []);

        $response->assertSessionHasErrors(['name', 'price', 'manufacturer_id']);
    }

    #[Test]
    public function it_validates_required_fields_when_updating_a_product()
    {
        $product = Product::factory()->create();

        $response = $this->put(route('admin.products.update', $product), []);

        $response->assertSessionHasErrors(['name', 'price', 'manufacturer_id']);
    }
}
