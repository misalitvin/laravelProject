<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class ServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    public function test_index_displays_services(): void
    {
        Service::factory()->count(3)->create();

        $response = $this->get(route('admin.services.index'));

        $response->assertOk();
        $response->assertViewIs('admin.services.index');
        $response->assertViewHas('services');
    }

    public function test_create_displays_form(): void
    {
        $response = $this->get(route('admin.services.create'));

        $response->assertOk();
        $response->assertViewIs('admin.services.create');
    }

    public function test_store_creates_service_and_redirects(): void
    {
        $data = [
            'name' => 'New Service',
        ];

        $response = $this->post(route('admin.services.store'), $data);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['name' => 'New Service']);
    }

    public function test_show_displays_service(): void
    {
        $service = Service::factory()->create();

        $response = $this->get(route('admin.services.show', $service));

        $response->assertOk();
        $response->assertViewIs('admin.services.show');
        $response->assertViewHas('service', $service);
    }

    public function test_edit_displays_edit_form(): void
    {
        $service = Service::factory()->create();

        $response = $this->get(route('admin.services.edit', $service));

        $response->assertOk();
        $response->assertViewIs('admin.services.edit');
        $response->assertViewHas('service', $service);
    }

    public function test_update_modifies_service_and_redirects(): void
    {
        $service = Service::factory()->create([
            'name' => 'Old Name',
        ]);

        $data = [
            'name' => 'Updated Name',
        ];

        $response = $this->put(route('admin.services.update', $service), $data);

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseHas('services', ['id' => $service->id, 'name' => 'Updated Name']);
    }

    public function test_destroy_deletes_service_and_redirects(): void
    {
        $service = Service::factory()->create();

        $response = $this->delete(route('admin.services.destroy', $service));

        $response->assertRedirect(route('admin.services.index'));
        $this->assertDatabaseMissing('services', ['id' => $service->id]);
    }
}
