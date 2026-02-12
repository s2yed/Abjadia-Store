<?php

namespace Tests\Feature\Api;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PageImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_page_with_image()
    {
        Storage::fake('public');

        $data = [
            'slug' => 'test-page',
            'title' => ['en' => 'Test Page', 'ar' => 'صفحة تجريبية'],
            'content' => ['en' => 'Content', 'ar' => 'محتوى'],
            'image' => UploadedFile::fake()->create('feature.jpg', 1024),
            'is_active' => true,
        ];

        $response = $this->postJson('/api/pages', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('pages', ['slug' => 'test-page']);
        
        $page = Page::first();
        $this->assertNotNull($page->image_path);
        Storage::disk('public')->assertExists($page->image_path);
    }

    public function test_can_update_page_image_and_old_one_is_deleted()
    {
        Storage::fake('public');

        $oldImage = UploadedFile::fake()->create('old.jpg', 1024)->store('pages', 'public');
        $page = Page::create([
            'slug' => 'test-page',
            'title' => ['en' => 'Title'],
            'content' => ['en' => 'Content'],
            'image_path' => $oldImage,
        ]);

        Storage::disk('public')->assertExists($oldImage);

        $newImage = UploadedFile::fake()->create('new.jpg', 1024);
        $response = $this->postJson("/api/pages/{$page->id}", [
            '_method' => 'PUT',
            'slug' => 'test-page',
            'title' => ['en' => 'Title'],
            'content' => ['en' => 'Content'],
            'image' => $newImage,
        ]);

        $response->assertStatus(200);
        $page->refresh();
        
        Storage::disk('public')->assertMissing($oldImage);
        Storage::disk('public')->assertExists($page->image_path);
    }

    public function test_image_is_deleted_when_page_is_deleted()
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->create('to-delete.jpg', 1024)->store('pages', 'public');
        $page = Page::create([
            'slug' => 'test-page',
            'title' => ['en' => 'Title'],
            'content' => ['en' => 'Content'],
            'image_path' => $image,
        ]);

        Storage::disk('public')->assertExists($image);

        $this->deleteJson("/api/pages/{$page->id}");

        $this->assertDatabaseMissing('pages', ['id' => $page->id]);
        Storage::disk('public')->assertMissing($image);
    }
}
