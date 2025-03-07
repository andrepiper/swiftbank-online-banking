<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

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
                'description' => 'A basic savings account with interest accrual',
                'interest_rate' => 0.75,
                'minimum_balance' => 100.00,
                'maintenance_fee' => 5.00,
                'transaction_limit' => 6,
                'is_active' => true,
                'features' => json_encode([
                    'online_banking' => true,
                    'mobile_banking' => true,
                    'atm_access' => true,
                    'overdraft_protection' => false,
                    'direct_deposit' => true,
                    'bill_pay' => false
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Checking Account',
                'description' => 'A standard checking account for daily transactions',
                'interest_rate' => 0.05,
                'minimum_balance' => 25.00,
                'maintenance_fee' => 10.00,
                'transaction_limit' => null,
                'is_active' => true,
                'features' => json_encode([
                    'online_banking' => true,
                    'mobile_banking' => true,
                    'atm_access' => true,
                    'overdraft_protection' => true,
                    'direct_deposit' => true,
                    'bill_pay' => true
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Money Market Account',
                'description' => 'A high-yield savings account with check-writing privileges',
                'interest_rate' => 1.25,
                'minimum_balance' => 1000.00,
                'maintenance_fee' => 15.00,
                'transaction_limit' => 6,
                'is_active' => true,
                'features' => json_encode([
                    'online_banking' => true,
                    'mobile_banking' => true,
                    'atm_access' => true,
                    'overdraft_protection' => true,
                    'direct_deposit' => true,
                    'bill_pay' => true
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certificate of Deposit',
                'description' => 'A time deposit account with fixed term and interest rate',
                'interest_rate' => 2.50,
                'minimum_balance' => 500.00,
                'maintenance_fee' => 0.00,
                'transaction_limit' => 0,
                'is_active' => true,
                'features' => json_encode([
                    'online_banking' => true,
                    'mobile_banking' => true,
                    'atm_access' => false,
                    'overdraft_protection' => false,
                    'direct_deposit' => true,
                    'bill_pay' => false
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Student Checking',
                'description' => 'A no-fee checking account for students',
                'interest_rate' => 0.01,
                'minimum_balance' => 0.00,
                'maintenance_fee' => 0.00,
                'transaction_limit' => null,
                'is_active' => true,
                'features' => json_encode([
                    'online_banking' => true,
                    'mobile_banking' => true,
                    'atm_access' => true,
                    'overdraft_protection' => false,
                    'direct_deposit' => true,
                    'bill_pay' => true
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Business Checking',
                'description' => 'A checking account designed for business transactions',
                'interest_rate' => 0.10,
                'minimum_balance' => 1500.00,
                'maintenance_fee' => 25.00,
                'transaction_limit' => null,
                'is_active' => true,
                'features' => json_encode([
                    'online_banking' => true,
                    'mobile_banking' => true,
                    'atm_access' => true,
                    'overdraft_protection' => true,
                    'direct_deposit' => true,
                    'bill_pay' => true
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($accountTypes as $accountType) {
            AccountType::create($accountType);
        }
    }
}
