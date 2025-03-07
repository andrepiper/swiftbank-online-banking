<?php

namespace Database\Seeders;

use App\Models\TransactionGroup;
use Illuminate\Database\Seeder;

class TransactionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'name' => 'Deposits',
                'description' => 'All deposit transactions',
                'type' => 'deposit',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-arrow-down',
                    'color' => '#28a745',
                    'priority' => 1,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Withdrawals',
                'description' => 'All withdrawal transactions',
                'type' => 'withdrawal',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-arrow-up',
                    'color' => '#dc3545',
                    'priority' => 2,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Transfers',
                'description' => 'All transfer transactions',
                'type' => 'transfer',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-transfer',
                    'color' => '#17a2b8',
                    'priority' => 3,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Payments',
                'description' => 'All payment transactions',
                'type' => 'payment',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-credit-card',
                    'color' => '#6c757d',
                    'priority' => 4,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fees',
                'description' => 'All fee transactions',
                'type' => 'fee',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-money-withdraw',
                    'color' => '#dc3545',
                    'priority' => 5,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Interest',
                'description' => 'All interest transactions',
                'type' => 'interest',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-line-chart',
                    'color' => '#28a745',
                    'priority' => 6,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Direct Deposits',
                'description' => 'Automatic deposits like salary and benefits',
                'type' => 'deposit',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-briefcase',
                    'color' => '#28a745',
                    'priority' => 7,
                    'automated' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Automatic Payments',
                'description' => 'Recurring automatic payments',
                'type' => 'payment',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-calendar',
                    'color' => '#6c757d',
                    'priority' => 8,
                    'automated' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATM Transactions',
                'description' => 'ATM withdrawals and deposits',
                'type' => 'withdrawal',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-credit-card-front',
                    'color' => '#dc3545',
                    'priority' => 9,
                    'location_based' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'International Transfers',
                'description' => 'Transfers to and from international accounts',
                'type' => 'transfer',
                'status' => 'active',
                'metadata' => json_encode([
                    'icon' => 'bx-globe',
                    'color' => '#17a2b8',
                    'priority' => 10,
                    'international' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($groups as $group) {
            TransactionGroup::create($group);
        }
    }
}
