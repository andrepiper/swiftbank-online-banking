<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Deposits',
                'description' => 'All deposit transactions',
                'type' => 'DEPOSIT',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-arrow-down',
                    'color' => '#28a745',
                    'priority' => 1,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Withdrawals',
                'description' => 'All withdrawal transactions',
                'type' => 'WITHDRAWAL',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-arrow-up',
                    'color' => '#dc3545',
                    'priority' => 2,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Transfers',
                'description' => 'All transfer transactions',
                'type' => 'TRANSFER',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-transfer',
                    'color' => '#17a2b8',
                    'priority' => 3,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Payments',
                'description' => 'All payment transactions',
                'type' => 'PAYMENT',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-credit-card',
                    'color' => '#6c757d',
                    'priority' => 4,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Fees',
                'description' => 'All fee transactions',
                'type' => 'FEE',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-money-withdraw',
                    'color' => '#dc3545',
                    'priority' => 5,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Interest',
                'description' => 'All interest transactions',
                'type' => 'INTEREST',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-line-chart',
                    'color' => '#28a745',
                    'priority' => 6,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Direct Deposits',
                'description' => 'Automatic deposits like salary and benefits',
                'type' => 'DEPOSIT',
                'status' => 'ACTIVE',
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
                'id' => Str::uuid()->toString(),
                'name' => 'Automatic Payments',
                'description' => 'Recurring automatic payments',
                'type' => 'PAYMENT',
                'status' => 'ACTIVE',
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
                'id' => Str::uuid()->toString(),
                'name' => 'ATM Transactions',
                'description' => 'ATM withdrawals and deposits',
                'type' => 'WITHDRAWAL',
                'status' => 'ACTIVE',
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
                'id' => Str::uuid()->toString(),
                'name' => 'International Transfers',
                'description' => 'Transfers to and from international accounts',
                'type' => 'TRANSFER',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-globe',
                    'color' => '#17a2b8',
                    'priority' => 10,
                    'international' => true,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Investments',
                'description' => 'Investment-related transactions',
                'type' => 'INVESTMENT',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-trending-up',
                    'color' => '#28a745',
                    'priority' => 11,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid()->toString(),
                'name' => 'Loan Payments',
                'description' => 'Loan and mortgage payments',
                'type' => 'PAYMENT',
                'status' => 'ACTIVE',
                'metadata' => json_encode([
                    'icon' => 'bx-home',
                    'color' => '#6c757d',
                    'priority' => 12,
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($groups as $group) {
            DB::table('transaction_groups')->insert($group);
        }
    }
}
