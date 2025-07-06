<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a user
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_create_product()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $supplier = Supplier::factory()->create(['user_id' => $this->user->id]);

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'price' => 100,
            'quantity' => 5,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Product']);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_can_fetch_products()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $supplier = Supplier::factory()->create(['user_id' => $this->user->id]);

        // Create products for this user
        Product::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);

        // Create products for another user (should not appear)
        $otherUser = User::factory()->create();
        $otherCategory = Category::factory()->create(['user_id' => $otherUser->id]);
        $otherSupplier = Supplier::factory()->create(['user_id' => $otherUser->id]);

        Product::factory()->count(2)->create([
            'user_id' => $otherUser->id,
            'category_id' => $otherCategory->id,
            'supplier_id' => $otherSupplier->id,
        ]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    public function test_can_update_product()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $supplier = Supplier::factory()->create(['user_id' => $this->user->id]);

        $product = Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'price' => 200,
            'quantity' => 10,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Product']);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_can_delete_product()
    {
        $category = Category::factory()->create(['user_id' => $this->user->id]);
        $supplier = Supplier::factory()->create(['user_id' => $this->user->id]);

        $product = Product::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
