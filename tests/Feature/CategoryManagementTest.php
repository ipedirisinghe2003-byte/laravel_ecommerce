<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['utype' => 'adm']);
    }

    private function logoUpload(string $name): UploadedFile
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'category-logo');
        file_put_contents($tempFile, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAF' . 'c5r6AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJ0Uk5T' . 'AAAAAAgI0wAAAABJRU5ErkJggg=='));

        return new UploadedFile($tempFile, $name, 'image/png', null, true);
    }

    public function test_admin_can_render_categories_page(): void
    {
        $this->actingAs($this->admin());

        $this->get(route('admin.categories'))
            ->assertOk()
            ->assertSee('Categories')
            ->assertSee('Search category...')
            ->assertSee('Add New Category');
    }

    public function test_admin_can_list_search_and_filter_categories(): void
    {
        $this->actingAs($this->admin());
        Category::create(['name' => 'Active Furniture', 'slug' => 'active-furniture', 'status' => 'active']);
        Category::create(['name' => 'Inactive Lighting', 'slug' => 'inactive-lighting', 'status' => 'inactive']);

        $this->getJson('/api/categories?status=active&search=furniture')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'active-furniture')
            ->assertJsonPath('meta.total', 1);

        $this->getJson('/api/categories?status=inactive')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'inactive-lighting');
    }

    public function test_admin_can_create_update_and_delete_a_category(): void
    {
        $this->actingAs($this->admin());

        $this->postJson('/api/categories', [
            'name' => 'New Category',
            'slug' => 'new-category',
            'description' => 'Category description',
            'status' => 'active',
        ])->assertCreated()->assertJsonPath('data.slug', 'new-category');

        $category = Category::where('slug', 'new-category')->firstOrFail();

        $this->putJson("/api/categories/{$category->id}", [
            'name' => 'Updated Category',
            'slug' => 'updated-category',
            'description' => 'Updated description',
            'status' => 'inactive',
        ])->assertOk()->assertJsonPath('data.status', 'inactive');

        $this->deleteJson("/api/categories/{$category->id}")
            ->assertOk();

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }

    public function test_admin_edit_persists_status_timestamp_and_logo_changes(): void
    {
        $this->actingAs($this->admin());
        $oldLogo = 'uploads/categories/old-logo.png';
        $oldLogoPath = public_path($oldLogo);
        if (!is_dir(dirname($oldLogoPath))) {
            mkdir(dirname($oldLogoPath), 0777, true);
        }
        file_put_contents($oldLogoPath, 'old logo');

        $category = Category::create([
            'name' => 'Original Category',
            'slug' => 'original-category',
            'logo' => $oldLogo,
            'status' => 'active',
            'created_at' => Carbon::now()->subMinute(),
            'updated_at' => Carbon::now()->subMinute(),
        ]);
        $originalUpdatedAt = $category->updated_at;
        Carbon::setTestNow(Carbon::now()->addMinute());

        $this->postJson("/api/categories/{$category->id}", [
            '_method' => 'PUT',
            'name' => 'Edited Category',
            'slug' => 'edited-category',
            'description' => 'Edited description',
            'status' => 'inactive',
            'logo' => $this->logoUpload('new-logo.png'),
        ])->assertOk()
            ->assertJsonPath('message', 'Category updated successfully');

        $category->refresh();
        $this->assertSame('Edited Category', $category->name);
        $this->assertSame('inactive', $category->status);
        $this->assertGreaterThan($originalUpdatedAt->timestamp, $category->updated_at->timestamp);
        $this->assertNotSame($oldLogo, $category->logo);
        $this->assertFileDoesNotExist($oldLogoPath);
        $this->assertFileExists(public_path($category->logo));
        Carbon::setTestNow();

        $newLogoPath = public_path($category->logo);
        $this->postJson("/api/categories/{$category->id}", [
            '_method' => 'PUT',
            'name' => 'Edited Category',
            'slug' => 'edited-category',
            'description' => 'Edited description',
            'status' => 'inactive',
            'remove_logo' => '1',
        ])->assertOk();

        $category->refresh();
        $this->assertNull($category->logo);
        $this->assertFileDoesNotExist($newLogoPath);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin());
        $category = Category::create(['name' => 'Used Category', 'slug' => 'used-category', 'status' => 'active']);
        Product::create(['category_id' => $category->id, 'name' => 'Sample Product']);

        $this->deleteJson("/api/categories/{$category->id}")
            ->assertStatus(409)
            ->assertJsonPath('message', 'This category cannot be deleted while products are attached to it.');
    }
}
