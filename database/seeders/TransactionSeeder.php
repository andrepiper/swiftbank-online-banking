<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();
        $accounts = DB::table('accounts')->get();
        $transactionGroups = DB::table('transaction_groups')->get();
        $transactionCategories = DB::table('transaction_categories')->get();

        // Generate transactions for each account
        foreach ($accounts as $account) {
            // Get user
            $user = $users->where('id', $account->user_id)->first();

            // Number of transactions to generate for this account
            $numTransactions = rand(10, 30);

            // Starting balance
            $balance = $account->balance;

            // Generate transactions
            for ($i = 0; $i < $numTransactions; $i++) {
                // Determine transaction type
                $transactionType = $this->getRandomTransactionType();

                // Find appropriate group
                $group = $transactionGroups->where('type', strtoupper($transactionType))->random();

                // Determine entry type based on transaction type
                $entryType = ($transactionType === 'deposit' || $transactionType === 'interest') ? 'credit' : 'debit';

                // Generate amount
                $amount = $this->getRandomAmount($transactionType);

                // Calculate balance after
                if ($entryType === 'credit') {
                    $balanceAfter = $balance + $amount;
                } else {
                    $balanceAfter = $balance - $amount;
                }

                // Generate transaction date (within the last 90 days)
                $transactionDate = now()->subDays(rand(1, 90));

                // Determine if this is a transfer
                $contraAccountId = null;
                $externalAccountId = null;

                if ($transactionType === 'transfer') {
                    // 70% chance of internal transfer, 30% chance of external
                    if (rand(1, 10) <= 7) {
                        // Internal transfer - get another account from the same user
                        $otherAccounts = $accounts->where('user_id', $user->id)->where('id', '!=', $account->id);
                        if ($otherAccounts->count() > 0) {
                            $contraAccountId = $otherAccounts->random()->id;
                        }
                    } else {
                        // External transfer
                        $externalAccountId = 'EXT-' . strtoupper(Str::random(8));
                    }
                }

                // Get a random category that matches the transaction type
                $category = $transactionCategories->random();

                // Generate merchant details for payment transactions
                $merchantName = null;
                $merchantCategoryCode = null;
                $merchantCategory = null;

                if ($transactionType === 'payment') {
                    $merchantName = $this->getRandomMerchant();
                    $merchantCategoryCode = $this->getRandomMCC();
                    $merchantCategory = $this->getMerchantCategory($merchantCategoryCode);
                }

                // Generate source and destination
                $source = $transactionType === 'withdrawal' || $transactionType === 'transfer' || $transactionType === 'payment'
                    ? 'account:' . $account->id
                    : $this->getRandomSource($transactionType);

                $destination = $transactionType === 'deposit' || $transactionType === 'interest'
                    ? 'account:' . $account->id
                    : ($contraAccountId
                        ? 'account:' . $contraAccountId
                        : $this->getRandomDestination($transactionType));

                // Generate fee (10% chance of having a fee)
                $fee = rand(1, 10) === 1 ? rand(100, 500) / 100 : 0.00;

                // Create transaction
                DB::table('transactions')->insert([
                    'user_id' => $user->id,
                    'transaction_type' => $transactionType,
                    'entry_type' => $entryType,
                    'amount' => $amount,
                    'currency' => $account->currency,
                    'account_id' => $account->id,
                    'contra_account_id' => $contraAccountId,
                    'external_account_id' => $externalAccountId,
                    'transaction_group_id' => $group->id,
                    'reference_id' => $this->generateReference($transactionType),
                    'trace_number' => 'TRN' . strtoupper(Str::random(12)),
                    'description' => $this->generateDescription($transactionType, $category->name),
                    'status' => $this->getRandomStatus(),
                    'category' => $category->code,
                    'source' => $source,
                    'destination' => $destination,
                    'merchant_name' => $merchantName,
                    'merchant_category_code' => $merchantCategoryCode,
                    'merchant_category' => $merchantCategory,
                    'fee' => $fee,
                    'balance_after' => $balanceAfter,
                    'metadata' => json_encode([
                        'ip_address' => $this->generateRandomIP(),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'location' => $this->generateRandomLocation(),
                        'device_id' => 'DEV-' . strtoupper(Str::random(8)),
                    ]),
                    'created_at' => $transactionDate,
                    'updated_at' => $transactionDate,
                    'settled_at' => rand(0, 10) > 2 ? $transactionDate : null,
                ]);

                // Update balance for next transaction
                $balance = $balanceAfter;
            }

            // Update account balance to match the final transaction
            DB::table('accounts')->where('id', $account->id)->update([
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
                'ATM Transactions' => ['ATM Withdrawal', 'Cash Withdrawal', 'Branch Withdrawal'],
                'default' => ['Withdrawal', 'Cash Withdrawal', 'Branch Withdrawal'],
            ],
            'transfer' => [
                'Internal Transfer' => ['Transfer to Savings', 'Transfer to Checking', 'Account Transfer'],
                'External Transfer' => ['External Account Transfer', 'Transfer to External Account'],
                'default' => ['Funds Transfer', 'Money Transfer', 'Account Transfer'],
            ],
            'payment' => [
                'Rent' => ['Rent Payment', 'Monthly Rent', 'Housing Payment'],
                'Mortgage' => ['Mortgage Payment', 'Home Loan Payment'],
                'Electricity' => ['Electric Bill', 'Utility Payment - Electric'],
                'Water' => ['Water Bill', 'Utility Payment - Water'],
                'Internet' => ['Internet Bill', 'ISP Payment', 'Broadband Service'],
                'Groceries' => ['Grocery Store Purchase', 'Supermarket', 'Food Shopping'],
                'Restaurants' => ['Restaurant Payment', 'Dining Out', 'Food Delivery'],
                'default' => ['Bill Payment', 'Online Payment', 'Recurring Payment'],
            ],
            'fee' => [
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
            'COMPLETED' => 85,
            'PENDING' => 10,
            'FAILED' => 5,
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $status => $probability) {
            $cumulative += $probability;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'COMPLETED'; // Default fallback
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

        return $cityStates[$city] ?? 'Unknown';
    }

    /**
     * Get a random merchant name
     */
    private function getRandomMerchant(): string
    {
        $merchants = [
            'Amazon', 'Walmart', 'Target', 'Costco', 'Best Buy',
            'Home Depot', 'Starbucks', 'McDonald\'s', 'Uber', 'Lyft',
            'Netflix', 'Spotify', 'Apple', 'Google', 'Microsoft',
            'Whole Foods', 'Trader Joe\'s', 'CVS Pharmacy', 'Walgreens',
            'Shell', 'Exxon', 'Chevron', 'Delta Airlines', 'American Airlines',
            'Marriott Hotels', 'Hilton Hotels', 'Airbnb', 'Expedia', 'Booking.com'
        ];

        return $merchants[array_rand($merchants)];
    }

    /**
     * Get a random Merchant Category Code (MCC)
     */
    private function getRandomMCC(): string
    {
        $mccs = [
            '5411', // Grocery Stores
            '5812', // Restaurants
            '5814', // Fast Food
            '5912', // Drug Stores
            '5541', // Gas Stations
            '4121', // Taxis/Rideshares
            '5311', // Department Stores
            '5999', // Miscellaneous Retail
            '4112', // Passenger Railways
            '4511', // Airlines
            '7011', // Hotels
            '7832', // Movie Theaters
            '5732', // Electronics Stores
            '5942', // Bookstores
            '8011', // Doctors
            '8021', // Dentists
            '8099', // Medical Services
            '4899', // Cable/Streaming Services
            '4814', // Telecom Services
            '6300'  // Insurance
        ];

        return $mccs[array_rand($mccs)];
    }

    /**
     * Get merchant category from MCC
     */
    private function getMerchantCategory(string $mcc): string
    {
        $categories = [
            '5411' => 'Grocery',
            '5812' => 'Dining',
            '5814' => 'Fast Food',
            '5912' => 'Pharmacy',
            '5541' => 'Gas',
            '4121' => 'Transportation',
            '5311' => 'Retail',
            '5999' => 'Retail',
            '4112' => 'Transportation',
            '4511' => 'Travel',
            '7011' => 'Travel',
            '7832' => 'Entertainment',
            '5732' => 'Electronics',
            '5942' => 'Retail',
            '8011' => 'Healthcare',
            '8021' => 'Healthcare',
            '8099' => 'Healthcare',
            '4899' => 'Subscription',
            '4814' => 'Utilities',
            '6300' => 'Insurance'
        ];

        return $categories[$mcc] ?? 'Other';
    }

    /**
     * Get a random source for a transaction
     */
    private function getRandomSource(string $transactionType): string
    {
        if ($transactionType === 'deposit') {
            $sources = [
                'ach:employer', 'ach:external', 'wire:external',
                'check:mobile', 'cash:branch', 'transfer:external'
            ];
        } elseif ($transactionType === 'interest') {
            $sources = ['system:interest'];
        } else {
            $sources = ['unknown'];
        }

        return $sources[array_rand($sources)];
    }

    /**
     * Get a random destination for a transaction
     */
    private function getRandomDestination(string $transactionType): string
    {
        if ($transactionType === 'withdrawal') {
            $destinations = ['atm:local', 'atm:external', 'cash:branch'];
        } elseif ($transactionType === 'payment') {
            $destinations = ['merchant:pos', 'merchant:online', 'bill:online', 'bill:autopay'];
        } elseif ($transactionType === 'transfer') {
            $destinations = ['account:external', 'account:recipient'];
        } elseif ($transactionType === 'fee') {
            $destinations = ['system:fee'];
        } else {
            $destinations = ['unknown'];
        }

        return $destinations[array_rand($destinations)];
    }
}
