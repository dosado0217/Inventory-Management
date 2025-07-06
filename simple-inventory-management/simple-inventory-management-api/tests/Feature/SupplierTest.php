<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Supplier;
use App\Models\User;

class SupplierTest extends TestCase
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

    public function test_can_create_supplier()
    {
        $response = $this->postJson('/api/suppliers', [
            'name' => 'Supplier One',
            'contact' => '09123456789',
            'address' => 'Mandaue City',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Supplier One']);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Supplier One',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_can_fetch_suppliers()
    {
        // Suppliers for current user
        Supplier::factory()->count(3)->create(['user_id' => $this->user->id]);

        // Suppliers for another user
        $otherUser = User::factory()->create();
        Supplier::factory()->count(2)->create(['user_id' => $otherUser->id]);

        $response = $this->getJson('/api/suppliers');

        $response->assertStatus(200)
                 ->assertJsonCount(3); // Only current user's suppliers
    }

    public function test_can_update_supplier()
    {
        $supplier = Supplier::factory()->create(['user_id' => $this->user->id]);

        $response = $this->putJson("/api/suppliers/{$supplier->id}", [
            'name' => 'Updated Supplier',
            'contact' => '09987654321',
            'address' => 'Cebu City',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Supplier']);

        $this->assertDatabaseHas('suppliers', [
            'id' => $supplier->id,
            'name' => 'Updated Supplier',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_can_delete_supplier()
    {
        $supplier = Supplier::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/suppliers/{$supplier->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }
}
