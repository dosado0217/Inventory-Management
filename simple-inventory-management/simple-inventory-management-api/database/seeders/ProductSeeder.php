<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a user
        $user = User::first() ?? User::factory()->create();

        $products = [
            [
                'name' => 'Laptop',
                'description' => 'High-end laptop for work and gaming.',
                'price' => 49999.99,
                'quantity' => 10,
                'category_id' => 1,
                'supplier_id' => 1,
            ],
            [
                'name' => 'T-Shirt',
                'description' => 'Cotton t-shirt in various sizes.',
                'price' => 299.99,
                'quantity' => 100,
                'category_id' => 2,
                'supplier_id' => 2,
            ],
            [
                'name' => 'Office Chair',
                'description' => 'Ergonomic office chair with lumbar support.',
                'price' => 2599.00,
                'quantity' => 25,
                'category_id' => 4,
                'supplier_id' => 3,
            ],
        ];

        foreach ($products as $data) {
            $data['user_id'] = $user->id;
            Product::create($data);
        }
    }
}
