<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Account;
use App\Models\AccountType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $accountTypes = AccountType::all();

        // Create accounts for each user
        foreach ($users as $user) {
            // Each user gets a savings account
            $savingsType = $accountTypes->where('name', 'Savings Account')->first();
            $this->createAccount($user, $savingsType, 'Primary Savings', rand(1000, 50000) / 100);

            // Each user gets a checking account
            $checkingType = $accountTypes->where('name', 'Checking Account')->first();
            $this->createAccount($user, $checkingType, 'Primary Checking', rand(500, 10000) / 100);

            // Some users get additional accounts
            if (rand(0, 1)) {
                $moneyMarketType = $accountTypes->where('name', 'Money Market Account')->first();
                $this->createAccount($user, $moneyMarketType, 'Money Market', rand(10000, 100000) / 100);
            }

            if (rand(0, 1)) {
                $cdType = $accountTypes->where('name', 'Certificate of Deposit')->first();
                $this->createAccount($user, $cdType, '12-Month CD', rand(5000, 20000) / 100);
            }

            // Business accounts for some users
            if ($user->id % 3 == 0) {
                $businessType = $accountTypes->where('name', 'Business Checking')->first();
                $this->createAccount($user, $businessType, 'Business Account', rand(5000, 100000) / 100);
            }
        }
    }

    /**
     * Create an account for a user
     */
    private function createAccount(User $user, AccountType $accountType, string $name, float $balance): Account
    {
        $accountNumber = $this->generateAccountNumber();
        $routingNumber = '021000021'; // Example routing number

        return Account::create([
            'user_id' => $user->id,
            'account_type_id' => $accountType->id,
            'account_number' => $accountNumber,
            'routing_number' => $routingNumber,
            'account_name' => $name,
            'balance' => $balance,
            'available_balance' => $balance,
            'currency' => 'USD',
            'status' => 'ACTIVE',
            'is_primary' => $accountType->name === 'Checking Account',
            'opened_at' => now()->subDays(rand(1, 365)),
            'last_activity_at' => now()->subDays(rand(0, 30)),
            'metadata' => json_encode([
                'interest_accrued' => rand(0, 1000) / 100,
                'statements_enabled' => true,
                'paperless' => rand(0, 1) ? true : false,
            ]),
            'created_at' => now()->subDays(rand(1, 365)),
            'updated_at' => now(),
        ]);
    }

    /**
     * Generate a random account number
     */
    private function generateAccountNumber(): string
    {
        return mt_rand(1000000000, 9999999999);
    }
}
