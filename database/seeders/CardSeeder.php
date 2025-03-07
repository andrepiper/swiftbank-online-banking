<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Card;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all checking accounts
        $accounts = Account::whereHas('accountType', function ($query) {
            $query->where('name', 'like', '%Checking%');
        })->get();

        if ($accounts->isEmpty()) {
            // Fallback to all accounts if no checking accounts found
            $accounts = Account::all();
        }

        // Card types and networks
        $cardTypes = ['debit', 'credit'];
        $cardNetworks = ['visa', 'mastercard', 'amex', 'discover'];

        // Each account gets 1-2 cards
        foreach ($accounts as $account) {
            $numCards = rand(1, 2);

            for ($i = 0; $i < $numCards; $i++) {
                // Determine card type and network
                $cardType = $cardTypes[array_rand($cardTypes)];
                $cardNetwork = $cardNetworks[array_rand($cardNetworks)];

                // Generate card details
                $cardNumber = $this->generateCardNumber($cardNetwork);
                $expiryDate = now()->addYears(rand(1, 5))->format('m/Y');
                $cvv = $this->generateCVV($cardNetwork);

                // Create card
                Card::create([
                    'user_id' => $account->user_id,
                    'account_id' => $account->id,
                    'card_type' => $cardType,
                    'card_network' => $cardNetwork,
                    'card_number' => $cardNumber,
                    'masked_number' => $this->maskCardNumber($cardNumber),
                    'cardholder_name' => $account->user->name,
                    'expiry_date' => $expiryDate,
                    'cvv' => $cvv,
                    'status' => $this->getRandomStatus(),
                    'is_virtual' => rand(0, 10) > 8,
                    'is_contactless' => rand(0, 10) > 2,
                    'daily_limit' => $this->getDailyLimit($cardType),
                    'monthly_limit' => $this->getMonthlyLimit($cardType),
                    'metadata' => json_encode([
                        'design' => $this->getRandomCardDesign(),
                        'pin_set' => true,
                        'international_enabled' => rand(0, 1) ? true : false,
                        'online_transactions_enabled' => rand(0, 1) ? true : false,
                        'atm_withdrawals_enabled' => rand(0, 1) ? true : false,
                    ]),
                    'created_at' => now()->subDays(rand(1, 365)),
                    'updated_at' => now(),
                    'activated_at' => now()->subDays(rand(1, 360)),
                ]);
            }
        }
    }

    /**
     * Generate a random card number based on network
     */
    private function generateCardNumber(string $network): string
    {
        switch ($network) {
            case 'visa':
                $prefix = '4';
                $length = 16;
                break;
            case 'mastercard':
                $prefix = (string) rand(51, 55);
                $length = 16;
                break;
            case 'amex':
                $prefix = rand(0, 1) ? '34' : '37';
                $length = 15;
                break;
            case 'discover':
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
        $length = ($network === 'amex') ? 4 : 3;
        $cvv = '';

        for ($i = 0; $i < $length; $i++) {
            $cvv .= rand(0, 9);
        }

        return $cvv;
    }

    /**
     * Mask card number for display
     */
    private function maskCardNumber(string $cardNumber): string
    {
        $length = strlen($cardNumber);
        $visibleCount = 4;
        $maskedSection = str_repeat('*', $length - $visibleCount);
        $visiblePart = substr($cardNumber, -$visibleCount);

        return $maskedSection . $visiblePart;
    }

    /**
     * Get a random card status
     */
    private function getRandomStatus(): string
    {
        $statuses = [
            'active' => 85,
            'inactive' => 10,
            'blocked' => 5,
        ];

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($statuses as $status => $probability) {
            $cumulative += $probability;
            if ($rand <= $cumulative) {
                return $status;
            }
        }

        return 'active'; // Default fallback
    }

    /**
     * Get daily limit based on card type
     */
    private function getDailyLimit(string $cardType): float
    {
        if ($cardType === 'credit') {
            return rand(1000, 5000);
        } else {
            return rand(500, 2000);
        }
    }

    /**
     * Get monthly limit based on card type
     */
    private function getMonthlyLimit(string $cardType): float
    {
        if ($cardType === 'credit') {
            return rand(5000, 20000);
        } else {
            return rand(2000, 10000);
        }
    }

    /**
     * Get a random card design
     */
    private function getRandomCardDesign(): string
    {
        $designs = ['standard', 'premium', 'metal', 'custom', 'limited_edition'];
        return $designs[array_rand($designs)];
    }
}
