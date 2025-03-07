<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\TransactionCategory;
use App\Models\TransactionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::all();
        $transactionGroups = TransactionGroup::all();
        $transactionCategories = TransactionCategory::all();

        // Generate transactions for each account
        foreach ($accounts as $account) {
            // Number of transactions to generate for this account
            $numTransactions = rand(10, 30);

            // Starting balance
            $balance = $account->balance;

            // Generate transactions
            for ($i = 0; $i < $numTransactions; $i++) {
                // Determine transaction type
                $transactionType = $this->getRandomTransactionType();

                // Find appropriate group
                $group = $transactionGroups->where('type', $transactionType)->random();

                // Find appropriate category based on transaction type
                $categoryType = ($transactionType === 'deposit') ? 'credit' :
                               (($transactionType === 'withdrawal' || $transactionType === 'payment' || $transactionType === 'fee') ? 'debit' : 'transfer');

                $category = $transactionCategories->where('type', $categoryType)->random();

                // Generate amount
                $amount = $this->getRandomAmount($transactionType);

                // Calculate entry type and balance after
                $entryType = ($transactionType === 'deposit' || $transactionType === 'interest') ? 'credit' : 'debit';

                if ($entryType === 'credit') {
                    $balanceAfter = $balance + $amount;
                } else {
                    $balanceAfter = $balance - $amount;
                }

                // Generate transaction date (within the last 90 days)
                $transactionDate = now()->subDays(rand(1, 90));

                // Generate reference
                $reference = $this->generateReference($transactionType);

                // Create transaction
                $transaction = Transaction::create([
                    'account_id' => $account->id,
                    'transaction_category_id' => $category->id,
                    'transaction_group_id' => $group->id,
                    'transaction_type' => $transactionType,
                    'entry_type' => $entryType,
                    'amount' => $amount,
                    'currency' => $account->currency,
                    'balance_after' => $balanceAfter,
                    'description' => $this->generateDescription($transactionType, $category->name),
                    'reference' => $reference,
                    'status' => $this->getRandomStatus(),
                    'metadata' => json_encode([
                        'ip_address' => $this->generateRandomIP(),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'location' => $this->generateRandomLocation(),
                    ]),
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate,
                    'settled_at' => rand(0, 10) > 2 ? $transactionDate : null,
                ]);

                // Update balance for next transaction
                $balance = $balanceAfter;
            }

            // Update account balance to match the final transaction
            $account->update([
                'balance' => $balance,
                'available_balance' => $balance,
                'last_activity_at' => now(),
            ]);
        }
    }

    /**
     * Get a random transaction type
     */
    private function getRandomTransactionType(): string
    {
        $types = [
            'deposit' => 20,
            'withdrawal' => 25,
            'transfer' => 15,
            'payment' => 30,
            'fee' => 5,
            'interest' => 5,
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($types as $type => $probability) {
            $cumulative += $probability;
            if ($rand <= $cumulative) {
                return $type;
            }
        }

        return 'payment'; // Default fallback
    }

    /**
     * Get a random amount based on transaction type
     */
    private function getRandomAmount(string $transactionType): float
    {
        switch ($transactionType) {
            case 'deposit':
                return rand(10000, 500000) / 100; // $100 - $5000
            case 'withdrawal':
                return rand(2000, 50000) / 100; // $20 - $500
            case 'transfer':
                return rand(5000, 200000) / 100; // $50 - $2000
            case 'payment':
                return rand(1000, 100000) / 100; // $10 - $1000
            case 'fee':
                return rand(100, 3000) / 100; // $1 - $30
            case 'interest':
                return rand(10, 5000) / 100; // $0.10 - $50
            default:
                return rand(1000, 10000) / 100; // $10 - $100
        }
    }

    /**
     * Generate a description based on transaction type and category
     */
    private function generateDescription(string $transactionType, string $categoryName): string
    {
        $descriptions = [
            'deposit' => [
                'Salary' => ['Payroll Deposit', 'Direct Deposit - Salary', 'Monthly Salary', 'Bi-weekly Paycheck'],
                'Investments' => ['Dividend Payment', 'Investment Return', 'Stock Sale Proceeds', 'Bond Interest'],
                'Gifts' => ['Gift Deposit', 'Birthday Gift', 'Holiday Gift'],
                'default' => ['Deposit', 'Cash Deposit', 'Check Deposit', 'Mobile Deposit'],
            ],
            'withdrawal' => [
                'ATM Fee' => ['ATM Withdrawal Fee', 'Out-of-network ATM Fee'],
                'default' => ['ATM Withdrawal', 'Cash Withdrawal', 'Branch Withdrawal'],
            ],
            'transfer' => [
                'Internal Transfer' => ['Transfer to Savings', 'Transfer to Checking', 'Account Transfer'],
                'External Transfer' => ['External Account Transfer', 'Transfer to External Account'],
                'default' => ['Funds Transfer', 'Money Transfer', 'Account Transfer'],
            ],
            'payment' => [
                'Rent' => ['Rent Payment', 'Monthly Rent', 'Housing Payment'],
                'Mortgage' => ['Mortgage Payment', 'Home Loan Payment'],
                'Utilities' => ['Utility Bill Payment', 'Electric Bill', 'Water Bill', 'Gas Bill'],
                'Groceries' => ['Grocery Store Purchase', 'Supermarket', 'Food Shopping'],
                'Restaurants' => ['Restaurant Payment', 'Dining Out', 'Food Delivery'],
                'default' => ['Bill Payment', 'Online Payment', 'Recurring Payment'],
            ],
            'fee' => [
                'ATM Fee' => ['ATM Fee', 'ATM Service Charge'],
                'Overdraft Fee' => ['Overdraft Fee', 'Insufficient Funds Fee'],
                'default' => ['Monthly Service Fee', 'Account Maintenance Fee', 'Transaction Fee'],
            ],
            'interest' => [
                'default' => ['Interest Payment', 'Monthly Interest', 'Quarterly Interest', 'Savings Interest'],
            ],
        ];

        // Get descriptions for this transaction type
        $typeDescriptions = $descriptions[$transactionType] ?? $descriptions['payment'];

        // Check if we have specific descriptions for this category
        if (isset($typeDescriptions[$categoryName])) {
            $options = $typeDescriptions[$categoryName];
        } else {
            $options = $typeDescriptions['default'];
        }

        return $options[array_rand($options)];
    }

    /**
     * Generate a reference number
     */
    private function generateReference(string $transactionType): string
    {
        $prefix = strtoupper(substr($transactionType, 0, 3));
        return $prefix . '-' . strtoupper(Str::random(8));
    }

    /**
     * Get a random status
     */
    private function getRandomStatus(): string
    {
        $statuses = [
            'completed' => 85,
            'pending' => 10,
            'failed' => 5,
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $status => $probability) {
            $cumulative += $probability;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'completed'; // Default fallback
    }

    /**
     * Generate a random IP address
     */
    private function generateRandomIP(): string
    {
        return rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    }

    /**
     * Generate a random location
     */
    private function generateRandomLocation(): array
    {
        $cities = [
            'New York' => ['40.7128', '-74.0060'],
            'Los Angeles' => ['34.0522', '-118.2437'],
            'Chicago' => ['41.8781', '-87.6298'],
            'Houston' => ['29.7604', '-95.3698'],
            'Phoenix' => ['33.4484', '-112.0740'],
            'Philadelphia' => ['39.9526', '-75.1652'],
            'San Antonio' => ['29.4241', '-98.4936'],
            'San Diego' => ['32.7157', '-117.1611'],
            'Dallas' => ['32.7767', '-96.7970'],
            'San Jose' => ['37.3382', '-121.8863'],
        ];

        $city = array_rand($cities);
        $coordinates = $cities[$city];

        return [
            'city' => $city,
            'state' => $this->getCityState($city),
            'country' => 'USA',
            'latitude' => $coordinates[0],
            'longitude' => $coordinates[1],
        ];
    }

    /**
     * Get state for a city
     */
    private function getCityState(string $city): string
    {
        $cityStates = [
            'New York' => 'NY',
            'Los Angeles' => 'CA',
            'Chicago' => 'IL',
            'Houston' => 'TX',
            'Phoenix' => 'AZ',
            'Philadelphia' => 'PA',
            'San Antonio' => 'TX',
            'San Diego' => 'CA',
            'Dallas' => 'TX',
            'San Jose' => 'CA',
        ];

        return $cityStates[$city] ?? 'CA';
    }
}
