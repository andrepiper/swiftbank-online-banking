<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define parent categories first
        $parentCategories = [
            [
                'name' => 'Income',
                'code' => 'INCOME',
                'description' => 'Money received from various sources',
                'parent_id' => null,
                'color_code' => '#28a745',
                'icon' => 'bx-dollar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Housing',
                'code' => 'HOUSING',
                'description' => 'Housing and accommodation expenses',
                'parent_id' => null,
                'color_code' => '#dc3545',
                'icon' => 'bx-home',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transportation',
                'code' => 'TRANSPORT',
                'description' => 'Transportation related expenses',
                'parent_id' => null,
                'color_code' => '#fd7e14',
                'icon' => 'bx-car',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Food',
                'code' => 'FOOD',
                'description' => 'Food and dining expenses',
                'parent_id' => null,
                'color_code' => '#6f42c1',
                'icon' => 'bx-food-menu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Entertainment',
                'code' => 'ENTERTAIN',
                'description' => 'Entertainment and leisure expenses',
                'parent_id' => null,
                'color_code' => '#e83e8c',
                'icon' => 'bx-movie',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shopping',
                'code' => 'SHOPPING',
                'description' => 'Shopping expenses',
                'parent_id' => null,
                'color_code' => '#20c997',
                'icon' => 'bx-shopping-bag',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Health',
                'code' => 'HEALTH',
                'description' => 'Health and medical expenses',
                'parent_id' => null,
                'color_code' => '#17a2b8',
                'icon' => 'bx-plus-medical',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Education',
                'code' => 'EDUCATION',
                'description' => 'Education and learning expenses',
                'parent_id' => null,
                'color_code' => '#007bff',
                'icon' => 'bx-book',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bills & Utilities',
                'code' => 'BILLS',
                'description' => 'Regular bills and utility payments',
                'parent_id' => null,
                'color_code' => '#6c757d',
                'icon' => 'bx-receipt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transfers',
                'code' => 'TRANSFER',
                'description' => 'Money transfers between accounts',
                'parent_id' => null,
                'color_code' => '#ffc107',
                'icon' => 'bx-transfer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert parent categories
        foreach ($parentCategories as $category) {
            DB::table('transaction_categories')->insert($category);
        }

        // Get the IDs of the parent categories
        $incomeId = DB::table('transaction_categories')->where('code', 'INCOME')->value('id');
        $housingId = DB::table('transaction_categories')->where('code', 'HOUSING')->value('id');
        $transportId = DB::table('transaction_categories')->where('code', 'TRANSPORT')->value('id');
        $foodId = DB::table('transaction_categories')->where('code', 'FOOD')->value('id');
        $entertainmentId = DB::table('transaction_categories')->where('code', 'ENTERTAIN')->value('id');
        $shoppingId = DB::table('transaction_categories')->where('code', 'SHOPPING')->value('id');
        $healthId = DB::table('transaction_categories')->where('code', 'HEALTH')->value('id');
        $educationId = DB::table('transaction_categories')->where('code', 'EDUCATION')->value('id');
        $billsId = DB::table('transaction_categories')->where('code', 'BILLS')->value('id');
        $transferId = DB::table('transaction_categories')->where('code', 'TRANSFER')->value('id');

        // Define subcategories
        $subCategories = [
            // Income subcategories
            [
                'name' => 'Salary',
                'code' => 'SALARY',
                'description' => 'Regular income from employment',
                'parent_id' => $incomeId,
                'color_code' => '#28a745',
                'icon' => 'bx-briefcase',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Investments',
                'code' => 'INVEST',
                'description' => 'Income from investments',
                'parent_id' => $incomeId,
                'color_code' => '#28a745',
                'icon' => 'bx-line-chart',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gifts',
                'code' => 'GIFT',
                'description' => 'Money received as gifts',
                'parent_id' => $incomeId,
                'color_code' => '#28a745',
                'icon' => 'bx-gift',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Housing subcategories
            [
                'name' => 'Rent',
                'code' => 'RENT',
                'description' => 'Monthly rent payments',
                'parent_id' => $housingId,
                'color_code' => '#dc3545',
                'icon' => 'bx-building-house',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mortgage',
                'code' => 'MORTGAGE',
                'description' => 'Mortgage payments',
                'parent_id' => $housingId,
                'color_code' => '#dc3545',
                'icon' => 'bx-building',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Transportation subcategories
            [
                'name' => 'Fuel',
                'code' => 'FUEL',
                'description' => 'Gasoline and fuel expenses',
                'parent_id' => $transportId,
                'color_code' => '#fd7e14',
                'icon' => 'bx-gas-pump',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Public Transit',
                'code' => 'TRANSIT',
                'description' => 'Public transportation expenses',
                'parent_id' => $transportId,
                'color_code' => '#fd7e14',
                'icon' => 'bx-train',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Car Maintenance',
                'code' => 'CAR_MAINT',
                'description' => 'Vehicle maintenance and repairs',
                'parent_id' => $transportId,
                'color_code' => '#fd7e14',
                'icon' => 'bx-wrench',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Food subcategories
            [
                'name' => 'Groceries',
                'code' => 'GROCERY',
                'description' => 'Grocery shopping',
                'parent_id' => $foodId,
                'color_code' => '#6f42c1',
                'icon' => 'bx-cart',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Restaurants',
                'code' => 'DINING',
                'description' => 'Dining out expenses',
                'parent_id' => $foodId,
                'color_code' => '#6f42c1',
                'icon' => 'bx-restaurant',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Entertainment subcategories
            [
                'name' => 'Movies',
                'code' => 'MOVIES',
                'description' => 'Movie tickets and streaming services',
                'parent_id' => $entertainmentId,
                'color_code' => '#e83e8c',
                'icon' => 'bx-film',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Travel',
                'code' => 'TRAVEL',
                'description' => 'Travel and vacation expenses',
                'parent_id' => $entertainmentId,
                'color_code' => '#e83e8c',
                'icon' => 'bx-plane',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Shopping subcategories
            [
                'name' => 'Clothing',
                'code' => 'CLOTHING',
                'description' => 'Clothing and apparel purchases',
                'parent_id' => $shoppingId,
                'color_code' => '#20c997',
                'icon' => 'bx-closet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Electronics',
                'code' => 'ELECTRON',
                'description' => 'Electronic devices and gadgets',
                'parent_id' => $shoppingId,
                'color_code' => '#20c997',
                'icon' => 'bx-laptop',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Health subcategories
            [
                'name' => 'Doctor',
                'code' => 'DOCTOR',
                'description' => 'Doctor visits and consultations',
                'parent_id' => $healthId,
                'color_code' => '#17a2b8',
                'icon' => 'bx-first-aid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pharmacy',
                'code' => 'PHARMACY',
                'description' => 'Medication and pharmacy expenses',
                'parent_id' => $healthId,
                'color_code' => '#17a2b8',
                'icon' => 'bx-capsule',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Education subcategories
            [
                'name' => 'Tuition',
                'code' => 'TUITION',
                'description' => 'School and college tuition fees',
                'parent_id' => $educationId,
                'color_code' => '#007bff',
                'icon' => 'bx-graduation',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Books',
                'code' => 'BOOKS',
                'description' => 'Books and educational materials',
                'parent_id' => $educationId,
                'color_code' => '#007bff',
                'icon' => 'bx-book-open',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Bills & Utilities subcategories
            [
                'name' => 'Electricity',
                'code' => 'ELECTRIC',
                'description' => 'Electricity bills',
                'parent_id' => $billsId,
                'color_code' => '#6c757d',
                'icon' => 'bx-bulb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Water',
                'code' => 'WATER',
                'description' => 'Water bills',
                'parent_id' => $billsId,
                'color_code' => '#6c757d',
                'icon' => 'bx-droplet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Internet',
                'code' => 'INTERNET',
                'description' => 'Internet and broadband bills',
                'parent_id' => $billsId,
                'color_code' => '#6c757d',
                'icon' => 'bx-wifi',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Transfer subcategories
            [
                'name' => 'Internal Transfer',
                'code' => 'INT_TRANS',
                'description' => 'Transfers between own accounts',
                'parent_id' => $transferId,
                'color_code' => '#ffc107',
                'icon' => 'bx-transfer-alt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'External Transfer',
                'code' => 'EXT_TRANS',
                'description' => 'Transfers to external accounts',
                'parent_id' => $transferId,
                'color_code' => '#ffc107',
                'icon' => 'bx-send',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert subcategories
        foreach ($subCategories as $category) {
            DB::table('transaction_categories')->insert($category);
        }
    }
}
