<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountTypes = [
            [
                'name' => 'Savings Account',
                'code' => 'SAVINGS',
                'description' => 'A basic savings account with interest accrual',
                'min_balance' => 100.00,
                'interest_rate' => 0.75,
                'monthly_fee' => 5.00,
                'withdrawal_limit' => 6,
                'daily_transfer_limit' => 5000.00,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Checking Account',
                'code' => 'CHECKING',
                'description' => 'A standard checking account for daily transactions',
                'min_balance' => 25.00,
                'interest_rate' => 0.05,
                'monthly_fee' => 10.00,
                'withdrawal_limit' => null,
                'daily_transfer_limit' => 10000.00,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Money Market Account',
                'code' => 'MONEY_MARKET',
                'description' => 'A high-yield savings account with check-writing privileges',
                'min_balance' => 1000.00,
                'interest_rate' => 1.25,
                'monthly_fee' => 15.00,
                'withdrawal_limit' => 6,
                'daily_transfer_limit' => 25000.00,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certificate of Deposit',
                'code' => 'CD',
                'description' => 'A time deposit account with fixed term and interest rate',
                'min_balance' => 500.00,
                'interest_rate' => 2.50,
                'monthly_fee' => 0.00,
                'withdrawal_limit' => 0,
                'daily_transfer_limit' => 0.00,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Student Checking',
                'code' => 'STUDENT',
                'description' => 'A no-fee checking account for students',
                'min_balance' => 0.00,
                'interest_rate' => 0.01,
                'monthly_fee' => 0.00,
                'withdrawal_limit' => null,
                'daily_transfer_limit' => 2000.00,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business Checking',
                'code' => 'BUSINESS',
                'description' => 'A checking account designed for business transactions',
                'min_balance' => 1500.00,
                'interest_rate' => 0.10,
                'monthly_fee' => 25.00,
                'withdrawal_limit' => null,
                'daily_transfer_limit' => 50000.00,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($accountTypes as $accountType) {
            DB::table('account_types')->insert($accountType);
        }
    }
}
