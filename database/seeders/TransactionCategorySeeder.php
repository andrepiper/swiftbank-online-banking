<?php

namespace Database\Seeders;

use App\Models\TransactionCategory;
use Illuminate\Database\Seeder;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Income',
                'description' => 'Money received from various sources',
                'type' => 'credit',
                'icon' => 'bx-dollar',
                'color' => '#28a745',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Salary',
                'description' => 'Regular income from employment',
                'type' => 'credit',
                'icon' => 'bx-briefcase',
                'color' => '#28a745',
                'parent_id' => 1, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Investments',
                'description' => 'Income from investments',
                'type' => 'credit',
                'icon' => 'bx-line-chart',
                'color' => '#28a745',
                'parent_id' => 1, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gifts',
                'description' => 'Money received as gifts',
                'type' => 'credit',
                'icon' => 'bx-gift',
                'color' => '#28a745',
                'parent_id' => 1, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Housing',
                'description' => 'Housing and accommodation expenses',
                'type' => 'debit',
                'icon' => 'bx-home',
                'color' => '#dc3545',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rent',
                'description' => 'Monthly rent payments',
                'type' => 'debit',
                'icon' => 'bx-building-house',
                'color' => '#dc3545',
                'parent_id' => 5, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mortgage',
                'description' => 'Mortgage payments',
                'type' => 'debit',
                'icon' => 'bx-building',
                'color' => '#dc3545',
                'parent_id' => 5, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Utilities',
                'description' => 'Utility bills like electricity, water, gas',
                'type' => 'debit',
                'icon' => 'bx-plug',
                'color' => '#dc3545',
                'parent_id' => 5, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transportation',
                'description' => 'Transportation related expenses',
                'type' => 'debit',
                'icon' => 'bx-car',
                'color' => '#dc3545',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fuel',
                'description' => 'Gasoline and fuel expenses',
                'type' => 'debit',
                'icon' => 'bx-gas-pump',
                'color' => '#dc3545',
                'parent_id' => 9, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Public Transit',
                'description' => 'Public transportation expenses',
                'type' => 'debit',
                'icon' => 'bx-train',
                'color' => '#dc3545',
                'parent_id' => 9, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Car Maintenance',
                'description' => 'Vehicle maintenance and repairs',
                'type' => 'debit',
                'icon' => 'bx-wrench',
                'color' => '#dc3545',
                'parent_id' => 9, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Food',
                'description' => 'Food and dining expenses',
                'type' => 'debit',
                'icon' => 'bx-food-menu',
                'color' => '#dc3545',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Groceries',
                'description' => 'Grocery shopping',
                'type' => 'debit',
                'icon' => 'bx-cart',
                'color' => '#dc3545',
                'parent_id' => 13, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Restaurants',
                'description' => 'Dining out expenses',
                'type' => 'debit',
                'icon' => 'bx-restaurant',
                'color' => '#dc3545',
                'parent_id' => 13, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Entertainment and leisure expenses',
                'type' => 'debit',
                'icon' => 'bx-movie',
                'color' => '#dc3545',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Movies',
                'description' => 'Movie tickets and streaming services',
                'type' => 'debit',
                'icon' => 'bx-film',
                'color' => '#dc3545',
                'parent_id' => 16, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel and vacation expenses',
                'type' => 'debit',
                'icon' => 'bx-plane',
                'color' => '#dc3545',
                'parent_id' => 16, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shopping',
                'description' => 'Shopping expenses',
                'type' => 'debit',
                'icon' => 'bx-shopping-bag',
                'color' => '#dc3545',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clothing',
                'description' => 'Clothing and apparel purchases',
                'type' => 'debit',
                'icon' => 'bx-closet',
                'color' => '#dc3545',
                'parent_id' => 19, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets',
                'type' => 'debit',
                'icon' => 'bx-laptop',
                'color' => '#dc3545',
                'parent_id' => 19, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Health',
                'description' => 'Health and medical expenses',
                'type' => 'debit',
                'icon' => 'bx-plus-medical',
                'color' => '#dc3545',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Doctor',
                'description' => 'Doctor visits and consultations',
                'type' => 'debit',
                'icon' => 'bx-first-aid',
                'color' => '#dc3545',
                'parent_id' => 22, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pharmacy',
                'description' => 'Medication and pharmacy expenses',
                'type' => 'debit',
                'icon' => 'bx-capsule',
                'color' => '#dc3545',
                'parent_id' => 22, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transfer',
                'description' => 'Transfers between accounts',
                'type' => 'transfer',
                'icon' => 'bx-transfer',
                'color' => '#17a2b8',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Internal Transfer',
                'description' => 'Transfers between own accounts',
                'type' => 'transfer',
                'icon' => 'bx-transfer-alt',
                'color' => '#17a2b8',
                'parent_id' => 25, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'External Transfer',
                'description' => 'Transfers to external accounts',
                'type' => 'transfer',
                'icon' => 'bx-export',
                'color' => '#17a2b8',
                'parent_id' => 25, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fees',
                'description' => 'Bank fees and charges',
                'type' => 'debit',
                'icon' => 'bx-money-withdraw',
                'color' => '#dc3545',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATM Fee',
                'description' => 'ATM withdrawal fees',
                'type' => 'debit',
                'icon' => 'bx-credit-card-front',
                'color' => '#dc3545',
                'parent_id' => 28, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Overdraft Fee',
                'description' => 'Overdraft charges',
                'type' => 'debit',
                'icon' => 'bx-error',
                'color' => '#dc3545',
                'parent_id' => 28, // Will be set after parent is created
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // First pass: create all categories without parent relationships
        foreach ($categories as $index => $category) {
            $parentId = $category['parent_id'];
            $category['parent_id'] = null;

            $createdCategory = TransactionCategory::create($category);

            // Store the created ID for the second pass
            $categories[$index]['created_id'] = $createdCategory->id;
        }

        // Second pass: update parent relationships
        foreach ($categories as $category) {
            if ($category['parent_id'] !== null) {
                $parentIndex = $category['parent_id'] - 1; // Adjust for 0-based index
                $parentId = $categories[$parentIndex]['created_id'];

                TransactionCategory::where('id', $category['created_id'])
                    ->update(['parent_id' => $parentId]);
            }
        }
    }
}
