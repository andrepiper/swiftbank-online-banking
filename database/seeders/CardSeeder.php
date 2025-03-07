<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all checking accounts
        $accounts = DB::table('accounts')
            ->join('account_types', 'accounts.account_type_id', '=', 'account_types.id')
            ->where('account_types.name', 'like', '%Checking%')
            ->select('accounts.*')
            ->get();

        if ($accounts->isEmpty()) {
            // Fallback to all accounts if no checking accounts found
            $accounts = DB::table('accounts')->get();
        }

        // Each account gets 1-2 cards
        foreach ($accounts as $account) {
            $numCards = rand(1, 2);

            for ($i = 0; $i < $numCards; $i++) {
                // Determine card type and network
                $cardType = $this->getRandomCardType();
                $cardNetwork = $this->getRandomCardNetwork();

                // Generate card details
                $cardNumber = $this->generateCardNumber($cardNetwork);
                $expiryMonth = rand(1, 12);
                $expiryYear = date('Y') + rand(1, 5);
                $cvv = $this->generateCVV($cardNetwork);
                $pinHash = Hash::make(str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT));

                // Get user name
                $user = DB::table('users')->where('id', $account->user_id)->first();
                $cardholderName = $user->firstname . ' ' . $user->lastname;

                // Activation date
                $activationDate = now()->subDays(rand(1, 360));

                // Create card
                DB::table('cards')->insert([
                    'user_id' => $account->user_id,
                    'account_id' => $account->id,
                    'card_number' => $cardNumber,
                    'card_type' => $cardType,
                    'card_network' => $cardNetwork,
                    'cardholder_name' => $cardholderName,
                    'expiry_month' => $expiryMonth,
                    'expiry_year' => $expiryYear,
                    'cvv' => $cvv,
                    'pin_hash' => $pinHash,
                    'daily_limit' => $this->getDailyLimit($cardType),
                    'monthly_limit' => $this->getMonthlyLimit($cardType),
                    'is_virtual' => rand(0, 10) > 8 ? 1 : 0,
                    'is_contactless' => rand(0, 10) > 2 ? 1 : 0,
                    'status' => $this->getRandomStatus(),
                    'activation_date' => $activationDate,
                    'created_at' => $activationDate->subDays(rand(1, 5)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Get a random card type
     */
    private function getRandomCardType(): string
    {
        $types = ['DEBIT', 'CREDIT', 'PREPAID'];
        $weights = [60, 30, 10]; // 60% debit, 30% credit, 10% prepaid

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($types as $index => $type) {
            $cumulative += $weights[$index];
            if ($rand <= $cumulative) {
                return $type;
            }
        }

        return 'DEBIT'; // Default fallback
    }

    /**
     * Get a random card network
     */
    private function getRandomCardNetwork(): string
    {
        $networks = ['VISA', 'MASTERCARD', 'AMEX', 'DISCOVER'];
        $weights = [40, 40, 10, 10]; // 40% Visa, 40% Mastercard, 10% Amex, 10% Discover

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($networks as $index => $network) {
            $cumulative += $weights[$index];
            if ($rand <= $cumulative) {
                return $network;
            }
        }

        return 'VISA'; // Default fallback
    }

    /**
     * Generate a random card number based on network
     */
    private function generateCardNumber(string $network): string
    {
        switch ($network) {
            case 'VISA':
                $prefix = '4';
                $length = 16;
                break;
            case 'MASTERCARD':
                $prefix = (string) rand(51, 55);
                $length = 16;
                break;
            case 'AMEX':
                $prefix = rand(0, 1) ? '34' : '37';
                $length = 15;
                break;
            case 'DISCOVER':
                $prefix = '6011';
                $length = 16;
                break;
            default:
                $prefix = '4';
                $length = 16;
        }

        // Generate remaining digits
        $remainingLength = $length - strlen($prefix);
        $number = $prefix;

        for ($i = 0; $i < $remainingLength; $i++) {
            $number .= rand(0, 9);
        }

        return $number;
    }

    /**
     * Generate CVV based on card network
     */
    private function generateCVV(string $network): string
    {
        $length = ($network === 'AMEX') ? 4 : 3;
        $cvv = '';

        for ($i = 0; $i < $length; $i++) {
            $cvv .= rand(0, 9);
        }

        return $cvv;
    }

    /**
     * Get a random card status
     */
    private function getRandomStatus(): string
    {
        $statuses = ['ACTIVE', 'INACTIVE', 'BLOCKED', 'EXPIRED'];
        $weights = [85, 5, 5, 5]; // 85% active, 5% inactive, 5% blocked, 5% expired

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $index => $status) {
            $cumulative += $weights[$index];
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'ACTIVE'; // Default fallback
    }

    /**
     * Get daily limit based on card type
     */
    private function getDailyLimit(string $cardType): float
    {
        if ($cardType === 'CREDIT') {
            return rand(1000, 5000);
        } elseif ($cardType === 'PREPAID') {
            return rand(200, 1000);
        } else {
            return rand(500, 2000);
        }
    }

    /**
     * Get monthly limit based on card type
     */
    private function getMonthlyLimit(string $cardType): float
    {
        if ($cardType === 'CREDIT') {
            return rand(5000, 20000);
        } elseif ($cardType === 'PREPAID') {
            return rand(1000, 5000);
        } else {
            return rand(2000, 10000);
        }
    }
}
