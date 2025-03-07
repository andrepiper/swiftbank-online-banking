<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();

        // Common banks
        $banks = [
            'Chase Bank' => ['code' => 'CHASUS33', 'routing' => '021000021', 'swift' => 'CHASUS33'],
            'Bank of America' => ['code' => 'BOFAUS3N', 'routing' => '026009593', 'swift' => 'BOFAUS3N'],
            'Wells Fargo' => ['code' => 'WFBIUS6S', 'routing' => '121000248', 'swift' => 'WFBIUS6S'],
            'Citibank' => ['code' => 'CITIUS33', 'routing' => '021000089', 'swift' => 'CITIUS33'],
            'US Bank' => ['code' => 'USBKUS44', 'routing' => '123103729', 'swift' => 'USBKUS44'],
            'PNC Bank' => ['code' => 'PNCCUS33', 'routing' => '043000096', 'swift' => 'PNCCUS33'],
            'TD Bank' => ['code' => 'NRTHUS33', 'routing' => '031201360', 'swift' => 'NRTHUS33'],
            'Capital One' => ['code' => 'CAFOUS31', 'routing' => '056073502', 'swift' => 'CAFOUS31'],
            'HSBC Bank' => ['code' => 'MRMDUS33', 'routing' => '022000020', 'swift' => 'MRMDUS33'],
            'Truist Bank' => ['code' => 'BRBTUS33', 'routing' => '061000104', 'swift' => 'BRBTUS33'],
        ];

        // Each user gets 2-5 beneficiaries
        foreach ($users as $user) {
            $numBeneficiaries = rand(2, 5);

            for ($i = 0; $i < $numBeneficiaries; $i++) {
                // Select a random bank
                $bankName = array_rand($banks);
                $bankInfo = $banks[$bankName];

                // Generate a random account number
                $accountNumber = $this->generateAccountNumber();

                // Determine transfer type
                $transferType = $this->getRandomTransferType();

                // Generate IBAN for international transfers
                $iban = ($transferType === 'INTERNATIONAL') ? $this->generateIBAN() : null;

                // Create beneficiary
                DB::table('beneficiaries')->insert([
                    'user_id' => $user->id,
                    'name' => $this->generateBeneficiaryName(),
                    'account_number' => $accountNumber,
                    'bank_name' => $bankName,
                    'bank_code' => $bankInfo['code'],
                    'routing_number' => $bankInfo['routing'],
                    'swift_code' => $bankInfo['swift'],
                    'iban' => $iban,
                    'address' => $this->generateAddress(),
                    'country' => 'USA',
                    'currency' => 'USD',
                    'transfer_type' => $transferType,
                    'is_favorite' => rand(0, 10) > 8 ? 1 : 0,
                    'created_at' => now()->subDays(rand(1, 180)),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Generate a random account number
     */
    private function generateAccountNumber(): string
    {
        return mt_rand(1000000000, 9999999999);
    }

    /**
     * Generate a random beneficiary name
     */
    private function generateBeneficiaryName(): string
    {
        $firstNames = [
            'James', 'Mary', 'Robert', 'Patricia', 'John', 'Jennifer', 'Michael', 'Linda',
            'William', 'Elizabeth', 'David', 'Susan', 'Richard', 'Jessica', 'Joseph', 'Sarah',
            'Thomas', 'Karen', 'Charles', 'Nancy', 'Christopher', 'Lisa', 'Daniel', 'Margaret',
            'Matthew', 'Betty', 'Anthony', 'Sandra', 'Mark', 'Ashley', 'Donald', 'Dorothy',
        ];

        $lastNames = [
            'Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller', 'Wilson',
            'Moore', 'Taylor', 'Anderson', 'Thomas', 'Jackson', 'White', 'Harris', 'Martin',
            'Thompson', 'Garcia', 'Martinez', 'Robinson', 'Clark', 'Rodriguez', 'Lewis', 'Lee',
            'Walker', 'Hall', 'Allen', 'Young', 'Hernandez', 'King', 'Wright', 'Lopez',
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    /**
     * Get a random transfer type
     */
    private function getRandomTransferType(): string
    {
        $types = ['INTERNAL', 'DOMESTIC', 'INTERNATIONAL'];
        $weights = [20, 60, 20]; // 20% internal, 60% domestic, 20% international

        $rand = rand(1, 100);
        $cumulative = 0;

        foreach ($types as $index => $type) {
            $cumulative += $weights[$index];
            if ($rand <= $cumulative) {
                return $type;
            }
        }

        return 'DOMESTIC'; // Default fallback
    }

    /**
     * Generate a random IBAN
     */
    private function generateIBAN(): string
    {
        $countries = ['DE', 'FR', 'GB', 'ES', 'IT', 'NL', 'BE', 'CH'];
        $country = $countries[array_rand($countries)];

        $checkDigits = str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
        $bankCode = strtoupper(Str::random(4));
        $accountNumber = strtoupper(Str::random(16));

        return $country . $checkDigits . $bankCode . $accountNumber;
    }

    /**
     * Generate a random address
     */
    private function generateAddress(): string
    {
        $streetNumbers = [123, 456, 789, 1024, 555, 777, 888, 999, 1111, 2222];
        $streetNames = ['Main St', 'Oak Ave', 'Maple Rd', 'Washington Blvd', 'Park Ave', 'Broadway', 'Cedar Ln', 'Lake St', 'River Rd', 'Highland Ave'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        $states = ['NY', 'CA', 'IL', 'TX', 'AZ', 'PA', 'TX', 'CA', 'TX', 'CA'];
        $zipCodes = ['10001', '90001', '60601', '77001', '85001', '19101', '78201', '92101', '75201', '95101'];

        $index = array_rand($streetNumbers);

        return $streetNumbers[$index] . ' ' . $streetNames[$index] . ', ' . $cities[$index] . ', ' . $states[$index] . ' ' . $zipCodes[$index];
    }
}
