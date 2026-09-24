<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BrandManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_and_view_a_brand(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'utype' => 'adm',
        ]);

        $this->actingAs($admin);

        $this->post(route('admin.brand-store'), [
            'name' => 'AsterTech',
            'status' => true,
        ])->assertRedirect(route('admin.brands'));

        $this->assertDatabaseHas('brands', [
            'name' => 'AsterTech',
            'slug' => 'astertech',
            'status' => true,
        ]);

        $response = $this->get(route('admin.brands'));

        $response->assertOk();
        $response->assertSee('AsterTech');
    }

    public function test_admin_add_brand_page_renders_form(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'utype' => 'adm',
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.brand-add'))
            ->assertOk()
            ->assertSee('Add New Brand')
            ->assertSee('Save Brand');
    }

    public function test_admin_can_search_brands_and_filter_by_status(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'utype' => 'adm',
        ]);

        $this->actingAs($admin);

        Brand::create(['name' => 'Active Tech', 'slug' => 'active-tech', 'status' => true]);
        Brand::create(['name' => 'Inactive Home', 'slug' => 'inactive-home', 'status' => false]);

        $this->get(route('admin.brands', ['status' => 'active']))
            ->assertSee('Active Tech')
            ->assertDontSee('Inactive Home');

        $this->get(route('admin.brands', ['status' => 'inactive']))
            ->assertSee('Inactive Home')
            ->assertDontSee('Active Tech');

        $this->get(route('admin.brands', ['search' => 'active-tech']))
            ->assertSee('Active Tech')
            ->assertDontSee('Inactive Home');

        $this->get(route('admin.brands', ['status' => 'all']))
            ->assertSee('Active Tech')
            ->assertSee('Inactive Home');
    }

    public function test_admin_can_update_and_delete_a_brand(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'utype' => 'adm',
        ]);

        $this->actingAs($admin);

        $brand = Brand::create([
            'name' => 'OldBrand',
            'slug' => 'old-brand',
            'status' => 1,
        ]);

        $this->patch(route('admin.brand-update', $brand), [
            'name' => 'UpdatedBrand',
            'slug' => 'updated-brand',
            'status' => false,
        ])->assertRedirect(route('admin.brands'));

        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'name' => 'UpdatedBrand',
            'slug' => 'updated-brand',
            'status' => false,
        ]);

        $this->delete(route('admin.brand-destroy', $brand))
            ->assertRedirect(route('admin.brands'));

        $this->assertDatabaseMissing('brands', [
            'id' => $brand->id,
        ]);
    }

    public function test_default_admin_user_is_created_for_admin_login(): void
    {
        $this->seed();

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'utype' => 'adm',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_admin_can_upload_brand_logo_and_save_it(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'utype' => 'adm',
        ]);

        $this->actingAs($admin);

        $tempFile = tempnam(sys_get_temp_dir(), 'brand-logo');
        file_put_contents($tempFile, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAF' . 'c5r6AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJ0UkG' . 'AAAAAAgI0wAAAABJRU5ErkJggg=='));

        $file = new UploadedFile($tempFile, 'logo.png', 'image/png', null, true);

        $response = $this->post(route('admin.brand-store'), [
            'name' => 'LogoBrand',
            'slug' => 'logo-brand',
            'status' => true,
            'image' => $file,
        ]);

        $response->assertRedirect(route('admin.brands'));

        $this->assertDatabaseHas('brands', [
            'name' => 'LogoBrand',
            'slug' => 'logo-brand',
            'status' => true,
        ]);

        $this->assertNotNull(Brand::where('name', 'LogoBrand')->value('image'));
        $this->assertStringContainsString('uploads/brands/', Brand::where('name', 'LogoBrand')->value('image'));
    }
}
