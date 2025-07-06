<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\User;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        // Get or create a user
        $user = User::first() ?? User::factory()->create();

        $suppliers = [
            ['name' => 'ABC Suppliers', 'contact' => '09171234567', 'address' => '123 Main St'],
            ['name' => 'Global Traders', 'contact' => '09179876543', 'address' => '456 Business Rd'],
            ['name' => 'Mega Supplies', 'contact' => '09981234567', 'address' => '789 Commerce Blvd'],
        ];

        foreach ($suppliers as $data) {
            $data['user_id'] = $user->id;
            Supplier::create($data);
        }
    }
}
