<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();
        $accountTypes = DB::table('account_types')->get();

        // Create accounts for each user
        foreach ($users as $user) {
            // Each user gets a savings account
            $savingsType = $accountTypes->where('name', 'Savings Account')->first();
            if ($savingsType) {
                $this->createAccount($user, $savingsType, 'Primary Savings', rand(1000, 50000) / 100);
            }

            // Each user gets a checking account
            $checkingType = $accountTypes->where('name', 'Checking Account')->first();
            if ($checkingType) {
                $this->createAccount($user, $checkingType, 'Primary Checking', rand(500, 10000) / 100);
            }

            // Some users get additional accounts
            if (rand(0, 1)) {
                $moneyMarketType = $accountTypes->where('name', 'Money Market Account')->first();
                if ($moneyMarketType) {
                    $this->createAccount($user, $moneyMarketType, 'Money Market', rand(10000, 100000) / 100);
                }
            }

            if (rand(0, 1)) {
                $cdType = $accountTypes->where('name', 'Certificate of Deposit')->first();
                if ($cdType) {
                    $this->createAccount($user, $cdType, '12-Month CD', rand(5000, 20000) / 100);
                }
            }

            // Business accounts for some users
            if ($user->id % 3 == 0) {
                $businessType = $accountTypes->where('name', 'Business Checking')->first();
                if ($businessType) {
                    $this->createAccount($user, $businessType, 'Business Account', rand(5000, 100000) / 100);
                }
            }
        }
    }

    /**
     * Create an account for a user
     */
    private function createAccount($user, $accountType, string $name, float $balance)
    {
        $accountNumber = $this->generateAccountNumber();
        $openedAt = now()->subDays(rand(1, 365));

        $accountId = DB::table('accounts')->insertGetId([
            'user_id' => $user->id,
            'account_type_id' => $accountType->id,
            'account_number' => $accountNumber,
            'account_name' => $name,
            'balance' => $balance,
            'available_balance' => $balance,
            'currency' => 'USD',
            'status' => 'ACTIVE',
            'opened_at' => $openedAt,
            'closed_at' => null,
            'last_activity_at' => now()->subDays(rand(0, 30)),
            'created_at' => $openedAt,
            'updated_at' => now(),
        ]);

        return $accountId;
    }

    /**
     * Generate a random account number
     */
    private function generateAccountNumber(): string
    {
        return mt_rand(1000000000, 9999999999);
    }
}
