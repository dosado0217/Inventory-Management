<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_can_create_a_category()
    {
        $data = [
            'name' => 'Test Category',
        ];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_fetch_all_categories_for_authenticated_user()
    {
        // Create categories for the authenticated user
        Category::factory()->count(3)->create(['user_id' => $this->user->id]);

        // Also create categories for another user (should not be fetched)
        $anotherUser = User::factory()->create();
        Category::factory()->count(2)->create(['user_id' => $anotherUser->id]);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonCount(3); // Only user's categories
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $data = ['name' => 'Updated Name'];

        $response = $this->putJson("/api/categories/{$category->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
